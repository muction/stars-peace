@extends("StarsPeace::iframe")


@section("car-body")

    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/jcrop/css/jquery.Jcrop.min.css") }}"/>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/jcrop/js/jcrop.js") }}"></script>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                图片裁剪
            </div>
            <div class="panel-body">
                <table>
                    <tr>
                        <td>
                            <div style="" id="img_handle_body">
                                <img id="target"  src="/storage/{{ $attachment['save_file_path'] }}/{{ $attachment['save_file_name'] }}"/>
                            </div>
                        </td>
                        <td valign="top">

                            <div class="panel panel-default" style="margin-left: 20px">
                                <div class="panel-heading">
                                    可选操作
                                </div>
                                <div class="panel-body">

                                    <table>
                                        <tr>
                                            <td>
                                                <select class="form-control" id="columnOptionResize" style="width: 146px;">
                                                    <option value="0">--自定义--</option>
                                                    @if( isset( $columnOption['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_IMG] ) )
                                                        @foreach( $columnOption['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_IMG] as $__option )
                                                            <option value="{{ $__option['width'] }},{{ $__option['height'] }}">{{ $__option['name'] }}({{ $__option['width'] }}x{{ $__option['height'] }})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" id="btn-crop-start">裁剪</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        var jrcopApi,bounds;
        $(function () {

            var $preview = $('#preview-pane'),
                boundx,
                boundy,
                $pcnt = $('#preview-pane .preview-container'),
                $pimg = $('#preview-pane .preview-container img');

            $('#target').Jcrop({
                bgColor: 'black',
                bgOpacity: .4,
                setSelect: [10, 10, 100, 50],
                aspectRatio: 0,
                boxWidth : 1024,
                boxHeight: 768,
                onChange : updatePreview,
                onSelect: updatePreview,
            }, function () {
                bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                jrcopApi = this;
                $preview.appendTo(jrcopApi.ui.holder);
            });

            $('#columnOptionResize').change(function () {
                let thisValue = $(this).val();
                let selectValue = thisValue.split(',');
                if (selectValue.length == 2) {
                    jrcopApi.setSelect([10, 20, selectValue[0], selectValue[1]]);
                    jrcopApi.setOptions({
                        aspectRatio: selectValue[0] / selectValue[1]
                    });
                } else {
                    jrcopApi.setSelect([10, 20, 100, 100]);
                    jrcopApi.setOptions({
                        aspectRatio: 0
                    });
                }
            });


            //点击裁剪按钮
            $('#btn-crop-start').click(function(){

                let self = $(this);
                if(self.hasClass("disabled")){
                    alert("裁剪中请稍后...");
                }else{
                   let tellSelect = jrcopApi.tellSelect();
                   $.ajax({
                        url :"{{ route('rotate.attachment.handle.cropper') }}",
                        type :"POST",
                        data :{
                            column : "{{$column}}",
                            menuId : "{{$menuId}}",
                            bindId : "{{$bindId}}",
                            attachmentId : "{{$attachmentId}}",
                            _token : "{{csrf_token()}}",
                            tell_select:tellSelect,
                            boundx : boundx,
                            boundy : boundy
                        },
                       beforeSend:function(){
                           self.addClass('disabled');
                       },
                        dataType:"json",
                        error:function(){
                            self.removeClass("disabled");
                            alert("裁剪出错，请联系管理员");
                        },
                        success:function(data){
                            self.removeClass("disabled");
                            if(data.error == 0){

                                $('#save_attachment_id_{{$column}}' , window.opener.document.body ).val( data.body.id );
                                $('#preview_upload_file_{{$column}}' , window.opener.document.body  ).html(
                                    '<a target="_blank" href="'+data.body.url +'" title="单击预览图片">' +
                                    ''+data.body.save_file_path + '/' + data.body.save_file_name+'' +
                                    '</a>'
                                );

                                //裁剪成功，关闭窗口
                                alert("裁剪成功，窗口即将关闭");
                                window.close();
                                // parent.layer.closeAll();

                            }else{
                                alert("失败了...");
                            }
                        }
                    });
                }

            });

            function updatePreview(c){
                if (parseInt(c.w) > 0) {
                    var rx = $pcnt.width() / c.w;
                    var ry = $pcnt.height() / c.h;

                    $pimg.css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                }
            }

        });
    </script>


    <style type="text/css">
        .jcrop-holder #preview-pane {
            display: block;
            position: absolute;
            z-index: 2000;
            top: 52px;
            right: -238px;
            background-color: #ccd7e2;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 0px;
            -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
            box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.2);
        }

        #preview-pane .preview-container {
            width: 200px;
            height: 150px;
            overflow: hidden;
        }
    </style>
@endsection