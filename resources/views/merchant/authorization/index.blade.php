@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.add_authorization') }}</title>
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
                            <div class="card-header"><h4>{{ __('merchant_view.add_authorization') }}</h4></div>
                            <div class="card-body">

                                <ul id="myTabs" class="nav nav-tabs" role="tablist">
                                    <li class="active">
                                        <a href="#authorization" id="authorization-tab" role="tab" data-toggle="tab">
                                            {{ __('merchant_view.activate_authorization') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#log" role="tab" id="log-tab" data-toggle="tab">
                                            {{ __('merchant_view.authorization_record') }}
                                        </a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">

                                    <!--授权-->
                                    <div class="tab-pane fade active in" id="authorization">

                                        <form id="formsubmit">
                                            <div class="input-group m-b-10">
                                                <span class="input-group-addon" id="activation-code">
                                                    {{ __('merchant_view.activation_code') }}
                                                </span>
                                                <input type="text" class="form-control"
                                                       placeholder="{{ __('merchant_view.please_enter_the_activation_code') }}"
                                                       name="code" aria-describedby="activation-code">
                                            </div>
                                            <div class="form-group mt35">
                                                <button class="btn btn-primary" type="button" data-url="{{ route('merchant.authorization.exchange') }}" data-type="POST" onClick="TObj.submit(this)">
                                                    {{ __('common.submit') }}
                                                </button>
                                            </div>
                                        </form>

                                        <p>{{ __('merchant_view.activation_code_hint') }}</p>
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
                        ,page: {
                            layout: [ 'prev', 'page', 'next', 'count', 'limit'], //自定义分页布局
                        }
                        ,limits: [10,30,50,100]
                        ,cols: [[
                            {field:'code', title: "{{ __('merchant_view.activation_code') }}", sort: true},
                            {field:'auth_count', title: "{{ __('merchant_view.the_number_of_new_u_disk_authorizations') }}", sort: true},
                            {field:'active_time', title: "{{ __('merchant_view.activation_time') }}", sort: true},
                        ]]
                    });
                });

            }

            this.submit = function(obj){
                var ajaxdata = _jM.getFormJson(_self.formId);

                if(_jM.validate.isEmpty(ajaxdata['code'])){
                    _jM.dialogErMsg("{{ __('merchant_view.please_enter_the_activation_code') }}");
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
