
<div class="row margin-bottom-12">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> Search Clients</h3>
            </div>
            <div class="panel-body">
                @include('admin.client.search')
            </div>
        </div>
    </div>
</div>

<div class="row margin-bottom-12">
     <div class="col-md-6">
        {!! Form::open(['method' => 'get', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        {!! Form::select('order_by', ["" => "select column", "first_name" => "First name", "last_name" => "Last name", "email" => "Email", "last_login" => "Last login", "active" => "Active"], $request->get('order_by',''), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::select('ordering', ["asc" => "Ascending", "desc" => "descending"], $request->get('ordering','asc'), ['class' =>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Order', ['class' => 'btn btn-default']) !!}
                    </div>
                {!! Form::close() !!}
    </div>
    <div class="col-md-6">
        <a href="{{ URL::to('/admin/client/add') }}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add New</a>
    </div>
</div>

@if(! $users->isEmpty() )
              <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>Email</th>
                              <th class="hidden-xs">First name</th>
                              <th class="hidden-xs">Last name</th>
                              <th>Active</th>
                              <th class="hidden-xs">Last login</th>
                              <th>Operations</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($users as $user)
                          <tr>
                              <td class="hidden-xs"><a href="{!! URL::to('/admin/client/'.$user->id.'/details') !!}">{!! $user->first_name !!}</a></td>
                              <td class="hidden-xs">{!! $user->last_name !!}</td>
                              <td><a href="{!! URL::to('/admin/client/'.$user->id.'/details') !!}">{!! $user->email !!}</a></td>
                              <td>{!! $user->activated ? '<i class="fa fa-circle green"></i>' : '<i class="fa fa-circle-o red"></i>' !!}</td>
                              <td class="hidden-xs">{!! $user->last_login ? $user->last_login : 'not logged yet.' !!}</td>
                              <td>
                                  @if(! $user->protected)
                                      <a href="{!! URL::route('users.edit', ['id' => $user->id]) !!}"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                                      <a href="{!! URL::route('users.delete',['id' => $user->id, '_token' => csrf_token()]) !!}" class="margin-left-5 delete"><i class="fa fa-trash-o fa-2x"></i></a>
                                  @else
                                      <i class="fa fa-times fa-2x light-blue"></i>
                                      <i class="fa fa-times fa-2x margin-left-12 light-blue"></i>
                                  @endif
                              </td>
                          </tr>
                      </tbody>
                      @endforeach
              </table>
              <div class="paginator">
                  {!! $users->appends($request->except(['page']) )->render() !!}
              </div>
              @else
                  <span class="text-warning"><h5>No results found.</h5></span>
              @endif
<div class="paginator">
    {!! $users->appends($request->except(['page']) )->render() !!}
</div>

<script>
        $(document).ready(function(){
            $(".delete").click(function(){
            });
        });
    </script>
