<?php

namespace App\Service;

use App\Models\StrategyUpdate;
use App\Models\StrategyUpdateFiles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StrategyUpdateService{
    /**
     * 新建策略
     */
    public static function createStrategy($params){
        $merchant_id = Auth::guard('merchant')->id();

        $name = $params['name']??'New Strategy';
        $hint = $params['hint']??StrategyUpdate::NOT_AUTO_UPDATE;
        $valid_type = $params['valid_type']??StrategyUpdate::NOW_VALID;
        $valid_time = $params['valid_time']??'';
        $files = $params['files']??[];

        if($valid_type == StrategyUpdate::NOW_VALID){
            $valid_time = date('Y-m-d H:i:s');
        }elseif ($valid_type == StrategyUpdate::DATE_VALID){
            if(empty($valid_time)){
                $valid_time = date('Y-m-d H:i:s');
            }else{
                $valid_time = conversionSetTime($valid_time);
            }
        }else{
            $valid_time = NULL;
        }

        $automatic_update_prompt = StrategyUpdate::NOT_AUTO_UPDATE;
        if($hint == 1) $automatic_update_prompt = StrategyUpdate::AUTO_UPDATE;

        try {
            $create_info = [
                'merchant_id' => $merchant_id,
                'name' => $name,
                'valid_time' => $valid_time,
                'automatic_update_prompt' => $automatic_update_prompt
            ];

            $createStrategy = StrategyUpdate::query()->create($create_info);

            if($createStrategy){
                $insert_files = [];
                foreach ($files as $value){
                    if(!empty($value['name']) && !empty($value['size']) && !empty($value['type']) && !empty($value['path'])){
                        $insert_files[] = [
                            'strategy_id' => $createStrategy->id,
                            'name' => $value['name'],
                            'size' => $value['size'],
                            'type' => $value['type'],
                            'path' => $value['path'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }

                if(!empty($insert_files)){
                    StrategyUpdateFiles::query()->insert($insert_files);
                }

                return resultSuccess();
            }

        } catch (\Exception $exception) {
            Log::info('创建更新策略异常:'.$exception->getMessage());
        }
        return resultError('创建更新策略失败');
    }



    /**
     * 编辑策略
     */
    public static function updateStrategy($strategy_id, $params){
        $merchant_id = Auth::guard('merchant')->id();

        $strategy = StrategyUpdate::query()->where('merchant_id', $merchant_id)->findOrFail($strategy_id);

        $name = $params['name']??'New Strategy';
        $hint = $params['hint']??StrategyUpdate::NOT_AUTO_UPDATE;
        $valid_type = $params['valid_type']??StrategyUpdate::NOW_VALID;
        $valid_time = $params['valid_time']??'';
        $files = $params['files']??[];

        if($valid_type == StrategyUpdate::NOW_VALID){
            $valid_time = date('Y-m-d H:i:s');
        }elseif ($valid_type == StrategyUpdate::DATE_VALID){
            if(empty($valid_time)){
                $valid_time = date('Y-m-d H:i:s');
            }else{
                $valid_time = conversionSetTime($valid_time);
            }
        }else{
            $valid_time = NULL;
        }

        $automatic_update_prompt = StrategyUpdate::NOT_AUTO_UPDATE;
        if($hint == 1) $automatic_update_prompt = StrategyUpdate::AUTO_UPDATE;

        try {
            $strategy->name = $name;
            $strategy->valid_time = $valid_time;
            $strategy->automatic_update_prompt = $automatic_update_prompt;
            $updateStrategy = $strategy->save();

            if($updateStrategy){
                $strategy->files()->delete();
                $insert_files = [];
                foreach ($files as $value){
                    if(!empty($value['name']) && !empty($value['size']) && !empty($value['type']) && !empty($value['path'])){
                        $insert_files[] = [
                            'strategy_id' => $strategy_id,
                            'name' => $value['name'],
                            'size' => $value['size'],
                            'type' => $value['type'],
                            'path' => $value['path'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }

                if(!empty($insert_files)){
                    StrategyUpdateFiles::query()->insert($insert_files);
                }

                return resultSuccess();
            }

        } catch (\Exception $exception) {
            Log::info('编辑更新策略异常:'.$exception->getMessage());
        }
        return resultError('编辑更新策略失败');
    }
}