<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MerchantService{
    /**
     * CRM管理员登录
     */
    public static function auth(string $username, string $password){
        $merchant = Merchant::where('username', $username)->first();
        if ($merchant === null){
            throw new OrException('用户名或密码错误');
        }

        $res = password_verify($password, $merchant->password);
        if ($res === false){
            throw new OrException('用户名或密码错误');
        }

        $validate = self::validateStatus($merchant);
        if(!$validate['result']){
            throw new OrException($validate['msg']);
        }

        Auth::guard('merchant')->login($merchant);
    }

    /**
     * 检验状态
     * @param $merchant
     */
    public static function validateStatus($merchant){
        if ($merchant->status === Merchant::NOT_ACTIVE){
            return resultError('商户已禁用');
        }
        if ($merchant->is_permanent != Merchant::PERMANENT && $merchant->expire_time <= Carbon::now()->toDateTimeString()){
            return resultError('商户已到期');
        }
        return resultSuccess();
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
