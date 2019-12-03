@extends("StarsPeace::iframe")

@section('car-body')
<div class="container-fluid p-t-15">

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>系统环境</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <colgroup>
                                <col width="13%"/>
                                <col width="37%"/>
                                <col width="13%"/>
                                <col width="37%"/>
                            </colgroup>

                            <tbody>
                            <tr>
                                <td>服务器信息</td>
                                <td>{{ Sysinfo::server() }}</td>
                                <td>PHP 版本信息</td>
                                <td>{{ Sysinfo::php() }}</td>
                            </tr>

                            <tr>
                                <td>总内存信息</td>
                                <td>{{ Sysinfo::memory() }}</td>
                                <td>laravel版本</td>
                                <td>{{ Sysinfo::laraver() }}</td>
                            </tr>

                            <tr>
                                <td>服务器IP</td>
                                <td>{{ Sysinfo::ip() }}</td>
                                <td>最大上传文件大小</td>
                                <td>{{ Sysinfo::upload_max_filesize() }}</td>
                            </tr>
                            <tr>
                                <td>CPU信息</td>
                                <td>{{ Sysinfo::cpu() }}</td>
                                <td>时区信息</td>
                                <td>{{ Sysinfo::timezone() }}</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>



@endsection