@extends("merchant.layouts.main")

@section("title")
    <title>U盘列表</title>
@endsection

@section("content")
    <body>
    <!--页面主要内容-->
    <main class="ftdms-layout-content">

        <div class="container-fluid">

            <div class="row mt15 mb60">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><h4>U盘列表</h4></div>
                        <!--搜索-->
                        <div class="card-toolbar clearfix">
                            <div class="row">
                                <div class="col-sm-9">
                                    <form class="search-from" id="searchForm">
                                        <input type="hidden" name="per_page" value="{{ $search_data['per_page'] }}">
                                        <div class="row">
                                            <div class="input-group col-sm-3">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button">物理序列号</button>
                                                </span>
                                                <input type="text" class="form-control" name="usb_serial" placeholder="请输入物理序列号..." value="{{ $search_data['usb_serial'] }}" >
                                            </div>
                                            <div class="input-group col-md-3">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button">备注名</button>
                                                </span>
                                                <input type="text" class="form-control" name="name" placeholder="请输入备注名..." value="{{ $search_data['name'] }}" >
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn btn-dark pull-left ml15" id="searchBtn" type="button">搜索</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-3">
                                    <div class="toolbar-btn-action pull-right">
                                        <button class="btn btn-success m-r-5" onClick="TObj.bacthHandle(this, 0)"> 批量启用</button>
                                        <button class="btn btn-warning m-r-5" onClick="TObj.bacthHandle(this, 1)"> 批量禁用</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!--内容-->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5">
                                                <label class="ftdms-checkbox checkbox-primary">
                                                    <input type="checkbox" onchange="_jM.checkAll(this, 'ids[]')"><span></span>
                                                </label>
                                            </th>
                                            <th>物理序列号</th>
                                            <th>备注名</th>
                                            <th>容量</th>
                                            <th>已加密次数</th>
                                            <th>更新策略</th>
                                            <th>权限策略</th>
                                            <th>有效期</th>
                                            <th>运行次数/总次数</th>
                                            <th>状态</th>
                                            <th>创建时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td>
                                                <label class="ftdms-checkbox checkbox-primary">
                                                    <input type="checkbox" name="ids[]" value="{{ $data->id }}"><span></span>
                                                </label>
                                            </td>
                                            <td>{{ $data->usb_serial }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->capacity }}</td>
                                            <td>{{ $data->encrypt_count }}</td>
                                            <td>{{ $data->strategy_update_id }}</td>
                                            <td>{{ $data->strategy_auth_id }}</td>
                                            <td>{{ $data->first_time_use }}</td>
                                            <td>{{ $data->run_count }}/{{ $data->run_count }}</td>
                                            <td>
                                                <label class="ftdms-switch switch-solid switch-primary">
                                                    <input type="checkbox" class="active" @if($data->status == 0) checked @endif
                                                           data-url="{{ route('merchant.disk.active', ['disk' => $data->id]) }}" data-type="PUT" />
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>{{ $data->created_at }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-xs btn-default record" title="使用轨迹" data-toggle="tooltip" data-url="{{ route('merchant.disk.track', ['disk' => $data->id]) }}">
                                                        <i class="ftsucai-navwz"></i>
                                                    </a>
                                                    <a class="btn btn-xs btn-default edit" title="编辑" data-toggle="tooltip" data-url="{{ route('merchant.disk.edit', ['disk' => $data->id]) }}">
                                                        <i class="ftsucai-edit-2"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!--分页-->
                            {{ $datas->appends($search_data)->links('merchant.layouts.paginate') }}

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

            this.init = function() {
                $('#searchBtn').click(function () {
                    var search_data = $('#searchForm').serialize();
                    location.href = "{{ route('merchant.disk.index') }}" + '?' + search_data;
                });

                $('.active').change(function () {
                    var _that = $(this),
                        isActive = _that.prop("checked");
                    _jM.submit(_that, {}, '', function () {
                        _that.prop('checked', !isActive);
                    });
                });

                $('.edit').click(function () {
                    _jM.dialogPop({
                        'title': '编辑',
                        'content': $(this).attr('data-url'),
                        'area': ['54%', '65%'],
                        'maxmin': false
                    });
                });

                $('.record').click(function () {
                    _jM.dialogOpen(false,$(this).attr('data-url'));
                });

                // 提示
                $('[data-toggle="tooltip"]').tooltip({
                    "container" : 'body',
                });
            }

            this.bacthHandle = function(obj, type){
                var checkID = [];//定义一个空数组
                $("input[name='ids[]']:checked").each(function(i){//把所有被选中的复选框的值存入数组
                    checkID[i] =$(this).val();
                });
                var ids = checkID.join('|');
                if(!ids){
                    _jM.dialogErMsg('请选择需要操作数据');
                    return false;
                }

                _jM.disabled(obj);
                _jM.ajax({
                    url: '{{ route("merchant.disk.active.bacth") }}',
                    type: 'POST',
                    data: {
                        'ids': ids,
                        'type': type,
                    },
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
