<?php

namespace App\ApiService;

use App\Exceptions\ApiException;
use App\Models\ApiMerchant;
use App\Models\Merchant;
use App\Models\MerchantVersion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MerchantService{

    /**
     * 检验状态
     * @param $merchant
     */
    public static function validateStatus($merchant){
        if ($merchant->status === Merchant::NOT_ACTIVE){
            throw new ApiException(ApiException::THE_MERCHANT_HAS_BEEN_DISABLED);
        }
        if ($merchant->is_permanent != Merchant::PERMANENT && $merchant->expire_time <= Carbon::now()->toDateTimeString()){
            throw new ApiException(ApiException::THE_MERCHANT_HAS_EXPIRED);
        }
    }

    /**
     * API商户登录
     */
    public static function auth(string $username, string $password){

        $merchant = ApiMerchant::where('username', $username)->first();
        if ($merchant === null){
            throw new ApiException(ApiException::USER_NAME_OR_PASSWORD_IS_WRONG);
        }

        $res = password_verify($password, $merchant->password);
        if ($res === false){
            throw new ApiException(ApiException::USER_NAME_OR_PASSWORD_IS_WRONG);
        }

        self::validateStatus($merchant);

        $token = auth('api')->attempt(['username'=>$username,'password'=>$password]);
        if(!$token){
            throw new ApiException(ApiException::AUTHENTICATION_FAILED);
        }

        $merchant->load('version');

        //可授权次数
        $version_count = empty($merchant->version) ? 0 : $merchant->version[0]->disk_number;
        $surplus_auth_amount = $version_count + $merchant->add_auth_count - $merchant->auth_number;
        if($surplus_auth_amount <= 0){
            throw new ApiException(ApiException::THE_REMAINING_AUTHORIZED_NUMBER_OF_U_DISK_IS_0);
        }

        return [
            'type' => 'Bearer',
            'token' => $token,
            'user' => [
                'id' => $merchant->id,
                'name' => $merchant->name,
                'username' => $merchant->username,
                'total_auth_amount' => $version_count + $merchant->add_auth_count,  //总授权
                'auth_amount' => $merchant->auth_number,    //授权数量
                'surplus_auth_amount' => $surplus_auth_amount,  //剩余授权
                'is_permanent' => $merchant->is_permanent,  //是否永久
                'expiration_time' => $merchant->expire_time //过期时间
            ]
        ];
    }
}
