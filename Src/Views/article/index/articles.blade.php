
@extends("StarsPeace::article.index.layout")


@section('car-head')

@endsection

@section('car-body')

    @include("StarsPeace::article.index.inc")

    <div class="tab-content">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th width="100" align="center">
                    操作
                </th>
                @foreach( $bindListColumns as $_listColumnName )
                    @foreach( $sheetColumns as $_sheetColumn )
                        @if( $_listColumnName == $_sheetColumn['db_name'] )
                            <th >{{$_sheetColumn['title']}}</th>
                            @break
                        @endif
                    @endforeach
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($datas as $_valueIndex=>$valueItem)
                <tr>
                    <td align="center">
                        @include("StarsPeace::plugs.list.action")
                    </td>

                    @foreach( $bindListColumns as $_listColumnName )
                        @if( isset( $sheetColumns[$_listColumnName]['plug'] ) )
                            <td>
                                @switch( $sheetColumns[$_listColumnName]['plug'] )
                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TEXT )
                                    @include("StarsPeace::plugs.list.text")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_SELECT )
                                    @include("StarsPeace::plugs.list.select")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_EDITOR )
                                    @include("StarsPeace::plugs.list.editor")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CROPPER )
                                    @include("StarsPeace::plugs.list.cropper")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_RADIOS )
                                    @include("StarsPeace::plugs.list.radios")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CHECKBOX )
                                    @include("StarsPeace::plugs.list.checkboxs")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_UPLOAD )
                                    @include("StarsPeace::plugs.list.upload")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_NUMBER )
                                    @include("StarsPeace::plugs.list.number")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TIME )
                                    @include("StarsPeace::plugs.list.timer")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TEXTAREA )
                                    @include("StarsPeace::plugs.list.textarea")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_PASSWORD )
                                    @include("StarsPeace::plugs.list.password")
                                    @break

                                    @default
                                    --
                                @endswitch
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        @include( "StarsPeace::inc.pagination" )
    </div>
@endsection
