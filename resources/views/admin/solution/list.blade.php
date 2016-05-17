@extends('laravel-authentication-acl::admin.layouts.base-2cols')
@section('title')
Admin area: Solutions
@stop
@section('content')
<section class="content-header">
    <h1>
        Solutions
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/admin/users/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#" class="active">Solutions</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{-- print messages --}}
            <?php $message = Session::get('message'); ?>
            @if( isset($message) )
            <div class="alert alert-success">{!! $message !!}</div>
            @endif
            {{-- print errors --}}
            @if($errors && ! $errors->isEmpty() )
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            @endif
            <div class="box">
                <div class="box-body">
                    <div class="text-right btn-block form-group">
                        <button class="btn btn-info" data-toggle="modal" data-target="#add-solution"><i class="fa fa-plus"> </i> Add New</button>
                    </div>
                    <div class="dev-soln-cont"><i>Loading...</i></div>
                </div><!-- /.box -->
            </div>
            @include('admin.solution.form')            
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script>
    $(function () {
        function loadData() {
            $.get("{{URL::route('solution.list')}}", {}, function (data) {
                $('.dev-soln-cont').html(data);
            });
        }
        loadData();        
    });
</script>
@stop
@section('footer_scripts')
@stop