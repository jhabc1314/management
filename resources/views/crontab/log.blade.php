@extends("management::layouts.management")

@section("title", "Log-Crontab")

@section("content")
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Crontab Log</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Crontab Log</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- COLOR PALETTE -->
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn"></i>
                        Log Content
                    </h3>
                </div>
                <div class="card-body">
                        @foreach($log_lines as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    @if(count($log_lines) >= 50)
                        <a href="{{ route('crontab.log', $id) }}?page={{ ++$page }}">
                            <button type="button" class="btn btn-outline-primary" >下一页</button>
                        </a>
                    @endif
                </div>

            </div>
            <!-- /.card -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section("script")

@endsection

@section("css")

@endsection