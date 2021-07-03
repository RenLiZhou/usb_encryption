<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivationCode extends BaseModel
{
    protected $guarded = [];

    const STATUS_NOT_ACTIVE = 0;//未激活
    const STATUS_ACTIVE = 1;//已激活

    public static function basicRules()
    {
        return [
            'prefix' => 'required|alpha_num|size:5',
            'auth_count' => 'required|integer|min:0',
            'amount' => 'required|integer|min:1'
        ];
    }

    public static function basicMessages()
    {
        return [
            'prefix.required' => '前缀错误',
            'prefix.alpha_num' => '前缀只能是字母，数字',
            'prefix.size' => '前缀必须为5个字符',
            'auth_count.required' => 'USB授权数量错误',
            'auth_count.integer' => 'USB授权数量错误',
            'auth_count.min' => 'USB授权数量错误',
            'amount.required' => '创建数量错误',
            'amount.integer' => '创建数量错误',
            'amount.min' => '创建数量错误'
        ];
    }

    public function getActiveTimeAttribute($value)
    {
        return conversionTime($value);
    }

    /**
     * 生成随机机
     * @param $prefix
     * @return string
     */
    public static function generateCode($prefix)
    {
        $hash = md5(password_hash(time(), PASSWORD_DEFAULT));
        $res = [
            $prefix,
            substr($hash, 0, 5),
            substr($hash, 5, 5),
            substr($hash, -10, 5),
            substr($hash, -3),
        ];
        $code = implode('-', $res);
        $code = strtoupper($code);
        $code = strtoupper($code . (substr(md5(md5($code) . md5('usb')), 0, 2)));
        return $code;
    }

    /**
     * 创建
     * @return string
     */
    public static function createData($params)
    {
        $prefix = strtoupper($params['prefix']??getRandomString(5));
        $auth_count = $params['auth_count']??0;
        $amount = $params['amount']??1;

        DB::beginTransaction();
        try {
            //批次号
            $batch_no = date('ymd-His').'-'.rand(100000,999999);
            $insert_data = [];
            for ($i = 1; $i <= $amount; $i++){
                $insert_data[] = [
                    'batch_no' => $batch_no,
                    'status' => self::STATUS_NOT_ACTIVE,
                    'auth_count' => $auth_count,
                    'code' => self::generateCode($prefix),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            $insert_result = self::query()->insert($insert_data);
            if($insert_result){
                DB::commit();
                return resultSuccess();
            }
        } catch (\Exception $exception) {
            Log::info('创建激活码异常:'.$exception->getMessage());
        }

        DB::rollBack();
        return resultError('创建激活码失败');
    }


}
