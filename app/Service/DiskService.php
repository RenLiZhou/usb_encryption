<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\Disk;
use App\Models\DiskEncryptRecord;
use App\Models\Merchant;
use App\Models\StrategyAuth;
use App\Models\StrategyUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class DiskService{
    /**
     * 加密U盘
     */
    public static function encryptionDisk($merchant_id, $params){
        $pythcal_serial = $params['pythcal_serial']??'';
        $logical_serial = $params['logical_serial']??'';
        $capacity = $params['capacity']??0;//容量

        //默认不生效的权限策略
        $default_strategy_auth = StrategyAuth::query()
            ->where('merchant_id', $merchant_id)
            ->where('expired_type', StrategyAuth::EXPIRED_PERPETUAL)
            ->first();
        $default_strategy_auth_id = $default_strategy_auth == null ? 0 : $default_strategy_auth->id;

        //商家可授权数量
        $merchant = Merchant::query()
            ->with('version')
            ->where('id', $merchant_id)
            ->firstOrFail();

        //授权数量不足
        $version_count = empty($merchant->version) ? 0 : $merchant->version[0]->disk_number;
        if($version_count + $merchant->add_auth_count - $merchant->auth_number <= 0){
            return resultError('商户可授权U盘数量不足');
        }

        //是否存在
        $disk = Disk::query()
            ->where('merchant_id', $merchant_id)
            ->where('usb_serial', $pythcal_serial)
            ->first();

        //如果存在，判断加密次数是否超出限制
        $disk_encryption_count = config('services.disk_encryption_count');
        if($disk != null && $disk->encrypt_count >= $disk_encryption_count){
            return resultError("U盘加密次数已达到{$disk_encryption_count}次");
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
                        'strategy_update_id' => 0,
                        'strategy_auth_id' => $default_strategy_auth_id,
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
                    return resultSuccess();
                }else{
                    DB::rollBack();
                }
            } catch (\Exception $exception) {
                Log::info('加密U盘上传异常:'.$exception->getMessage());
                DB::rollBack();
            } finally{
                $lock->release();
            }

            return resultError('U盘加密失败');
        }else{
            return resultError(__("common.the_system_is_busy"));
        }
    }

    /**
     * 更新U盘
     */
    public static function updateDisk($disk_id, $params){
        $merchant_id = Auth::guard('merchant')->id();
        $disk = Disk::query()->where('merchant_id', $merchant_id)->find($disk_id);
        if($disk === null){
            return resultError(__('common.illegal_operation'));
        }

        $name = $params['name']??'';
        $update_id = $params['update_id']??0;
        $auth_id = $params['auth_id']??0;
        $status = $params['status']??$disk->status;

        //验证更新策略
        if($update_id > 0){
            $strategy_update = StrategyUpdate::query()->where('merchant_id', $merchant_id)->find($update_id);
            if($strategy_update === null){
                $update_id = 0;
            }
        }

        //验证权限策略
        if($auth_id > 0){
            $strategy_auth = StrategyAuth::query()->where('merchant_id', $merchant_id)->find($auth_id);
            if($strategy_auth === null){
                $auth_id = 0;
            }
        }

        $disk->name = $name;
        $disk->strategy_update_id = $update_id;
        $disk->strategy_auth_id = $auth_id;
        $disk->status = $status;

        $disk->save();

        return resultSuccess();
    }
}
