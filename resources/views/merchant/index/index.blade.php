@extends("merchant.layouts.main")

@section("title")
    <title>商户管理</title>
@endsection

@section("css")
    <link href="{{ asset('merchant-static/css/theme.css') }}" rel="stylesheet">
@endsection

@section("content")
    <body class="theme-blue-gradient pace-done" style="overflow: hidden; ">
        <div class="pace  pace-inactive">
            <div class="pace-progress" style="width: 100%;" data-progress-text="100%" data-progress="99">
                <div class="pace-progress-inner"></div>
            </div>
            <div class="pace-activity"></div>
        </div>
        <div id="ajax-loader" style="background: rgb(255, 255, 255); left: -50%; top: -50%; width: 200%; height: 200%; overflow: hidden; display: none; position: fixed; z-index: 10000; cursor: progress;">
            <img style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; margin: auto;" src="{{ asset('merchant-static/images/loader.gif')}}">
        </div>
        <div id="theme-wrapper">
            <header class="navbar" id="header-navbar">
                <div class="container" style="padding-right: 0px;">
                    <a class="navbar-brand" id="logo" href="#">商户管理</a>
                    <div class="clearfix">
                        <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                            <ul class="nav navbar-nav pull-left">
                                <li>
                                    <a id="make-small-nav">
                                        <div class="ftdms-aside-toggler">
                                            <span class="ftdms-toggler-bar"></span>
                                            <span class="ftdms-toggler-bar"></span>
                                            <span class="ftdms-toggler-bar"></span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="nav-no-collapse pull-right" id="header-nav">
                            <ul class="nav navbar-nav">
                                <li class="dropdown profile-dropdown">
                                    <a class="dropdown" href="#" data-toggle="dropdown">
                                        <span class="hidden-xs">
                                            @if($merchant_timezone == 'local')
                                                本地时区
                                            @else
                                                UTC时区
                                            @endif
                                        </span>
                                        <i class="ftsucai-100 m-l-5" style="position: relative;top:2px;"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:;" onclick="TObj.switchLanguage('utc')">
                                                UTC时区
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" onclick="TObj.switchLanguage('local')">
                                                本地时区
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav navbar-nav">
                                <li class="dropdown profile-dropdown">
                                    <a class="dropdown" href="#" data-toggle="dropdown">
                                        <span class="hidden-xs">{{ $merchant->name }}-{{ $merchant->version[0]->title_name }}</span>
                                        <i class="ftsucai-100 m-l-5" style="position: relative;top:2px;"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a class="editPassword" href="javascript:;" data-url="{{ route("merchant.password.edit") }}">
                                                <i class="ft ftsucai-edit-2"></i>修改密码
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route("merchant.logout") }}">
                                                <i class="ft ftsucai-exit2"></i>安全退出
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container" id="page-wrapper">
                <div class="row">
                    <div id="nav-col">
                        <section class="col-left-nano" id="col-left">
                            <div class="col-left-nano-content" id="col-left-inner">
                                <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                                    <ul class="nav nav-pills nav-stacked">
                                        <li>
                                            <a class="dropdown-toggle tabCloseOther submenuitem" href="{{ route("merchant.overview") }}" data-id="overview">
                                                <i class="ft ftsucai-cate"></i>
                                                <span>概括</span>
                                            </a>
                                        </li>
                                        @foreach($menus as $menu)
                                            <li>
                                                <a class="dropdown-toggle tabCloseOther @if(empty($menu['children'])) submenuitem @endif" href="{{ $menu['href'] }}" data-id="link{{ $menu['id'] }}">
                                                    <i class="ft {{ $menu['icon'] }}"></i>
                                                    <span>{{ $menu['title'] }}</span>
                                                    @if(!empty($menu['children']))
                                                        <i class="ft ftsucai-139 drop-icon"></i>
                                                    @endif
                                                </a>
                                                @if(!empty($menu['children']))
                                                    <ul class="submenu">
                                                        @foreach($menu['children'] as $children)
                                                            <li>
                                                                <a class="submenuitem tabCloseOther" href="{{ $children['href'] }}" data-id="link{{ $children['id'] }}">{{ $children['title'] }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div id="content-wrapper">
                        <div class="content-tabs" style="height:44px;border-bottom:2px solid #f0f0f0;display: none">
                            <nav class="page-tabs menuTabs">
                                <div class="page-tabs-content" style="margin-left: 0px;"></div>
                            </nav>
                        </div>
                        <div class="content-iframe" style="background-color: #f9f9f9;">
                            <div class="mainContent" id="content-main" style="margin: 0px; padding: 0px; height: 1050px;">
                                <iframe name="main_iframe" width="100%" height="100%" class="main_iframe" id="default" src="{{ route("merchant.overview") }}" frameborder="0" data-id="merchant.overview"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loadingPage" style="display: none;">
            <div class="loading-shade"></div>
            <div class="loading-content" onClick="$.loading(false)">数据加载中，请稍后…</div>
        </div>
    </body>
@endsection

@section("js")
    <script src="{{ asset('merchant-static/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('merchant-static/js/framework.js') }}"></script>
    <script src="{{ asset('merchant-static/js/index.js') }}"></script>
    <script src="{{ asset('merchant-static/js/indextab.js') }}"></script>
    <script src="{{ asset('merchant-static/js/pace.min.js') }}"></script>
    <script>
        var TObject = function(){
            var _self = this;

            this.formObj = $("#formsubmit");

            this.init = function() {
                $(".editPassword").click(function () {
                    var index = _jM.dialogPop({
                        'title': '修改密码',
                        'content': $(this).attr('data-url'),
                        'area': ['54%', '60%'],
                        'maxmin': false
                    });
                    layer.full(index);
                });
            }

            this.switchLanguage = function(type = 'local'){
                var type = type == 'utc'?'utc':'local';
                _jM.setCookie('merchant_timezone', type, 86400*10, '/');
                location.reload();
            }
        }

        var TObj = new TObject();
        $(document).ready(function(){
            TObj.init();
        })
    </script>
@endsection
