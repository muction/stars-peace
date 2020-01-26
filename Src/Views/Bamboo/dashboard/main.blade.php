@extends("StarsPeace::iframe")

@section('car-body')
<div class="container-fluid p-t-15">

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card bg-primary">
                <div class="card-body clearfix">
                    <div class="pull-right">
                        <p class="h6 text-white m-t-0">附件数量</p>
                        <p class="h3 text-white m-b-0">{{$attachmentTotal}}</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent">
                            <i class="mdi mdi-all-inclusive fa-1-5x"></i></span> </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card bg-danger">
                <div class="card-body clearfix">
                    <div class="pull-right">
                        <p class="h6 text-white m-t-0">管理员总数</p>
                        <p class="h3 text-white m-b-0">{{$adminTotal}}</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-account fa-1-5x"></i></span> </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card bg-success">
                <div class="card-body clearfix">
                    <div class="pull-right">
                        <p class="h6 text-white m-t-0">下载总量</p>
                        <p class="h3 text-white m-b-0">0</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card bg-purple">
                <div class="card-body clearfix">
                    <div class="pull-right">
                        <p class="h6 text-white m-t-0">留言总数</p>
                        <p class="h3 text-white m-b-0">{{$messageTotal}} 条</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>每周用户</h4>
                </div>
                <div class="card-body">
                    <canvas class="js-chartjs-bars"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>交易历史记录</h4>
                </div>
                <div class="card-body">
                    <canvas class="js-chartjs-lines"></canvas>
                </div>
            </div>
        </div>

    </div>

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

<script type="text/javascript" src="{{asset("static/stars/js/jquery.min.js")}}"></script>
<script type="text/javascript" src="{{asset("static/stars/js/bootstrap.min.js")}}"></script>
<script type="text/javascript" src="{{asset("static/stars/js/main.min.js")}}"></script>

<!--图表插件-->
<script type="text/javascript" src="{{asset("static/stars/js/Chart.js")}}"></script>
<script type="text/javascript">
    $(document).ready(function(e) {
        var $dashChartBarsCnt  = jQuery( '.js-chartjs-bars' )[0].getContext( '2d' ),
            $dashChartLinesCnt = jQuery( '.js-chartjs-lines' )[0].getContext( '2d' );

        var $dashChartBarsData = {
            labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
            datasets: [
                {
                    label: '注册用户',
                    borderWidth: 1,
                    borderColor: 'rgba(0,0,0,0)',
                    backgroundColor: 'rgba(51,202,185,0.5)',
                    hoverBackgroundColor: "rgba(51,202,185,0.7)",
                    hoverBorderColor: "rgba(0,0,0,0)",
                    data: [2500, 1500, 1200, 3200, 4800, 3500, 1500]
                }
            ]
        };
        var $dashChartLinesData = {
            labels: ['2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014'],
            datasets: [
                {
                    label: '交易资金',
                    data: [20, 25, 40, 30, 45, 40, 55, 40, 48, 40, 42, 50],
                    borderColor: '#358ed7',
                    backgroundColor: 'rgba(53, 142, 215, 0.175)',
                    borderWidth: 1,
                    fill: false,
                    lineTension: 0.5
                }
            ]
        };

        new Chart($dashChartBarsCnt, {
            type: 'bar',
            data: $dashChartBarsData
        });

        var myLineChart = new Chart($dashChartLinesCnt, {
            type: 'line',
            data: $dashChartLinesData,
        });
    });
</script>

@endsection