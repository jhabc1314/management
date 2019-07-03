@extends("management::layouts.management")

@section("title", 'Client')

@section("content")

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $server->server_name }} 客户端列表</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('servers.index') }}">服务管理</a></li>
                        <li class="breadcrumb-item active">客户端管理</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <!-- CREATE FORM -->
                        <form class="form-inline ml-3" action="{{route("clients.store", $id)}}" method="post">
                            {{ csrf_field() }}
                            <div class="input-group input-group-sm">
                                <input name="ip" class="form-control form-control-navbar" type="text"
                                       placeholder="ip address">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">添加</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Ip</th>
                                <th>添加人</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td style="width: 10px">{{ $client->id }}</td>
                                    <td>{{ $client->client_ip }}</td>
                                    <td>{{ $client->creator }}</td>
                                    <td>{{ $client->created_at }}</td>
                                    <td>
                                        <a href="{{ route('clients.push', [$id, $client->id]) }}">
                                            <button type="button" class="btn btn-outline-primary">下发配置</button>
                                        </a>
                                        <a href="{{ route('clients.destroy', [$id, $client->id]) }}">
                                            <button type="button" class="btn btn-outline-danger">删除</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Ip</th>
                                <th>添加人</th>
                                <th>添加时间</th>
                                <th>
                                    <a href="{{ route('clients.pushAll', $id) }}">
                                        <button type="button" class="btn btn-outline-success">全部下发</button>
                                    </a>
                                </th>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


@endsection


@section("script")

@endsection
