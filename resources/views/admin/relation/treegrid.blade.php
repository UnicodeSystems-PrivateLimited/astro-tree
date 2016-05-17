@foreach($rels as $rel)
<tr class="treegrid-{{$offset+1}}">
    <td><span class="client-profile"><img class="img-responsive img-thumbnail img-circle" src="{{ URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png') }}" alt=".." > </span> {{$rel->first_name.(isset($rel->last_name)?" $rel->last_name":'')}}</td><td class="hidden-xs">{{$rel->name}}</td><td><label class="table-edit-trash"><a href="#"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label></td>
</tr>
@endforeach
   
