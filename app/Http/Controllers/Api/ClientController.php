<?php

namespace App\Http\Controllers\Api;

use App\ApiService\DiskService;
use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    /**
     * * 获取U盘详情授权信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\OrException
     */
    public function getUsbInfo(Request $request)
    {
        //验证
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'pythcal_serial' => 'required|alpha_dash',
        ],[
            'merchant_id.required' => ApiException::BUSINESS_ID_IS_EMPTY,
            'merchant_id.integer' => ApiException::BUSINESS_ID_ERROR,
            'pythcal_serial.required' => ApiException::PHYSICAL_SERIAL_NUMBER_IS_EMPTY,
            'pythcal_serial.alpha_dash' => ApiException::PHYSICAL_SERIAL_NUMBER_ERROR
        ]);
        $error = $validator->errors()->first();
        if ($error){
            throw new ApiException($error);
        }

        $data = DiskService::getUsbInfo($request->merchant_id, $request->pythcal_serial);
        return responseSuccess($data);

    }


    /**
     * * 获取更新的文件列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\OrException
     */
    public function getUpdateList(Request $request)
    {
        //验证
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'pythcal_serial' => 'required|alpha_dash',
        ],[
            'merchant_id.required' => ApiException::BUSINESS_ID_IS_EMPTY,
            'merchant_id.integer' => ApiException::BUSINESS_ID_ERROR,
            'pythcal_serial.required' => ApiException::PHYSICAL_SERIAL_NUMBER_IS_EMPTY,
            'pythcal_serial.alpha_dash' => ApiException::PHYSICAL_SERIAL_NUMBER_ERROR
        ]);
        $error = $validator->errors()->first();
        if ($error){
            throw new ApiException($error);
        }

        $data = DiskService::getUpdateList($request->merchant_id, $request->pythcal_serial);
        return responseSuccess($data);
    }


    /**
     * * 创建U盘轨迹
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\OrException
     */
    public function createUsbTrack(Request $request)
    {
        //验证
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'pythcal_serial' => 'required|alpha_dash',
            'event_name' => 'required',
            'machine_code' => 'required',
        ],[
            'merchant_id.required' => ApiException::BUSINESS_ID_IS_EMPTY,
            'merchant_id.integer' => ApiException::BUSINESS_ID_ERROR,
            'pythcal_serial.required' => ApiException::PHYSICAL_SERIAL_NUMBER_IS_EMPTY,
            'pythcal_serial.alpha_dash' => ApiException::PHYSICAL_SERIAL_NUMBER_ERROR,
            'event_name.required' => ApiException::EVENT_NAME_CANNOT_BE_EMPTY,
            'machine_code.required' => ApiException::THE_MACHINE_CODE_CANNOT_BE_EMPTY
        ]);
        $error = $validator->errors()->first();
        if ($error){
            throw new ApiException($error);
        }

        DiskService::createUsbTrack($request->all());
        return responseSuccess();
    }


}
