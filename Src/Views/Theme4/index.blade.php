<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>首页 - 光年(Light Year Admin)后台管理系统模板</title>
    <link rel="icon" href="favicon.ico" type="image/ico">
    <meta name="keywords" content="LightYear,光年,后台模板,后台管理系统,光年HTML模板">
    <meta name="description" content="LightYear是一个基于Bootstrap v3.3.7的后台管理系统的HTML模板。">
    <meta name="author" content="yinqi">
    <link href="{{ asset('static/stars/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/multitabs.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/materialdesignicons.min.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ asset('static/stars/js/perfect-scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/main.min.js') }}"></script>

    <link href="{{ asset('static/stars/css/style.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .lyear-layout-web, .lyear-layout-container, .lyear-layout-content, .container-fluid {
            height: 100%;
        }

        iframe {
            border: 0px;
            padding: 0px;
            margin: 0px
        }

        .container-fluid {
            margin-top: 48px;
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
                <a href="index.html"><img src="{{ asset('static/stars/images/logo-sidebar.png') }}" title="LightYear"
                                          alt="LightYear"/></a>
            </div>
            <div class="lyear-layout-sidebar-scroll">

                <nav class="sidebar-main">
                    @include("StarsPeace::inc.sidebar")
                </nav>

                <div class="sidebar-footer">
                    <p class="copyright">Copyright &copy; 2019. <a target="_blank"
                                            href="http://lyear.itshubao.com">IT书包</a> All rights
                        reserved.</p>
                </div>
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
                        <span class="navbar-page-title"> 后台首页 </span>
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
