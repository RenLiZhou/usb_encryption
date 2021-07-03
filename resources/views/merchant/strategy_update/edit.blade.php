@extends("merchant.layouts.main")

@section("title")
    <title>编辑更新策略</title>
@endsection

@section("css")
    <link href="{{ asset('merchant-static/js/bootstrap-validator/css/bootstrapValidator.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}"/>
@endsection

@section("content")
    <body>
    <!--页面主要内容-->
    <main class="ftdms-layout-content">
        <div class="container-fluid">
            <div class="row mt15 mb60">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><h4>编辑更新策略 | <a class="text-cyan" href="javascript:;" onclick="_jM.dialogCloseCurIf()">返回列表</a></h4></div>

                        <div class="card-body">

                            <form class="row" id="formsubmit">

                                <div class="form-group col-md-12">
                                    <label for="title">策略名称</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="请输入策略名称"/>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="title">自动更新提示</label>
                                    <div class="example-box">
                                        <label class="ftdms-checkbox checkbox-primary m-t-10">
                                            <input type="checkbox" name="hint" value="1" @if($data->automatic_update_prompt == 1) checked @endif><span>U盘运行后自动提示更新文件</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 clearfix">
                                    <label for="valid_time">策略生效时间</label>
                                    <div class="example-box">
                                        <label class="ftdms-radio radio-primary m-t-10">
                                            <input type="radio" name="valid_type" value="1"><span>不生效</span>
                                        </label>
                                        <label class="ftdms-radio radio-primary mt15">
                                            <input type="radio" name="valid_type" value="2"><span>立即生效</span>
                                        </label>
                                        <div>
                                            <label class="ftdms-radio radio-primary mt15 pull-left">
                                                <input type="radio" name="valid_type" checked value="3"><span>指定日期</span>
                                            </label>
                                            <span class="col-sm-2 mt15">
                                                <div class="input-group m-t-5">
                                                    <input type="text" class="form-control form_datetime" name="valid_time" value="{{ $data->valid_time }}" aria-label="..." />
                                                    <span class="input-group-addon">
                                                        <span class="ft ftsucai-413" aria-label="..."></span>
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 mt15">
                                    <div>
                                        <label>从文件库选择文件</label>
                                        <button class="btn btn-dark btn-w-md m-l-10" type="button" onclick="TObj.addFile()">选择文件</button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="file_list" lay-filter="file_list">
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 text-center">
                                    <button type="button" class="btn btn-primary" data-url="{{ route('merchant.strategy_update.update',['strategy_update'=>$data->id]) }}" data-type="PATCH" onClick="TObj.submit(this)">确定</button>
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
    <script src="{{ asset('layui/layui.js') }}"></script>
    <script type="text/html" id="barFile">
        <a class='btn btn-xs btn-default delete' title='删除' data-toggle='tooltip' lay-event="del"><i class='ftsucai-del'></i></a>
    </script>
    <script>
        var TObject = function(){
            var _self = this;
            this.files = "{{ htmlspecialchars($strategy_files) }}";
            this.formId = '#formsubmit';
            this.filesTable;

            this.init = function() {

                this.filesData();

                laydate.render({
                    elem: '.form_datetime',
                    type: 'datetime',
                    lang: 'en'
                });

                // 提示
                $('[data-toggle="tooltip"]').tooltip({
                    "container" : 'body',
                });

                //切换
                $("input[name='valid_type']").change(function () {
                    var val = $(this).val();
                    if(val == 3){
                        _jM.undisabled(".form_datetime");
                    }else{
                        _jM.disabled(".form_datetime");
                    }
                })

                //验证
                _jM.validates($(this.formId),{
                    name: {
                        validators: {
                            notEmpty: {
                                message: '策略名称为空'
                            }
                        }
                    }
                },{
                    valid: 'glyphicon glyphicon-ok right18 top27',
                    invalid: 'glyphicon glyphicon-remove right18 top27',
                    validating: 'glyphicon glyphicon-refresh right18 top27'
                });
            }

            this.filesData = function(){
                //初始化数据表格
                var reg = new RegExp("&amp;quot;","g");
                _self.files = _self.files.replace(reg,"\"");
                _self.files = $.parseJSON(_self.files);

                layui.use('table', function(){
                    var table = layui.table;

                    _self.filesTable = table.render({
                        elem: '#file_list'
                        ,data: _self.files
                        ,page: true
                        ,limits: [10,30,50,100]
                        ,cols: [[
                            {field:'name', title: '文件名', sort: true},
                            {field:'path', title: '路径', sort: true},
                            {field:'type', title: '类型', sort: true},
                            {field:'size', title: '大小', sort: true},
                            {title:'操作', toolbar: '#barFile'}
                        ]]
                    });

                    table.on('tool(file_list)', function(obj){
                        if(obj.event === 'del'){
                            var index = $(obj.tr).attr("data-index");
                            _self.files.splice(index,1);
                            //执行重载
                            _self.filesTable.reload();
                        }
                    });
                });
            }

            this.addFile = function () {
                _self.files.push({
                    'path': '/asdas/asda',
                    'name': 'asdas',
                    'size': 10154,
                    'type': 'pdf' + Math.random()*100
                })

                //执行重载
                _self.filesTable.reload();
            }

            this.submit = function(obj){
                $(this.formId).data("bootstrapValidator").validate();
                if ($(this.formId).data("bootstrapValidator").isValid()) {
                    var url = $(obj).data('url');
                    var type = $(obj).data('type');

                    var ajaxdata = _jM.getFormJson(_self.formId);

                    if(_jM.validate.isEmpty(ajaxdata['valid_type']) || (ajaxdata['valid_type'] == 3 && _jM.validate.isEmpty(ajaxdata['valid_time']))){
                        _jM.dialogErMsg('请设置生效时间');
                        return false;
                    }

                    ajaxdata['files'] = _self.files;

                    _jM.disabled(obj);
                    _jM.ajax({
                        url: url,
                        type: type,
                        data: ajaxdata,
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function () {
                            _jM.dialogSuccess('编辑成功', function () {
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
