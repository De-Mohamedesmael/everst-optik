<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['url' => route('admin.store.update',$store->id), 'method' => 'put','id' => isset($quick_add)&&$quick_add? 'quick_add_store_form' : 'add_store' ]) !!}
            <div class="modal-header">
                <h5 class="modal-title" id="exampleLargeModalLabel">@lang('lang.add_store')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('branch_id', __('lang.branch'))  !!}
                    {!! Form::select('branch_id',$branches,$store->branch_id, ['class' => 'form-control select2' , 'placeholder' => __('lang.branch') ]);  !!}
                </div>
                <div class="form-group">
                    <input type="hidden" name="quick_add" value="{{ isset($quick_add)&&$quick_add?$quick_add:'' }}">
                    {!! Form::label('name', __('lang.name')) .'*' !!}
                    {!! Form::text('name', $store->name, ['class' => 'form-control' , 'placeholder' => __('lang.name') , 'required']);  !!}
                </div>
                <div class="form-group">
                    {!! Form::label('phone_number', __('lang.phone_number'))  !!}
                    {!! Form::text('phone_number', $store->phone_number, ['class' => 'form-control' , 'placeholder' => __('lang.phone_number') ]);  !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email', __('lang.name'))  !!}
                    {!! Form::text('email',$store->email , ['class' => 'form-control' , 'placeholder' => __('lang.email') ]);  !!}
                </div>
                <div class="form-group">
                    {!! Form::label('manager_name', __('lang.manager_name'))  !!}
                    {!! Form::text('manager_name', $store->manager_name, ['class' => 'form-control' , 'placeholder' => __('lang.manager_name') ]);  !!}
                </div>
                <div class="form-group">
                    {!! Form::label('manager_mobile_number', __('lang.manager_mobile_number'))  !!}
                    {!! Form::text('manager_mobile_number', $store->manager_mobile_number, ['class' => 'form-control' , 'placeholder' => __('lang.manager_mobile_number') ]);  !!}
                </div>
                <div class="form-group">
                    {!! Form::label('details', __('lang.details'))  !!}
                    {!! Form::textarea('details', $store->details, ['class' => 'form-control' , 'placeholder' => __('lang.details') , 'rows' => '2']);  !!}
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang.close')</button>
                <button  id="create-store-btn" class="btn btn-primary">{{__('lang.save')}}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
