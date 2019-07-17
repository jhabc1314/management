@extends("management::layouts.management")

@section("title", "Crontab")

@section("content")
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>调度任务列表</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">任务节点管理</li>
                        <li class="breadcrumb-item "><a href="{{route('crontab.create')}}">添加服务</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="servers-table" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>节点ip</th>
                            <th>调度命令</th>
                            <th>执行频率</th>
                            <th>任务状态</th>
                            <th>创建人</th>
                            <th>更新时间</th>
                            <th>更新人</th>
                            <th>操 作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($crontabs as $crontab)
                            <tr>
                                <td>{{ $crontab->id }}</td>
                                <td>{{ $crontab->cron_node_ip }}</td>
                                <td>{{ $crontab->cron_command }}</td>
                                <td>每{{ $crontab->cron_timer }}秒运行一次</td>
                                <td>
                                    @if($crontab->cron_node_status == 0)
                                        <span class="btn btn-warning">停止</span>
                                    @elseif($crontab->cron_node_status == 1)
                                        <span class="btn btn-success">运行</span>
                                    @endif
                                </td>
                                <td>{{ $crontab->creator }}</td>
                                <td>{{ $crontab->updated_at }}</td>
                                <td>{{ $crontab->modifier }}</td>
                                <td>
                                    <form action="{{route('crontab.destroy', $crontab->id)}}" method="post"
                                          class="form-inline" onsubmit="submit()">
                                        {{method_field('DELETE')}}
                                        {{csrf_field()}}
                                        <a href="{{route('crontab.edit', [$crontab->id])}}">
                                            <button type="button" class="btn btn-outline-primary">编辑</button>
                                        </a>&nbsp;
                                        @if($crontab->cron_node_status == 0)
                                            <a href="{{route('crontab.start', [$crontab->id])}}/">
                                                <button type="button" class="btn btn-outline-success">启动</button>
                                            </a>
                                        @elseif($crontab->cron_node_status == 1)
                                            <a href="{{route('crontab.stop', [$crontab->id])}}/">
                                                <button type="button" class="btn btn-outline-warning">暂停</button>
                                            </a>
                                        @endif
                                        &nbsp;
                                        <button type="submit" class="btn btn-outline-danger">删除</button>
                                        &nbsp;
                                        <a href="{{route('crontab.log', $crontab->id)}}">
                                            <button type="button" class="btn btn-outline-info">日志</button>
                                        </a>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>节点ip</th>
                            <th>调度命令</th>
                            <th>执行频率</th>
                            <th>任务状态</th>
                            <th>创建人</th>
                            <th>更新时间</th>
                            <th>更新人</th>
                            <th>操 作</th>
                        </tr>

                        </tfoot>
                    </table>
                    {{ $crontabs->links() }}
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section("script")
    <!-- jQuery -->
    <script src="{{ m_asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ m_asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{m_asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{m_asset('plugins/datatables/dataTables.bootstrap4.js')}}"></script>
    <!-- FastClick -->
    <script src="{{m_asset('plugins/fastclick/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ m_asset('dist/js/adminlte.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{m_asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            $("#servers-table").DataTable({
                "paging": false,
                "info": false
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            function succ(v) {
                Toast.fire({
                    type: 'success',
                    title: v
                });
            }

            let success = '{{ session()->get('success', '') }}';
            let fail = '{{session()->get('fail', '')}}';
            if (success.length > 0) {
                succ(success);
            }
            if (fail.length > 0) {
                Toast.fire({
                    type: 'error',
                    title: fail
                })
            }
        });
        function submit()
        {
            let c = confirm('确定删除?');
            console.log(c);
            return c;
        }
    </script>
@endsection

@section("css")
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{m_asset('plugins/sweetalert2/sweetalert2.min.css')}}">
@endsection