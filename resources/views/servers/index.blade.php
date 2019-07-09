@extends("management::layouts.management")

@section("title", "Servers")

@section("content")
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>服务列表</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">服务管理</li>
                        <li class="breadcrumb-item "><a href="{{route('servers.create')}}">添加服务</a></li>
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
                            <th>编 号</th>
                            <th>服务名称</th>
                            <th>服务说明</th>
                            <th>创建时间</th>
                            <th>创建人</th>
                            <th>更新时间</th>
                            <th>更新人</th>
                            <th>操 作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($servers as $server)
                            <tr>
                                <td>{{ $server->id }}</td>
                                <td>{{ $server->server_name }}</td>
                                <td>{{ $server->server_desc }}</td>
                                <td>{{ $server->created_at }}</td>
                                <td>{{ $server->creator }}</td>
                                <td>{{ $server->updated_at }}</td>
                                <td>{{ $server->modifier }}</td>
                                <td>
                                    <a href="{{route('servers.edit', [$server->id])}}">
                                        <button type="button" class="btn btn-outline-primary">编辑服务</button>
                                    </a>
                                    <a href="{{route('clients.index', [$server->id])}}/">
                                        <button type="button" class="btn btn-outline-info">客户端管理</button>
                                    </a>
                                    <a href="{{route('supervisor.index', [$server->id])}}">
                                        <button type="button" class="btn btn-outline-success">Supervisor</button>
                                    </a>
                                    {{--<button type="button" class="btn btn-outline-danger disabled">删 除</button>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>编 号</th>
                            <th>服务名称</th>
                            <th>服务说明</th>
                            <th>创建时间</th>
                            <th>创建人</th>
                            <th>更新时间</th>
                            <th>更新人</th>
                            <th>操 作</th>
                        </tr>

                        </tfoot>
                    </table>
                    {{ $servers->links() }}
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
                    title: 'Fail'
                })
            }
        });
    </script>
@endsection

@section("css")
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{m_asset('plugins/sweetalert2/sweetalert2.min.css')}}">
@endsection