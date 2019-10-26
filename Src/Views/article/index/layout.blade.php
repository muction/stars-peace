<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>STARS 后台管理系统--内容页</title>

    @include("StarsPeace::inc.iframe.static")

    @yield("page-head")
    @yield("car-head")


    <style type="text/css">
        th {
            text-align: center;
        }
        .required{
            color: red;
        }
    </style>
</head>

<body style="height: 100%;">

<div class="container-fluid" style="height: 100%;overflow-y: hidden; ">
    <div class="row" style="height: 100%;background-color: #ffff;">
        @yield('car-body')
    </div>
</div>


</body>
</html>