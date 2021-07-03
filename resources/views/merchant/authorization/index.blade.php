@extends("merchant.layouts.main")

@section("title")
    <title>增加授权</title>
@endsection

@section("css")
    <link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}"/>
@endsection

@section("content")
    <body>
        <!--页面主要内容-->
        <main class="ftdms-layout-content">
            <div class="container-fluid mb90">
                <div class="row mt15">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><h4>增加授权</h4></div>
                            <div class="card-body">

                                <ul id="myTabs" class="nav nav-tabs" role="tablist">
                                    <li class="active"><a href="#authorization" id="authorization-tab" role="tab" data-toggle="tab">授权</a></li>
                                    <li><a href="#log" role="tab" id="log-tab" data-toggle="tab">授权记录</a></li>
                                </ul>
                                <div id="myTabContent" class="tab-content">

                                    <!--授权-->
                                    <div class="tab-pane fade active in" id="authorization">

                                        <form id="formsubmit">
                                            <div class="input-group m-b-10">
                                                <span class="input-group-addon" id="activation-code">激活码</span>
                                                <input type="text" class="form-control" placeholder="请输入激活码" name="code" aria-describedby="activation-code">
                                            </div>
                                            <div class="form-group mt35">
                                                <button class="btn btn-primary" type="button" data-url="{{ route('merchant.authorization.exchange') }}" data-type="POST" onClick="TObj.submit(this)">提交</button>
                                            </div>
                                        </form>

                                        <p>注意： 购买后，系统将自动发送包含激活码的邮件到您的邮箱。 在上方填写激活码后即可增加U盘授权。</p>
                                    </div>

                                    <!--记录-->
                                    <div class="tab-pane fade" id="log">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="log_list">
                                            </table>
                                        </div>
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
    <script src="{{ asset('layui/layui.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;
            this.logs = "{{ htmlspecialchars($activation_logs) }}";
            this.formId = '#formsubmit';

            this.init = function() {
                //初始化数据表格
                var reg = new RegExp("&amp;quot;","g");
                _self.logs = _self.logs.replace(reg,"\"");
                _self.logs = $.parseJSON(_self.logs);

                layui.use('table', function(){
                    var table = layui.table;

                    table.render({
                        elem: '#log_list'
                        ,data: _self.logs
                        ,page: true
                        ,limits: [10,30,50,100]
                        ,cols: [[
                            {field:'code', title: '激活码', sort: true},
                            {field:'auth_count', title: '新增U盘授权数量', sort: true},
                            {field:'active_time', title: '激活时间', sort: true},
                        ]]
                    });
                });

            }

            this.submit = function(obj){
                var ajaxdata = _jM.getFormJson(_self.formId);

                if(_jM.validate.isEmpty(ajaxdata['code'])){
                    _jM.dialogErMsg('请输入激活码');
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
                        _jM.dialogSuccess('操作成功', function () {
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
