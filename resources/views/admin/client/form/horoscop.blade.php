<div id="add-horoscope" class="modal fade" role="dialog">
    <div class="modal-dialog horoscope-ui">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Horoscope</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $planets = ['' => 'None', 'Sun' => 'Sun', 'Moon' => 'Moon', 'Mercury' => 'Mercury', 'Venus' => 'Venus', 'Mars' => 'Mars', 'Jupiter' => 'Jupiter', 'Saturn' => 'Saturn', 'Rahu' => 'Rahu', 'Ketu' => 'Ketu'];
                    ?>
                    {!!Form::open(['url'=>URL::route('client.astro.add',['cid'=>$user->id]),'class'=>'dev-astro-frm'])!!}
                    @for ($i = 0; $i < 12; $i++)
                    <div class="col-sm-6 form-group">
                        <div class="row">
                            <div class="col-sm-4"><label>   {!! Form::label('place_'.$i,'Place '.($i+1).':') !!}</label></div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    {!! Form::select('place_'.$i, $planets, (isset($horo)?$horo[$i]:null), ['class' => 'form-control dev-place-sel']) !!}
                                    <span class="text-danger">{!! $errors->first('place_'.$i) !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor    
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-4"><label>   {!! Form::label('manglik','Manglik:') !!}</label></div>
                            <div class="col-sm-8">
                                {!! Form::select('manglik', ['0'=>'None','1'=>'Anshik','2'=>'Full'],(isset($astroProfile->manglik)?$astroProfile->manglik:null), ['class' => 'form-control']) !!}
                                <span class="text-danger">{!! $errors->first('manglik') !!}</span>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
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
        $('#manglik, select.dev-place-sel').select2({});
        $('#add-horoscope .dev-btn-save').click(function () {
            var $frm = $('.dev-astro-frm');
            var astroData = $frm.serialize();
            $.post($frm.attr('action'), astroData, function (data) {
                if (data.success) {
                    console.log("Data:" + data.msg);
                    $('#add-horoscope').modal('hide');
                }
            }, 'json');
        });
    });
</script>

