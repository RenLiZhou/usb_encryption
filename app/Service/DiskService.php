<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\Disk;
use App\Models\Merchant;
use App\Models\StrategyAuth;
use App\Models\StrategyUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DiskService{
    /**
     * 创建U盘
     */
    public static function createData(){
//        $data = [];
//        for ($i = 1; $i <= 10; $i++){
//            $data[] = [
//                'merchant_id' => 7,
//                'name' => uniqid($i),
//                'strategy_update_id' => 0,
//                'strategy_auth_id' => 0,
//                'status' => Disk::STATUS_USE,
//                'run_count' => 0,
//                'encrypt_count' => 1,
//                'capacity' => rand(1000,9999),
//                'usb_serial' => uniqid($i),
//                'first_time_use' => Carbon::now()->toDateTimeString()
//            ];
//        }
//        Disk::query()->insert($data);
    }

    /**
     * 更新U盘
     */
    public static function updateDisk($disk_id, $params){
        $merchant_id = Auth::guard('merchant')->id();
        $disk = Disk::query()->where('merchant_id', $merchant_id)->find($disk_id);
        if($disk === null){
            return resultError('非法操作');
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
