@extends("merchant.layouts.main")

@section("title")
    <title>U盘编辑</title>
@endsection

@section("content")
    <body>
    <!--页面主要内容-->
    <main class="ftdms-layout-content">
        <div class="container-fluid">
            <div class="row mt15 mb60">
            <div class="col-lg-12">
                <div class="card mb0">
                    <div class="card-body">

                        <form class="row" id="formsubmit">

                            <div class="form-group col-md-12">
                                <label for="title">物理序列号</label>
                                <input type="text" class="form-control" disabled value="{{ $data->usb_serial }}"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title">启动次数</label>
                                <input type="text" class="form-control" disabled value="{{ $data->run_count }}"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title">备注名</label>
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="请输入备注名"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="type">文件更新策略</label>
                                <div class="form-controls">
                                    <select class="form-control" name="update_id">
                                        <option value="1">小说</option>
                                        <option value="2">古籍</option>
                                        <option value="3">专辑</option>
                                        <option value="4">自传</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="type">权限策略</label>
                                <div class="form-controls">
                                    <select class="form-control" name="auth_id">
                                        <option value="1">小说</option>
                                        <option value="2">古籍</option>
                                        <option value="3">专辑</option>
                                        <option value="4">自传</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="status">状态</label>
                                <div class="clearfix">
                                    <label class="ftdms-radio radio-inline radio-primary">
                                        <input type="radio" name="status" value="0" @if($data->status == 0) checked @endif><span>启用</span>
                                    </label>
                                    <label class="ftdms-radio radio-inline radio-primary">
                                        <input type="radio" name="status" value="1" @if($data->status == 1) checked @endif><span>禁用</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-md-12 text-center">
                                <button type="button" class="btn btn-primary" data-url="{{ route('merchant.disk.update', ['disk' => $data->id]) }}" data-type="PATCH" onClick="TObj.submit(this)">确定</button>
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
    <script src="{{ asset('merchant-static/js/perfect-scrollbar.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;

            this.formObj = $("#formsubmit");

            this.init = function() {}

            this.submit = function(obj){

                var url = $(obj).data('url');
                var type = $(obj).data('type');
                var ajaxdata = _self.formObj.serialize();

                _jM.disabled(obj);
                _jM.ajax({
                    url: url,
                    type: type,
                    data: ajaxdata,
                    error: function (errMsg) {
                        _jM.dialogMsg(errMsg);
                    },
                    success: function () {
                        _jM.dialogSuccess('更新成功', function () {
                            parent.location.reload();
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
