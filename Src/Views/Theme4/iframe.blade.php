<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>IFRAMECONTENT</title>
    <link href="{{ asset('static/stars/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/stars/css/style.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('static/stars/js/jquery.min.js')}}"></script>


    <script type="text/javascript" src="{{asset('static/stars/js/perfect-scrollbar.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap-notify.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/lightyear.js')}}"></script>

    @yield("page-head")

    <style type="text/css">
        .nav-tabs{
            margin-bottom: 0px;
        }
    </style>

    <script type="text/javascript">
        $(function(){
           try{
               parent. pageProgress ('hide');
           }catch (e) {

           }
        });
    </script>
</head>
<body>

<div class="stars-nav-tabs" id="frame-nav-tabes">
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


@error('messageError')
<script type="text/javascript">
    parent.lightyear.notify( '{{ $message }}' , 'danger', 2000 );
</script>
@enderror

@error('messageWarning')
<script type="text/javascript">
    parent.lightyear.notify( '{{ $message }}' , 'warning', 2000);
</script>
@enderror

@error('messageInfo')
<script type="text/javascript">
    parent.lightyear.notify( '{{ $message }}' , 'success', 2000 ,  'mdi mdi-emoticon-happy');
</script>
@enderror

<script type="text/javascript">
    $(function(){

        $( '.act-stars-remove' ).click(function(){
            return window.confirm("确定要继续操作吗？");
        });

        $('.article-stars-remove').click(function(){
            return window.confirm("确定要继续操作吗？");
        });

    });
</script>
</body>
</html>
