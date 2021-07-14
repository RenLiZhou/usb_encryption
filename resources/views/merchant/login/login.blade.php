@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.login_page_merchant_background') }}</title>
@endsection

@section("css")
    <link href="{{ asset('merchant-static/js/bootstrap-validator/css/bootstrapValidator.min.css') }}" rel="stylesheet">
@endsection

@section("content")
    <body>
        <div class="loginpage" style="background: url({{ asset('merchant-static/images/login-bg.png') }}) no-repeat center;background-size: 100% 100%">
            <div class="login">
                <div class="login-center">
                    <div class="login-header text-center">
                        {{ __('merchant_view.business_background') }}
                    </div>
                    <form id="formsubmit">
                        <div class="form-group has-feedback feedback-left">
                            <span class="ftsucai-65 form-control-feedback" aria-hidden="true"></span>
                            <input type="text" placeholder="{{ __('merchant_view.please_enter_your_username') }}" class="form-control" name="uname"/>
                        </div>
                        <div class="form-group has-feedback feedback-left">
                            <span class="ftsucai-216 form-control-feedback" aria-hidden="true"></span>
                            <input type="password" placeholder="{{ __('merchant_view.please_enter_a_password') }}" class="form-control" name="psword"/>
                        </div>
                        <div class="form-group has-feedback feedback-left row">
                            <div class="col-xs-7">
                                <span class="ftsucai-mao form-control-feedback" aria-hidden="true"></span>
                                <input type="text" name="captcha" class="form-control" placeholder="{{ __('merchant_view.verification_code') }}">
                            </div>
                            <div class="col-xs-5">
                                <img src="{{ captcha_src() }}" class="pull-right" id="captcha" style="cursor: pointer;"
                                     onclick="TObj.flushForm()" title="{{ __('merchant_view.click_to_refresh') }}" alt="captcha">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-primary" type="button" onClick="TObj.login(this)">{{ __('merchant_view.log_in_now') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection

@section("js")
    <script type="text/javascript" src="{{ asset('merchant-static/js/bootstrap-validator/js/bootstrapValidator.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;

            this.formObj = $("#formsubmit");

            this.init = function(){
                _jM.validates(this.formObj,{
                    uname: {
                        validators: {
                            notEmpty: {
                                message: "{{ __('merchant_view.business_user_name_is_empty') }}"
                            }
                        }
                    },
                    psword: {
                        validators: {
                            notEmpty: {
                                message: "{{ __('merchant_view.password_is_empty') }}"
                            }
                        }
                    },
                    captcha: {
                        validators: {
                            notEmpty: {
                                message: "{{ __('merchant_view.graphic_verification_code_is_empty') }}"
                            }
                        }
                    }
                });
            }

            this.flushForm = function() {
                $('#captcha').attr('src', '{{ captcha_src() }}'+Math.random());
                $('[name="captcha"]').val('');
            }

            this.login = function(obj){
                _self.formObj.data("bootstrapValidator").validate();
                if (_self.formObj.data("bootstrapValidator").isValid()) {

                    var ajaxdata = _self.formObj.serialize();

                    _jM.disabled(obj);
                    _jM.ajax({
                        url: '{{ route("merchant.signin") }}',
                        type: 'POST',
                        data: ajaxdata,
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                            _self.flushForm();
                        },
                        success: function () {
                            _jM.dialogOkMsg("{{ __('merchant_view.login_is_successful_we_are_redirecting_for_you') }}");
                            window.top.location.href="{{ route('merchant.main') }}";
                        },
                        complete: function (XMLHttpRequest, textStatus) {
                            _jM.undisabled(obj);
                        }
                    });
                }
            }
        }
        var TObj = new TObject();
        $(document).ready(function(){
            TObj.init();
        })
    </script>
@endsection
