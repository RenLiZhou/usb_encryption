@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.strategy_update_file_update_strategy') }}</title>
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
                        <div class="card-header"><h4>{{ __('merchant_view.strategy_update_file_update_strategy') }}</h4></div>
                        <!--搜索-->
                        <div class="card-toolbar clearfix">
                            <div class="row">
                                <div class="col-sm-9">
                                    <form class="search-from" id="searchForm">
                                        <input type="hidden" name="per_page" value="{{ $search_data['per_page'] }}">
                                        <div class="row">
                                            <div class="input-group col-md-3">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button">{{ __('merchant_view.policy_name') }}</button>
                                                </span>
                                                <input type="text" class="form-control" name="name"
                                                       placeholder="{{ __('merchant_view.please_enter_the_name_of_the_strategy') }}"
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
                                        <button class="btn btn-success m-r-5 create" data-url="{{ route('merchant.strategy_update.create') }}">
                                            {{ __('merchant_view.strategy_update_new_strategy') }}
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
                                            <th>{{ __('merchant_view.update_policy_name') }}</th>
                                            <th>{{ __('merchant_view.client_prompts_to_update') }}</th>
                                            <th>{{ __('merchant_view.number_of_files') }}</th>
                                            <th>{{ __('merchant_view.policy_effective_time') }}</th>
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
                                            <td>
                                                @if($data->automatic_update_prompt == 1)
                                                    {{ __('merchant_view.yes') }}
                                                @else
                                                    {{ __('merchant_view.no') }}
                                                @endif
                                            </td>
                                            <td>{{ $data->files_count }}</td>
                                            <td>{{ $data->valid_time == null ? __('merchant_view.not_effective') : $data->valid_time }}</td>
                                            <td>{{ $data->created_at }}</td>
                                            <td>
                                                @if($data->valid_time != null)
                                                    <div class="btn-group">
                                                        <a class="btn btn-xs btn-default edit" title="{{ __('merchant_view.edit') }}" data-toggle="tooltip" data-url="{{ route('merchant.strategy_update.edit', ['strategy_update' => $data->id]) }}">
                                                            <i class="ftsucai-edit-2"></i>
                                                        </a>
                                                        <a class="btn btn-xs btn-default delete" title="{{ __('merchant_view.delete') }}" data-toggle="tooltip" data-url="{{ route('merchant.strategy_update.delete', ['strategy_update' => $data->id]) }}" data-type="DELETE">
                                                            <i class="ftsucai-del"></i>
                                                        </a>
                                                    </div>
                                                @endif
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
                    location.href = "{{ route('merchant.strategy_update.index') }}" + '?' + search_data;
                });

                $('.create').click(function () {
                    _jM.dialogOpen(false,$(this).attr('data-url'));
                });

                $('.edit').click(function () {
                    _jM.dialogOpen(false,$(this).attr('data-url'));
                });

                $('.delete').click(function () {
                    var _that = $(this);
                    _jM.dialogHint("{{ __('merchant_view.strategy_update_delete_or_not') }}", function() {
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
                    _jM.dialogErMsg("{{ __('merchant_view.strategy_update_please_select_the_data_you_need_to_operate') }}");
                    return false;
                }

                _jM.dialogHint("{{ __('merchant_view.strategy_update_delete_or_not') }}", function() {
                    _jM.disabled(obj);
                    _jM.ajax({
                        url: '{{ route("merchant.strategy_update.delete.bacth") }}',
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
