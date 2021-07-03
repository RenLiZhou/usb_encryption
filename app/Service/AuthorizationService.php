<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\ActivationCode;
use App\Models\Merchant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class AuthorizationService{
    /**
     * CRM管理员登录
     */
    public static function exchange($params){
        $merchant_id = Auth::guard('merchant')->id();

        $code = $params['code']??'';

        //注册码是否存在
        $activation_code = ActivationCode::query()
            ->where('code',$code)
            ->where('auth_count', '>', 0)
            ->where('status', ActivationCode::STATUS_NOT_ACTIVE)
            ->first();

        if(empty($code) || $activation_code === null){
            return resultError('注册码不存在或已激活');
        }

        $lock = Cache::lock('activation_code_'.$activation_code->id, 60);
        if ($lock->get()) {
            DB::beginTransaction();
            $rs = [];
            try {
                $updata_data = [
                    'active_time' => date('Y-m-d H:i:s'),
                    'active_merchant_id' => $merchant_id,
                    'active_ip' => request()->ip(),
                    'status' => ActivationCode::STATUS_ACTIVE
                ];
                $rs[] = ActivationCode::query()
                    ->where('code',$code)
                    ->where('status', ActivationCode::STATUS_NOT_ACTIVE)
                    ->update($updata_data);

                $rs[] = Merchant::query()
                    ->where('id',$merchant_id)
                    ->increment('add_auth_count', $activation_code->auth_count);

                if(checkResult($rs)){
                    DB::commit();
                    return resultSuccess();
                }else{
                    DB::rollBack();
                }
            } catch (\Exception $exception) {
                Log::info('激活码授权异常:'.$exception->getMessage());
                DB::rollBack();
            } finally{
                $lock->release();
            }

            return resultError('授权失败');
        }else{
            return resultError('系统繁忙，请稍后重试');
        }
    }
}
