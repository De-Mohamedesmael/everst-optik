<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => route('admin.designs.store'), 'id'=>'form-design','method' => 'post']) !!}

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title px-2 position-relative">@lang('lang.add_design')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                    data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="position-absolute modal-border"></span>
        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div class="col-md-6 px-5">
                <div
                    class=" mb-2 align-items-center form-group ">
                    <label class="modal-label-width" for="name">@lang('lang.name') *</label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <input type="text" required style="width: 100%"
                               class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                               placeholder="@lang('lang.name')" name="name" value="{{ old('name') }}" >
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="col-md-6 px-5">
                <div class="mb-2 align-items-center form-group">
                    {!! Form::label('focus_id', __('lang.foci'), [
                        'class' => 'form-label d-block mb-1 ',
                    ]) !!}
                    {!! Form::select('focus_id[]', $foci,null, [
                        'class' => ' selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'multiple',
                       'data-actions-box' => 'true',
                        'id' => 'focus_id',
                    ]) !!}
                </div>
            </div>

        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('#focus_id').selectpicker('refresh');
</script>
