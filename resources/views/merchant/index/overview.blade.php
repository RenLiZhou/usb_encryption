@extends("merchant.layouts.main")

@section("title")
    <title>商户管理</title>
@endsection

@section("content")
    <body>
        <!--页面主要内容-->
        <main class="ftdms-layout-content">
            <div class="container-fluid mb90">
                <div class="row mt15">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><h4>概括信息</h4></div>
                            <div class="card-body">
                                <p>用户 ID：{{ $merchant->id }}</p>
                                <p>当前版本：{{ $merchant->version[0]->title_name }} <a class="text-success ml15" href="#">升级版本</a></p>
                                <p>有效期：{{ $merchant->expire_date }}</p>
                                <p>总授权数量：{{ $merchant->version[0]->disk_number + $merchant->add_auth_count }}</p>
                                <p>已消耗授权数量：{{ $merchant->auth_number }}（说明：同一个U盘重复加密多次只消耗一个授权）</p>
                                <p>剩余授权数量：{{ $merchant->version[0]->disk_number + $merchant->add_auth_count - $merchant->auth_number }} <a class="text-success ml15" href="#">购买额外U盘授权</a></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
        <!--End 页面主要内容-->
    </body>
@endsection
