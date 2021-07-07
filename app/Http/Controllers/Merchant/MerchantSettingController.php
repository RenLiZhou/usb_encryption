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
            'status.required' => __('merchant_controller.rip_function_is_not_set'),
            'status.in' => __('merchant_controller.ripping_function_is_set_incorrectly'),
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
            'status.required' => __('merchant_controller.watermark_function_setting_is_not_set'),
            'status.in' => __('merchant_controller.the_watermark_function_is_set_incorrectly'),
            'content.required' => __('merchant_controller.watermark_content_is_not_set'),
            'content.in' => __('merchant_controller.the_watermark_content_is_set_incorrectly'),
            'size.required' => __('merchant_controller.watermark_font_size_is_not_set'),
            'size.integer' => __('merchant_controller.the_watermark_font_size_is_set_incorrectly'),
            'size.min' => __('merchant_controller.the_watermark_font_size_is_set_incorrectly'),
            'color.required' => __('merchant_controller.watermark_text_color_is_not_set'),
            'transparency.required' => __('merchant_controller.watermark_text_transparency_is_not_set'),
            'transparency.integer' =>  __('merchant_controller.the_transparency_of_the_watermark_text_is_set_incorrectly'),
            'transparency.min' =>  __('merchant_controller.the_transparency_of_the_watermark_text_is_set_incorrectly'),
            'transparency.max' =>  __('merchant_controller.the_transparency_of_the_watermark_text_is_set_incorrectly'),
            'video_style.required' => __('merchant_controller.video_file_watermark_style_is_not_set'),
            'video_style.in' => __('merchant_controller.the_watermark_style_of_the_video_file_is_set_incorrectly'),
            'video_refresh_interval.required' => __('merchant_controller.the_new_interval_for_water_printing_of_video_files_is_not_set'),
            'video_refresh_interval.numeric' => __('merchant_controller.the_new_water_printing_interval_of_the_video_file_is_set_incorrectly'),
            'video_refresh_interval.min' => __('merchant_controller.the_new_water_printing_interval_of_the_video_file_is_set_incorrectly'),
            'picture_style.required' => __('merchant_controller.picture_watermark_is_not_set'),
            'picture_style.in' => __('merchant_controller.the_picture_watermark_is_set_incorrectly')
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
