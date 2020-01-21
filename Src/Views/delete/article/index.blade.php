@extends("StarsPeace::iframe")

@section('car-head')
@endsection

@section('car-body')

    <div style="height: 100%;" >
        <div style="width: 180px;float:left;height: 100%;overflow-y: scroll; " >
            <nav class="article-sidebar-main" >
                <ul id="treeDemo" class="ztree"></ul>
            </nav>
        </div>
        <div style="margin-left: 190px;height: 100%;" >
            <iframe name="articleContent" id="articleContent" onload="lightyear.loading('hide')" src="{{ route('rotate.help') }}" style="width: 100% ;height: 99%; border: 0px"></iframe>
        </div>
    </div>
@endsection

@section('car-footer')
    <script type="text/javascript" src="{{asset("static/stars/plugs/zTree/js/jquery.ztree.all.min.js")}}"></script>
    <link href="{{asset("static/stars/plugs/zTree/css/zTreeStyle/zTreeStyle.css")}}" rel="stylesheet">

    <SCRIPT type="text/javascript">

        var iniNavMenUrl =0;
        function getFontConfig( treeId, node ) {
            if( node.url && iniNavMenUrl==0 ){
                iniNavMenUrl =2;
                $('#articleContent').attr( 'src', node.url );
                lightyear.loading('hide');
            }
            return node.url ? {color:'#333' , 'size': '15px'} : {color:'#8b95a5'} ;
        }

        // 点击
        function clickMenu( event, treeId, treeNode ){

            if( treeNode .url){
                lightyear.loading('show');  // 显示
            }
             
            //console.log( event, treeId, treeNode );
           // lightyear.loading('hide');
        }

        $(document).ready(function(){

            let zTree=$.fn.zTree.init($("#treeDemo"), {
                data: {
                    key:{
                        name : "title",
                        title : "title",
                        children : "nodes"
                    }
                },
                view:{
                    //fontCss : { 'font-size':'15px'},
                    fontCss: getFontConfig,
                    //showLine: false ,
                        showIcon: false
                },
                callback: {
                    onClick: clickMenu
                }
            }, @json( $sides ) );
            zTree.expandAll( true );
        });

    </SCRIPT>

@endsection