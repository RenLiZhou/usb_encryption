<?php

namespace App\Http\Controllers\Api;

use App\ApiService\DiskService;
use App\ApiService\MerchantService;
use App\Exceptions\ApiException;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{

    /**
     * * 商户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\OrException
     */
    public function login(Request $request)
    {
        //验证
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ],[
            'username.required' => ApiException::USERNAME_DOES_NOT_EXIST,
            'password.required' => ApiException::PASSWORD_DOES_NOT_EXIST,
        ]);
        $error = $validator->errors()->first();
        if ($error){
            throw new ApiException($error);
        }

        $token = MerchantService::auth($request->username, $request->password);
        return responseSuccess($token);
    }

    /**
     * 商家信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(Request $request)
    {
        $merchant_id = auth('api')->id();

        $merchant = Merchant::query()
            ->where('id', $merchant_id)
            ->with(['language' => function($query){
                $query->select('id','name','desc');
            },'version'])
            ->select('id', 'name', 'username', 'expire_time', 'status', 'lang_id', 'add_auth_count', 'auth_number', 'is_permanent')
            ->firstOrFail();

        $version_count = empty($merchant->version) ? 0 : $merchant->version[0]->disk_number;
        $surplus_auth_amount = $version_count + $merchant->add_auth_count - $merchant->auth_number;
        if($surplus_auth_amount < 0) $surplus_auth_amount = 0;

        $merchant->total_auth_amount = $version_count + $merchant->add_auth_count;
        $merchant->auth_amount = $merchant->auth_number;
        $merchant->surplus_auth_amount = $surplus_auth_amount;
        $merchant->expiration_time = $merchant->expire_time;

        $merchant->makeHidden(['add_auth_count','auth_number','version','lang_id','expire_time']);

        return responseSuccess($merchant);
    }

    /**
     * 退出
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return responseSuccess();
    }


    /**
     * 加密U盘信息
     */
    public function encryptionDisk(Request $request)
    {
        //验证
        $validator = Validator::make($request->all(), [
            'pythcal_serial' => 'required|alpha_dash',
            'logical_serial' => 'required|alpha_dash',
            'capacity' => 'required|numeric',
        ],[
            'pythcal_serial.required' => ApiException::PHYSICAL_SERIAL_NUMBER_IS_EMPTY,
            'pythcal_serial.alpha_dash' => ApiException::PHYSICAL_SERIAL_NUMBER_ERROR,
            'logical_serial.required' => ApiException::LOGICAL_SERIAL_NUMBER_IS_EMPTY,
            'logical_serial.alpha_dash' => ApiException::LOGICAL_SERIAL_NUMBER_ERROR,
            'capacity.required' => ApiException::U_DISK_CAPACITY_IS_EMPTY,
            'capacity.numeric' => ApiException::U_DISK_CAPACITY_ERROR,
        ]);
        $error = $validator->errors()->first();
        if ($error){
            throw new ApiException($error);
        }

        $merchant_id = auth('api')->id();

        DiskService::encryptionDisk($merchant_id, $request->all());

        return responseSuccess();
    }

}
