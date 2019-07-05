@extends("management::layouts.management")

@section('title', "Dashboard")
@section("content")

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">欢迎使用 Management 管理后台</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a href="http://www.jackdou.top">
                    <button type="button" class="btn btn-outline-primary">查看教程</button>
                </a> 更多功能敬请期待！
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="https://github.com/jhabc1314/management">
                    <button type="button" class="btn btn-outline-secondary">支持一下</button>
                </a>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection

@section("script")
    <!-- jQuery -->
    <script src="{{m_asset("plugins/jquery/jquery.min.js")}}"></script>
    <!-- Bootstrap -->
    <script src="{{m_asset("plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <!-- AdminLTE -->
    <script src="{{ m_asset("dist/js/adminlte.js") }}"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="{{ m_asset("plugins/chart.js/Chart.min.js") }}"></script>
    <script src="{{ m_asset("dist/js/demo.js") }}"></script>
    <script src="{{ m_asset("dist/js/pages/dashboard3.js") }}"></script>

@endsection