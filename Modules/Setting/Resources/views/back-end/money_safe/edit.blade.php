
<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
                      'route' => ['admin.money_safe.update', $money_safe->id],
                      'method' => 'put',
                      'id' => 'money-safe-update-form',
                  ]) !!}
        @csrf
        @method('PUT')

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title px-2 position-relative">@lang('lang.edit')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                    data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="position-absolute modal-border"></span>
        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">

            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="name">@lang('lang.name') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="hidden" name="id" value="{{ $money_safe->id }}" />
                    <input type="text" required style="width: 100%"
                           class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                           placeholder="@lang('lang.name')" name="name" value="{{ $money_safe->name }}" required>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="store_id">@lang('lang.store') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    {!! Form::select('store_id', $stores, $money_safe->store_id, [
                        'class' => ' select category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
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
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="currency_id">@lang('lang.currency') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    {!! Form::select(
                        'currency_id',
                        $selected_currencies ,
                        $money_safe->currency_id,
                        [
                            'class' => ' category select p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                            'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                            'placeholder' => __('lang.please_select'),
                            'required',
                        ],
                    ) !!}
                </div>
                @error('currency_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                {!! Form::label('type', __('lang.type_of_safe') . '*', [
                    'class' => 'modal-label-width',
                    'style' => 'font-size: 12px;font-weight: 500;',
                ]) !!}
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    {!! Form::select('type', ['cash' => __('lang.cash'), 'later' => __('lang.later')], $money_safe->type, [
                        'class' => ' select  p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                        'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                        'required',
                        'placeholder' => __('lang.please_select'),
                    ]) !!}
                </div>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="btn btn-main col-md-3 py-1">@lang('lang.save')</button>
            <button type="button" class="btn btn-danger col-md-3 py-1" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
