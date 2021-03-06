<?php

namespace App\Service;

use App\Models\MerchantSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MerchantSettingService{

    public static function getSetting($merchant_id, $name){
        $setting = MerchantSetting::query()
            ->where('merchant_id', $merchant_id)
            ->where('name', $name)
            ->first();
        if(empty($setting)){
            if(in_array($name, [MerchantSetting::SCREEN_RECORDING,MerchantSetting::WATERMARK])){
                $result = self::setSetting($merchant_id, $name);
                if(!$result['result']) return $result;
            }else{
                return resultError(__('merchant_service.the_configuration_does_not_exist'));
            }
            $setting = MerchantSetting::query()
                ->where('merchant_id', $merchant_id)
                ->where('name', $name)
                ->first();
        }
        return resultSuccess($setting);
    }

    public static function setSetting($merchant_id, $name, $params = []){
        $data = self::getSettingData($name, $params);
        try {
            $setting = [
                'merchant_id' => $merchant_id,
                'name' => $name,
                'data' => json_encode($data)
            ];

            $exists = MerchantSetting::query()
                ->where('merchant_id', $merchant_id)
                ->where('name', $name)
                ->exists();

            if($exists){
                $result = MerchantSetting::query()
                    ->where('merchant_id', $merchant_id)
                    ->where('name', $name)
                    ->update($setting);
            }else{
                $result = MerchantSetting::query()->create($setting);
            }
            if($result){
                return resultSuccess();
            }
        }catch (\Exception $exception){
            Log::info('设置异常'.$exception->getMessage());
        }

        return resultError(__('merchant_service.setup_failed'));
    }

    public static function getSettingData($name, $params = []){
        $data = [];
        switch ($name){
            //防录屏数据
            case MerchantSetting::SCREEN_RECORDING :{
                $data = [
                    'status' => intval($params['status']??MerchantSetting::SCREEN_RECORDING_ENABLE) //是否启用
                ];
                break;
            }
            //水印
            case MerchantSetting::WATERMARK :{
                $data = [
                    'status' => intval($params['status']??MerchantSetting::WATERMARK_ENABLE), //是否启用
                    'content' => intval($params['content']??MerchantSetting::WATERMARK_CONTENT_USB_SERIAL), //水印内容
                    'size' => intval($params['size']??14), //水印文字大小
                    'color' => $params['color']??'#ffffff', //水印文字颜色
                    'transparency' => intval($params['transparency']??50), //水印文字透明度
                    'video_style' => intval($params['video_style']??MerchantSetting::WATERMARK_STYLE_FIXED), //视频文件水印样式
                    'video_refresh_interval' => floatval($params['video_refresh_interval']??0), //视频文件水印刷新间隔
                    'picture_style' => intval($params['picture_style']??MerchantSetting::WATERMARK_PICTURE_STYLE_FULL_SCREEN) //图片&PDF水印设置
                ];
                break;
            }
        }

        return $data;
    }
}
