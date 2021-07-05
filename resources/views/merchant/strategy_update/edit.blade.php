@extends("merchant.layouts.main")

@section("title")
    <title>编辑更新策略</title>
@endsection

@section("css")
    <link rel="stylesheet" href="{{ asset('merchant-static/js/bootstrap-validator/css/bootstrapValidator.min.css') }}">
    <link rel="stylesheet" href="{{ asset('merchant-static/js/bootstrap-treeview/css/bootstrap-treeview.css') }}">
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
                                            <input type="radio" name="valid_type" checked value="2"><span>立即生效</span>
                                        </label>
                                        <div>
                                            <label class="ftdms-radio radio-primary mt15 pull-left">
                                                <input type="radio" name="valid_type" checked value="3"><span>指定日期</span>
                                            </label>
                                            <span class="col-sm-2 ml15">
                                                <div class="input-group m-t-5">
                                                    <input type="text" class="form-control form_datetime" value="{{ $data->valid_time }}" name="valid_time" aria-label="..." />
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
                                        <button class="btn btn-dark btn-w-md m-l-10" type="button" onclick="TObj.selectFileOpen()">选择文件</button>
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

        {{-- 选择文件夹 --}}
        <div class="modal fade bs-example-modal-lg" id="folder-modal" tabindex="-1" role="dialog" aria-labelledby="myFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="margin-top: 10vh">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">选择文件</h4>
                    </div>
                    <div class="modal-body" style="max-height: 50vh;overflow-y: auto">
                        <div id="procitytree" class="folder-modal"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" onclick="TObj.addFile()">确认选择</button>
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
    <script type="text/javascript" src="{{ asset('merchant-static/js/bootstrap-treeview/js/bootstrap-treeview.js') }}"></script>
    <script type="text/javascript" src="{{ asset('layui/layui.js') }}"></script>
    <script type="text/html" id="barFile">
        <a class='btn btn-xs btn-default delete' title='删除' data-toggle='tooltip' lay-event="del"><i class='ftsucai-del'></i></a>
    </script>
    <script>
        var TObject = function(){
            var _self = this;
            this.files_data = "{{ htmlspecialchars($strategy_files) }}"
            this.files = [];
            this.formId = '#formsubmit';
            this.filesTable;

            this.fileModal = "#folder-modal";
            this.treeId = "#procitytree"; //tree

            this.select_file_status = false;

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
                _self.files_data = _self.files_data.replace(reg,"\"");
                _self.files_data = $.parseJSON(_self.files_data);
                $(_self.files_data).each(function (index,item) {
                    _self.files.push({
                        'path': item.path,
                        'name': item.name,
                        'size': (item.size/1024).toFixed(1) + 'kb',
                        'type': item.type
                    });
                })

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
                            {field:'type', title: '类型', sort: true, width: 150},
                            {field:'size', title: '大小', sort: true, width: 150},
                            {title:'操作', toolbar: '#barFile', width: 100}
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
                var select_file = $(_self.treeId).treeview('getSelected');

                if(select_file.length <= 0){
                    _jM.dialogMsg('请选择文件');
                    return false;
                }

                var channel  = false;
                var files_data = [];
                $(select_file).each(function(index, item){
                    if(!_jM.validate.isEmpty(item.path) || !_jM.validate.isEmpty(item.text) || !_jM.validate.isEmpty(item.type)){
                        if(item.type == 'folder'){
                            channel = true;
                            return false;
                        }

                        files_data.push({
                            'path': item.path,
                            'name': item.text,
                            'size': (item.size/1024).toFixed(1) + 'kb',
                            'type': item.text.substr(item.text.lastIndexOf('.')+1)
                        })
                    }
                });

                if(channel){
                    _jM.dialogMsg('不允许选择文件夹');
                    return false;
                }

                if(_jM.validate.isEmpty(files_data)){
                    _jM.dialogMsg('请选择文件');
                    return false;
                }

                $(files_data).each(function(index, item){

                    //对存在的文件实现过滤
                    var is_exist = false;
                    $(_self.files).each(function(index2, item2){
                        if(item2.path == item.path){
                            is_exist = true;
                        }
                    })
                    if(!is_exist){
                        _self.files.push(item);
                    }else{
                        _jM.dialogMsg(item.name + '重复,已自动过滤');
                    }
                });

                //执行重载
                _self.filesTable.reload();
                $(_self.fileModal).modal('hide');
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

                    ajaxdata['files'] = [];
                    $(_self.files).each(function(index, item){
                        ajaxdata['files'].push(item.path);
                    })

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

            //选择文件
            this.selectFileOpen = function () {
                if(_self.select_file_status){
                    _jM.dialogMsg('加载中');
                    return false;
                }

                _self.select_file_status = true;
                var load = _jM.dialogLoad();

                _jM.ajax({
                    url: '{{ route("merchant.file.all_files",["type" => 'file']) }}',
                    type: 'GET',
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function (resMsg, resData) {
                        var treeData = _self.traverse(resData);

                        $(_self.treeId).treeview({
                            data: treeData,
                            color: "#111", //所有节点使用的默认前景色，这个颜色会被节点数据上的backColor属性覆盖.
                            backColor: "#fff", //所有节点使用的默认背景色，这个颜色会被节点数据上的backColor属性覆盖.
                            collapseIcon: "ftsucai-caret-right", //节点被折叠时显示的图标
                            expandIcon: "ftsucai-caret-left", //节点展开时显示的图标        String
                            emptyIcon: "empty_icon",
                            multiSelect: true, //是否可以同时选择多个节点      Boolean
                            onhoverColor: "#efefef", //光标停在节点上激活的默认背景色      String
                            selectedBackColor: "#efefef", //当节点被选中时的背景色
                            selectedColor: "#111", //当节点被选中时的背景色
                            showIcon: true, //是否显示节点图标
                            showBorder: false,
                            levels: 2, //设置整棵树的层级数  Integer
                        });
                    },
                    complete: function () {
                        _jM.dialogClose(load);
                        _self.select_file_status = false;
                        $(_self.fileModal).modal({backdrop: 'static', keyboard: false});
                    }
                });
            }

            //转化数据
            this.traverse = function (obj) {
                for (var data in obj) {
                    var icon = 'file glyphicon glyphicon-file';
                    if(obj[data].type == 'folder'){
                        icon = 'folder glyphicon glyphicon-folder-close';
                    }
                    obj[data].icon = icon;
                    if(typeof(obj[data].nodes) == "object" && !_jM.validate.isEmpty(obj[data].nodes)){
                        obj[data].nodes = _self.traverse(obj[data].nodes); //递归遍历
                    }
                }
                return obj;
            }
        }

        var TObj = new TObject();
        $(document).ready(function(){
            TObj.init();
        })
    </script>
@endsection
