@extends("management::layouts.management")

@section("title", "Show-Server")

@section("content")
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>编辑服务</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">服务管理</a></li>
                        <li class="breadcrumb-item active">编辑服务</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('servers.update', $id)}}" method="post">
                            {{method_field('put')}}
                            {{csrf_field()}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="server_name">服务名称（数字字母唯一的标识 eg.swoole）</label>
                                    <input type="text" name="server_name" class="form-control" id="server_name"
                                           placeholder="Enter unique server name" value="{{ $server->server_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="server_desc">服务说明</label>
                                    <input type="text" name="server_desc" class="form-control" id="server_desc"
                                           placeholder="Enter server desc" value="{{ $server->server_desc }}">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="auto_governance" class="custom-control-input"
                                               id="auto_governance" {{ $server->auto_governance == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="auto_governance">开启服务治理</label>
                                    </div>
                                </div>
                                <!-- textarea -->
                                <div class="form-group">
                                    <label>节点配置
                                        <button type="button" class="btn btn-success" onclick="format()">format</button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modal-default">demo
                                        </button>
                                    </label>
                                    <textarea id="server_node" class="form-control" name="server_node" rows="15"
                                              placeholder="Enter ...">{{ $server->server_node }}</textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <span class="text-danger">注意：节点配置修改后需重新下发</span>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Node Demo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    建议直接复制粘贴修改
                    <pre>
[
    {
        "ip": "127.0.0.1",
        "port": 8880,
        "status": "online",
        "weight": 100
    },
    {
        "ip": "127.0.0.2",
        "port": 8880,
        "status": "offline",
        "weight": 80
    }
]
                    </pre>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section("script")
    <!-- jQuery -->
    <script src="{{ m_asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{m_asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script type="application/javascript">
        function format() {
            let raw = $("#server_node").val();
            if (!raw || raw.length === 0) return;
            let obj = JSON.parse(raw);
            let pretty = JSON.stringify(obj, undefined, 4);
            $("#server_node").val(pretty);
        }
        $(function () {
            format();
        })
    </script>
@endsection
