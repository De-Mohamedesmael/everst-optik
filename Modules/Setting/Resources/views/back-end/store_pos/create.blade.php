<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => route('admin.store-pos.store'), 'id'=>'form-store-pos','method' => 'post']) !!}

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title px-2 position-relative">@lang('lang.add_store_pos')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="name">@lang('lang.name') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="text" required style="width: 100%"
                        class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                        placeholder="@lang('lang.name')" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="store_id">@lang('lang.store') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    {!! Form::select('store_id', $stores, null, [
                    'class' => ' select category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end :
                    text-start',
                    'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                    'placeholder' => __('lang.please_select'),
                    'required',
                    ]) !!}
                </div>
                @error('store_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="admin_id">@lang('lang.store') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    {!! Form::select('admin_id', $admins, null, [
                    'class' => ' select category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end :
                    text-start',
                    'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                    'placeholder' => __('lang.please_select'),
                    'required',
                    ]) !!}
                </div>
                @error('store_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

{!! JsValidator::formRequest('Modules\Setting\Http\Requests\StorePosRequest','#form-store-pos') !!}
