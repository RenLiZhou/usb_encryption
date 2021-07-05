@extends("merchant.layouts.main")

@section("title")
    <title>U盘列表</title>
@endsection

@section("css")
    <link rel="stylesheet" href="{{ asset('merchant-static/js/jconfirm/jquery-confirm.min.css') }}">
@endsection


@section("content")
    <body>
    <!--页面主要内容-->
    <main class="ftdms-layout-content">

        <div class="container-fluid">

            <div class="row mt15 mb60">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><h4>U盘轨迹 | <a class="text-cyan" href="javascript:;" onclick="_jM.dialogCloseCurIf()">返回列表</a></h4></div>
                        <!--搜索-->
                        <div class="card-toolbar clearfix">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="toolbar-btn-action">
                                        <a href="{{ route("merchant.disk.track.export", ['disk' => $disk_id]) }}"><button class="btn btn-success m-r-5" onClick="TObj.exportTrack(this)"> 导出日志</button></a>
                                        <button class="btn btn-warning m-r-5" onClick="TObj.emptyTrack(this)"> 清空日志</button>
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
                                            <th>ID</th>
                                            <th>用户</th>
                                            <th>事件名</th>
                                            <th>事件详情</th>
                                            <th>时间</th>
                                            <th>计算机</th>
                                            <th>操作IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->event_username }}</td>
                                            <td>{{ $data->event_name }}</td>
                                            <td>{{ $data->event_desc }}</td>
                                            <td>{{ $data->created_at }}</td>
                                            <td>{{ $data->machine_code }}</td>
                                            <td>{{ $data->ip }}</td>
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
    <script src="{{ asset('merchant-static/js/jconfirm/jquery-confirm.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;

            this.init = function() {

            }

            //清空
            this.emptyTrack = function(obj){
                _jM.dialogHint('是否清空所有轨迹', function() {
                    _jM.disabled(obj);
                    _jM.ajax({
                        url: '{{ route("merchant.disk.track.empty", ['disk' => $disk_id]) }}',
                        type: 'POST',
                        data: {},
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function () {
                            _jM.dialogSuccess('操作成功', function () {
                                location.href = "{{ route("merchant.disk.track", ['disk' => $disk_id]) }}";
                            });
                        },
                        complete: function (XMLHttpRequest, textStatus) {
                            _jM.undisabled(obj);
                        }
                    });
                })
            }
        }
        var TObj = new TObject();
        $(document).ready(function(){
            TObj.init();
        })
    </script>
@endsection
