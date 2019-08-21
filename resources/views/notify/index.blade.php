@extends("management::layouts.management")

@section('title', "notify")
@section("content")
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Notify</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Notify</li>
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
                <h3 class="card-title">消息列表</h3>
            </div>
            <div class="card-body">
                @foreach($notices as $notify)
                    @if($notify->notice_level == 'error')
                        <a href="{{ route('notify.read', $notify->id) }}">
                            <h3>{{ $notify->notice_title }}</h3>
                            <p class="text-danger">{{$notify->notice_text}}</p>
                        </a>

                    @elseif($notify->notice_level == 'warning')
                        <a href="{{ route('notify.read', $notify->id) }}">
                            <h3>{{ $notify->notice_title }}</h3>
                            <p class="text-warning">{{$notify->notice_text}}</p>
                        </a>
                    @else
                        <a href="{{ route('notify.read', $notify->id) }}">
                            <h3>{{ $notify->notice_title }}</h3>
                            <p class="text-info">{{$notify->notice_text}}</p>
                        </a>
                    @endif
                    <hr>
                @endforeach
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
