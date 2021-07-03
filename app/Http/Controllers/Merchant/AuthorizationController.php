<?php

namespace App\Http\Controllers\Merchant;

use App\Models\ActivationCode;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    public $v = 'merchant.authorization.';

    /**
     * 新增授权页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $merchant_id = Auth::guard('merchant')->id();

        $activation_logs = ActivationCode::query()
            ->where('active_merchant_id', $merchant_id)
            ->where('status', ActivationCode::STATUS_ACTIVE)
            ->select('code', 'auth_count', 'active_time')
            ->orderByDesc('active_time')
            ->get();
        $activation_logs = json_encode($activation_logs);

        return view($this->v . 'index', compact('activation_logs'));
    }

    /**
     * 激活码激活
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ],[
            'code.required' => '激活码不存在'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $res = AuthorizationService::exchange($request->all());
        if (!$res['result']){
            return responseError($res['msg']);
        }
        return responseSuccess();
    }
}
