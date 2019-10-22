@extends("StarsPeace::iframe")

@section('car-head')

@endsection

@section('car-body')


    <div style="height: 100%;" >
        <div style="width: 180px;float:left;height: 100%;" >
            <nav class="article-sidebar-main" >
                <ul id="treeDemo" class="ztree"></ul>
            </nav>
        </div>
        <div style="margin-left: 190px;height: 100%;" >
            <iframe name="articleContent" id="articleContent" src="{{ route('rotate.help') }}" style="width: 100% ;height: 99%; border: 0px"></iframe>
        </div>
    </div>
@endsection

@section('car-footer')
    <script type="text/javascript" src="{{asset("static/stars/plugs/zTree/js/jquery.ztree.all.min.js")}}"></script>
    <link href="{{asset("static/stars/plugs/zTree/css/zTreeStyle/zTreeStyle.css")}}" rel="stylesheet">

    <SCRIPT type="text/javascript">
        function treeClick(e,treeId, treeNode) {
            return false;
        }
        $(document).ready(function(){
            let zTree=$.fn.zTree.init($("#treeDemo"), {
                data: {
                    key:{
                        name : "title",
                        title : "title",
                        children : "nodes"
                    },
                    view:{
                        fontCss : { 'font-weight':'bold'}
                    }
                },
                callback: {
                    onClick: treeClick
                }
            }, @json( $sides ) );
            zTree.expandAll( true );
        });

    </SCRIPT>

@endsection