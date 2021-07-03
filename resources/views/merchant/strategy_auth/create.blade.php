@extends("merchant.layouts.main")

@section("title")
    <title>新建权限策略</title>
@endsection

@section("css")
    <link href="{{ asset('merchant-static/js/bootstrap-validator/css/bootstrapValidator.min.css') }}" rel="stylesheet">
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
                                <label for="title">策略名称</label>
                                <input type="text" class="form-control" name="name" value="" placeholder="请输入策略名称"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title">运行次数(-1表示永久)</label>
                                <input type="number" class="form-control" name="run_number" value="" placeholder="请输入运行次数"/>
                            </div>

                            <div class="form-group col-md-12 clearfix">
                                <label for="valid_time">U盘有效期</label>
                                <div class="example-box">
                                    <label class="ftdms-radio radio-primary mt15 clearfix">
                                        <input type="radio" name="expired_type" value="0" checked><span>永久有效</span>
                                    </label>
                                    <div class="clearfix  m-t-5">
                                        <label class="ftdms-radio radio-primary mt20 pull-left">
                                            <input type="radio" name="expired_type" value="1"><span>固定天数</span>
                                        </label>
                                        <span class="col-sm-4 ml15 m-t-10">
                                            <input type="text" class="form-control form_day" name="expired_day" disabled value=""/>
                                        </span>
                                    </div>
                                    <div class="clearfix">
                                        <label class="ftdms-radio radio-primary mt20 pull-left">
                                            <input type="radio" name="expired_type" value="2"><span>固定日期</span>
                                        </label>
                                        <span class="col-sm-4 ml15">
                                            <div class="input-group m-t-10">
                                                <input type="text" class="form-control form_datetime" name="expired_time" disabled aria-label="..." />
                                                <span class="input-group-addon">
                                                    <span class="ft ftsucai-413" aria-label="..."></span>
                                                </span>
                                              </div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12 text-center mt35">
                                <button type="button" class="btn btn-primary" data-url="{{ route('merchant.strategy_auth.store') }}" data-type="POST" onClick="TObj.submit(this)">确定</button>
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
    <script type="text/javascript" src="{{ asset('laydate/laydate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('merchant-static/js/bootstrap-validator/js/bootstrapValidator.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;
            this.formId = '#formsubmit';

            this.init = function() {

                laydate.render({
                    elem: '.form_datetime',
                    type: 'datetime',
                    lang: 'en'
                });


                $("input[name='expired_type']").change(function () {
                    var val = $(this).val();
                    if(val == 0){
                        _jM.disabled(".form_datetime");
                        _jM.disabled(".form_day");
                    }else if(val == 1){
                        _jM.disabled(".form_datetime");
                        _jM.undisabled(".form_day");
                    }else if(val == 2){
                        _jM.disabled(".form_day");
                        _jM.undisabled(".form_datetime");
                    }
                })

                _jM.validates($(this.formId),{
                    name: {
                        validators: {
                            notEmpty: {
                                message: '策略名称为空'
                            }
                        }
                    },
                    run_number: {
                        validators: {
                            notEmpty: {
                                message: '运行次数为空'
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

                    if(_jM.validate.isEmail(ajaxdata['expired_type'])
                        || (ajaxdata['expired_type'] == 1 && _jM.validate.isEmpty(ajaxdata['expired_day']))
                        || (ajaxdata['expired_type'] == 2 && _jM.validate.isEmpty(ajaxdata['expired_time']))
                    ){
                        _jM.dialogErMsg('请设置生效时间');
                        return false;
                    }

                    _jM.disabled(obj);
                    _jM.ajax({
                        url: url,
                        type: type,
                        data: ajaxdata,
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function () {
                            _jM.dialogSuccess('创建成功', function () {
                                parent.location.reload();
                            });
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
