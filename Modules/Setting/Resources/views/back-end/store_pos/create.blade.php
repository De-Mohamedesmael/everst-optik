<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog  rollIn  animated" role="document">
        <div class="modal-content">
            {!! Form::open(['url' => route('store-pos.store'), 'method' => 'post']) !!}

            <div class="modal-header">

                <h4 class="modal-title">@lang( 'lang.add_store_pos' )</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
{{--                <div class="form-group">--}}
{{--                    {!! Form::label('store_id', __('lang.store'). ':*', []) !!}--}}
{{--                    {!! Form::select('store_id', $stores,--}}
{{--                    null, ['class' => 'select2 form-control',--}}
{{--                    'data-live-search'=>"true", 'required',--}}
{{--                    'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}--}}
{{--                </div>--}}
                <div class="form-group">
                    {!! Form::label('name', __( 'lang.name' ) . ':*') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __( 'lang.name' ), 'required'
                    ]);
                    !!}
                </div>
                <div class="form-group">
                    {!! Form::label('user_id', __('lang.user'). ':*', []) !!}
                    {!! Form::select('user_id', $users,
                    null, ['class' => 'select2 form-control',
                    'data-live-search'=>"true", 'required',
                    'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'lang.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\MoneySafeUpdateRequest','#money-safe-update-form'); !!}
