@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
Admin area: Edit Client
@stop

@section('content')
<section class="content-header">
    <h1>
        {!! isset($user->id) ? 'Edit' : 'New' !!} Client
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/admin/users/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::route('client.list') }}">Clients</a></li>
        <li class="active">{!! isset($user->id) ? 'Edit' : 'Create' !!} client</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{-- model general errors from the form --}}
            @if($errors->has('model') )
            <div class="alert alert-danger"><i class="fa fa-warning"></i> {{$errors->first('model')}}</div>
            @endif

            {{-- successful message --}}
            <?php $message = Session::get('message'); ?>
            @if( isset($message) )
            <div class="alert alert-success"><i class="fa fa-check"></i> {{$message}}</div>
            @endif

            <!--<div class="col-lg-12">-->
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Login data</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($user, [ 'url' => URL::route('client.add')] )  !!}
                {{-- Field hidden to fix chrome and safari autocomplete bug --}}
                {!! Form::password('__to_hide_password_autocomplete', ['class' => 'hidden']) !!}
                <div class="box-body">
                    <div class="row col-min-height">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('name','Name: *') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'client name', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('name') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('email','Email: *') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope">&nbsp;</i></span>
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'user email', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('phone','Phone: *') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone">&nbsp;</i></span>
                                    {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'client phone', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('phone') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                               {!! Form::label('b_date','Birth Date &amp; Time: *') !!}
                                <div class="row small-gutter">
                                    <div class="col-sm-6 form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar">&nbsp;</i></span>
                                            {!! Form::text('b_date', null, ['class' => 'form-control', 'placeholder' => 'birth date', 'autocomplete' => 'off']) !!}
                                            <span class="text-danger">{!! $errors->first('b_date') !!}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <div class="bootstrap-timepicker">
                                            <div class="bootstrap-timepicker-widget dropdown-menu"><table><tbody><tr><td><a href="#" data-action="incrementHour"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="incrementMinute"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td class="meridian-column"><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-up"></i></a></td></tr><tr><td><span class="bootstrap-timepicker-hour">11</span></td> <td class="separator">:</td><td><span class="bootstrap-timepicker-minute">30</span></td> <td class="separator">&nbsp;</td><td><span class="bootstrap-timepicker-meridian">AM</span></td></tr><tr><td><a href="#" data-action="decrementHour"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator"></td><td><a href="#" data-action="decrementMinute"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-down"></i></a></td></tr></tbody></table></div>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                {!! Form::text('b_time', null, ['class' => 'form-control timepicker', 'placeholder' => 'birth time']) !!}                            
                                                <span class="text-danger">{!! $errors->first('b_time') !!}</span>
                                            </div>                        
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('b_place','Place: *') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                    {!! Form::select('b_place', [''=>'--Select--','Lucknow'=>'Lucknow','Bareilly'=>'Bareilly'], ['class' => 'form-control', 'placeholder' => 'birth place', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('b_place') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                 {!! Form::label('sex','Gender: *') !!}
                                <div class="radio astro-radio" style='margin:0;'>
                                    <label>
                                        {!! Form::radio('sex', 1,true) !!}   
                                        <!--{!! Form::label('sex','Male') !!}-->
                                        Male
                                    </label>
                                    <label>

                                        {!! Form::radio('sex', 2,false) !!}                            
                                        <!--{!! Form::label('sex','Female') !!}-->
                                        Female
                                    </label>
                                    <span class="text-danger">{!! $errors->first('sex') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('password',isset($user->id) ? "Change password: " : "Password: ") !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock">&nbsp;</i></span>
                                    {!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => '']) !!}
                                    <span class="text-danger">{!! $errors->first('password') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('password_confirmation',isset($user->id) ? "Confirm change password: " : "Confirm password: ") !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock">&nbsp;</i></span>
                                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => '','autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                                </div> 
                            </div> 
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('addr_street','Street:') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-street-view">&nbsp;</i></span>
                                    {!! Form::text('addr_street', null, ['class' => 'form-control', 'placeholder' => 'street address', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('addr_street') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('addr_city','City:') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-location-arrow">&nbsp;</i></span>
                                    {!! Form::select('addr_city', [''=>'--Select--','Lucknow'=>'Lucknow','Bareilly'=>'Bareilly'], ['class' => 'form-control', 'placeholder' => 'city', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('addr_city') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('addr_state','State:') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map">&nbsp;</i></span>
                                    {!! Form::select('addr_state', [''=>'--Select--','Uttar Pradesh'=>'Uttar Pradesh','Madhya Pradesh'=>'Madhya Pradesh'], ['class' => 'form-control', 'placeholder' => 'state', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('addr_state') !!}</span>
                                </div>                        
                            </div>                        
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('addr_country','Country:') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                    {!! Form::select('addr_country', [''=>'--Select--','India'=>'India','Nepal'=>'Nepal'], ['class' => 'form-control', 'placeholder' => 'country', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('addr_country') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('pincode','Pin Code:') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                    {!! Form::text('pincode', null, ['class' => 'form-control', 'placeholder' => 'pin code', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{!! $errors->first('pincode') !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label( 'find_relative','Reference:', ['class' => 'new-detail']) !!}
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('rel_person','Reference Person:') !!}
                                        <div class="input-group">
                                            <?php
                                              $relClient=[''=>'--Reference Person--'];
                                              foreach($clients as $client){
                                                  $relClient[$client->id]=$client->first_name.(isset($client->last_name)?" $client->last_name":'');
                                              }
                                            ?>
                                            <span class="input-group-addon"><i class="fa fa-user">&nbsp;</i></span>
                                            {!! Form::select('rel_person', $relClient, null, ['class' => 'form-control']) !!}
                                            <span class="text-danger">{!! $errors->first('rel_person') !!}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('rel_type','Relation:') !!}
                                        <div class="input-group">
                                            <?php
                                              $relations=[''=>'--Select Relation--'];
                                              foreach($relTypes as $rel){
                                                  $relations[$rel->id]=$rel->name;
                                              }
                                            ?>
                                            <span class="input-group-addon"><i class="fa fa-users">&nbsp;</i></span>
                                            {!! Form::select('rel_type', $relations, null, ['class' => 'form-control']) !!}
                                            <span class="text-danger">{!! $errors->first('rel_type') !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                {!! Form::hidden('id') !!}
                {!! Form::hidden('form_name','user') !!}
                <div class="box-footer">
                    {!! Form::submit('Save', array("class"=>"btn btn-info")) !!}
                    {!! Form::submit('Reset', array("class"=>"btn btn-default search-reset")) !!}
                </div>
                {!! Form::close() !!}
            </div><!-- /.box -->
            <!--            </div>-->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script type="text/javascript">
    $(document).ready(function () {
        $('input#b_date').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        });
        $('select#b_place').select2({});
        $('select#addr_city').select2({});
        $('select#addr_state').select2({});
        $('select#addr_country').select2({});
        $('select#rel_person').select2({});
        $('select#rel_type').select2({});
        $('.timepicker').timepicker({
            showInputs: false
        });
    });
</script>
@stop

@section('footer_scripts')
@stop