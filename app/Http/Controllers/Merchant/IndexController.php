<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Merchant;
use App\Models\MerchantVersionRelation;
use App\Service\MerchantService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public $v = 'merchant.index.';

    /**
     * 首页
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $merchant_id = Auth::guard('merchant')->id();

        $merchant = Merchant::query()
            ->with('version')
            ->find($merchant_id);

        $menus = $merchant->getMerchantRules();
        $menus = MerchantService::treeMenu($menus);
        $merchant_timezone = $request->cookie('merchant_timezone', 'local');
        return view($this->v . 'index', compact('merchant', 'menus', 'merchant_timezone'));
    }

    /**
     * 概括
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview(){
        $merchant_id = Auth::guard('merchant')->id();

        $merchant = Merchant::query()
            ->with(['version','language'])
            ->find($merchant_id);
        return view($this->v . 'overview', compact('merchant'));
    }

    /**
     * 修改密码
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPassword(){
        $merchant = Auth::guard('merchant')->user();
        return view($this->v . 'edit_password', compact('merchant'));
    }

    /**
     * 修改密码
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required'
        ],[
            'old_password.required' => __('merchant_controller.the_original_password_is_empty'),
            'password.required' => __('merchant_controller.the_new_password_is_empty')
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $merchant = Auth::guard('merchant')->user();

        $old_password = $request->old_password;
        $password = $request->password;

        $compare = password_verify($old_password, $merchant->password);
        if ($compare === false){
            return responseError(__('merchant_controller.the_original_password_is_incorrect'));
        }

        $merchant->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => Merchant::PWD_COST]);
        $merchant->save();

        return responseSuccess();
    }
}
