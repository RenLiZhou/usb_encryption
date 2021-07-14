@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.u_disk_track') }}</title>
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
                        <div class="card-header">
                            <h4>
                                {{ __('merchant_view.u_disk_track') }} |
                                <a class="text-cyan" href="javascript:;" onclick="_jM.dialogCloseCurIf()">
                                    {{ __('merchant_view.return_to_list') }}
                                </a>
                            </h4>
                        </div>
                        <!--搜索-->
                        <div class="card-toolbar clearfix">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="toolbar-btn-action">
                                        <a href="{{ route("merchant.disk.track.export", ['disk' => $disk_id]) }}">
                                            <button class="btn btn-success m-r-5" onClick="TObj.exportTrack(this)">
                                                {{ __('merchant_view.export_log') }}
                                            </button>
                                        </a>
                                        <button class="btn btn-warning m-r-5" onClick="TObj.emptyTrack(this)">
                                            {{ __('merchant_view.empty_log') }}
                                        </button>
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
                                            <th>{{ __('merchant_view.user') }}</th>
                                            <th>{{ __('merchant_view.event_name') }}</th>
                                            <th>{{ __('merchant_view.event_details') }}</th>
                                            <th>{{ __('merchant_view.time') }}</th>
                                            <th>{{ __('merchant_view.computer') }}</th>
                                            <th>{{ __('merchant_view.operation_ip') }}</th>
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
                _jM.dialogHint("{{ __('merchant_view.whether_to_clear_all_tracks') }}", function() {
                    _jM.disabled(obj);
                    _jM.ajax({
                        url: '{{ route("merchant.disk.track.empty", ['disk' => $disk_id]) }}',
                        type: 'POST',
                        data: {},
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function () {
                            _jM.dialogSuccess("{{ __('merchant_view.operation_succeeded') }}", function () {
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
