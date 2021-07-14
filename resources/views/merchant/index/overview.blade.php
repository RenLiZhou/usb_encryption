@extends("merchant.layouts.main")

@section("title")
    <title>{{ __('merchant_view.summary_information') }}</title>
@endsection

@section("content")
    <body>
        <!--页面主要内容-->
        <main class="ftdms-layout-content">
            <div class="container-fluid mb90">
                <div class="row mt15">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><h4>{{ __('merchant_view.summary_information') }}</h4></div>
                            <div class="card-body">
                                <p>{{ __('merchant_view.user_id') }}：{{ $merchant->id }}</p>
                                <p>
                                    {{ __('merchant_view.current_version') }}：{{ $merchant->version[0]->title_name }}
                                    <a class="text-success ml15" href="#">{{ __('merchant_view.upgrade_version') }}</a>
                                </p>
                                <p>{{ __('merchant_view.validity_period') }}：{{ $merchant->expire_date }}</p>
                                <p>{{ __('merchant_view.total_authorized_quantity') }}：{{ $merchant->version[0]->disk_number + $merchant->add_auth_count }}</p>
                                <p>
                                    {{ __('merchant_view.the_number_of_authorized_licenses_has_been_consumed') }}：{{ $merchant->auth_number }}（{{ __('merchant_view.index_description') }}）
                                </p>
                                <p>
                                    {{ __('merchant_view.remaining_authorized_quantity') }}：{{ $merchant->version[0]->disk_number + $merchant->add_auth_count - $merchant->auth_number }}
                                    <a class="text-success ml15" href="#">{{ __('merchant_view.purchase_additional_u_disk_authorization') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
        <!--End 页面主要内容-->
    </body>
@endsection
