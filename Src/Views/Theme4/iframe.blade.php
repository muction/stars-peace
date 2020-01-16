<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>bootstrap-table - 光年(Light Year Admin)后台管理系统模板</title>

    <link href="{{ asset('static/stars/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/materialdesignicons.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('static/stars/js/bootstrap-multitabs/multitabs.min.css') }}">
    <link href="{{asset('static/stars/css/style.min.css')}}" rel="stylesheet">

</head>
<body>
<div class="mt-nav-bar " style="background-color: #ffffff;">
    <div class="mt-nav mt-nav-tools-left">
        <ul class="nav nav-tabs">
            <li class="mt-move-left">
                &nbsp;
            </li>
        </ul>
    </div>
    <nav class="mt-nav mt-nav-panel" style="width: calc(100% - 120px);">
        <ul class="nav nav-tabs" style="margin-left: 0px;">
            @yield("car-head")
        </ul>
    </nav>
</div>
<div class="container-fluid">
    @yield("car-body")
</div>
</body>
</html>
