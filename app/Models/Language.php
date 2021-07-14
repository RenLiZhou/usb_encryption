<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class Language extends BaseModel
{
    protected $guarded = [];

    public static function basicRules()
    {
        return [
            'name' => 'required|alpha_dash',
            'desc' => 'required'
        ];
    }

    public static function basicMessages()
    {
        return [
            'name.required' => '语言标识为空',
            'name.alpha_dash' => '语言标识只能是字母、数字，-，_ ',
            'desc.required' => '语言名称为空'
        ];
    }

    /**
     * 创建
     * @return string
     */
    public static function createData($params)
    {
        $name = $params['name']??'';
        $desc = $params['desc']??'';

        if(empty($name)){
            return resultError('语言标识为空');
        }

        $exists = self::query()->where('name',$name)->exists();
        if($exists){
            return resultError('该语言标识已存在');
        }

        try {
            $insert_info = [
                'name' => $name,
                'desc' => $desc
            ];

            $insert_result = self::query()->create($insert_info);

            if($insert_result){
                return resultSuccess();
            }

        } catch (\Exception $exception) {
            Log::info('创建多语言异常:'.$exception->getMessage());
        }
        return resultError('创建多语言失败');
    }


    /**
     * 更新
     * @return string
     */
    public static function updateData($id, $params)
    {
        $name = $params['name']??'';
        $desc = $params['desc']??'';

        if(empty($name)){
            return resultError('语言标识为空');
        }

        $data = self::query()->find($id);
        if(empty($data)){
            return resultError('该语言不存在');
        }

        $if_repetition = self::query()->where('name',$name)->where('id', '<>', $data->id)->exists();
        if($if_repetition){
            return resultError('该语言标识已存在');
        }

        try {
            $data->name = $name;
            $data->desc = $desc;
            $data->save();

            return resultSuccess();
        } catch (\Exception $exception) {
            Log::info('更新多语言异常:'.$exception->getMessage());
            return resultError('更新多语言异常');
        }
    }
}
