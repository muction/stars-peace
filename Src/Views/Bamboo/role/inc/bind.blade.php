<link href="{{ asset('static/stars/plugs/zTree/css/demo.css') }}" rel="stylesheet">
<link href="{{ asset('static/stars/plugs/zTree/css/zTreeStyle/zTreeStyle.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{asset('static/stars/plugs/zTree/js/jquery.ztree.core.js')}}"></script>
<script type="text/javascript" src="{{asset('static/stars/plugs/zTree/js/jquery.ztree.excheck.js')}}"></script>
<SCRIPT type="text/javascript" >

    var setting = {
        check: {
            enable: true,
            chkboxType :  { "Y": "ps", "N": "ps"}
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };

    var zNodes =[
        { id:1, pId:0, name:"随意勾选 1", open:true},
        { id:11, pId:1, name:"随意勾选 1-1", open:true},
        { id:111, pId:11, name:"随意勾选 1-1-1"},
        { id:112, pId:11, name:"随意勾选 1-1-2"},
        { id:12, pId:1, name:"随意勾选 1-2", open:true},
        { id:121, pId:12, name:"随意勾选 1-2-1"},
        { id:122, pId:12, name:"随意勾选 1-2-2"},
        { id:2, pId:0, name:"随意勾选 2", checked:true, open:true},
        { id:21, pId:2, name:"随意勾选 2-1"},
        { id:22, pId:2, name:"随意勾选 2-2", open:true},
        { id:221, pId:22, name:"随意勾选 2-2-1", checked:true},
        { id:222, pId:22, name:"随意勾选 2-2-2"},
        { id:23, pId:2, name:"随意勾选 2-3"}
    ];
    var zNodes= @json($allNavMenus)

    $(document).ready(function(){
        var abc= $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        console.log(  abc. getCheckedNodes() );

        $('#test-btn').click(function(){
            console.log(  abc. getCheckedNodes() );
        });
    });

    $(function(){

    });
</SCRIPT>
