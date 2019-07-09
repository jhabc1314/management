@extends("management::layouts.management")

@section("title", "Supervisor")

@section("content")

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $server->server_name }} 进程管理</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('servers.index') }}">服务列表</a></li>
                        <li class="breadcrumb-item active">Supervisor</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Supervisor</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1"
                                                        data-toggle="tab">服务状态</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2"
                                                        data-toggle="tab">编辑配置</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3"
                                                        data-toggle="tab">下发配置</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <table id="status-table" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>节点</th>
                                            <th>配置状态</th>
                                            <th>运行状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($nodes as $k => $node)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $node->ip }} : {{$node->port}}</td>
                                                <td>
                                                    @if($node->status == 'online')
                                                        <span class="btn btn-success">{{$node->status}}</span>
                                                    @else
                                                        <span class="btn btn-danger">{{$node->status}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(strpos($node->run_status, 'RUNNING') !== false)
                                                        <span class="btn btn-success">{{$node->run_status}}</span>
                                                    @else
                                                        <span class="btn btn-danger">{{$node->run_status}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($node->status == 'online')
                                                        <button type="button" class="btn btn-outline-danger"
                                                                data-toggle="modal" data-target="#modal-offline">下线
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-outline-success"
                                                                data-toggle="modal" data-target="#modal-online">上线
                                                        </button>
                                                    @endif
                                                    @if (strpos($node->run_status, 'STOPPED') !== false)
                                                        <a href="{{ route('supervisor.start', [$server->id, $node->ip]) }}">
                                                            <button type="button" class="btn btn-outline-success">启动
                                                            </button>
                                                        </a>
                                                    @elseif (strpos($node->run_status, 'RUNNING') !== false)
                                                        <button type="button" class="btn btn-outline-danger"
                                                                data-toggle="modal" data-target="#modal-restart">重启
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger"
                                                                data-toggle="modal" data-target="#modal-stop">停止
                                                        </button>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>节点</th>
                                            <th>配置状态</th>
                                            <th>运行状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">
                                    <!-- form start -->
                                    <form role="form" action="{{route('supervisor.store', $server->id)}}" method="post">
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <label for="command">Command(启动服务命令)</label>
                                            <input type="text" name="s_command" class="form-control" id="command"
                                                   placeholder="eg. /usr/bin/php /var/www/yourproject/artisan swoole:server server_name start"
                                                   value="{{ is_object($supervisor) ? $supervisor->s_command : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="s_stdout">stdout_logfile_path</label>
                                            <input type="text" name="s_stdout" class="form-control" id="s_stdout"
                                                   placeholder="eg./var/www/yourproject/storage/logs/stdout.log"
                                                   value="{{ is_object($supervisor) ? $supervisor->s_stdout : ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="s_stdin">stdin_logfile_path</label>
                                            <input type="text" name="s_stdin" class="form-control" id="s_stdin"
                                                   placeholder="eg./var/www/yourproject/storage/logs/stdin.log"
                                                   value="{{is_object($supervisor) ? $supervisor->s_stdin : ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="s_user">user</label>
                                            <input type="text" name="s_user" class="form-control" id="s_user"
                                                   placeholder="Enter user name"
                                                   value="{{is_object($supervisor) ? $supervisor->s_user : ''}}">
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <span class="text-danger">注意：提交后需要重新下发才能生效</span>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_3">
                                    <table id="nodes-table" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>节点</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($nodes as $k => $node)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $node->ip }} : {{$node->port}}</td>
                                                <td>
                                                    <a href="{{ route('supervisor.push', [$server->id, $node->ip]) }}">
                                                        <button type="button" class="btn btn-outline-primary">下发
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>节点</th>
                                            <th>
                                                <a href="{{ route('supervisor.pushAll', $server->id) }}">
                                                    <button type="button" class="btn btn-outline-success">全部下发</button>
                                                </a>
                                            </th>
                                        </tr>

                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- END CUSTOM TABS -->
            <div class="modal fade" id="modal-offline">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h4 class="modal-title">确定下线吗？</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            下线操作为：
                            <p>1. 修改配置文件中该节点为 offline</p>
                            <p>2. 下发配置文件到所有客户端</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">取 消</button>
                            <a href="{{route('supervisor.offline', [$server->id, $node->ip])}}">
                                <button type="button" class="btn btn-outline-light">确 定</button>
                            </a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <div class="modal fade" id="modal-restart">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h4 class="modal-title">确定重启吗？</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>请务必先下线节点再重启，不然可能会导致重启期间服务不可用！</p>
                            重启操作为：
                            <p>Supervisor restart 该节点</p>
                            <p>重启比较耗时，请耐心等待</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">取 消</button>
                            <a href="{{route('supervisor.restart', [$server->id, $node->ip])}}">
                                <button type="button" class="btn btn-outline-light">确 定</button>
                            </a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="modal-stop">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h4 class="modal-title">确定停止节点吗？</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>请务必先将配置 offline 再停止服务，否则可能导致服务不可用！</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">取 消</button>
                            <a href="{{route('supervisor.stop', [$server->id, $node->ip])}}">
                                <button type="button" class="btn btn-outline-light">确 定</button>
                            </a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="modal-online">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h4 class="modal-title">确定 online 节点吗？</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>请务必保证服务处于 RUNNING 状态再 online 节点！</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">取 消</button>
                            <a href="{{route('supervisor.online', [$server->id, $node->ip])}}">
                                <button type="button" class="btn btn-outline-light">确 定</button>
                            </a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

        </div>
    </section>
@endsection

@section("script")
    <!-- jQuery -->
    <script src="{{ m_asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{m_asset('dist/js/adminlte.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{m_asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- SweetAlert2 -->
    <script src="{{m_asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {
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

            let success = '{{session()->get('success', '')}}';
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