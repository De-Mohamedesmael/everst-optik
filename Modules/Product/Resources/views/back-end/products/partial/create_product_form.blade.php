@php
    $recent_product = \Modules\Product\Entities\Product::orderBy('created_at', 'desc')->first();
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
            <div class="i-checks">
                <input id="active" name="active" type="checkbox" checked value="1" class="form-control-custom">
                <label for="active"><strong>
                        @lang('lang.active')
                    </strong></label>
            </div>
        </div>
        <div class="col-md-2 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="have_weight" name="have_weight" type="checkbox" value="1" class="form-control-custom">
                <label for="have_weight"><strong>@lang('lang.have_weight')</strong></label>
            </div>
        </div>
        <div class="col-md-2  px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="weighing_scale_barcode" name="weighing_scale_barcode" type="checkbox"
                    @if (!empty($product->weighing_scale_barcode)) checked @endif value="1" class="form-control-custom">
                <label for="weighing_scale_barcode"><strong>
                        @lang('lang.weighing_scale_barcode')
                    </strong></label>
            </div>
        </div>
        <div class="col-md-3 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="clear_all_input_form" name="clear_all_input_form" type="checkbox"
                    @if ($clear_all_input_form == null || $clear_all_input_form == '1') checked @endif value="1" class="form-control-custom">
                <label for="clear_all_input_form">
                    <strong>
                        @lang('lang.clear_all_input_form')
                    </strong>
                </label>
            </div>
        </div>

        @php
            $products_count = Modules\Product\Entities\Product::where('show_at_the_main_pos_page', 'yes')->count();
        @endphp
        <div class="col-md-2 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="show_at_the_main_pos_page" name="show_at_the_main_pos_page" type="checkbox"
                    @if (isset($products_count) && $products_count > 40) disabled @endif class="form-control-custom">
                <label for="show_at_the_main_pos_page"><strong>@lang('lang.show_at_the_main_pos_page')</strong></label>
            </div>
        </div>
    </div>
</div>
<div
    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <h6 class="mb-0">
        @lang('lang.add_product_information')
        <span class=" section-header-pill"></span>
    </h6>
</div>
<div class="card mb-3">
    <div class="card-body p-2">
        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <div class="col-md-4 px-5">
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


            <div class="col-md-4 px-5">
                    {!! Form::label('category_id', __('lang.category') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        <input type="hidden"
                            id="category_value_id" />

                        {!! Form::select('category_id[]', $categories, false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'id' => 'category_id',
                             'multiple',
                           'data-actions-box' => 'true',
                            'style' => 'width: 80%',
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.category.create_and_edit')
                                <button class="btn-modal btn-flat select-button "
                                    data-href="{{ route('admin.categories.create')  }}?quick_add=1"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                    <div class="error-msg text-red"></div>
                </div>

            <div class="col-md-4 px-5">
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
                                data-type="product"><i class="dripicons-web"></i></button>
                        </span>
                    </div>
                </div>
                @include('back-end.layouts.partials.translation_inputs', [
                    'attribute' => 'name',
                    'translations' => [],
                    'type' => 'products',
                ])
            </div>
            <div class="col-md-3 px-2">
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
            <div class="col-md-3 px-2">
                <div class="form-group">
                    {!! Form::label('alert_quantity', __('lang.alert_quantity'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::text('alert_quantity', !empty($recent_product) ? @num_format($recent_product->alert_quantity) : 3, [
                        'class' => 'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.alert_quantity'),
                    ]) !!}
                </div>
            </div>
            <div class="col-md-2 px-2">
                {!! Form::label('color_id', __('lang.color'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::select(
                        'color_id',
                        $colors,
                        !empty($recent_product) ? $recent_product->color_id : false,
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
            <div class="col-md-2 px-2">
                {!! Form::label('size_id', __('lang.size'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::select('size_id', $sizes, !empty($recent_product) ? $recent_product->size_id : false, [
                        'class' => 'clear_input_form selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'placeholder' => __('lang.please_select'),
                    ]) !!}
                    <span class="input-group-btn">
                            @can('product_module.size.create_and_edit')
                            <button class="btn-modal select-button btn-flat"
                                    data-href="{{ route('admin.sizes.create') }}?quick_add=1"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                        @endcan
                        </span>
                </div>
            </div>
            <div class="col-md-2 px-2">
                {!! Form::label('brand_id', __('lang.brand'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::select('brand_id', $brands, !empty($recent_product) ? $recent_product->brand_id : false, [
                        'class' => 'clear_input_form selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'placeholder' => __('lang.please_select'),
                        'required',
                    ]) !!}
                    <span class="input-group-btn">
                            @can('product_module.brand.create_and_edit')
                            <button class="btn-modal select-button btn-flat"
                                    data-href="{{ route('admin.brands.create') }}?quick_add=1"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                        @endcan
                        </span>
                </div>
                <div class="error-msg text-red"></div>
            </div>
        </div>
    </div>
</div>

<div
    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <h6 class="mb-0">
        @lang('lang.add_product_image')
        <span class=" section-header-pill"></span>
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
        data-bs-target="#discountInfoCollapse" aria-expanded="false" aria-controls="discountInfoCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.discount_information')
        <span class="section-header-pill"></span>
    </button>
</div>

<div class="collapse" id="discountInfoCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="col-md-12">
                <table class="table mb-1" id="consumption_table_discount">
                    <thead>
                        <tr>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_type')</th>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount')</th>
                            <th class="py-2 text-center" style="width: 25%;">@lang('lang.discount_category')</th>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_start_date')</th>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_end_date')</th>
                            <th class="py-2 text-center" style="width: 20%;">@lang('lang.customer_type') <i
                                    class="dripicons-question" data-toggle="tooltip" title="@lang('lang.discount_customer_info')"></i>
                            </th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @include('products.partial.raw_discount', ['row_id' => 0]) --}}
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-main px-5 py-1 add_discount_row" type="button">@lang('lang.add_new')</button>
                </div>
                <input type="hidden" name="raw_discount_index" id="raw_discount_index" value="1">
            </div>
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
                            !empty($recent_product) ? $recent_product->barcode_type : false,
                            ['class' => 'form-control', 'required'],
                        ) !!}
                    </div>
                </div>

                <div class="col-md-3 px-5">
                    <div class="form-group">
                        {!! Form::label('other_cost', __('lang.other_cost'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::text('other_cost', !empty($recent_product) ? @num_format($recent_product->other_cost) : null, [
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
                        {!! Form::select('tax_id', $taxes, !empty($recent_product) ? $recent_product->tax_id : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.tax.create')
                                <button class="btn-modal select-button btn-flat"
                                    data-href="{{ action('TaxController@create') }}?quick_add=1&type=product_tax"
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
                            !empty($recent_product) ? $recent_product->tax_method : false,
                            [
                                'class' => 'clear_input_form selectpicker form-control',
                                'data-live-search' => 'true',
                                'style' => 'width: 80%',
                                'placeholder' => __('lang.please_select'),
                            ],
                        ) !!}
                    </div>
                </div>
                <div
                    class="d-flex col-12 flex-column @if (app()->isLocale('ar')) align-items-end @else  align-items-start @endif
                    ">
                    <div
                        class="col-md-4 d-flex @if (app()->isLocale('ar')) justify-content-end @else  justify-content-start @endif">
                        <div class="i-checks">
                            <input id="show_to_customer" name="show_to_customer" type="checkbox" checked
                                value="1" class="form-control-custom">
                            <label for="show_to_customer"><strong>@lang('lang.show_to_customer')</strong></label>
                        </div>
                    </div>
                    <div class="col-md-3 show_to_customer_type_div">
                        <div class="form-group">
                            {!! Form::label('show_to_customer_types', __('lang.show_to_customer_types'), [
                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                            ]) !!}
                            <i class="dripicons-question" data-toggle="tooltip" title="@lang('lang.show_to_customer_types_info')"></i>
                            {!! Form::select(
                                'show_to_customer_types[]',
                                $customer_types,
                                !empty($recent_product) ? $recent_product->show_to_customer_types : false,
                                ['class' => ' selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'multiple'],
                            ) !!}
                        </div>
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
