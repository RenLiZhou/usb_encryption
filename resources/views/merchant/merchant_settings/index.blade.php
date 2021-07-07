@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.advanced_settings') }}</title>
@endsection

@section("css")
    <link rel="stylesheet" href="{{ asset('merchant-static/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('merchant-static/js/ion-rangeslider/ion.rangeSlider.min.css') }}">
@endsection

@section("content")
    <body>
        <!--页面主要内容-->
        <main class="ftdms-layout-content">
            <div class="container-fluid">
                <div class="row mt15 mb60">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><h4>{{ __('merchant_view.advanced_settings') }}</h4></div>
                            <div class="card-body">

                                <ul id="myTabs" class="nav nav-tabs" role="tablist">
                                    <li class="active">
                                        <a href="#screen_recording" id="screen_recording-tab" role="tab" data-toggle="tab">
                                            {{ __('merchant_view.anti_screen_recording_detection_settings') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#watermark" role="tab" id="watermark-tab" data-toggle="tab">
                                            {{ __('merchant_view.watermark_settings') }}
                                        </a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">

                                    <!--防录屏检测设置-->
                                    <div class="tab-pane fade active in" id="screen_recording">

                                        <form id="form_screen_recording">
                                            <div class="card">
                                                <div class="card-header"><h4>{{ __('merchant_view.basic_settings') }}</h4></div>
                                                <div class="card-body clearfix">
                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>{{ __('merchant_view.anti_rip_function') }}</label>
                                                        <div class="controls-box m-t-10">
                                                            <label class="ftdms-radio radio-inline radio-primary">
                                                                <input type="radio" name="status" value="0" @if($screen_recording->data['status'] == 0) checked @endif /><span>{{ __('common.enable') }}</span>
                                                            </label>
                                                            <label class="ftdms-radio radio-inline radio-primary">
                                                                <input type="radio" name="status" value="1" @if($screen_recording->data['status'] == 1) checked @endif /><span>{{ __('common.disable') }}</span>
                                                            </label>
                                                        </div>
                                                        <p class="m-t-10 text-cyan">{{ __('merchant_view.anti_rip_function_description') }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mt35">
                                                <button class="btn btn-primary" type="button" data-url="{{ route('merchant.merchant_setting.screen_recording') }}" data-type="POST" onClick="TObj.ScreeRecordingSubmit(this)">
                                                    {{ __('common.submit') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!--水印记录-->
                                    <div class="tab-pane fade" id="watermark">

                                        <form id="form_watermark">

                                            <div class="card clearfix">
                                                <div class="card-header"><h4>{{ __('merchant_view.basic_settings') }}</h4></div>
                                                <div class="card-body clearfix">
                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>{{ __('merchant_view.enable_watermark') }}</label>
                                                        <div class="controls-box m-t-5">
                                                            <label class="ftdms-radio radio-inline radio-primary">
                                                                <input type="radio" name="status" value="0" @if($watermark->data['status'] == 0) checked @endif /><span>{{ __('common.enable') }}</span>
                                                            </label>
                                                            <label class="ftdms-radio radio-inline radio-primary">
                                                                <input type="radio" name="status" value="1" @if($watermark->data['status'] == 1) checked @endif /><span>{{ __('common.disable') }}</span>
                                                            </label>
                                                        </div>
                                                        <p class="m-t-10 text-cyan">{{ __('merchant_view.watermark_description') }}</p>
                                                    </div>

                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>水印内容</label>
                                                        <select class="form-control m-t-5" name="content">
                                                            <option value="1" @if($watermark->data['content'] == 1) selected @endif>{{ __('merchant_view.merchant_settings_u_disk_physical_serial_number') }}</option>
                                                            <option value="2" @if($watermark->data['content'] == 2) selected @endif>{{ __('merchant_view.merchant_settings_u_disk_remarks_name') }}</option>
                                                        </select>
                                                    </div>
                                                    <br/>
                                                </div>
                                            </div>

                                            <div class="card clearfix">
                                                <div class="card-header"><h4>{{ __('merchant_view.watermark_text') }}</h4></div>
                                                <div class="card-body clearfix">
                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>{{ __('merchant_view.font_size') }}</label>
                                                        <input class="form-control" type="number" name="size" value="{{ $watermark->data['size'] }}" />
                                                    </div>
                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>{{ __('merchant_view.font_color') }}</label>
                                                        <div class="colorpicker input-group colorpicker-element">
                                                            <input class="form-control" type="text" name="color" value="{{ $watermark->data['color'] }}" />
                                                            <span class="input-group-addon"><i style="background-color: {{ $watermark->data['color'] }};"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>{{ __('merchant_view.transparency') }}(%)</label>
                                                        <input class="transparency" name="transparency" value="{{ $watermark->data['transparency'] }}" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card">
                                                <div class="card-header"><h4>{{ __('merchant_view.video_file_watermark') }}</h4></div>
                                                <div class="card-body clearfix">
                                                    <div class="col-sm-5 clearfix">
                                                        <div class="form-group">
                                                            <label>{{ __('merchant_view.watermark_style') }}</label>
                                                            <div class="example-box m-t-10 clearfix">
                                                                <label class="ftdms-radio radio-primary">
                                                                    <input type="radio" name="video_style" value="1" @if($watermark->data['video_style'] == 1) checked @endif />
                                                                    <span>{{ __('merchant_view.fixed_watermark') }}</span>
                                                                </label>
                                                                <p class="m-t-10 text-cyan">*{{ __('merchant_view.the_top_right_of_the_screen') }}</p>

                                                                <label class="ftdms-radio radio-primary m-t-10">
                                                                    <input type="radio" name="video_style" value="2" @if($watermark->data['video_style'] == 2) checked @endif />
                                                                    <span>{{ __('merchant_view.marquee_watermark') }}</span>
                                                                </label>
                                                                <p class="m-t-10 text-cyan">*{{ __('merchant_view.move_right_to_display') }}</p>

                                                                <label class="ftdms-radio radio-primary m-t-10">
                                                                    <input type="radio" name="video_style" value="3" @if($watermark->data['video_style'] == 3) checked @endif />
                                                                    <span>{{ __('merchant_view.floating_watermark_around') }}</span>
                                                                </label>
                                                                <p class="m-t-10 text-cyan">*{{ __('merchant_view.peripheral_edge') }}</p>

                                                                <label class="ftdms-radio radio-primary m-t-10">
                                                                    <input type="radio" name="video_style" value="4" @if($watermark->data['video_style'] == 4) checked @endif />
                                                                    <span>{{ __('merchant_view.full_screen_floating_watermark') }}</span>
                                                                </label>
                                                                <p class="m-t-10 text-cyan">*{{ __('merchant_view.full_screen_random_floating_display_watermark') }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="form-group m-r-10">
                                                            <label>{{ __('merchant_view.floating_water_printing_new_interval') }}</label>
                                                            <div class="input-group m-r-5">
                                                                <input type="number" class="form-control" name="video_refresh_interval" value="{{ $watermark->data['video_refresh_interval'] }}"/>
                                                                <span class="input-group-btn">
                                                                <span class="btn btn-default">{{ __('merchant_view.seconds') }}</span>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <style>

                                                    </style>
                                                    <div class="col-lg-7">
                                                        <div class="video_img" >
                                                            <img id="video_img" src="" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header"><h4>{{ __('merchant_view.image_watermark_settings') }}</h4></div>
                                                <div class="card-body clearfix">
                                                    <div class="form-group col-sm-7 clearfix">
                                                        <label>Position</label>
                                                        <div class="example-box m-t-10">
                                                            <label class="ftdms-radio radio-primary">
                                                                <input type="radio" name="picture_style" value="1" @if($watermark->data['picture_style'] == 1) checked @endif />
                                                                <span>{{ __('merchant_view.full_screen') }}</span>
                                                            </label>

                                                            <label class="ftdms-radio radio-primary m-t-10">
                                                                <input type="radio" name="picture_style" value="2" @if($watermark->data['picture_style'] == 2) checked @endif />
                                                                <span>{{ __('merchant_view.top_centered') }}</span>
                                                            </label>

                                                            <label class="ftdms-radio radio-primary m-t-10">
                                                                <input type="radio" name="picture_style" value="3" @if($watermark->data['picture_style'] == 3) checked @endif />
                                                                <span>{{ __('merchant_view.centered') }}</span>
                                                            </label>

                                                            <label class="ftdms-radio radio-primary m-t-10">
                                                                <input type="radio" name="picture_style" value="4" @if($watermark->data['picture_style'] == 4) checked @endif />
                                                                <span>{{ __('merchant_view.bottom_centered') }}</span>
                                                            </label>

                                                            <label class="ftdms-radio radio-primary m-t-10">
                                                                <input type="radio" name="picture_style" value="5" @if($watermark->data['picture_style'] == 5) checked @endif />
                                                                <span>{{ __('merchant_view.random_position') }}</span>
                                                            </label>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mt35">
                                                <button class="btn btn-primary" type="button" data-url="{{ route('merchant.merchant_setting.watermark') }}" data-type="POST" onClick="TObj.WatermarkSubmit(this)">提交</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
        <!--End 页面主要内容-->
    </body>
@endsection

@section("js")
    <script src="{{ asset('merchant-static/js/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('merchant-static/js/ion-rangeslider/ion.rangeSlider.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;
            this.formScreenRecordingId = '#form_screen_recording';
            this.formWatermarkId = '#form_watermark';

            this.init = function() {
                // 颜色选取
                $('.colorpicker').colorpicker();

                //滑块
                $(".transparency").ionRangeSlider({
                    min: 0,
                    max: 100,
                    from: {{ $watermark->data['transparency'] }}
                });

                //默认图片
                $("input[type='radio'][name='video_style']").change(function () {
                    var video_style = $(this).val();
                    _self.switchVideoStyle(video_style);
                })

                _self.switchVideoStyle("{{ $watermark->data['video_style'] }}");
            }

            this.switchVideoStyle = function (type) {

                var img = "";
                switch(type){
                    case '2' :
                        img = "{{ asset("/merchant-static/images/watermark2.gif") }}";
                        break;
                    case '3' :
                        img = "{{ asset("/merchant-static/images/watermark3.gif") }}"
                        break;
                    case '4' :
                        img = "{{ asset("/merchant-static/images/watermark4.gif") }}"
                        break;
                    default :
                        img = "{{ asset("/merchant-static/images/watermark1.png") }}"
                        break;
                }
                console.log(type);
                console.log(img);
                $("#video_img").prop('src', img);
            }

            this.ScreeRecordingSubmit = function(obj){
                var ajaxdata = _jM.getFormJson(_self.formScreenRecordingId);

                if(_jM.validate.isEmpty(ajaxdata['status'])){
                    _jM.dialogErMsg("{{ __('merchant_view.the_anti_rip_function_is_not_set') }}");
                    return false;
                }

                var url = $(obj).data('url');
                var type = $(obj).data('type');

                _jM.disabled(obj);
                _jM.ajax({
                    url: url,
                    type: type,
                    data: ajaxdata,
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function () {
                        _jM.dialogSuccess("{{ __('common.operation_succeeded') }}", function () {
                            location.reload();
                        });
                    },
                    complete: function (XMLHttpRequest, textStatus) {
                        _jM.undisabled(obj);
                    }
                });
            }

            this.WatermarkSubmit = function(obj){
                var ajaxdata = _jM.getFormJson(_self.formWatermarkId);

                if(_jM.validate.isEmpty(ajaxdata['status'])){
                    _jM.dialogErMsg("{{ __('merchant_view.watermark_function_is_not_set') }}");
                    return false;
                }

                var url = $(obj).data('url');
                var type = $(obj).data('type');

                _jM.disabled(obj);
                _jM.ajax({
                    url: url,
                    type: type,
                    data: ajaxdata,
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function () {
                        _jM.dialogSuccess("{{ __('common.operation_succeeded') }}", function () {
                            location.reload();
                        });
                    },
                    complete: function (XMLHttpRequest, textStatus) {
                        _jM.undisabled(obj);
                    }
                });
            }
        }

        var TObj = new TObject();
        $(document).ready(function(){
            TObj.init();
        })
    </script>
@endsection
