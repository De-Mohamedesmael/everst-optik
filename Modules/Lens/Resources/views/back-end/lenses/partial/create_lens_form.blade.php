@php
    $recent_lens = \Modules\Product\Entities\Product::Lens()->orderBy('created_at', 'desc')->first();
    $clear_all_input_form = \Modules\Setting\Entities\System::getProperty('clear_all_input_form');
@endphp
<div class="card mb-2 d-flex flex-column justify-content-center align-items-center">

    <div class="col-12  d-flex flex-row justify-content-center align-items-center">
        <p class="italic mb-0 py-1">
            <small>@lang('lang.required_fields_info')</small>
        <div style="width: 30px;height: 30px;">
            <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
        </div>
        </p>
    </div>

    <div class="col-12 d-flex  flex-row justify-content-between align-items-center">
        <div class="col-md-1 px-0 d-flex justify-content-center">
            <div class="i-checks toggle-pill-color flex-col-centered">
                <input id="active" name="active" type="checkbox" checked value="1" class="form-control-custom">
                <label for="active">
                </label>
                <span>
                    <strong>
                        @lang('lang.active')
                    </strong>
                </span>
            </div>
        </div>
        <div class="col-md-3 px-0 d-flex justify-content-center">
            <div class="i-checks toggle-pill-color flex-col-centered">
                <input id="clear_all_input_form" name="clear_all_input_form" type="checkbox"
                    @if ($clear_all_input_form == null || $clear_all_input_form == '1') checked @endif value="1" class="form-control-custom">
                <label for="clear_all_input_form">
                </label>
                <span>
                    <strong>
                        @lang('lang.clear_all_input_form')
                    </strong>
                </span>
            </div>
        </div>
    </div>
</div>
<div
    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <h6 class="mb-0">
        {{translate('add_lens_information')}}

    </h6>
</div>
<div class="card mb-3">
    <div class="card-body p-2">
        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('store_ids', __('lang.store'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::select('store_ids[]', $stores_select, array_keys($stores_select), [
                        'class' => ' selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'multiple',
                       'data-actions-box' => 'true',
                        'id' => 'store_ids',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('name', __('lang.name') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {!! Form::text('name', null, [
                            'class' => 'form-control clear_input_form modal-input app()->isLocale("ar") ? text-end : text-start',
                            'required',
                            'placeholder' => __('lang.name'),
                        ]) !!}
                        <span class="input-group-btn">
                            <button class="select-button btn-flat translation_btn" type="button"
                                data-type="lens"><i class="dripicons-web"></i></button>
                        </span>
                    </div>
                </div>
                @include('back-end.layouts.partials.translation_inputs', [
                    'attribute' => 'name',
                    'translations' => [],
                    'type' => 'lenses',
                ])
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('sku', __('lang.sku'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::text('sku', null, [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'id' => 'sku',
                        'placeholder' => __('lang.sku'),
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('kun',translate('kun') . ' *', [
                        'class' => 'form-label d-block mb-1',
                    ]) !!}
                    {!! Form::number('kun', null, [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'id' => 'kun',
                        'required',
                        'placeholder' => translate('kun'),
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('alert_quantity', __('lang.alert_quantity'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::text('alert_quantity', !empty($recent_lens) ? @num_format($recent_lens->alert_quantity) : 3, [
                        'class' => 'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.alert_quantity'),
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                {!! Form::label('color_id', __('lang.color'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::select(
                        'color_id',
                        $colors,
                        !empty($recent_lens) ? $recent_lens->color_id : false,
                        [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                        ],
                    ) !!}
                    <span class="input-group-btn">
                                @can('product_module.color.create_and_edit')
                            <button class="btn-modal select-button btn-flat"
                                    data-href="{{ route('admin.colors.create') }}?quick_add=1"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                        @endcan
                            </span>
                </div>
            </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                {!! Form::label('brand_id', __('lang.brands') . ' *', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    <input type="hidden"
                           id="focus_value_id" />

                    {!! Form::select('brand_id[]', $brands, !empty($recent_lens) ? $recent_lens->brand_lenses()->pluck('brand_lenses.id') : false, [
                        'class' => 'clear_input_form selectpicker form-control',
                        'data-live-search' => 'true',
                        'id' => 'brand_id',
                         'multiple',
                       'data-actions-box' => 'true',
                        'style' => 'width: 80%',
                    ]) !!}
                </div>
                <div class="error-msg text-red"></div>
            </div>
            </div>

            <div class="col-md-3 px-5">
                <div class="form-group">
                {!! Form::label('focus_id', __('lang.foci') . ' *', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    <input type="hidden"
                           id="focus_value_id" />

                    {!! Form::select('focus_id[]', $foci, !empty($recent_lens) ? $recent_lens->foci()->pluck('foci.id') : false, [
                        'class' => 'clear_input_form selectpicker form-control',
                        'data-live-search' => 'true',
                        'id' => 'focus_id',
                         'multiple',
                       'data-actions-box' => 'true',
                        'style' => 'width: 80%',
                    ]) !!}
                </div>
                <div class="error-msg text-red"></div>
            </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                {!! Form::label('index_lens_id', __('lang.index_lenses') . ' *', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    <input type="hidden"
                           id="focus_value_id" />

                    {!! Form::select('index_lens_id[]', $index_lenses, !empty($recent_lens) ? $recent_lens->index_lenses()->pluck('index_lenses.id') : false, [
                        'class' => 'clear_input_form selectpicker form-control',
                        'data-live-search' => 'true',
                        'id' => 'index_lens_id',
                         'multiple',
                       'data-actions-box' => 'true',
                        'style' => 'width: 80%',
                    ]) !!}
                </div>
                <div class="error-msg text-red"></div>
            </div>
            </div>


            <div class="col-md-3 px-5">
                <div class="form-group">
                {!! Form::label('focus_id', __('lang.purchase_price') . ' *', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">


                    {!! Form::text('purchase_price', !empty($recent_lens) ? @num_format($recent_lens->purchase_price) : 0, [
                        'class' => 'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.purchase_price'),
                                                    'required',

                    ]) !!}
                </div>
                <div class="error-msg text-red"></div>
            </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                {!! Form::label('index_lens_id', __('lang.sell_price') . ' *', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">

                    {!! Form::text('sell_price', !empty($recent_lens) ? @num_format($recent_lens->sell_price) : 0, [
                               'class' => 'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                               'placeholder' => __('lang.sell_price'),
                                                           'required',

                           ]) !!}

                </div>
                <div class="error-msg text-red"></div>
            </div>
            </div>

        </div>
    </div>
</div>

<div
    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <h6 class="mb-0">
        {{translate('add_lens_image')}}

    </h6>
</div>

<div class="card mb-3">
    <div
        class="card-body p-2 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
        <div class="variants col-md-6">
            <div class='file file--upload w-100'>
                <label for='file-input' class="w-100   modal-input m-0">
                    <i class="fas fa-cloud-upload-alt"></i>
                </label>
                <!-- <input  id="file-input" multiple type='file' /> -->
                <input type="file" id="file-input">
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
            <div class="preview-container"></div>
        </div>
    </div>
</div>




<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#moreInfoCollapse" aria-expanded="false" aria-controls="moreInfoCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.more_info')
        <span class="section-header-pill"></span>
    </button>
</div>
<div class="collapse" id="moreInfoCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <div class="col-md-3 px-5">
                    <div class="form-group">
                        {!! Form::label('barcode_type', __('lang.barcode_type'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::select(
                            'barcode_type',
                            [
                                'C128' => 'Code 128',
                                'C39' => 'Code 39',
                                'UPCA' => 'UPC-A',
                                'UPCE' => 'UPC-E',
                                'EAN8' => 'EAN-8',
                                'EAN13' => 'EAN-13',
                            ],
                            !empty($recent_lens) ? $recent_lens->barcode_type : false,
                            ['class' => 'form-control', 'required'],
                        ) !!}
                    </div>
                </div>

                <div class="col-md-3 px-5">
                    <div class="form-group">
                        {!! Form::label('other_cost', __('lang.other_cost'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::text('other_cost', !empty($recent_lens) ? @num_format($recent_lens->other_cost) : null, [
                            'class' => 'form-control clear_input_form modal-input app()->isLocale("ar") ? text-end : text-start',
                            'placeholder' => __('lang.other_cost'),
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3 px-5">
                    {!! Form::label('tax_id', __('lang.tax'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {!! Form::select('tax_id', $taxes, !empty($recent_lens) ? $recent_lens->tax_id : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                        <span class="input-group-btn">
                            @can('lens_module.tax.create')
                                <button class="btn-modal select-button btn-flat"
                                    data-href="{{ action('TaxController@create') }}?quick_add=1&type=lens_tax"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                    <div class="error-msg text-red"></div>
                </div>

                <div class="col-md-3 px-5">
                    <div class="form-group">
                        {!! Form::label('tax_method', __('lang.tax_method'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::select(
                            'tax_method',
                            ['inclusive' => __('lang.inclusive'), 'exclusive' => __('lang.exclusive')],
                            !empty($recent_lens) ? $recent_lens->tax_method : false,
                            [
                                'class' => 'clear_input_form selectpicker form-control',
                                'data-live-search' => 'true',
                                'style' => 'width: 80%',
                                'placeholder' => __('lang.please_select'),
                            ],
                        ) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>






<div class="clearfix"></div>


<input type="hidden" name="default_purchase_price_percentage" id="default_purchase_price_percentage"
    value="{{ Modules\Setting\Entities\System::getProperty('default_purchase_price_percentage') ?? 75 }}">
<input type="hidden" name="default_profit_percentage" id="default_profit_percentage"
    value="{{ Modules\Setting\Entities\System::getProperty('default_profit_percentage') ?? 0 }}">








<input type="hidden" name="row_id" id="row_id" value="0">
