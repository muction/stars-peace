@extends("StarsPeace::iframe")


@section('car-body')
    <table class="table table-hover">
        <colgroup>
            <col width="120"/>
            <col width="120"/>
            <col width="160"/>
        </colgroup>
        <thead>
        <tr>
            <th>原文件名</th>
            <th>大小</th>
            <th>保存名</th>
            <th>类型</th>
            <th>上传源</th>
            <th>上传日期</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>
                <td>{{ $item['original_name'] }}</td>
                <td>{{ $item['size'] }}</td>
                <td>{{ $item['save_file_name'] }}</td>
                <td>{{ $item['type'] }}</td>
                <td>{{ $item['source'] }}</td>
                <td>{{ $item['created_at'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection
