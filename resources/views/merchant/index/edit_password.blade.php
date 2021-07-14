@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.change_password') }}</title>
@endsection

@section("content")
    <body>
    <!--页面主要内容-->
    <main class="ftdms-layout-content">
        <div class="row mb60 mt15">
            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <form class="row" id="formsubmit">

                                <div class="form-group col-md-12">
                                    <label for="title">{{ __('merchant_view.original_password') }}</label>
                                    <input type="password" class="form-control" name="old_password" value=""
                                           placeholder="{{ __('merchant_view.please_enter_the_original_password') }}"/>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="title">{{ __('merchant_view.new_password') }}</label>
                                    <input type="password" class="form-control" name="new_password1" value=""
                                           placeholder="{{ __('merchant_view.please_enter_a_new_password') }}"/>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="title">{{ __('merchant_view.confirm_new_password') }}</label>
                                    <input type="password" class="form-control" name="new_password2" value=""
                                           placeholder="{{ __('merchant_view.please_confirm_the_new_password') }}"/>
                                </div>


                                <div class="form-group col-md-12 text-center mt35">
                                    <button type="button" class="btn btn-primary" data-url="{{ route('merchant.password.update') }}" data-type="POST" onClick="TObj.submit(this)">
                                        {{ __('common.ok') }}
                                    </button>
                                    <button type="button" class="btn btn-default ml15">
                                        {{ __('common.cancel') }}
                                    </button>
                                </div>
                            </form>

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
    <script type="text/javascript" src="{{ asset('merchant-static/js/bootstrap-validator/js/bootstrapValidator.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;
            this.formId = "#formsubmit"

            this.init = function() {


                _jM.validates($(this.formId),{
                    old_password: {
                        validators: {
                            notEmpty: {
                                message: "{{ __('merchant_view.the_original_password_is_empty') }}"
                            }
                        }
                    },
                    new_password1: {
                        validators: {
                            notEmpty: {
                                message: "{{ __('merchant_view.the_new_password_is_empty') }}"
                            }
                        }
                    },
                    new_password2: {
                        validators: {
                            notEmpty: {
                                message: "{{ __('merchant_view.confirm_that_the_password_is_empty') }}"
                            }
                        }
                    }
                },{
                    valid: 'glyphicon glyphicon-ok right18 top27',
                    invalid: 'glyphicon glyphicon-remove right18 top27',
                    validating: 'glyphicon glyphicon-refresh right18 top27'
                });
            }

            this.submit = function(obj){
                $(this.formId).data("bootstrapValidator").validate();
                if ($(this.formId).data("bootstrapValidator").isValid()) {
                    var url = $(obj).data('url');
                    var type = $(obj).data('type');

                    var ajaxdata = _jM.getFormJson(_self.formId);

                    if(ajaxdata['new_password1'] != ajaxdata['new_password2']){
                        _jM.dialogErMsg("{{ __('merchant_view.confirm_that_the_passwords_are_inconsistent') }}");
                        return false;
                    }

                    ajaxdata['password'] = ajaxdata['new_password1'];

                    delete ajaxdata['new_password1'];
                    delete ajaxdata['new_password2'];

                    _jM.disabled(obj);
                    _jM.ajax({
                        url: url,
                        type: type,
                        data: ajaxdata,
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function () {
                            _jM.dialogSuccess("{{ __('merchant_view.modified_successfully') }}", function () {
                                _jM.dialogCloseCurIf();
                            });
                            _jM.dialogClose();
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
