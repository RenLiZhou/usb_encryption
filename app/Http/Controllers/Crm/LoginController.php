<?php

namespace App\Http\Controllers\Crm;

use App\Models\CrmAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public $v = 'crm.login.';

    /*
     * 登录页面
     */
    public function index()
    {
        if (Auth::guard('crm')->check()){
            return redirect()->route('crm.main');
        }
        return view($this->v . 'login');
    }

    /*
     * 登录
     */
    public function signIn(Request $request, CrmAdmin $admin)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'code' => 'required|captcha',
        ],[
            'code.captcha' => '验证码错误',
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $remeber = $request->filled('remember') ? true : false;
        $admin->auth($request->username, $request->password, $remeber);
        return responseSuccess();
    }

    /*
     * 退出
     */
    public function logOut()
    {
        (new CrmAdmin())->cleanAdminData();
        Auth::guard('crm')->logout();
        return redirect()->route('crm.login');
    }

}
