<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog  rollIn  animated" role="document">
        <div class="modal-content @if (app()->isLocale('ar')) text-end @else text-start @endif">
            <div class="modal-header mb-4 d-flex justify-content-between py-0 ">
                <h5 class="modal-title" id="editBrandModalLabel">{{ __('lang.add_to_money_safe') }}</h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {!! Form::open([
                'route' => 'admin.money_safe.post-add-money-to-safe',
                'method' => 'post',
                'files' => true,
                'id' => 'safe-money-form',
            ]) !!}
            <div class="modal-body p-0">
                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    <input type="hidden" value="{{ $money_safe_id }}" name="money_safe_id" />
                    {!! Form::label('source_type', __('lang.source_type') . '*', [
                        'class' => 'modal-label-width',
                        'style' => 'font-size: 12px;font-weight: 500;',
                    ]) !!}
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::select(
                            'source_type',
                            ['user' => __('lang.user'), 'pos' => __('lang.pos'), 'store' => __('lang.store'), 'safe' => __('lang.safe')],
                            null,
                            [
                                'class' => 'select p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                                'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                                'placeholder' => __('lang.please_select'),
                                'required',
                            ],
                        ) !!}
                    </div>
                </div>

                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    {!! Form::label('source_of_payment', __('lang.source_of_payment') . '*', [
                        'class' => 'modal-label-width',
                        'style' => 'font-size: 12px;font-weight: 500;',
                    ]) !!}
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::select('source_id', $admins, null, [
                            'class' => 'select category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                            'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                            'placeholder' => __('lang.please_select'),
                            'id' => 'source_id',
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    <label class="modal-label-width" for="store_id">@lang('lang.store') *</label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::select('store_id', $stores, null, [
                            'class' => 'select category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                            'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                            'placeholder' => __('lang.please_select'),
                            'required',
                        ]) !!}
                        @error('store_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    <label class="modal-label-width" for="amount">@lang('lang.amount') * {{ $currency_symbol }}
                    </label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <input type="text"  class="form-control m-0 initial-balance-input"
                            style="width: 100%" placeholder="@lang('lang.amount')" name="amount"
                            value="{{ old('amount') }}" required>
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    {!! Form::label('job', __('lang.job') . '*', [
                        'class' => 'modal-label-width',
                        'style' => 'font-size: 12px;font-weight: 500;',
                    ]) !!}
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::select('job_type_id', $jobs, null, [
                            'class' => 'select category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                            'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                            'required',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                    </div>
                    @error('job_type_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    <label class="modal-label-width" for="transaction_date">@lang('lang.date')*</label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        {!! Form::text('transaction_date', @format_date(date('Y-m-d')), [
                            'class' =>
                                'select form-control datepicker category p-0 initial-balance-input my-0 app()->isLocale("ar")? text-end : text-start',
                            'style' => 'width:100%;border-radius:16px;border:2px solid #cececf',
                            'placeholder' => __('lang.date'),
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div
                    class=" d-flex mb-2 pt-4 align-items-center form-group @if (app()->isLocale('ar')) mr-3 @else ml-3 @endif">
                    <label class="modal-label-width" for="details">@lang('lang.details') *</label>
                    <div
                        class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <textarea type="text" class="form-control initial-balance-input m-0" style="width: 100%"
                            placeholder="@lang('lang.details')" name="details" >{{ old('details') }}</textarea>
                        @error('details')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
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
