<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>bootstrap-table - 光年(Light Year Admin)后台管理系统模板</title>
    <link href="{{ asset('static/stars/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/style.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('static/stars/js/perfect-scrollbar.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('static/stars/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap-notify.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/lightyear.js')}}"></script>

    @yield("page-head")
</head>
<body>

<div class="stars-nav-tabs">
       @yield("car-head")
</div>

<div class="container-fluid stars-container-fluid">
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

@yield("car-footer")
</body>
</html>
