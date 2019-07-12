@extends("management::layouts.management")

@section("title", "Crontab_Create")

@section("content")
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>添加调度任务节点</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">任务管理</a></li>
                        <li class="breadcrumb-item active">添加节点</li>
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
                        <form role="form" action="{{route('crontab.store')}}" method="post">
                            {{csrf_field()}}
                            <div class="card-body">
                                <label>IP mask:</label>

                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                                        </div>
                                        <input type="text" name="cron_node_ip" class="form-control"
                                               data-inputmask="'alias': 'ip'" data-mask>
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <div class="form-group">
                                    <label for="cron_timer">任务执行频率(每隔n秒执行一次)</label>
                                    <input type="text" name="cron_timer" class="form-control" id="cron_timer"
                                           value="60">
                                </div>
                                <div class="form-group">
                                    <label for="cron_command">调度命令(尽量使用绝对路径)</label>
                                    <input type="text" name="cron_command" class="form-control" id="cron_command"
                                           placeholder="eg. /usr/bin/php /your-project-directory/artisan schedule:run">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
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

@endsection

@section("script")
    <!-- jQuery -->
    <script src="{{ m_asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{m_asset('plugins/inputmask/jquery.inputmask.bundle.js')}}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{m_asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            $('[data-mask]').inputmask()
        });
    </script>
@endsection
