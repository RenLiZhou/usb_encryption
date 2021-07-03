@extends("merchant.layouts.main")

@section("title")
    <title>登录页面-商家后台</title>
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
                        商家后台
                    </div>
                    <form id="formsubmit">
                        <div class="form-group has-feedback feedback-left">
                            <span class="ftsucai-65 form-control-feedback" aria-hidden="true"></span>
                            <input type="text" placeholder="请输入您的用户名" class="form-control" name="uname"/>
                        </div>
                        <div class="form-group has-feedback feedback-left">
                            <span class="ftsucai-216 form-control-feedback" aria-hidden="true"></span>
                            <input type="password" placeholder="请输入密码" class="form-control" name="psword"/>
                        </div>
                        <div class="form-group has-feedback feedback-left row">
                            <div class="col-xs-7">
                                <span class="ftsucai-mao form-control-feedback" aria-hidden="true"></span>
                                <input type="text" name="captcha" class="form-control" placeholder="验证码">
                            </div>
                            <div class="col-xs-5">
                                <img src="{{ captcha_src() }}" class="pull-right" id="captcha" style="cursor: pointer;"
                                     onclick="TObj.flushForm()" title="点击刷新" alt="captcha">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-primary" type="button" onClick="TObj.login(this)">立即登录</button>
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
                                message: '商家用户名为空'
                            }
                        }
                    },
                    psword: {
                        validators: {
                            notEmpty: {
                                message: '密码为空'
                            }
                        }
                    },
                    captcha: {
                        validators: {
                            notEmpty: {
                                message: '图形验证码为空'
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
                            _jM.dialogOkMsg('登录成功,正在为您跳转');
                            location.href="{{ route('merchant.main') }}";
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
