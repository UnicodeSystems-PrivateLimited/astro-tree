@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
Admin area: Client Details
@stop

@section('content')

<section class="content-header">
    <h1>Client Details</h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/admin/users/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to('/admin/client/list') }}" class="active">Clients</a></li>
        <li><a href="#" class="active">Details</a></li>
    </ol>
</section>

<section class="content user-details">

    <div class="layout-row layout-align-space-between client-details-wrap">
        <div class="column1">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile text-center">
                    <!--<img class="profile-user-img img-responsive img-circle" src="{!! $profile->presenter()->avatar() !!}" alt="User profile picture">-->
                    <!--                    <div class='profile-edit'>
                                            <img class="profile-user-img img-responsive img-circle" src="../../../public/packages/jacopo/laravel-authentication-acl/images/avatar.png" alt="User profile picture">
                                            <input type="file" />
                                            <span class="change-profile">Change</span>
                                        </div>-->
                    <div class='profile-edit'>
                        <img class="profile-user-img img-responsive img-circle" src="../../../public/packages/jacopo/laravel-authentication-acl/images/avatar.png" alt="User profile picture">
                        <a href="#" class="upload-icon"  data-toggle="modal" data-target="#upload-img"><span class="fa fa-edit">&nbsp;</span></a>
                    </div>
                    <h3 class="profile-username text-center">{{$profile->first_name.(isset($profile->first_name)?" $profile->last_name":'')}}</h3>

                    <p class="text-muted text-center">{{$user->email}}</p>
                    <?php
                    if (isset($astroProfile->planets)) {
                        $horo = json_decode($astroProfile->planets, TRUE);
                    }
                    ?>
                    @if(isset($horo))
                    <p class="text-muted text-center" ><b>Horoscope</b></p>
                    <div class="btn-block text-center">
                        <div class="horoscope-bg">
                            <div class="horo-ghar ghar-1">{{$horo[1]}}</div>
                            <div class="horo-ghar ghar-2">{{$horo[0]}}</div>
                            <div class="horo-ghar ghar-3"> {{$horo[6]}}</div>
                            <div class="horo-ghar ghar-4"> {{$horo[9]}}</div>
                            <div class="horo-ghar ghar-5">{{$horo[4]}}</div>
                            <div class="horo-ghar ghar-6">{{$horo[7]}}</div>
                            <div class="horo-ghar ghar-7">{{$horo[10]}}</div>
                            <div class="horo-ghar ghar-8">{{$horo[3]}}</div>
                            <div class="horo-ghar ghar-9">{{$horo[5]}}</div>
                            <div class="horo-ghar ghar-10">{{$horo[8]}}</div>
                            <div class="horo-ghar ghar-11">{{$horo[11]}}</div>
                            <div class="horo-ghar ghar-12">{{$horo[2]}}</div>                          
                        </div>
                    </div>
                    @endif
                    <button type="button" href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#add-horoscope"><b>{{isset($horo)?'Update':'Add'}} Horoscope</b></button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">About <i>{{$profile->first_name}}</i></h3>
                </div>
                <div class="box-body box-profile">
                    <ul class="list-group list-group-unbordered">
                        <?php
                        $ts = strtotime($astroProfile->dob);
                        $bDate = date('d M Y', $ts);
                        $bTime = date('g:i A', $ts);
                        $manglik = ["No", "Anshik", "Full"];
                        ?>
                        <li class="list-group-item">
                            <b>Birth Date</b> <a class="pull-right">{{$bDate}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Birth Time</b> <a class="pull-right">{{$bTime}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Birth Place</b> <a class="pull-right">{{$astroProfile->b_place}}</a>
                        </li>
                        @if(isset($astroProfile->manglik))
                        <li class="list-group-item">
                            <b>Manglik</b> <a class="pull-right">{{$manglik[$astroProfile->manglik]}}</a>
                        </li>
                        @endif
                    </ul>
                </div>                
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="column2">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#solutions" data-toggle="tab">Solutions</a></li>
                    <li><a href="#gifts" data-toggle="tab">Gifts</a></li>
                    <li><a href="#relations" data-toggle="tab">Relations</a></li>
                    <li><a href="#p_details" data-toggle="tab">Personal Details</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="solutions">
                        <div class="text-right btn-block form-group">
                            <button class="btn btn-info"><i class="fa fa-plus">&nbsp;</i> Add New</button>
                        </div>
                        <div class="dev-soln-cont">
                            <i>Loading...</i>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="gifts">
                        <div class="text-right btn-block form-group">
                            <button class="btn btn-info" data-toggle="modal" data-target="#add-gift"><i class="fa fa-plus">&nbsp;</i> Add New</button>
                        </div>
                        <table class="dev-gift-list table table-striped table-bordered display" cellspacing="0" width="100%">
                            <thead>
                                <tr>                           
                                    <th>Gift</th>
                                    <th class="hidden-xs">Date</th> 
                                    <th class="hidden-xs">Category</th> 
                                    <th class="sorting_asc_disabled sorting_desc_disabled">&nbsp;</th>  
                                </tr>
                            </thead>
                        </table>
                        <div id="add-gift" class="modal fade" role="dialog">
                            <div class="modal-dialog horoscope-ui">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Add Gift</h4>
                                    </div>
                                    <div class="modal-body">
                                        {!!Form::open(['url'=>URL::route('relation.type.add'), 'class'=>'dev-type-form'])!!}
                                        <div class="row">
                                            <div class="">
                                                <div class="col-sm-4 form-group"><label>   {!! Form::label('user_id','Client:') !!}</label></div>
                                                <div class="col-sm-8 form-group">
                                                    <?php
                                                    $userIds = ['' => '--Select--'];
                                                    foreach ($clients as $client) {
                                                        $userIds[$client->id] = $client->first_name . (isset($client->last_name) ? " $client->last_name" : '');
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        {!! Form::select('user_id', $userIds, ['class' => 'form-control', 'placeholder' => 'state', 'autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{!! $errors->first('user_id') !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="col-sm-4 form-group"><label>   {!! Form::label('rel_type_id','Category:') !!}</label></div>
                                                <div class="col-sm-8 form-group">
                                                    <div class="form-group">
                                                        <?php
                                                        $relTypeData = ['' => '--Select--'];
                                                        foreach ($relTypes as $relType) {
                                                            $relTypeData[$relType->id] = $relType->name;
                                                        }
                                                        ?>
                                                        {!! Form::select('rel_type_id', $relTypeData, ['class' => 'form-control', 'placeholder' => 'state', 'autocomplete' => 'off']) !!}
                                                        <span class="text-danger">{!! $errors->first('rel_type_id') !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {!!Form::hidden('id',null,['id'=>'rid'])!!}
                                        {!!Form::close()!!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="button" class="dev-btn-save btn btn-info">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="relations">
                        <div class="text-right btn-block form-group">
                            <button class="btn btn-info" data-toggle="modal" data-target="#add-rel"><i class="fa fa-plus">&nbsp;</i> Add New</button>
                        </div>
                        <table class="dev-rel-grid table tree table-bordered table-striped"></table>
<!--                        <tr class="treegrid-2 treegrid-parent-1">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Node 1-1</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
<tr class="treegrid-3 treegrid-parent-1">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Node 1-2</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
<tr class="treegrid-4 treegrid-parent-3">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Node 1-2-1</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
<tr class="treegrid-5">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Root node 2</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
<tr class="treegrid-6 treegrid-parent-5">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Node 2-1</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
<tr class="treegrid-7 treegrid-parent-5">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Node 2-2</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
<tr class="treegrid-8 treegrid-parent-7">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span>Node 2-2-1</td><td class="hidden-xs">Additional info</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>-->
                    </div>
                    <div class="tab-pane" id="p_details">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">About <span>{{$profile->first_name}}</span></h3>
                                    </div>
                                    <div class="box-body box-profile">
                                        <ul class="list-group list-group-unbordered">
                                            <?php
                                            $ts = strtotime($astroProfile->dob);
                                            $bDate = date('d M Y', $ts);
                                            $bTime = date('g:i A', $ts);
                                            $manglik = ["No", "Anshik", "Full"];
                                            ?>
                                            <li class="list-group-item">
                                                <b>Birth Date</b> <a class="pull-right">{{$bDate}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Birth Time</b> <a class="pull-right">{{$bTime}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Birth Place</b> <a class="pull-right">{{$astroProfile->b_place}}</a>
                                            </li>
                                            @if(isset($astroProfile->manglik))
                                            <li class="list-group-item">
                                                <b>Manglik</b> <a class="pull-right">{{$manglik[$astroProfile->manglik]}}</a>
                                            </li>
                                            @endif
                                            <li class="list-group-item">
                                                <b>Street</b> <a class="pull-right">{{$profile->address}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>City</b> <a class="pull-right">{{$profile->city}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>State</b> <a class="pull-right">{{$profile->state}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Country</b> <a class="pull-right">{{$profile->country}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Pin Code</b> <a class="pull-right">{{$profile->zip}}</a>
                                            </li>
                                        </ul>
                                    </div>                
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Address</h3>
                                    </div>
                                    <div class="box-body box-profile">
                                        <ul class="list-group list-group-unbordered">                                    
                                            <li class="list-group-item">
                                                <b>Street</b> <a class="pull-right">{{$profile->address}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>City</b> <a class="pull-right">{{$profile->city}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>State</b> <a class="pull-right">{{$profile->state}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Country</b> <a class="pull-right">{{$profile->country}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Pin Code</b> <a class="pull-right">{{$profile->zip}}</a>
                                            </li>
                                        </ul>
                                    </div>                
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    @include('admin.client.form.horoscop')
    @include('admin.client.form.avatar')
    @include('admin.relation.form')
    <script type="text/javascript">
        $(document).ready(function () {
            loadData();                      
            var giftTable = $('.dev-gift-list').DataTable({
                ajax: "{{ URL::route('gift.list')}}?cid={{$user->id}}",
                order: [[1, 'desc']],
                columns: [
                    null,
                    {className: "visible-lg"},
                    {className: "hidden-xs"},
                    null
                ]
            });            
            $(".tree").treegrid({
                expanderExpandedClass: 'fa fa-minus-circle',
                expanderCollapsedClass: 'fa fa-plus-circle'
            });            
        });
        function loadData() {
            $.get("{{URL::route('solution.list')}}", {cid: "{{$user->id}}"}, function (data) {
                $('.dev-soln-cont').html(data);
            });
        }
    </script>
</section><!-- /.content -->
@stop

@section('footer_scripts')
@stop
