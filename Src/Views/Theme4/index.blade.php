<!DOCTYPE html>
<html>
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
    <link href="{{ asset('static/stars/css/animate.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ asset('static/stars/js/perfect-scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/lightyear.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/stars/js/main.min.js') }}"></script>

    <link href="{{ asset('static/stars/css/style.min.css') }}" rel="stylesheet">
    <style type="text/css">
        iframe {
            border: 0px;
            padding: 0px;
            margin: 0px
        }
    </style>

    <script type="text/javascript">

        function pageProgress( status ){
            if( status == 'show'){
                let random =  Math.random() * 10;
                random= parseInt(random*10 );
                if(random == 0){
                    random = 70;
                }
                $('#show-progress').css({width:    random + "%"}).show();
            }else if( status =='hide'){
                $('#show-progress').animate({width: "100%"} , function(){
                    $('#show-progress').hide();
                });
            }
        }

        //触发loading
        $(function(){
            $(".sidebar-main a[target='request-content']").click(function(){
                if( $(this).attr('href') != undefined ){
                    pageProgress('show');
                }
            });

            $("#content-iframe").on("load", function(event){//判断 iframe是否加载完成  这一步很重要
                $("#frame-nav-tabes a", this.contentDocument).click(function(){//添加点击事件
                    pageProgress('show');
                });
            });

        });
    </script>

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
            <div class="progress" style="height: 2px;margin-bottom: -1px">
                <div class="progress-bar progress-bar-striped active" id="show-progress" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">75% Complete</span>
                </div>
            </div>
            <iframe src="{{ route('rotate.dashboard.index') }}" name="request-content" id="content-iframe" width="100%" height="100%"></iframe>
        </main>
        <!--End 页面主要内容-->
    </div>
</div>


</body>
</html>
