<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>STARS 后台管理系统</title>

    @include("StarsPeace::inc.iframe.static")

    @yield("page-head")
    <style type="text/css">
        .required{
            color: red;
        }
    </style>
</head>

<body style="height: 100%;">

<div class="container-fluid p-t-10" style="height: 99%;overflow-y: hidden; background-color: #ffffff">
    @yield('car-head')
    @yield('car-body')
</div>



@yield('car-footer')

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

        $('.act-stars-remove').click(function(){
            let href = $(this).attr('href');
            parent.$.confirm({
                title: '警告提示',
                content: '您在进行危险操作，确认继续吗?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: '确认',
                        btnClass: 'btn-default',
                        action: function(){
                            window.location.href= href;
                        }
                    },
                    close: {
                        text: '关闭'
                    }
                }
            });
            return false;
        });

        $('.article-stars-remove').click(function(){
            let href = $(this).attr('href');
            parent.parent.$.confirm({
                title: '警告提示',
                content: '您在进行危险操作，确认继续吗?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: '确认',
                        btnClass: 'btn-default',
                        action: function(){
                            window.location.href= href;
                        }
                    },
                    close: {
                        text: '关闭'
                    }
                }
            });
            return false;
        });

    });
</script>

</body>
</html>
