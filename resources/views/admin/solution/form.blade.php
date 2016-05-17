<div id="add-solution" class="modal fade" role="dialog">
    <div class="modal-dialog horoscope-ui">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Add Solution</h4>
            </div>
            <div class="modal-body">
                {!!Form::open(['url'=>URL::route('solution.add'), 'class'=>'dev-soln-form'])!!}
                <div class="row">
                    <div class="">
                        <div class="col-sm-4 form-group"><label> {!! Form::label('solution','Solution:') !!}</label></div>
                        <div class="col-sm-8 form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"> </i></span>
                                {{ Form::textarea('solution', null, ['size' => '10x3', 'class' => 'form-control']) }}
                                <span class="text-danger">{!! $errors->first('solution') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="col-sm-4 form-group"><label> {!! Form::label('cat_id','Category:') !!}</label></div>
                        <div class="col-sm-8 form-group">
                            <div class="form-group">
                                <?php
                                $solnCatData = ['' => '--Select--'];
                                foreach ($solnCats as $solnCat) {
                                    $solnCatData[$solnCat->id] = $solnCat->name;
                                }
                                ?>
                                {!! Form::select('cat_id', $solnCatData, ['class' => 'form-control', 'placeholder' => 'state', 'autocomplete' => 'off']) !!}
                                <span class="text-danger">{!! $errors->first('cat_id') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="col-sm-4 form-group"><label> {!! Form::label('user_id','Client:') !!}</label></div>
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
                </div>
                {!!Form::hidden('type','1')!!}
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
    $(function () {
        $('select#cat_id,select#user_id').select2({});
        $('.dev-btn-save').click(function () {
            saveForm();
        });
        function saveForm() {
            var $frm = $('.dev-soln-form')
            var frmData = $frm.serialize();
            $.post($frm.attr('action'), frmData, function (data) {
                console.log("Response:" + JSON.stringify(data));
                if (data.success) {
                    $('#add-solution').modal('hide');
                    loadData();
                }
            }, 'json');
        }
    });
</script>