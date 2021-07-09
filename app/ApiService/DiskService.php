<?php

namespace App\ApiService;

use App\Exceptions\ApiException;
use App\Models\Disk;
use App\Models\DiskEncryptRecord;
use App\Models\DiskTrack;
use App\Models\Merchant;
use App\Models\MerchantSetting;
use App\Models\StrategyAuth;
use App\Models\StrategyUpdate;
use App\Service\MerchantSettingService;
use App\Service\ResourceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiskService{
    /**
     * 加密U盘
     */
    public static function encryptionDisk($merchant_id, $params){
        $pythcal_serial = $params['pythcal_serial']??'';
        $logical_serial = $params['logical_serial']??'';
        $capacity = $params['capacity']??0;//容量

        //默认不生效的文件更新策略
        $default_strategy_update = StrategyUpdate::query()
            ->where('merchant_id', $merchant_id)
            ->whereNull('valid_time')
            ->first();
        $default_strategy_update_id = $default_strategy_update == null ? 0 : $default_strategy_update->id;

        //商家可授权数量
        $merchant = Merchant::query()
            ->with('version')
            ->where('id', $merchant_id)
            ->firstOrFail();

        //授权数量不足
        $version_count = empty($merchant->version) ? 0 : $merchant->version[0]->disk_number;
        if($version_count + $merchant->add_auth_count - $merchant->auth_number <= 0){
            throw new ApiException(ApiException::INSUFFICIENT_NUMBER_OF_USB_FLASH_DRIVES);
        }

        //是否存在
        $disk = Disk::query()
            ->where('merchant_id', $merchant_id)
            ->where('usb_serial', $pythcal_serial)
            ->first();

        //如果存在，判断加密次数是否超出限制
        $disk_encryption_count = config('services.disk_encryption_count');
        if($disk != null && $disk->encrypt_count >= $disk_encryption_count){
            throw new ApiException(ApiException::U_DISK_ENCRYPTION_NUMBER_HAS_REACHED_THE_MAXIMUM);
        }

        $lock = Cache::lock('merchant_id_'.$merchant_id, 60);
        if ($lock->get()) {
            DB::beginTransaction();
            $rs = [];
            try {
                //不存在,先创建
                if($disk == null){
                    $data = [
                        'merchant_id' => $merchant_id,
                        'name' => $pythcal_serial,
                        'strategy_update_id' => $default_strategy_update_id,
                        'strategy_auth_id' => 0,
                        'status' => Disk::STATUS_USE,
                        'run_count' => 0,
                        'encrypt_count' => 0,//加密次数
                        'capacity' => $capacity,
                        'usb_serial' => $pythcal_serial,
                        'first_time_use' => Carbon::now()->toDateTimeString()
                    ];
                    $rs[] = $disk = Disk::query()->create($data);

                    //商户授权记录加1
                    $rs[] = Merchant::query()
                        ->where('id', $merchant_id)
                        ->where('auth_number', $merchant->auth_number)
                        ->increment('auth_number',1);
                }

                if($disk){
                    $rs[] = Disk::query()
                        ->where('merchant_id', $merchant_id)
                        ->where('usb_serial', $pythcal_serial)
                        ->where('encrypt_count', $disk->encrypt_count)
                        ->where('encrypt_count', '<', $disk_encryption_count)
                        ->increment('encrypt_count',1);

                    if(checkResult($rs)){
                        $disk_encrypt_record_data = [
                            'merchant_id' => $merchant_id,
                            'disk_id' => $disk->id,
                            'logical_sequence' => $logical_serial,
                            'ip' => request()->ip(),
                        ];
                        DiskEncryptRecord::query()->create($disk_encrypt_record_data);
                    }
                }

                if(checkResult($rs)){
                    DB::commit();
                    return true;
                }else{
                    DB::rollBack();
                }
            } catch (\Exception $exception) {
                Log::info('加密U盘上传异常:'.$exception->getMessage());
                DB::rollBack();
            } finally{
                $lock->release();
            }

            throw new ApiException(ApiException::U_DISK_ENCRYPTION_FAILED);
        }else{
            throw new ApiException(ApiException::THE_SYSTEM_IS_BUSY_PLEASE_TRY_AGAIN_LATER);
        }
    }

    /**
     * 检验商家，U盘
     * @param $merchant
     */
    public static function validateMerchantOrUsb($merchant, $disk){
        //商家验证
        if(empty($merchant)) throw new ApiException(ApiException::THE_MERCHANT_DOES_NOT_EXIST);

        //U盘验证
        if(empty($disk)) throw new ApiException(ApiException::PHYSICAL_SERIAL_NUMBER_ERROR);
        if($disk->status == Disk::STATUS_DISABLED) throw new ApiException(ApiException::U_DISK_IS_DISABLED);

        //U盘过期验证
        if(empty($disk->strategy_auth)) throw new ApiException(ApiException::U_DISK_IS_NOT_VALID);

        $format = 'Y-m-d H:i:s';
        $now_data = Carbon::now()->format($format);

        if($disk->strategy_auth->expired_type == StrategyAuth::EXPIRED_DAY){
            //过期天数
            $expiration_date = Carbon::parse($disk->first_time_use)->addDays($disk->strategy_auth->expired_day)->format($format);
            if($expiration_date <= $now_data){
                throw new ApiException(ApiException::U_DISK_HAS_EXPIRED);
            }
        }elseif($disk->strategy_auth->expired_type == StrategyAuth::EXPIRED_DATE){
            //过期时间
            $expiration_date = $disk->strategy_auth->expired_time;
            if($expiration_date <= $now_data){
                throw new ApiException(ApiException::U_DISK_HAS_EXPIRED);
            }
        }

        //U盘运行次数验证
        if($disk->strategy_auth->run_number != -1 && $disk->strategy_auth->run_number <= $disk->run_count){
            throw new ApiException(ApiException::U_DISK_RUNNING_TIMES_REACHED_THE_MAXIMUM);
        }
    }


    /**
     * 获取U盘信息
     */
    public static function getUsbInfo($merchant_id, $pythcal_serial){
        //商家
        $merchant = Merchant::query()->find($merchant_id);

        //U盘
        $disk = Disk::query()
            ->with(['strategy_auth','strategy_update'])
            ->where('merchant_id', $merchant_id)
            ->where('usb_serial', $pythcal_serial)
            ->first();

        //商家/U盘验证
        self::validateMerchantOrUsb($merchant, $disk);

        if($disk->strategy_auth->expired_type == StrategyAuth::EXPIRED_PERPETUAL){
            //永久
            $expiration_date = "-1";
        }elseif($disk->strategy_auth->expired_type == StrategyAuth::EXPIRED_DAY){
            //过期天数
            $expiration_date = Carbon::parse($disk->first_time_use)->addDays($disk->strategy_auth->expired_day)->format('Y-m-d H:i:s');
        }else{
            //过期时间
            $expiration_date = $disk->strategy_auth->expired_time;
        }

        $limited_times = $disk->strategy_auth->run_number == -1 ? -1 : $disk->strategy_auth->run_number-$disk->run_count;

        $merchant_screen_recording = MerchantSettingService::getSetting($merchant_id, MerchantSetting::SCREEN_RECORDING);
        if (!$merchant_screen_recording['result']){
            throw new ApiException(ApiException::THE_MERCHANT_CONFIGURATION_IS_INCORRECT);
        }
        $screen_recording = $merchant_screen_recording['data']->data;

        $merchant_watermark = MerchantSettingService::getSetting($merchant_id, MerchantSetting::WATERMARK);
        if (!$merchant_watermark['result']){
            throw new ApiException(ApiException::THE_MERCHANT_CONFIGURATION_IS_INCORRECT);
        }
        $watermark = $merchant_watermark['data']->data;

        //自动更新
        $auto_update = empty($disk->strategy_update)? StrategyUpdate::NOT_AUTO_UPDATE : $disk->strategy_update->automatic_update_prompt;

        //运行次数加1
        $add_run_count = Disk::query()
            ->where('id', $disk->id)
            ->where('run_count', $disk->run_count)
            ->increment('run_count',1);

        if(empty($add_run_count)){
            throw new ApiException(ApiException::THE_SYSTEM_IS_BUSY_PLEASE_TRY_AGAIN_LATER);
        }

        $data = [
            'merchant_id' => $disk->merchant_id,
            'name' => $disk->name,
            'capacity' => $disk->capacity,
            'pythcal_serial' => $disk->usb_serial,
            'expiration_date' => $expiration_date,
            'limited_times' => $limited_times-1,
            'watermark' => $watermark,
            'screen_recording' => $screen_recording,
            'auto_update' => $auto_update
        ];

        return $data;
    }


    /**
     * 获取U盘更新文件
     */
    public static function getUpdateList($merchant_id, $pythcal_serial){
        //商家
        $merchant = Merchant::query()->find($merchant_id);

        //U盘
        $disk = Disk::query()
            ->with(['strategy_auth'])
            ->where('merchant_id', $merchant_id)
            ->where('usb_serial', $pythcal_serial)
            ->first();

        //商家/U盘验证
        self::validateMerchantOrUsb($merchant, $disk);

        //更新策略
        $strategy_update = StrategyUpdate::query()
            ->with('files')
            ->where('merchant_id', $merchant_id)
            ->find($disk->strategy_update_id);

        if(empty($disk->strategy_update_id) || empty($strategy_update)){
            throw new ApiException(ApiException::NO_UPDATE_STRATEGY);
        }

        $ResourceService = new ResourceService();
        $strategy_files = $strategy_update->files;
        $files = [];
        $resource_url = config('services.resource_url').'/'.$ResourceService->resource_name.'/';
        foreach($strategy_files as $key => $data){
            $path = $merchant->root_directory.$data->path;
            $exists = $ResourceService->exists($path);
            if($exists){
                $files[] = [
                    'path' => $resource_url.$path,
                    'file_name' => $ResourceService->basename($path),
                    'file_size' => $ResourceService->size($path),
                    'last_modified' => Carbon::parse($ResourceService->lastModified($path))->format('Y-m-d H:i:s'),
                ];
            }
        }

        $data = [
            'auto_update' => $strategy_update->automatic_update_prompt,
            'updated_date' => $strategy_update->updated_at,
            'created_date' => $strategy_update->created_at,
            'files' => $files,
            'files_count' => count($files)
        ];

        return $data;

    }


    /**
     * 创建U盘轨迹
     */
    public static function createUsbTrack($params){
        $merchant_id = $params['merchant_id']??0;
        $pythcal_serial = $params['pythcal_serial']??'';
        $event_name = $params['event_name']??'';
        $event_username = $params['event_username']??'';
        $event_desc = $params['event_desc']??'';
        $machine_code = $params['machine_code']??'';

        //商家
        $merchant = Merchant::query()->find($merchant_id);

        //U盘
        $disk = Disk::query()
            ->with(['strategy_auth'])
            ->where('merchant_id', $merchant_id)
            ->where('usb_serial', $pythcal_serial)
            ->first();

        //商家/U盘验证
        self::validateMerchantOrUsb($merchant, $disk);

        $data = [
            'merchant_id' => $merchant_id,
            'disk_id' => $disk->id,
            'event_username' => $event_username,
            'event_name' => $event_name,
            'event_desc' => $event_desc,
            'machine_code' => $machine_code,
            'ip' => request()->ip(),
        ];

        DiskTrack::query()->create($data);
    }
}
