@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
Admin area: Gifts
@stop

@section('content')

<section class="content-header">
    <h1>
        Gifts
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/admin/users/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#" class="active">Gifts</a></li>
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
                    <div class="alert alert-danger alert-dismissible">
                        <i class="icon fa fa-ban">&nbsp;</i>something is wrong
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <i class="icon fa fa-check">&nbsp;</i>Successfully Done.
                    </div>
                    <div class="alert alert-warning alert-dismissible">
                        <i class="icon fa fa-warning">&nbsp;</i>warning message done
                    </div>

                    <div class="text-right btn-block form-group">
                        <button class="btn btn-info" data-toggle="modal" data-target="#add-gift"><i class="fa fa-plus">&nbsp;</i> Add New</button>
                    </div>
                    <table class="dev-client-list table table-bordered table-striped display" cellspacing="0" width="100%">
                        <thead>
                            <tr>                           
                                <th>Gift</th>
                                <th>Client</th>
                                <th class="hidden-xs">Date</th> 
                                <th class="hidden-xs">Category</th> 
                                <th class="sorting_asc_disabled sorting_desc_disabled">&nbsp;</th> 
                            </tr>
                        </thead>
                    </table>       
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    @include('admin.gift.form')    
    <script>
        $(function () {
            var giftTable = $('.dev-client-list').DataTable({
                ajax: "{{ URL::route('gift.list')}}",
                order: [[2, 'desc']],
                columns: [
                    null,
                    null,
                    {className: "hidden-xs"},
                    {className: "hidden-xs"},
                    null
                ]
            });           
        });
    </script>
</section><!-- /.content -->
@stop

@section('footer_scripts')
@stop
