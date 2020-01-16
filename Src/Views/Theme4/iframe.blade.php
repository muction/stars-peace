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

<div class="mt-wrapper " style="height: 100%;">
    <div class="mt-nav-bar " style="background-color: #ffffff;">
        <div class="mt-nav mt-nav-tools-left">
            <ul class="nav nav-tabs">
                <li class="mt-move-left">
                    <a><i class="mdi mdi-skip-backward"></i></a>
                </li>
            </ul>
        </div>
        <nav class="mt-nav mt-nav-panel" style="width: calc(100% - 120px);">
            @yield("car-head")
        </nav>
        <div class="mt-nav mt-nav-tools-right">
            <ul class="nav nav-tabs">

            </ul>
        </div>
    </div>
    <div class="tab-content" style="padding-top: 48px;">
        <div class="container-fluid p-t-15">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @yield("car-body")
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
