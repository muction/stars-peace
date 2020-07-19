<link href="{{ asset('static/stars/plugs/zTree/css/zTreeStyle/zTreeStyle.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{asset('static/stars/plugs/zTree/js/jquery.ztree.core.js')}}"></script>
<script type="text/javascript" src="{{asset('static/stars/plugs/zTree/js/jquery.ztree.excheck.js')}}"></script>
<SCRIPT type="text/javascript" >
    var zNodes= @json($allNavMenus)
        
    $(document).ready(function(){

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

        var ztrees=[];
        zNodes.forEach(function(v,k){
            let x= $.fn.zTree.init($("#ztree" + k), setting, v.menus );
            ztrees.push( x );
        });


        //
        $('#save-btn').click(function(){

            var self = $(this);
            var totalSubmit = [];
            if( self.hasClass('disabled') ){
                $.notify(  '提交中，请勿重复点击~' , "warn");
                return false;
            }

            ztrees.forEach(function(v){
                let checkedItems =  v. getCheckedNodes() ;
                let len = checkedItems.length ;
                if( len  ){
                    checkedItems.forEach(function(v){
                        totalSubmit.push(
                            {
                                'id': v.id ,
                                'dataType' : v.dataType ,
                                'name': v.name
                            }
                        );
                    });
                }
            });

            var ajax= $.ajax({
                url : "{{ route( 'rotate.role.bindRoleStorage' ,['infoId'=> isset($role['id']) ? $role['id'] : null ] ) }}",
                data : { _token : "{{csrf_token()}}" , items: totalSubmit },
                timeout : 5000, //超时时间设置，单位毫秒
                type :"post",
                dataType :'json' ,
                error: function(){
                    $.notify(  '服务器开了小差，请稍后再试~' , "error");
                },

                success : function( e){
                    if( e.error == 0 ){
                        $.notify(  e.msg , "success");
                    }else{

                        $.notify(  '授权失败，请联系超级管理员' , "error");

                    }
                },
                complete : function(){
                    self.text('保存');
                    self.removeClass('disabled' );
                },
                beforeSend: function(){
                    if(ajax){
                        ajax.abort();
                    }
                    self.addClass('disabled');
                    self.text('提交中...');
                }
            });

        });
    });

</SCRIPT>
