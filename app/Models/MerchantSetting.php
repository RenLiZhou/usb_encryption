<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class MerchantSetting extends BaseModel
{
    protected $guarded = [];

    const SCREEN_RECORDING = 'screen_recording'; //防录屏检测设置
    const WATERMARK = 'watermark'; //水印设置

    //防录屏
    const SCREEN_RECORDING_ENABLE = 0;  //启用
    const SCREEN_RECORDING_DISABLE = 1; //禁用

    //水印
    const WATERMARK_ENABLE = 0;  //启用
    const WATERMARK_DISABLE = 1; //禁用

    //文字内容
    const WATERMARK_CONTENT_USB_SERIAL = 1;  //U盘物理序列号
    const WATERMARK_CONTENT_NAME = 2; //U盘别名

    //视频文件水印样式
    const WATERMARK_STYLE_FIXED = 1;  //固定
    const WATERMARK_STYLE_MARQUEE = 2; //跑马灯
    const WATERMARK_STYLE_FLOAT_AROUND = 3;  //四周浮动
    const WATERMARK_STYLE_FLOAT_FULL_SCREEN = 4;  //全屏浮动

    //PDF&图片水印设置
    const WATERMARK_PICTURE_STYLE_FULL_SCREEN = 1;  //全屏
    const WATERMARK_PICTURE_STYLE_TOP_CENTER= 2; //顶部居中
    const WATERMARK_PICTURE_STYLE_CENTER = 3;  //居中
    const WATERMARK_PICTURE_STYLE_BOTTOM_CENTER = 4;  //底部居中
    const WATERMARK_PICTURE_STYLE_RANDOM = 5;  //随机位置

    public function getDataAttribute($value)
    {
        return json_decode($value,true);
    }
}
