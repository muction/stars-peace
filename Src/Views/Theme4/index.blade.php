<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title> {{ config('app.name' , "星际智能") }} 管理系统 </title>
    <link rel="icon" href="favicon.ico" type="image/ico">
    <meta name="keywords" content=" {{ config('app.name' , "星际智能") }} 管理系统">
    <meta name="description" content=" {{ config('app.name' , "星际智能") }} 管理系统">
    <meta name="author" content="muction@yeah.net">

    <link href="{{ asset('static/stars/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/multitabs.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/materialdesignicons.min.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ asset('static/stars/js/perfect-scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/main.min.js') }}"></script>

    <link href="{{ asset('static/stars/css/style.min.css') }}" rel="stylesheet">
    <style type="text/css">


        iframe {
            border: 0px;
            padding: 0px;
            margin: 0px
        }

    </style>
</head>

<body>

<div class="lyear-layout-web">

    <div class="lyear-layout-container">
        <!--左侧导航-->
        <aside class="lyear-layout-sidebar">

            <!-- logo -->
            <div id="logo" class="sidebar-header">
                <a href="{{ route('rotate.dashboard.index') }}" target="request-content">
                    <img src="{{ asset('static/stars/images/logo-sidebar.png') }}" title="{{ config('app.name' , "星际智能") }} 管理系统"  alt="{{ config('app.name' , "星际智能") }} 管理系统"/>
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
                        <span class="navbar-page-title">  </span>
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
            <iframe src="{{ route('rotate.dashboard.index') }}" name="request-content" width="100%" height="100%"></iframe>
        </main>
        <!--End 页面主要内容-->
    </div>
</div>


</body>
</html>
