 {!! Form::open(['route' => 'users.list','method' => 'get']) !!}
 <div class="row">
     <div class="col-sm-3">
         <div class="form-group">
             {!! Form::label('name','Name: ') !!}
             {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'name']) !!}
             <span class="text-danger">{!! $errors->first('first_name') !!}</span>
         </div>
     </div>
     <div class="col-sm-3">
         <div class="form-group">
             {!! Form::label('email','Email: ') !!}
             {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'user email']) !!}
              <span class="text-danger">{!! $errors->first('email') !!}</span>
         </div>
     </div>
     <div class="col-sm-3">
         <div class="form-group">
             {!! Form::label('last_name','Last name:') !!}
             {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'last name']) !!}
             <span class="text-danger">{!! $errors->first('last_name') !!}</span>
         </div>
     </div>     
     <div class="col-sm-3">
         <div class="form-group">
            {!! Form::label('code','User code:') !!}
            {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'user code']) !!}
             <span class="text-danger">{!! $errors->first('code') !!}</span>
         </div>
     </div>
 </div>
 <div class="row" style="margin-left: 0px;">
     <div class="form-group">
            <a href="{!! URL::route('users.list') !!}" class="btn btn-default search-reset">Reset</a>
            {!! Form::submit('Search', ["class" => "btn btn-info", "id" => "search-submit"]) !!}
        </div>
 </div>
 {!! Form::close() !!}