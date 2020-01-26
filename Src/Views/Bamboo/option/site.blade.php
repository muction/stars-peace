@extends("StarsPeace::iframe")


@section( 'car-head' )

    <ul id="myTabs" class="nav nav-tabs" role="tablist">
        <li>
            <a href="{{ route( 'rotate.option.site' ) }}" >动态设置</a>
        </li>
    </ul>
@endsection

@section('car-body')
    <form class="form-inline" method="post" action="{{ route( 'rotate.option.sites.storage') }}">
        @csrf
        <div class="" style="margin-bottom: 10px;">
            <button type="submit" class="btn btn-success btn-sm">保存</button>
            <button type="button" class="btn btn-default btn-sm stars-create-new-customer">新增</button>
        </div>

        <div class="system-item">

            @foreach( $options as $key=>$value )
            <div class="">
                <div class="form-group input-group m-b-10">
                    <input class="form-control" type="text" name="key[]" value="{{$value['key']}}"  placeholder="Key">
                </div>
                <div class="form-group input-group m-b-10" style="margin-left: 6px;">
                    <input class="form-control" type="text" name="value[]" value="{{$value['value']}}" placeholder="Value">
                </div>
            </div>
            @endforeach
        </div>

        <div class="customer-item">


        </div>

    </form>


    <script type="text/javascript">
        $( function () {
            $('.stars-create-new-customer').click(function () {
                $('.customer-item').append(

                    '<div class="customer-item-children">' +
                        '' +
                        '<div class="form-group input-group m-b-10">' +
                            '<input class="form-control" type="text" name="key[]" placeholder="Key">' +
                        '</div>' +
                        '' +
                        '<div class="form-group input-group m-b-10" style="margin-left: 10px;">' +
                            '<input class="form-control" type="text" name="value[]" placeholder="Value">' +
                        '</div>' +
                        '' +
                        '<div class="form-group">' +
                            '<a class="btn btn-sm stars-remove-new-item" >删除</a>' +
                        '</div>' +
                    '</div>'
                );
            });


            $(document.body).on( 'click' ,'.stars-remove-new-item', function () {
                if( confirm("确定要移除吗") ){

                    $( '.customer-item-children').eq(
                        $('.stars-remove-new-item').index( $(this) )
                    ).remove();

                }
            });
        } );
    </script>
@endsection
