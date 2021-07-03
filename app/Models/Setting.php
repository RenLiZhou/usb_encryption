<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class Setting extends BaseModel
{
    protected $guarded = [];

    const EMAIL = 'email';

    public static function getSetting($name){
        $setting = self::query()->where('name',$name)->first();
        if(empty($setting)){
            switch ($name){
                case self::EMAIL :
                    $result = self::setEmailSetting();
                    if(!$result['result']){
                        return $result;
                    }
                    break;
                default:
                    return resultError('没有该配置');
            }
            $setting = self::query()->where('name',$name)->first();
        }
        $setting->data = json_decode($setting['data'],true);
        return resultSuccess($setting);
    }

    public static function setEmailSetting($params = []){
        $name = self::EMAIL;
        $data = [
            'addresser' => $params['addresser']??'',
            'smtp_service' => $params['smtp_service']??'',
            'account' => $params['account']??'',
            'password' => $params['password']??''
        ];

        try {
            $setting = [
                'name' => $name,
                'data' => json_encode($data)
            ];

            $exists = self::query()->where('name',$name)->exists();
            if($exists){
                $result = self::query()->where('name',$name)->update($setting);
            }else{
                $result = self::query()->create($setting);
            }
            if($result){
                return resultSuccess();
            }
        }catch (Exception $exception){
            Log::info('邮件配置设置异常');
        }

        return resultError('邮件配置设置失败');
    }
}
