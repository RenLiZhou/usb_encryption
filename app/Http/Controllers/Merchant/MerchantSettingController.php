<?php

namespace App\Http\Controllers\Merchant;

use App\Models\MerchantSetting;
use App\Service\MerchantSettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MerchantSettingController extends Controller
{
    public $v = 'merchant.merchant_settings.';

    public function index(){

        //防录屏设置
        $screen_recording = MerchantSettingService::getSetting(MerchantSetting::SCREEN_RECORDING);
        if (!$screen_recording['result']){
            abort(403,$screen_recording['msg']);
        }
        $screen_recording = $screen_recording['data'];

        //水印设置
        $watermark = MerchantSettingService::getSetting(MerchantSetting::WATERMARK);
        if (!$watermark['result']){
            abort(403,$watermark['msg']);
        }
        $watermark = $watermark['data'];

        return view($this->v . 'index', compact('screen_recording','watermark'));
    }

    public function setScreenRecording(Request $request){
        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                Rule::in([MerchantSetting::SCREEN_RECORDING_ENABLE,MerchantSetting::SCREEN_RECORDING_DISABLE])
            ]
        ],[
            'status.required' => '防翻录功能未设置',
            'status.in' => '防翻录功能设置有误',
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $result = MerchantSettingService::setSetting(MerchantSetting::SCREEN_RECORDING, $request->all());
        if(!$result['result']){
            return responseError($result['msg']);
        }
        return responseSuccess();
    }

    public function setWatermark(Request $request){
        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                Rule::in([MerchantSetting::WATERMARK_ENABLE,MerchantSetting::WATERMARK_DISABLE])
            ], //是否启用
            'content' => [
                'required',
                Rule::in([MerchantSetting::WATERMARK_CONTENT_NAME,MerchantSetting::WATERMARK_CONTENT_USB_SERIAL])
            ], //水印内容
            'size' => 'required|integer|min:0', //水印文字大小
            'color' => 'required', //水印文字颜色
            'transparency' => 'required|integer|min:0|max:100', //水印文字透明度
            'video_style' => [
                'required',
                Rule::in([
                    MerchantSetting::WATERMARK_STYLE_FIXED,
                    MerchantSetting::WATERMARK_STYLE_MARQUEE,
                    MerchantSetting::WATERMARK_STYLE_FLOAT_AROUND,
                    MerchantSetting::WATERMARK_STYLE_FLOAT_FULL_SCREEN
                ])
            ], //视频文件水印样式
            'video_refresh_interval' => 'required|numeric|min:0', //视频文件水印刷新间隔
            'picture_style' => [
                'required',
                Rule::in([
                    MerchantSetting::WATERMARK_PICTURE_STYLE_FULL_SCREEN,
                    MerchantSetting::WATERMARK_PICTURE_STYLE_TOP_CENTER,
                    MerchantSetting::WATERMARK_PICTURE_STYLE_CENTER,
                    MerchantSetting::WATERMARK_PICTURE_STYLE_BOTTOM_CENTER,
                    MerchantSetting::WATERMARK_PICTURE_STYLE_RANDOM
                ])
            ], //Position
        ],[
            'status.required' => '水印功能设置未设置',
            'status.in' => '水印功能设置有误',
            'content.required' => '水印内容未设置',
            'content.in' => '水印内容设置有误',
            'size.required' => '水印字体大小未设置',
            'size.integer' => '水印字体大小设置有误',
            'size.min' => '水印字体大小设置有误',
            'color.required' => '水印文字颜色未设置',
            'transparency.required' => '水印文字透明度未设置',
            'transparency.integer' => '水印文字透明度设置有误',
            'transparency.min' => '水印文字透明度设置有误',
            'transparency.max' => '水印文字透明度设置有误',
            'video_style.required' => '视频文件水印样式未设置',
            'video_style.in' => '视频文件水印样式设置有误',
            'video_refresh_interval.required' => '视频文件水印刷新间隔未设置',
            'video_refresh_interval.numeric' => '视频文件水印刷新间隔设置有误',
            'video_refresh_interval.min' => '视频文件水印刷新间隔设置有误',
            'picture_style.required' => '图片水印未设置',
            'picture_style.in' => '图片水印设置有误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $result = MerchantSettingService::setSetting(MerchantSetting::WATERMARK, $request->all());
        if(!$result['result']){
            return responseError($result['msg']);
        }
        return responseSuccess();
    }
}
