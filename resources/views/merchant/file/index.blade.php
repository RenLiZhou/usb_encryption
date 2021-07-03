﻿@extends("merchant.layouts.main")

@section("title")
    <title>文件管理</title>
@endsection

@section("css")
    <link rel="stylesheet" href="{{ asset('merchant-static/js/jconfirm/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('merchant-static/js/bootstrap-treeview/css/bootstrap-treeview.css') }}">
@endsection

@section("content")
    <body>
        <!--页面主要内容-->
        <main class="ftdms-layout-content">
            <div class="container-fluid mb90">
                <div class="row mt15">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>文件管理</h4>
                                <div class="pull-right file-col-row">
                                    <span class="ftsucai-app m-r-5 file-col" onClick="TObj.switchFoldersClass('col')"></span>
                                    <span class="ftsucai-format_align_justify m-r-5 file-row" onClick="TObj.switchFoldersClass('row')"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header font15">
                                        <div class="pull-left">当前位置：</div>
                                        <div class="pull-left" id="pathSite"></div>
                                    </div>
                                    <div class="card-header">
                                        <button class="btn btn-warning m-r-10 btn-sm" onClick="TObj.checkAll()">全选/取消</button>
                                        <button class="btn btn-warning m-r-10 btn-sm" onclick="TObj.moreDelete()">批量删除</button>
                                        <button class="btn btn-warning m-r-10 btn-sm" onclick="TObj.moreMoveSelectFolder()">批量移动</button>
                                        <button class="btn btn-warning m-r-10 btn-sm" onclick="TObj.createFolderOpen()">新建文件夹</button>
                                        <button class="btn btn-warning m-r-10 btn-sm">上传文件</button>
                                        <button class="btn btn-warning m-r-10 btn-sm pull-right" onclick="TObj.foldersData('/')">返回根目录</button>
                                    </div>
                                    <div class="card-body clearfix">
                                        <ul id="folders" class="clearfix">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        {{-- 选择文件夹 --}}
        <div class="modal fade bs-example-modal-lg" id="folder-modal" tabindex="-1" role="dialog" aria-labelledby="myFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="margin-top: 10vh">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">选择文件夹</h4>
                    </div>
                    <div class="modal-body" style="max-height: 50vh;overflow-y: auto">
                        <div id="procitytree" class="folder-modal"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" onclick="TObj.moreMove()">移动到该文件夹</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 新建文件夹 --}}
        <div class="modal fade" id="create-folder-modal" tabindex="-1" role="dialog" aria-labelledby="myNewFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 10vh">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">新建文件夹</h4>
                    </div>
                    <div class="modal-body" style="max-height: 50vh;overflow-y: auto">
                        <input type="text" class="form-control" id="folder_name" value="" placeholder="请输入文件夹名"/>
                        <br />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" onclick="TObj.createFolder()">创建</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 重命名 --}}
        <div class="modal fade" id="rename-modal" tabindex="-1" role="dialog" aria-labelledby="myRenameModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 10vh">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">重命名</h4>
                    </div>
                    <div class="modal-body" style="max-height: 50vh;overflow-y: auto">
                        <input type="text" class="form-control" id="rename" value="" placeholder="请输入新的名称"/>
                        <br />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" onclick="TObj.rename()">提交</button>
                    </div>
                </div>
            </div>
        </div>

        <ul class="rightMenu" id="rightMenu">
            <li class="ftsucai-del delete" onmousedown="TObj.moreDelete()">删除</li>
            <li class="ftsucai-44 move" onmousedown="TObj.moreMoveSelectFolder()">移动</li>
            <li class="ftsucai-edit-2 rename" onmousedown="TObj.renameOpen()">重命名</li>
        </ul>
        <!--End 页面主要内容-->
    </body>
    <style>
        .file-col-row{
            height:16px;
        }
        .file-col-row span{
            font-size: 28px;
            position: relative;
            top:-10px;
        }
        .file_manager_col{
            padding: 0px;
            display: block;
        }
        .file_manager_col li{
            position: relative;
            border: 1px #fff solid;
            float: left;
            width: 8.6%;
            padding: 1vw 1%;
            overflow: hidden;
            margin:0px 0.7% 0.7vw;
            border-radius: 7px;
            background: none;
        }
        .file_manager_col li .checkbox{
            display: none;
            position: absolute;
            top:0.3vw;
            right:0.3vw;
            height:17px;
            width: 17px;
        }
        .file_manager_col li img.folder-img{
            display: block;
            width: 60%;
            margin: 0 20%;
        }

        .file_manager_col li .name{
            margin-top: 0.3vw;
            width: 100%;
            text-align: center;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
            margin-bottom: 0px;
        }

        .file_manager_col li.active,.file_manager_col li:hover{
            background: #efefef;
        }

        .file_manager_col li.active .checkbox,.file_manager_col li:hover .checkbox{
            display: block;
        }

        .file_manager_col li .tool{
            display: none;
        }


        .file_manager_row{
            padding: 0px;
            display: block;
        }
        .file_manager_row li{
            border-bottom: 1px #eee solid;
            width: 98%;
            padding: 15px 1%;
            height:64px;
            overflow: hidden;
            background: none;
        }
        .file_manager_row li .checkbox{
            height:16px;
            width: 16px;
            float: left;
            margin-top:9px;
        }

        .file_manager_row li img.folder-img{
            width: 35px;
            float: left;
            margin-left: 15px;
        }

        .file_manager_row li.top-folder-item img.folder-img{
            margin-left:30px;
        }

        .file_manager_row li .name{
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
            margin-bottom: 0px;
            float: left;
            line-height: 34px;
            margin-left: 8px;
        }

        .file_manager_row li .tool{
            float: right;
            margin-left: 17px;
            line-height: 34px;
            cursor:pointer;
        }

        .file_manager_row li.active,.file_manager_row li:hover{
            background: #efefef;
        }


        .rightMenu {
            position: fixed;
            display: none;
            padding: 8px 20px 8px 4px;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 0 3px #eee;
            font-size: 14px;
            color: #66757f;
        }
        .rightMenu li {
            display: block;
            cursor: pointer;
            line-height: 32px;
        }
        .rightMenu li:hover {
            color: #7ebee3;
            border-color: #7ebee3;
        }
        .rightMenu li:before {
            float: left;
            width: 26px;
            text-align: center;
        }


        .folder-modal.treeview span.node-icon{
            width: 20px;
            font-size: 24px;
            position: relative;
            top:5px;
            margin-right: 13px;
        }

        .folder-modal.treeview span.folder{
            color: #FFD977;
        }

        .folder-modal.treeview span.file{
            color: #ccc;
        }

        .folder-modal.treeview span.expand-icon{
            font-size: 20px;
            position: relative;
            top: 2px;
            color: #ccc;
        }
        .folder-modal .node-procitytree{
            padding: 2px 15px;
        }
        .folder-modal.treeview span.empty_icon{
            width: 20px;
            height: 30px;
            display: block;
            float: left;
        }
    </style>
@endsection

@section("js")
    <script src="{{ asset('merchant-static/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('merchant-static/js/jconfirm/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('merchant-static/js/bootstrap-treeview/js/bootstrap-treeview.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;

            this.click_times_event = null; //单击双击的时间事件
            this.tree_event = null;

            this.folders_class = _jM.getCookie('folders_class'); //文件夹样式

            this.right_menu = ".rightMenu";  //右键菜单
            this.folder_id = "#folders"; //文件夹主体ID
            this.folder_item = ".folder-item"; //文件夹类名
            this.checkbox = ".checkbox"; //复选框
            this.treeId = "#procitytree"; //tree
            this.folder_modal = "#folder-modal";
            this.create_folder_modal = "#create-folder-modal"
            this.rename_modal = "#rename-modal"


            this.current_path = "/"; //当前路径

            this.rename_path = ""; //重命名路径

            this.all_checked_status = false; //是否全选
            this.load_path_status = false; //当前数据加载
            this.delete_path_status = false; //删除状态
            this.move_path_status = false; //移动状态
            this.rename_path_status = false; //重命名状态
            this.newfolder_status = false; //新建文件夹状态

            this.init = function() {

                _self.foldersClass(); //文件夹样式
                _self.foldersData(_self.current_path); //文件夹数据

                //单击
                $(_self.folder_id).on('click', ".folder-img,.name", function(){
                    var that = $(this);
                    var is_checked = that.siblings(_self.checkbox).prop('checked');
                    clearTimeout(_self.click_times_event);
                    _self.click_times_event = setTimeout(function(){
                        if(is_checked){
                            that.siblings(_self.checkbox).prop('checked',false);
                            that.parent(_self.folder_item).removeClass('active');
                        }else{
                            that.siblings(_self.checkbox).prop('checked',true);
                            that.parent(_self.folder_item).addClass('active');
                        }
                    },500);
                });

                //双击事件
                $(_self.folder_id).on('dblclick', ".folder-img,.name", function(){
                    clearTimeout(_self.click_times_event);
                    var path = $(this).parent(_self.folder_item).data('path');
                    var type = $(this).parent(_self.folder_item).data('type');
                    if(type == 'folder'){
                        _self.foldersData(path);
                    }
                });

                //复选框值变动
                $(_self.folder_id).on('change', ".checkbox", function(){
                    var is_checked = $(this).prop('checked');
                    if(is_checked){
                        $(this).parent(_self.folder_item).addClass('active');
                    }else{
                        $(this).parent(_self.folder_item).removeClass('active');
                    }
                });

                // 右键菜单
                document.oncontextmenu = function (e) {
                    $(_self.right_menu).css('display','none');
                };
                $(_self.folder_id).on('contextmenu', _self.folder_item, function (e) {
                    var item;
                    if(e.target.tagName == 'LI'){
                        item = e.target;
                    }else{
                        item = e.target.parentNode;
                    }

                    $(_self.checkbox).prop('checked', false);
                    $(_self.folder_item).removeClass('active');
                    $(item).find(_self.checkbox).prop('checked', true);
                    $(item).addClass('active');

                    if (item) {
                        $(_self.right_menu).css('left', e.clientX + "px");
                        $(_self.right_menu).css('top', e.clientY + "px");
                        $(_self.right_menu).css('display', "block");
                        e.cancelBubble = true;
                        return false;
                    }
                });
                // 点击其它地方,右键菜单消失
                document.onmousedown = function (e) {
                    $(_self.right_menu).css('display','none');
                };
                // 防止冒泡
                $(_self.right_menu).on('mousedown', function (e) {
                    e.cancelBubble = true;
                });

                //点击列表后面的操作
                $(_self.folder_id).on('mousedown', ".delete,.move,.rename", function(){
                    var that = $(this);

                    $(_self.checkbox).prop('checked', false);
                    $(_self.folder_item).removeClass('active');

                    that.siblings(_self.checkbox).prop('checked',true);
                    that.parent(_self.folder_item).addClass('active');
                });

            }

            //文件夹显示样式
            this.foldersClass = function () {
                var activeClass = 'text-danger';
                if(_self.folders_class == 'file_manager_row'){
                    $('.file-col-row').find('span').removeClass(activeClass);
                    $('.file-col-row').find('.file-row').addClass(activeClass);

                    $(_self.folder_id).removeClass('file_manager_col');
                    $(_self.folder_id).addClass('file_manager_row');
                }else{
                    $('.file-col-row').find('span').removeClass(activeClass);
                    $('.file-col-row').find('.file-col').addClass(activeClass);

                    $(_self.folder_id).removeClass('file_manager_row');
                    $(_self.folder_id).addClass('file_manager_col');
                }
            }

            //切换文件夹显示样式
            this.switchFoldersClass = function (type) {
                if(type == 'row'){
                    _self.folders_class = 'file_manager_row';
                }else{
                    _self.folders_class = 'file_manager_col';
                }
                _jM.setCookie('folders_class', _self.folders_class);
                _self.foldersClass();
            }

            //全选/取消
            this.checkAll = function (type) {
                if(_self.all_checked_status == true){
                    $(this.checkbox).prop("checked", !_self.all_checked_status);
                    $(_self.folder_item).removeClass('active');
                }else{
                    $(this.checkbox).prop('checked', !_self.all_checked_status);
                    $(_self.folder_item).addClass('active');
                }
                _self.all_checked_status = !_self.all_checked_status;
            }

            //多个删除
            this.moreDelete = function () {
                var path_data = [];
                $('input[name="files[]"]:checked').each(function(){
                    path_data.push($(this).val());
                });

                if(_jM.validate.isEmpty(path_data)){
                    _jM.dialogMsg('删除目标不存在');
                    return false;
                }

                if(_self.delete_path_status){
                    _jM.dialogMsg('正在删除');
                    return false;
                }

                _jM.dialogHint('是否删除', function() {
                    _self.delete_path_status = true;
                    var load = _jM.dialogLoad();
                    _jM.ajax({
                        url: '{{ route("merchant.file.delete_files") }}',
                        type: 'DELETE',
                        data: {
                            'paths': path_data
                        },
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function (resMsg, resData) {
                            _jM.dialogMsg('已删除');
                            _self.foldersData(_self.current_path);
                        },
                        complete: function (XMLHttpRequest, textStatus) {
                            _self.delete_path_status = false;
                            _jM.dialogClose(load);
                        }
                    });
                });
            }

            this.renameOpen = function () {
                var path_data = [];
                $('input[name="files[]"]:checked').each(function(){
                    path_data.push($(this).val());
                });

                if(_jM.validate.isEmpty(path_data)){
                    _jM.dialogMsg('移动源不存在');
                    return false;
                }

                if(path_data.length > 1){
                    _jM.dialogMsg('不允许同时修改多个');
                    return false;
                }

                _self.rename_path = path_data[0];
                var name = _self.rename_path.substr(_self.rename_path.lastIndexOf('/')+1);
                $("#rename").val(name);
                $(_self.rename_modal).modal({backdrop: 'static', keyboard: false});
            }

            this.rename = function () {
                if(_jM.validate.isEmpty(_self.rename_path) || _self.rename_path == '/'){
                    _jM.dialogMsg('未选则修改文件');
                    return false;
                }

                var rename = $("#rename").val();
                if(_jM.validate.isEmpty(rename)){
                    _jM.dialogMsg('请输入名称');
                    return false;
                }

                var reg = new RegExp('^[^\\\\\\/:*?\\"<>|]+$');
                if(!reg.test(rename)){
                    _jM.dialogMsg('存在特殊字符\\/:*?"<>|+$');
                    return false;
                }

                if(_self.rename_path_status){
                    _jM.dialogMsg('正在加载');
                    return false;
                }

                _self.rename_path_status = true;
                var load = _jM.dialogLoad();

                _jM.ajax({
                    url: '{{ route("merchant.file.rename") }}',
                    type: 'POST',
                    data: {
                        'name': rename,
                        'path': _self.rename_path
                    },
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function (resMsg, resData) {
                        $(_self.rename_modal).modal('hide');
                        _jM.dialogMsg('操作成功');
                        _self.foldersData(_self.current_path);
                    },
                    complete: function () {
                        _jM.dialogClose(load);
                        _self.rename_path_status = false;
                    }
                });
            }

            this.createFolderOpen = function () {
                $(_self.create_folder_modal).modal({backdrop: 'static', keyboard: false});
            }

            this.createFolder = function () {
                var folder_name = $('#folder_name').val();
                if(_jM.validate.isEmpty(folder_name)){
                    _jM.dialogMsg('请输入文件夹名');
                    return false;
                }

                var reg = new RegExp('^[^\\\\\\/:*?\\"<>|]+$');
                if(!reg.test(folder_name)){
                    _jM.dialogMsg('存在特殊字符\\/:*?"<>|+$');
                    return false;
                }

                if(_self.newfolder_status){
                    _jM.dialogMsg('加载中');
                    return false;
                }

                _self.newfolder_status = true;
                var load = _jM.dialogLoad();

                _jM.ajax({
                    url: '{{ route("merchant.file.create_folder") }}',
                    type: 'POST',
                    data: {
                        'name' : folder_name,
                        'path' : _self.current_path
                    },
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function (resMsg, resData) {
                        _self.foldersData(_self.current_path);
                        $(_self.create_folder_modal).modal('hide');
                        $('#folder_name').val('');
                        _jM.dialogMsg('创建成功');
                    },
                    complete: function () {
                        _jM.dialogClose(load);
                        _self.newfolder_status = false;
                    }
                });

            }

            //选择文件夹
            this.moreMoveSelectFolder = function () {
                var path_data = [];
                $('input[name="files[]"]:checked').each(function(){
                    path_data.push($(this).val());
                });

                if(_jM.validate.isEmpty(path_data)){
                    _jM.dialogMsg('移动源不存在');
                    return false;
                }

                if(_self.move_path_status){
                    _jM.dialogMsg('加载中');
                    return false;
                }

                _self.move_path_status = true;
                var load = _jM.dialogLoad();

                _jM.ajax({
                    url: '{{ route("merchant.file.all_files",["type" => 'folder']) }}',
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
                            multiSelect: false, //是否可以同时选择多个节点      Boolean
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
                        _self.move_path_status = false;
                        $(_self.folder_modal).modal({backdrop: 'static', keyboard: false});
                    }
                });
            }

            //多个移动
            this.moreMove = function () {
                var select_folder = $(_self.treeId).treeview('getSelected');

                if(select_folder.length <= 0){
                    _jM.dialogMsg('请选择目标文件夹');
                    return false;
                }

                var move_path = select_folder[0].path;
                if(_jM.validate.isEmpty(move_path)){
                    _jM.dialogMsg('目标文件夹不存在');
                    return false;
                }

                var path_data = [];
                $('input[name="files[]"]:checked').each(function(){
                    path_data.push($(this).val());
                });

                if(_jM.validate.isEmpty(path_data)){
                    _jM.dialogMsg('移动源不存在');
                    return false;
                }

                if(_self.move_path_status){
                    _jM.dialogMsg('正在移动');
                    return false;
                }

                _self.move_path_status = true;
                var load = _jM.dialogLoad();

                _jM.ajax({
                    url: '{{ route("merchant.file.move") }}',
                    type: 'POST',
                    data: {
                        'move_path': move_path,
                        'paths': path_data
                    },
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function (resMsg, resData) {
                        $(_self.folder_modal).modal('hide');
                        _jM.dialogMsg('操作成功');
                        _self.foldersData(move_path);
                    },
                    complete: function () {
                        _jM.dialogClose(load);
                        _self.move_path_status = false;
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

            //文件夹数据
            this.foldersData = function (path) {
                if(_self.load_path_status){
                    _jM.dialogMsg('正在加载');
                    return false;
                }
                if(_jM.validate.isEmpty(path)){
                    _jM.dialogMsg('路径错误');
                    return false;
                }

                var load = _jM.dialogLoad();
                _self.load_path_status = true;

                _jM.ajax({
                    url: '{{ route("merchant.file.files") }}',
                    type: 'POST',
                    data: {
                        'path': path,
                    },
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function (resMsg, resData) {
                        var pathData = resData.paths;
                        var topPath = resData.top_path;
                        _self.current_path = resData.current_path;

                        var _html = '';

                        //返回上一级文件夹
                        if(!_jM.validate.isEmpty(topPath)){
                            _html += "<li class='top-folder-item' ondblclick=\"TObj.foldersData(\'"+topPath +"\')\">";
                            _html += "    <img class='folder-img' src='{{ asset('merchant-static/images/folder.png') }}' />";
                            _html += "    <p class='name'>返回上一级</p>";
                            _html += "</li>";
                        }

                        //位置数据
                        var path_arr = resData.current_path.split('/');
                        var path_site_html = '<span class="m-r-10">/我的文档</span>';
                        for(const path_site in path_arr){
                            if(!_jM.validate.isEmpty(path_arr[path_site])){
                                path_site_html += '<span class="m-r-10">/'+ path_arr[path_site] +'</span>';
                            }
                        }
                        $("#pathSite").html(path_site_html);

                        for (var i=0; i < pathData.length; i++){
                            if(pathData[i].type == 'folder'){
                                _html += "<li class='folder-item' data-path='"+ pathData[i].path +"' data-name='"+ pathData[i].name +"' data-type='folder'>";
                                _html += "    <input class='checkbox checkbox-warning' type='checkbox' value='"+ pathData[i].path +"' name='files[]' />";
                                _html += "    <img class='folder-img' src='{{ asset('merchant-static/images/folder.png') }}' />";
                                _html += "    <p class='name'>"+ pathData[i].name +"</p>";
                                _html += "    <span class='delete tool' onclick='TObj.moreDelete()'>删除</span>";
                                _html += "    <span class='move tool' onclick='TObj.moreMoveSelectFolder()'>移动</span>";
                                _html += "    <span class='rename tool' onclick='TObj.renameOpen()'>重命名</span>";
                                _html += "</li>";
                            }else{
                                _html += "<li class='folder-item' data-path='"+ pathData[i].path +"' data-name='"+ pathData[i].name +"' data-type='file'>";
                                _html += "    <input class='checkbox checkbox-warning' type='checkbox' value='"+ pathData[i].path +"' name='files[]' />";
                                _html += "    <img class='folder-img' src='{{ asset("merchant-static/images/file.png") }}' />";
                                _html += "    <p class='name'>"+ pathData[i].name +"</p>";
                                _html += "    <span class='delete tool' onclick='TObj.moreDelete()'>删除</span>";
                                _html += "    <span class='move tool' onclick='TObj.moreMove()'>移动</span>";
                                _html += "    <span class='rename tool' onclick='TObj.renameOpen()'>重命名</span>";
                                _html += "</li>";
                            }
                        }
                        $(_self.folder_id).html(_html);
                    },
                    complete: function (XMLHttpRequest, textStatus) {
                        _self.load_path_status = false;
                        _jM.dialogClose(load);
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