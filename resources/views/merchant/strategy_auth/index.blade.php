@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.permission_policy') }}</title>
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
                        <div class="card-header"><h4>{{ __('merchant_view.permission_policy') }}</h4></div>
                        <!--搜索-->
                        <div class="card-toolbar clearfix">
                            <div class="row">
                                <div class="col-sm-9">
                                    <form class="search-from" id="searchForm">
                                        <input type="hidden" name="per_page" value="{{ $search_data['per_page'] }}">
                                        <div class="row">
                                            <div class="input-group col-md-3">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button">{{ __('merchant_view.permission_policy_name') }}</button>
                                                </span>
                                                <input type="text" class="form-control" name="name"
                                                       placeholder="{{ __('merchant_view.please_enter_the_permission_policy_name') }}"
                                                       value="{{ $search_data['name'] }}" >
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn btn-dark pull-left ml15" id="searchBtn" type="button">
                                                    {{ __('common.search') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-3">
                                    <div class="toolbar-btn-action pull-right">
                                        <button class="btn btn-success m-r-5 create" data-url="{{ route('merchant.strategy_auth.create') }}">
                                            {{ __('merchant_view.new_strategy') }}
                                        </button>
                                        <button class="btn btn-warning m-r-5" onClick="TObj.bacthDelete(this)">
                                            {{ __('common.batch_delete') }}
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
                                            <th width="5">
                                                <label class="ftdms-checkbox checkbox-primary">
                                                    <input type="checkbox" onchange="_jM.checkAll(this, 'ids[]')"><span></span>
                                                </label>
                                            </th>
                                            <th>ID</th>
                                            <th>{{ __('merchant_view.permission_policy_name') }}</th>
                                            <th>{{ __('merchant_view.run_times_title') }}</th>
                                            <th>{{ __('merchant_view.strategy_auth_u_disk_validity_period') }}</th>
                                            <th>{{ __('common.created_time') }}</th>
                                            <th>{{ __('common.operation') }}</th>
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
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->run_number == -1 ? '不限' : $data->run_number }}</td>
                                            <td>{{ $data->expired_date }}</td>
                                            <td>{{ $data->created_at }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-xs btn-default edit" title="{{ __('common.edit') }}" data-toggle="tooltip" data-url="{{ route('merchant.strategy_auth.edit', ['strategy_auth' => $data->id]) }}">
                                                        <i class="ftsucai-edit-2"></i>
                                                    </a>
                                                    <a class="btn btn-xs btn-default delete" title="{{ __('common.delete') }}" data-toggle="tooltip" data-url="{{ route('merchant.strategy_auth.delete', ['strategy_auth' => $data->id]) }}" data-type="DELETE">
                                                        <i class="ftsucai-del"></i>
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
    <script src="{{ asset('merchant-static/js/jconfirm/jquery-confirm.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;

            this.init = function() {
                $('#searchBtn').click(function () {
                    var search_data = $('#searchForm').serialize();
                    location.href = "{{ route('merchant.strategy_auth.index') }}" + '?' + search_data;
                });

                $('.create').click(function () {
                    _jM.dialogPop({
                        'title': "{{ __('merchant_view.new_strategy') }}",
                        'content': $(this).attr('data-url'),
                        'area': ['54%', '60%'],
                        'maxmin': false
                    });
                });

                $('.edit').click(function () {
                    _jM.dialogPop({
                        'title': "{{ __('common.edit') }}",
                        'content': $(this).attr('data-url'),
                        'area': ['54%', '60%'],
                        'maxmin': false
                    });
                });

                $('.delete').click(function () {
                    var _that = $(this);
                    _jM.dialogHint("{{ __('merchant_view.strategy_auth_delete_or_not') }}", function() {
                        _jM.submit(_that, {}, function () {
                            window.location.reload();
                        });
                    })
                });
            }

            //批量删除
            this.bacthDelete = function(obj, type){
                var checkID = [];//定义一个空数组
                $("input[name='ids[]']:checked").each(function(i){//把所有被选中的复选框的值存入数组
                    checkID[i] =$(this).val();
                });
                var ids = checkID.join('|');
                if(!ids){
                    _jM.dialogErMsg("{{ __('merchant_view.please_select_the_data_you_need_to_operate') }}");
                    return false;
                }

                _jM.dialogHint("{{ __('merchant_view.strategy_auth_delete_or_not') }}", function() {
                    _jM.disabled(obj);
                    _jM.ajax({
                        url: '{{ route("merchant.strategy_auth.delete.bacth") }}',
                        type: 'delete',
                        data: {
                            'ids': ids
                        },
                        error: function (errMsg) {
                            _jM.dialogMsg(errMsg);
                        },
                        success: function () {
                            _jM.dialogSuccess("{{ __('common.operation_succeeded') }}", function () {
                                window.location.reload();
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
