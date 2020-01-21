@extends("StarsPeace::iframe")

@section("page-head")
    <style type="text/css">
        .list-group-item{
            padding: 6px 15px;
        }
    </style>
@endsection

@section('car-head')
    <ul id="myTabs" class="nav nav-tabs" role="tablist">
        <li>
            <a href="" class="">刷新页面</a>
        </li>
    </ul>
@endsection

@section('car-body')
    <div class="row" >
        <div class="col-sm-2">
            <div class="list-group" id="articleSideBar">
                @component('StarsPeace::component.content-sidebar', ['datas'=> $sides])  @endcomponent
            </div>
        </div>
        <div class="col-sm-10">
            <iframe name="articleContentIframe" src="http://jd.com" style="width: 100% ;height: 100%; border: 0px"></iframe>
        </div>
    </div>
@endsection


@section("car-footer")
    <script type="text/javascript">
        $(function(){
            $("iframe[name='articleContentIframe']").css({ height : $('#articleSideBar').height() });

            $('#articleSideBar a').click(function(){
               $("iframe[name='articleContentIframe']").attr('src', $(this).data('url'));
            });
        });
    </script>
@endsection
