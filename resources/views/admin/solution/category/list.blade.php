@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
Admin area: Solution Categories
@stop

@section('content')

<section class="content-header">
    <h1>
        Solution Categories
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/admin/users/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#" class="active">Solution</a></li>
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
                        <button class="btn btn-info" data-toggle="modal" data-target="#add-cat"><i class="fa fa-plus">&nbsp;</i> Add New</button>
                    </div>
                    <div class="">
                        <table class="dev-cat-list table table-bordered table-striped display" cellspacing="0" width="100%">
                            <thead>
                                <tr>                           
                                    <th>Category</th> 
                                    <th class="hidden-xs">Description</th>                           
                                    <th class="hidden-xs">Status</th> 
                                    <th class="hidden-xs">Created At</th> 
                                    <th class="sorting_asc_disabled sorting_desc_disabled" style="width: 100px;">Actions</th> 
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div id="add-cat" class="modal fade" role="dialog">
        <div class="modal-dialog horoscope-ui">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Solution Category</h4>
                </div>
                <div class="modal-body">
                    {!!Form::open(['url'=>URL::route('solution.category.add'), 'class'=>'dev-cat-form'])!!}
                    <div class="row">
                        <div class="">
                            <div class="col-sm-4 form-group"><label>   {!! Form::label('name','Category:') !!}</label></div>
                            <div class="col-sm-8 form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'category name', 'required']) !!}
                                    <span class="text-danger">{!! $errors->first('name') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="col-sm-4 form-group"><label>   {!! Form::label('description','Description:') !!}</label></div>
                            <div class="col-sm-8 form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                    {{ Form::textarea('description', null, ['size' => '10x3', 'class' => 'form-control']) }}
                                    <span class="text-danger">{!! $errors->first('description') !!}</span>
                                </div>
                            </div>
                        </div>                        
                        <div class="">
                            <div class="col-sm-4 form-group"><label>   {!! Form::label('status','Status:') !!}</label></div>
                            <div class="col-sm-8 form-group">
                                <div class="form-group">
                                    {{ Form::checkbox('status', 1,null) }}
                                </div>
                            </div>
                        </div>                        
                    </div>
                    {!!Form::hidden('id',null,['id'=>'cid'])!!}
                    {!!Form::close()!!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="dev-btn-save btn btn-info">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>

        $(function () {
            var $addModel = $('#add-cat').on('show.bs.modal', function (event) {
                var $this=$(this);
                $this.find('#cid').val("");
                $this.find('#name').val("");
                $this.find('#description').val("");
                $('#status').iCheck('uncheck');
            });
            var $chkStatus = $('#status').iCheck({checkboxClass: 'icheckbox_square-blue', radioClass: 'iradio_square-blue', increaseArea: '20%'});
            var $catTable = $('.dev-cat-list').DataTable({
                ajax: "{{ URL::route('solution.categories')}}",
                columns: [
                    null,
                    {className: "hidden-xs"},
                    {className: "hidden-xs"},
                    {className: "hidden-xs"},
                    null
                ]
            });
            $('.dev-btn-save').click(function () {
                saveForm();
            });
            $('.dev-cat-form').submit(function(e){
                e.preventDefault();
                saveForm();
            });
            $('.dev-cat-list').on('click', '.dev-edit-rec', function (e) {
                e.preventDefault();
                $.get($(this).attr('href'), {}, function (res) {
                    if (res.success) {
                        $addModel.find('#cid').val(res.data.id);
                        $addModel.find('#name').val(res.data.name);
                        $addModel.find('#description').val(res.data.description);
                        $('#status').iCheck(res.data.status ? 'check' : 'uncheck');
                        console.log("Data:" + JSON.stringify(res.data));
                    }
                }, 'json');
                $addModel.modal('show');
            });
            $('.dev-cat-list').on('click', '.dev-del-rec', function (e) {
                e.preventDefault();
                if (confirm("Do you realy want to delete?")) {
                    $.get($(this).attr('href'), {}, function (res) {
                        if (res.success) {
                            $catTable.ajax.reload();
                            console.log(res.msg);
                        }
                    }, 'json');
                }
            });
            function saveForm() {
                var $frm = $('.dev-cat-form');
                var frmData = $frm.serialize();
                $.post($frm.attr('action'), frmData, function (data) {
                    if (data.success) {
                        $('#add-cat').modal('hide');
                        $catTable.ajax.reload();
                        console.log(data.msg);
                    }
                }, 'json');
            }
        });
    </script>
</section><!-- /.content -->
@stop

@section('footer_scripts')
@stop
