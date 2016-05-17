<div id="add-rel" class="modal fade" role="dialog">
    <div class="modal-dialog horoscope-ui">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Relation</h4>
            </div>
            <div class="modal-body">
                {!!Form::open(['url'=>URL::route('relation.add'), 'class'=>'dev-rel-form'])!!}
                <div class="row">
                    <div class="">
                        <div class="col-sm-4 form-group"><label>   {!! Form::label('rel_user_id','Person:') !!}</label></div>
                        <div class="col-sm-8 form-group">
                            <?php
                            $userIds = ['' => '--Select--'];
                            foreach ($clients as $client) {
                                if ($client->id != $user->id) {
                                    $userIds[$client->id] = $client->first_name . (isset($client->last_name) ? " $client->last_name" : '');
                                }
                            }
                            ?>
                            <div class="form-group">
                                {!! Form::select('rel_user_id', $userIds, ['class' => 'form-control', 'placeholder' => 'state', 'autocomplete' => 'off']) !!}
                                <span class="text-danger">{!! $errors->first('rel_user_id') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="col-sm-4 form-group"><label>   {!! Form::label('rel_type_id','Relation:') !!}</label></div>
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
                    <div class="">
                        <div class="col-sm-4 form-group"><label>   {!! Form::label('is_referrer','Is Referrer?:') !!}</label></div>
                        <div class="col-sm-8 form-group">
                            <div class="form-group">
                                {{ Form::checkbox('is_referrer', 1,null,['class'=>'dev-is_referrer']) }}
                            </div>
                        </div>
                    </div>    
                </div>
                {!!Form::hidden('rel_client_id',$user->id)!!}
                {!!Form::hidden('rel_id',null)!!}
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="dev-btn-save btn btn-info">Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#rel_user_id,#rel_type_id').select2({});
        $('.dev-is_referrer').iCheck({checkboxClass: 'icheckbox_square-blue', radioClass: 'iradio_square-blue', increaseArea: '20%'});
        var $addRel = $("#add-rel");
        $(".dev-btn-save", $addRel).click(function (e) {
            var $frm = $(".dev-rel-form", $addRel);
            var frmData = $frm.serialize();
            $.post($frm.attr("action"), frmData, function (res) {
                console.log("Data:" + JSON.stringify(res));
                if (res.SUCCESS) {
                    loadRelations();
                    $("#add-rel").modal("hide");
                } else {
                    alert(res.msg);
                }
            }, 'json');
        });
        function loadRelations() {
            var $trGrid = $('.dev-rel-grid');
            $.get("{{URL::route('relation.list')}}", {cid: "{{$user->id}}", rows: $trGrid.children('tr').length}, function (data) {
                //if (data.success) {
                $('.dev-rel-grid').html(data);
                //}
            });
        }
        loadRelations();
    });
</script>