<?php

namespace App\Service;

use App\Models\StrategyAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StrategyAuthService{
    /**
     * 新建策略
     */
    public static function createStrategy($params){
        $merchant_id = Auth::guard('merchant')->id();

        $name = $params['name']??'New Strategy';
        $run_number = $params['run_number']??0;
        $expired_type = $params['expired_type']??StrategyAuth::EXPIRED_PERPETUAL;


        $expired_day = 0;
        $expired_time = NULL;
        if ($expired_type == StrategyAuth::EXPIRED_DAY && !empty($params['expired_day']) && $params['expired_day'] > 0){
            $expired_day = $params['expired_day'];
        }elseif($expired_type == StrategyAuth::EXPIRED_DATE) {
            if (empty($valid_time)) {
                $expired_time = date('Y-m-d H:i:s');
            } else {
                $expired_time = conversionSetTime($params['expired_time']);
            }
        }

        try {
            $create_info = [
                'merchant_id' => $merchant_id,
                'name' => $name,
                'expired_type' => $expired_type,
                'expired_day' => $expired_day,
                'expired_time' => $expired_time,
                'run_number' => $run_number
            ];

            $createStrategy = StrategyAuth::query()->create($create_info);

            return resultSuccess();

        } catch (\Exception $exception) {
            Log::info('创建权限策略异常:'.$exception->getMessage());
        }
        return resultError(__('merchant_service.failed_to_create_permission_policy'));
    }



    /**
     * 编辑策略
     */
    public static function updateStrategy($strategy_id, $params){
        $merchant_id = Auth::guard('merchant')->id();

        $strategy = StrategyAuth::query()->where('merchant_id', $merchant_id)->findOrFail($strategy_id);

        $name = $params['name']??'New Strategy';
        $run_number = $params['run_number']??0;
        $expired_type = $params['expired_type']??StrategyAuth::EXPIRED_PERPETUAL;


        $expired_day = 0;
        $expired_time = NULL;
        if ($expired_type == StrategyAuth::EXPIRED_DAY && !empty($params['expired_day']) && $params['expired_day'] > 0){
            $expired_day = $params['expired_day'];
        }elseif($expired_type == StrategyAuth::EXPIRED_DATE) {
            if (empty($valid_time)) {
                $expired_time = date('Y-m-d H:i:s');
            } else {
                $expired_time = conversionSetTime($params['expired_time']);
            }
        }

        try {
            $strategy->name = $name;
            $strategy->expired_type = $expired_type;
            $strategy->expired_day = $expired_day;
            $strategy->expired_time = $expired_time;
            $strategy->run_number = $run_number;
            $strategy->save();

            return resultSuccess();

        } catch (\Exception $exception) {
            Log::info('编辑权限策略异常:'.$exception->getMessage());
        }
        return resultError(__('merchant_service.failed_to_update_permission_policy'));
    }
}
