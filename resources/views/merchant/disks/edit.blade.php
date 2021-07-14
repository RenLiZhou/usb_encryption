@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.u_disk_editing') }}</title>
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
                                <label for="title">
                                    {{ __('merchant_view.physical_serial_number') }}
                                </label>
                                <input type="text" class="form-control" disabled value="{{ $data->usb_serial }}"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title">
                                    {{ __('merchant_view.u_disk_remarks_name_disk') }}
                                </label>
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}"
                                       placeholder="{{ __('merchant_view.please_enter_the_remark_name') }}"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="type">
                                    {{ __('merchant_view.file_update_strategy') }}
                                </label>
                                <div class="form-controls">
                                    <select class="form-control" name="update_id">
                                        <option value="">{{ __('common.please_select') }}</option>
                                        @foreach($strategy_update as $value)
                                            <option value="{{ $value->id }}"  @if($data->strategy_update_id == $value->id) selected @endif>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="type">
                                    {{ __('merchant_view.privilege_policy') }}
                                </label>
                                <div class="form-controls">
                                    <select class="form-control" name="auth_id">
                                        <option value="">{{ __('common.please_select') }}</option>
                                        @foreach($strategy_auth as $value)
                                            <option value="{{ $value->id }}"  @if($data->strategy_auth_id == $value->id) selected @endif>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="status">
                                    {{ __('merchant_view.u_disk_status') }}
                                </label>
                                <div class="clearfix">
                                    <label class="ftdms-radio radio-inline radio-primary">
                                        <input type="radio" name="status" value="0" @if($data->status == 0) checked @endif><span>{{ __('common.enable') }}</span>
                                    </label>
                                    <label class="ftdms-radio radio-inline radio-primary">
                                        <input type="radio" name="status" value="1" @if($data->status == 1) checked @endif><span>{{ __('common.disable') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title">
                                    {{ __('merchant_view.u_disk_boot_times') }}
                                </label>
                                <input type="text" class="form-control" disabled value="{{ $data->run_count }} {{ __('common.times') }}"/>
                            </div>

                            <div class="form-group col-md-12 text-center">
                                <button type="button" class="btn btn-primary" data-url="{{ route('merchant.disk.update', ['disk' => $data->id]) }}" data-type="PATCH" onClick="TObj.submit(this)">
                                    {{ __('common.ok') }}
                                </button>
                                <button type="button" class="btn btn-default ml15" onclick="_jM.dialogCloseCurIf()">
                                    {{ __('common.cancel') }}
                                </button>
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
                        _jM.dialogSuccess("{{ __('common.update_successfully') }}", function () {
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
