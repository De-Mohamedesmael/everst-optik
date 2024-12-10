<!-- Modal -->
<div class="modal fade" id="createMoneySafeModal" tabindex="-1" role="dialog" aria-labelledby="exampleStandardModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog  rollIn  animated" role="document">
        <div class="modal-content @if (app()->isLocale('ar')) text-end @else text-start @endif">
            <div
                class="modal-header mb-4 d-flex justify-content-between py-0 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title" id="exampleStandardModalLabel">{{ __('lang.add_money_safe') }}</h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {!! Form::open(['route' => 'admin.money_safe.store', 'method' => 'post', 'files' => true, 'id' => 'safe-money-form']) !!}
            <div class="modal-body p-0">
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
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                    <label class="modal-label-width" for="currency_id">@lang('lang.currency') *</label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::select(
                            'currency_id',
                            !empty($settings['currency']) ? $selected_currencies : $selected_currencies,
                            $settings['currency'],
                            [
                                'class' => ' select p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
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
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                    {!! Form::label('type', __('lang.type_of_safe') . '*', [
                        'class' => 'modal-label-width',
                        'style' => 'font-size: 12px;font-weight: 500;',
                    ]) !!}
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::select(
                            'type',
                            ['cash' => __('lang.cash'), 'later' => __('lang.later')],
                            $settings['default_payment_type'],
                            [
                                'class' => ' select  p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                                'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                                'required',
                                'placeholder' => __('lang.please_select'),
                            ],
                        ) !!}
                    </div>
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang.close')</button>
                <button type="submit" class="btn btn-primary">{{ __('lang.save') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('Modules\Setting\Http\Requests\MoneySafeRequest', '#safe-money-form') !!}
