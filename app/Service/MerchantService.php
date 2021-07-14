<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\ApiMerchant;
use App\Models\Merchant;
use App\Models\MerchantVersion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MerchantService{
    /**
     * 商户登录
     */
    public static function auth(string $username, string $password){
        $merchant = Merchant::where('username', $username)->first();
        if ($merchant === null){
            throw new OrException(__('merchant_service.username_or_password_is_wrong'));
        }

        $res = password_verify($password, $merchant->password);
        if ($res === false){
            throw new OrException(__('merchant_service.username_or_password_is_wrong'));
        }

        self::validateStatus($merchant);

        Auth::guard('merchant')->login($merchant);
    }

    /**
     * 检验状态
     * @param $merchant
     */
    public static function validateStatus($merchant){
        if ($merchant->status === Merchant::NOT_ACTIVE){
            throw new OrException(__('merchant_service.the_merchant_has_been_disabled'));
        }
        if ($merchant->is_permanent != Merchant::PERMANENT && $merchant->expire_time <= Carbon::now()->toDateTimeString()){
            throw new OrException(__('merchant_service.the_merchant_has_expired'));
        }
    }

    /**
     * 菜单转化树形结构
     * @param $merchant
     */
    public static function treeMenu($data, $pid = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['children'] = self::treeMenu($data, $v['id']);
                unset($data[$k]);
                $arr[] = $v;
            }
        }
        return $arr;
    }
}
