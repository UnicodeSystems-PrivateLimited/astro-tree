@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
Admin area: Clients List
@stop

@section('content')

<section class="content-header">
    <h1>Clients</h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/admin/users/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Clients</li>
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
                        <a href="{{ URL::to('/admin/client/add') }}" class="btn btn-info"><i class="fa fa-plus"></i> Add New</a>
                    </div>                    
                    <table class="dev-client-list table table-bordered table-striped display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Birth Date</th>
                                <th>Status</th>
                                <th class="sorting_asc_disabled sorting_desc_disabled" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                    </table> 
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <script>
        $(function () {
            $('.dev-client-list').DataTable({
                ajax: "{{ URL::route('client.list')}}",
                columns: [
                    {className: "client-profile"},
                    null,
                    {className: "hidden-xs"},
                    {className: "hidden-xs"},
                    {className: "hidden-xs hidden-sm"},
                    null,
                    null
                ]
            });
        });
    </script>
</section><!-- /.content -->
@stop

@section('footer_scripts')
@stop
