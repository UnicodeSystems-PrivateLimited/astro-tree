@foreach($solutions as $solution)
<?php
$cat = $solution->category;
$user = $solution->client->user_profile->first();
$data[] = [$solution->query,$solution->solution, $solution->type,$user->first_name, $solution->created_at->format('d M, Y'), $cat->name];
?>
<div class="post">
    <div class="user-block">
        <img class="img-circle img-bordered-sm" src="{!!URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png')!!}" alt="user image">
        <span class="username">
            <a href="#">{{$user->first_name.(isset($user->last_name)?" $user->last_name":'')}}</a>
            <button class="btn btn-box-tool" data-toggle="collapse" data-target="#question"><i class="fa fa-question-circle">&nbsp;</i></button>
            <label class="table-edit-trash pull-right visible-xs"><a href="#" data-toggle="modal" data-target="#add-solution"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label>
        </span>
        <span class="description">Posted at - {{$solution->created_at->format('d M, Y')}}</span>
    </div>
    <div class="user-description">
    <div id="question" class="collapse">
        <b>Question:</b> Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
    </div>
    <p>{{$solution->solution}}</p>
    <input class="form-control input-sm" type="text" placeholder="Type a comment" />
    </div>
    <!-- /.user-block -->
</div>
@endforeach