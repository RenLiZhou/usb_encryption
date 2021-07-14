@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.u_disk_encryption_record') }}</title>
@endsection

@section("content")
    <body>
    <!--页面主要内容-->
    <main class="ftdms-layout-content">

        <div class="container-fluid">

            <div class="row mt15 mb60">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><h4>{{ __('merchant_view.u_disk_encryption_record') }}</h4></div>

                        <div class="card-body">
                            <!--内容-->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>{{ __('merchant_view.u_disk_physical_serial_number') }}</th>
                                            <th>{{ __('merchant_view.u_disk_logical_serial_number') }}</th>
                                            <th>{{ __('merchant_view.u_disk_capacity') }}</th>
                                            <th>{{ __('merchant_view.u_disk_remarks_name') }}</th>
                                            <th>{{ __('common.operation_time') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->disk->usb_serial }}</td>
                                            <td>{{ $data->logical_sequence }}</td>
                                            <td>{{ $data->disk->capacity }}</td>
                                            <td>{{ $data->disk->name }}</td>
                                            <td>{{ $data->created_at }}</td>
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
@endsection
