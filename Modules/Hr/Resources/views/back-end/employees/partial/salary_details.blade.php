<!-- Modal -->
<div class="modal modal-jobs animate__animated   salary-details" data-animate-in="animate__rollIn"
     data-animate-out="animate__rollOut" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div
                class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                <h5 class="modal-title  px-2 position-relative" id="salary_details">@lang('lang.salary_details')

                    <span class=" header-modal-pill"></span>
                </h5>
                <button type="button"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                    data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div
                    class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif align-items-start">

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="i-checks">
                                <input id="fixed_wage" name="fixed_wage" type="checkbox" value="1"
                                    @if (!empty($employee->fixed_wage)) checked @endif
                                    class="form-control-custom salary_checkbox">
                                <label
                                    class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                    for="fixed_wage"><strong>@lang('lang.enter_the_fixed_wage')</strong></label>
                                {!! Form::text('fixed_wage_value', !empty($employee->fixed_wage_value) ? $employee->fixed_wage_value : null, [
                                    'class' => 'form-control salary_fields  modal-input app()->isLocale("ar") ? text-end : text-start',
                                    'placeholder' => __('lang.enter_the_fixed_wage'),
                                ]) !!}
                            </div>
                        </div>
                        {!! Form::select(
                            'payment_cycle',
                            $payment_cycle,
                            !empty($employee->payment_cycle) ? $employee->payment_cycle : null,
                            [
                                'class' => 'form-control salary_select selectpicker',
                                'data-live-search' => 'true',
                                'placeholder' => __('lang.select_payment_cycle'),
                            ],
                        ) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="i-checks">
                                <input id="commission" name="commission" type="checkbox" value="1"
                                    @if (!empty($employee->commission)) checked @endif
                                    class="form-control-custom salary_checkbox">
                                <label
                                    class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                    for="commission"><strong>@lang('lang.enter_the_commission_%')</strong></label>
                                {!! Form::text('commission_value', !empty($employee->commission_value) ? $employee->commission_value : null, [
                                    'class' => 'form-control salary_fields  modal-input app()->isLocale("ar") ? text-end : text-start',
                                    'placeholder' => __('lang.enter_the_commission_%'),
                                ]) !!}
                            </div>
                        </div>

                        <div
                            class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif align-items-center">
                            <div class="col-md-6">
                                {!! Form::select(
                                    'commission_type',
                                    $commission_type,
                                    !empty($employee->commission_type) ? $employee->commission_type : null,
                                    [
                                        'class' => 'form-control salary_select selectpicker',
                                        'data-live-search' => 'true',
                                        'placeholder' => __('lang.select_commission_type'),
                                    ],
                                ) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::select(
                                    'commission_calculation_period',
                                    $commission_calculation_period,
                                    !empty($employee->commission_calculation_period) ? $employee->commission_calculation_period : null,
                                    [
                                        'class' => 'form-control salary_select selectpicker',
                                        'data-live-search' => 'true',
                                        'placeholder' => __('lang.select_commission_calculation_period'),
                                    ],
                                ) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('commissioned_products', __('lang.products'), [
                                    'class' => 'text-muted form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                {!! Form::select(
                                    'commissioned_products[]',
                                    $products,
                                    !empty($employee->commissioned_products) ? $employee->commissioned_products : null,
                                    [
                                        'class' => 'form-control salary_select selectpicker',
                                        'multiple',
                                        'data-live-search' => 'true',
                                        'data-actions-box' => 'true',
                                    ],
                                ) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('commission_customer_types', __('lang.customer_types'), [
                                    'class' => 'text-muted form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                {!! Form::select(
                                    'commission_customer_types[]',
                                    $customer_types,
                                    !empty($employee->commission_customer_types) ? $employee->commission_customer_types : null,
                                    [
                                        'class' => 'form-control salary_select selectpicker',
                                        'multiple',
                                        'data-live-search' => 'true',
                                        'data-actions-box' => 'true',
                                    ],
                                ) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('commission_stores', __('lang.stores'), [
                                    'class' => 'text-muted form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                {!! Form::select(
                                    'commission_stores[]',
                                    $stores,
                                    !empty($employee->commission_stores) ? $employee->commission_stores : null,
                                    [
                                        'class' => 'form-control salary_select selectpicker',
                                        'multiple',
                                        'data-live-search' => 'true',
                                        'data-actions-box' => 'true',
                                    ],
                                ) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('commission_cashiers', __('lang.cashiers'), [
                                    'class' => 'text-muted form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                {!! Form::select(
                                    'commission_cashiers[]',
                                    $cashiers,
                                    !empty($employee->commission_cashiers) ? $employee->commission_cashiers : null,
                                    [
                                        'class' => 'form-control salary_select selectpicker',
                                        'multiple',
                                        'data-live-search' => 'true',
                                        'data-actions-box' => 'true',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
                <button type="button" class="btn btn-main col-md-3 py-1"
                    data-dismiss="modal">@lang('lang.save')</button>
                <button type="button" class="btn btn-danger col-md-3 py-1 salary_cancel"
                    data-dismiss="modal">@lang('lang.cancel')</button>
            </div>
        </div>
    </div>
</div>
