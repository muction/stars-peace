<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>STARS 后台管理系统</title>

    <link href="{{asset("static/stars/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("static/stars/css/materialdesignicons.min.css")}}" rel="stylesheet">
    <link href="{{asset("static/stars/js/bootstrap-multitabs/multitabs.min.css")}}" rel="stylesheet" >
    <link href="{{asset("static/stars/css/style.min.css")}}" rel="stylesheet">
    <link href="{{asset('static/stars/css/materialdesignicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/stars/js/jconfirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/stars/css/animate.css')}}" rel="stylesheet">

    <script >
        var dashboardUrl = "{{route('rotate.dashboard.index')}}";
    </script>
</head>

<body>
<div class="lyear-layout-web">
    <div class="lyear-layout-container">
        <!--左侧导航-->
        <aside class="lyear-layout-sidebar">

            <!-- logo -->
            <div id="logo" class="sidebar-header">
                <a href="javascript:void( 0) ">
                    <img src="{{ asset('static/stars/images/logo-sidebar.png') }}" />
                </a>
            </div>
            <div class="lyear-layout-sidebar-scroll">
                <nav class="sidebar-main">
                    @include("StarsPeace::inc.sidebar")
                </nav>
            </div>
        </aside>
        <!--End 左侧导航-->

        <!--头部信息-->
        <header class="lyear-layout-header">

            <nav class="navbar navbar-default">
                <div class="topbar">

                    <div class="topbar-left">
                        <div class="lyear-aside-toggler">
                            <span class="lyear-toggler-bar"></span>
                            <span class="lyear-toggler-bar"></span>
                            <span class="lyear-toggler-bar"></span>
                        </div>
                    </div>

                    <ul class="topbar-right">
                        @include("StarsPeace::inc.profile")
                        @include("StarsPeace::inc.theme")
                    </ul>

                </div>
            </nav>

        </header>
        <!--End 头部信息-->

        <!--页面主要内容-->
        <main class="lyear-layout-content">
            <div id="iframe-content"></div>
        </main>
        <!--End 页面主要内容-->
    </div>
</div>

@include("StarsPeace::inc.footer")
</body>
</html>
