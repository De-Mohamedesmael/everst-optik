<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog  rollIn  animated" role="document">
        <div class="modal-content @if (app()->isLocale('ar')) text-end @else text-start @endif">
            <div class="modal-header mb-4 d-flex justify-content-between py-0 ">
                <h5 class="modal-title" id="editBrandModalLabel">{{ __('lang.edit_customer_type') }}</h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {!! Form::open([
                'route' => ['moneysafe.update', $moneysafe->id],
                'method' => 'put',
                'id' => 'money-safe-update-form',
            ]) !!}
            @csrf
            @method('PUT')
            <div class="modal-body p-0">
                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    <label class="modal-label-width" for="name">@lang('lang.name') *</label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <input type="hidden" name="id" value="{{ $moneysafe->id }}" />
                        <input type="text" required style="width: 100%"
                            class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                            placeholder="@lang('lang.name')" name="name" value="{{ $moneysafe->name }}" required>
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
                        {!! Form::select('store_id', $stores, $moneysafe->store_id, [
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
                            !empty($settings['currency']) ? $selected_currencies : [],
                            $moneysafe->currency_id,
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
                        {!! Form::select('type', ['cash' => __('lang.cash'), 'later' => __('lang.later')], $moneysafe->type, [
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('lang.save') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\MoneySafeUpdateRequest', '#money-safe-update-form') !!}
