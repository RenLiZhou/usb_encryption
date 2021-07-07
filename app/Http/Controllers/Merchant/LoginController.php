<?php

namespace App\Http\Controllers\Merchant;

use App\Service\MerchantService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public $v = 'merchant.login.';

    public function index()
    {
        if (Auth::guard('merchant')->check()){
            return redirect()->route('merchant.main');
        }
        return view($this->v . 'login');
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uname' => 'required',
            'psword' => 'required',
            'captcha' => 'required|captcha',
        ],[
            'uname.required' => __('merchant_service.business_user_name_is_empty'),
            'psword.required' => __('merchant_service.password_is_empty'),
            'captcha.required' => __('merchant_service.graphic_verification_code_is_empty'),
            'captcha.captcha' => __('merchant_service.graphic_verification_code_error')
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        MerchantService::auth($request->uname, $request->psword);
        return responseSuccess();
    }

    public function logOut()
    {
        Auth::guard('merchant')->logout();
        return redirect()->route('merchant.login');
    }
}
