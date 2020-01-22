@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::nav.menu.inc")
@endsection

@section('car-body')


    <table class="table table-hover">
        <colgroup>
            <col width="150"/>
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>菜单名称</th>
            <th>路由名称</th>
            <th>外连地址</th>
            <th>icon</th>
            <th>排序</th>
            <th>状态</th>
        </tr>
        </thead>
        <tbody>
            @component('StarsPeace::component.navmenu', ['datas'=> $datas ]) @endcomponent
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection
