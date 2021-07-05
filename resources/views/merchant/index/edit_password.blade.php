@extends("merchant.layouts.main")

@section("title")
    <title>修改密码</title>
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
                                    <label for="title">原始密码</label>
                                    <input type="password" class="form-control" name="old_password" value="" placeholder="请输入原始密码"/>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="title">新密码</label>
                                    <input type="password" class="form-control" name="new_password1" value="" placeholder="请输入新密码"/>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="title">确认新密码</label>
                                    <input type="password" class="form-control" name="new_password2" value="" placeholder="请确认新密码"/>
                                </div>


                                <div class="form-group col-md-12 text-center mt35">
                                    <button type="button" class="btn btn-primary" data-url="{{ route('merchant.password.update') }}" data-type="POST" onClick="TObj.submit(this)">确定</button>
                                    <button type="button" class="btn btn-default ml15">取消</button>
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
                                message: '原始密码为空'
                            }
                        }
                    },
                    new_password1: {
                        validators: {
                            notEmpty: {
                                message: '新密码为空'
                            }
                        }
                    },
                    new_password2: {
                        validators: {
                            notEmpty: {
                                message: '确认密码为空'
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
                        _jM.dialogErMsg('确认密码不一致');
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
                            _jM.dialogSuccess('修改成功',function () {
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
