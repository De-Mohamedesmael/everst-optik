@extends('back-end.layouts.app')
@section('title', __('lang.products'))
@section('styles')
    <style>
        .preview-edit-product-container {
            /* display: flex;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        flex-wrap: wrap;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        gap: 10px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }

        .preview {
            position: relative;
            width: 150px;
            height: 150px;
            padding: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin: 30px 0px;
            border: 1px solid #ddd;
        }

        .preview img {
            width: 100%;
            height: 100%;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            object-fit: cover;

        }

        .delete-btn {
            position: absolute;
            top: 156px;
            right: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: transparent;
            color: rgba(235, 32, 38, 0.97);
        }

        .crop-btn {
            position: absolute;
            top: 156px;
            left: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
            background: transparent;
            color: #007bff;
        }
    </style>
    <style>
        .variants {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .variants>div {
            margin-right: 5px;
        }

        .variants>div:last-of-type {
            margin-right: 0;
        }

        .file {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>input[type='file'] {
            display: none
        }

        .file>label {
            font-size: 1rem;
            font-weight: 300;
            cursor: pointer;
            outline: 0;
            user-select: none;
            border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
            border-style: solid;
            border-radius: 4px;
            border-width: 1px;
            background-color: hsl(0, 0%, 100%);
            color: hsl(0, 0%, 29%);
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>label:hover {
            border-color: hsl(0, 0%, 21%);
        }

        .file>label:active {
            background-color: hsl(0, 0%, 96%);
        }

        .file>label>i {
            padding-right: 5px;
        }

        .file--upload>label {
            color: hsl(204, 86%, 53%);
            border-color: hsl(204, 86%, 53%);
        }

        .file--upload>label:hover {
            border-color: hsl(204, 86%, 53%);
            background-color: hsl(204, 86%, 96%);
        }

        .file--upload>label:active {
            background-color: hsl(204, 86%, 91%);
        }

        .file--uploading>label {
            color: hsl(48, 100%, 67%);
            border-color: hsl(48, 100%, 67%);
        }

        .file--uploading>label>i {
            animation: pulse 5s infinite;
        }

        .file--uploading>label:hover {
            border-color: hsl(48, 100%, 67%);
            background-color: hsl(48, 100%, 96%);
        }

        .file--uploading>label:active {
            background-color: hsl(48, 100%, 91%);
        }

        .file--success>label {
            color: hsl(141, 71%, 48%);
            border-color: hsl(141, 71%, 48%);
        }

        .file--success>label:hover {
            border-color: hsl(141, 71%, 48%);
            background-color: hsl(141, 71%, 96%);
        }

        .file--success>label:active {
            background-color: hsl(141, 71%, 91%);
        }

        .file--danger>label {
            color: hsl(348, 100%, 61%);
            border-color: hsl(348, 100%, 61%);
        }

        .file--danger>label:hover {
            border-color: hsl(348, 100%, 61%);
            background-color: hsl(348, 100%, 96%);
        }

        .file--danger>label:active {
            background-color: hsl(348, 100%, 91%);
        }

        .file--disabled {
            cursor: not-allowed;
        }

        .file--disabled>label {
            border-color: #e6e7ef;
            color: #e6e7ef;
            pointer-events: none;
        }

        @keyframes pulse {
            0% {
                color: hsl(48, 100%, 67%);
            }

            50% {
                color: hsl(48, 100%, 38%);
            }

            100% {
                color: hsl(48, 100%, 67%);
            }
        }
    </style>
@endsection

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.products.index') }}">/
            @lang('lang.products')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.edit_product')</li>
@endsection
@section('content')
<section class="forms py-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 px-1">


                {!! Form::open([
                    'url' =>  route('admin.products.update', $product->id),
                    'id' => 'product-edit-form',
                    'method' => 'PUT',
                    'class' => '',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                <div class="card mb-2 d-flex flex-column justify-content-center align-items-center">

                    <div class="col-12  d-flex flex-row justify-content-center align-items-center">
                        <p class="italic mb-0 py-1"><small>@lang('lang.required_fields_info')</small>
                        <div style="width: 30px;height: 30px;">
                            <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
                        </div>
                        </p>
                    </div>

                    <div class="col-12 d-flex  flex-row justify-content-between align-items-center">

                        <div class="col-md-1 px-0 d-flex justify-content-center">
                            <div class="i-checks">
                                <input id="active" name="active" type="checkbox"
                                    @if (!empty($product->active)) checked @endif value="1"
                                    class="form-control-custom">
                                <label for="active"><strong>
                                        @lang('lang.active')
                                    </strong></label>
                            </div>
                        </div>
                        <div class="col-md-2 px-0 d-flex justify-content-center">
                            <div class="i-checks">
                                <input id="have_weight" name="have_weight" type="checkbox"
                                    @if (!empty($product->have_weight)) checked @endif value="1"
                                    class="form-control-custom">
                                <label for="have_weight"><strong>
                                        @lang('lang.have_weight')
                                    </strong></label>
                            </div>
                        </div>
                        <div class="col-md-2 px-0 d-flex justify-content-center">
                            <div class="i-checks">
                                <input id="weighing_scale_barcode" name="weighing_scale_barcode" type="checkbox"
                                    @if (!empty($product->weighing_scale_barcode)) checked @endif value="1"
                                    class="form-control-custom">
                                <label for="weighing_scale_barcode"><strong>
                                        @lang('lang.weighing_scale_barcode')
                                    </strong></label>
                            </div>
                        </div>
                        @php
                            $products_count = Modules\Product\Entities\Product::where('show_at_the_main_pos_page', 'yes')->count();
                        @endphp
                        <div class="col-md-3 px-0 d-flex justify-content-center">
                            <div class="i-checks">
                                <input id="show_at_the_main_pos_page" name="show_at_the_main_pos_page" type="checkbox"
                                    @if (isset($products_count) && $products_count < 40) @if (!empty($product->show_at_the_main_pos_page) && $product->show_at_the_main_pos_page == 'yes') checked @endif
                                @elseif(isset($products_count) && $products_count == 40) disabled @endif
                                value="1" class="form-control-custom">
                                <label for="show_at_the_main_pos_page"><strong>@lang('lang.show_at_the_main_pos_page')</strong></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h6 class="mb-0">
                        @lang('lang.product_information')
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
                                    {!! Form::select('store_ids[]', $stores_select, array_values($stores_selected), [
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


                                    {!! Form::select('category_id[]', $categories,  array_values($category_id_selected), [
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
                                        {!! Form::text('name', $product->name, [
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
                                    'translations' => $product->translations,
                                    'type' => 'products',
                                ])
                            </div>
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('sku', __('lang.sku'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('sku', $product->sku, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                        'id' => 'sku',
                                        'placeholder' => __('lang.sku'),
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('alert_quantity', __('lang.alert_quantity'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('alert_quantity', !empty($product) ? @num_format($product->alert_quantity) : 3, [
                                        'class' => 'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                        'placeholder' => __('lang.alert_quantity'),
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-2 px-5">
                                {!! Form::label('color_id', __('lang.color'), [
                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                <div class="input-group my-group select-button-group">
                                    {!! Form::select(
                                        'color_id',
                                        $colors,
                                         $product->color_id,
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
                            <div class="col-md-2 px-5">
                                {!! Form::label('size_id', __('lang.size'), [
                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                <div class="input-group my-group select-button-group">
                                    {!! Form::select('size_id', $sizes,  $product->size_id , [
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
                            <div class="col-md-2 px-5">
                                {!! Form::label('brand_id', __('lang.brand'), [
                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                <div class="input-group my-group select-button-group">
                                    {!! Form::select('brand_id', $brands,  $product->brand_id, [
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
                        @lang('lang.product_image')
                        <span class=" section-header-pill"></span>
                    </h6>
                </div>

                <div class="card mb-3">
                    <div
                        class="card-body p-2 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <div class="variants col-md-6">
                            <div class='file file-upload w-100'>
                                <label for='file-product-edit-product' class="w-100  modal-input m-0">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </label>
                                <!-- <input  id="file-input" multiple type='file' /> -->
                                <input type="file" id="file-product-edit-product">
                            </div>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="preview-edit-product-container">
                                @if (!empty($product->getFirstMediaUrl('products')))
                                    <div id="preview{{ $product->id }}" class="preview">
                                        <img src="{{ $product->getFirstMediaUrl('products') }}"
                                            id="img{{ $product->id }}" alt="">
                                        <div class="action_div"></div>
                                        <button type="button" class="delete-btn"><i style="font-size: 16px;"
                                                data-href="{{ route('admin.products.deleteProductImage', $product->id) }}"
                                                id="deleteBtn{{ $product->id }}" class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>







                <div
                    class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#discountInfoCollapse" aria-expanded="false"
                        aria-controls="discountInfoCollapse">
                        <i class="fas fa-arrow-down"></i>
                        @lang('lang.discount_information')
                        <span class="section-header-pill"></span>
                    </button>
                </div>

                <div class="collapse"   id="discountInfoCollapse">
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="consumption_table_discount">
                                    <thead>
                                        <tr>
                                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_type')</th>
                                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount')</th>
                                            <th class="py-2 text-center" style="width: 25%;">@lang('lang.discount_category')</th>
                                            <th class="py-2 text-center" style="width: 5%;"></th>
                                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_start_date')</th>
                                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_end_date')</th>
                                            <th class="py-2 text-center" style="width: 20%;">@lang('lang.customer_type') <i
                                                    class="dripicons-question" data-toggle="tooltip"
                                                    title="@lang('lang.discount_customer_info')"></i></th>
                                            <th class="py-2 text-center" style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $discounts = \Modules\Product\Entities\ProductDiscount::where(
                                                'product_id',
                                                $product->id,
                                            )->get();
                                            $index_old = 0;
                                        @endphp

                                        {{-- @if ($product->discount)
                                            @php
                                            $index_old=1;
                                            @endphp
                                            @include('products.partial.raw_discount', [
                                                'row_id' => 0,
                                                'discount_product'=>$products,
                                            ])
                                        @endif --}}
                                        @foreach ($discounts as $discount)
                                            @include('product::back-end.partial.raw_discount', [
                                                'row_id' => $loop->index + $index_old,
                                                'discount' => $discount,
                                            ])
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-main px-5 py-1 add_discount_row"
                                        type="button">@lang('lang.add_new')</button>
                                </div>
                                <input type="hidden" name="raw_discount_index" id="raw_discount_index"
                                    value="{{ count($discounts) }}">
                            </div>
                        </div>
                    </div>
                </div>


                <div
                    class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#moreInfoCollapse" aria-expanded="false" aria-controls="moreInfoCollapse">
                        <i class="fas fa-arrow-down"></i>
                        @lang('lang.more_info')
                        <span class="section-header-pill"></span>
                    </button>
                </div>
                <div class="collapse"  id="moreInfoCollapse">
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div
                                class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            {!! Form::label('barcode_type', __('lang.barcode_type') . ' *', [
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
                                                $product->barcode_type,
                                                ['class' => 'form-control', 'required'],
                                            ) !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('other_cost', __('lang.other_cost'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('other_cost', @num_format($product->other_cost), [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.other_cost'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    {!! Form::label('tax_id', __('lang.tax'), []) !!}
                                    <div class="input-group my-group select-button-group">
                                        {!! Form::select('tax_id', $taxes, $product->tax_id, [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                        <span class="input-group-btn">
                                            @can('product_module.tax.create')
                                                <button type="button" class="btn-modal select-button btn-flat"
                                                    data-href="{{ action('TaxController@create') }}?quick_add=1"
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
                                            $product->tax_method,
                                            [
                                                'class' => 'selectpicker form-control',
                                                'data-live-search' => 'true',
                                                'style' => 'width: 80%',
                                                'placeholder' => __('lang.please_select'),
                                            ],
                                        ) !!}
                                    </div>
                                </div>

                                <div
                                    class="col-md-4 d-flex @if (app()->isLocale('ar')) justify-content-end @else  justify-content-start @endif">
                                    <div class="i-checks">
                                        <input id="show_to_customer" name="show_to_customer" type="checkbox"
                                            @if ($product->show_to_customer) checked @endif value="1"
                                            class="form-control-custom">
                                        <label for="show_to_customer"><strong>@lang('lang.show_to_customer')</strong></label>
                                    </div>
                                </div>

                                <div class="col-md-3 show_to_customer_type_div">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('show_to_customer_types', __('lang.show_to_customer_types'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                            <i class="dripicons-question" data-toggle="tooltip"
                                                title="@lang('lang.show_to_customer_types_info')"></i>
                                            {!! Form::select('show_to_customer_types[]', $customer_types, $product->show_to_customer_types, [
                                                'class' => 'selectpicker form-control',
                                                'data-live-search' => 'true',
                                                'style' => 'width: 80%',
                                                'multiple',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>


                <div id="cropped_edit_product_images"></div>
                <div class="row my-2 justify-content-center align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="button" id="submit-btn" value="{{ trans('lang.save') }}"
                                class="btn btn-primary py-1">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</section>

<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="croppie-edit-product-modal" style="display:none">
                    <div id="croppie-edit-product-container"></div>
                    <button data-dismiss="modal" id="croppie-edit-product-cancel-btn" type="button"
                        class="btn btn-secondary"><i class="fas fa-times"></i></button>
                    <button id="croppie-edit-product-submit-btn" type="button" class="btn btn-primary"><i
                            class="fas fa-crop"></i></button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/product_edit.js') }}"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

<script>



    // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
    $('#discountInfoCollapse').on('show.bs.collapse', function() {
        // Change the arrow icon to 'chevron-up' when the content is expanded
        $('button[data-bs-target="#discountInfoCollapse"] i').removeClass('fa-arrow-down').addClass(
            'fa-arrow-up');
    });

    $('#discountInfoCollapse').on('hide.bs.collapse', function() {
        // Change the arrow icon to 'chevron-down' when the content is collapsed
        $('button[data-bs-target="#discountInfoCollapse"] i').removeClass('fa-arrow-up').addClass(
            'fa-arrow-down');
    });

    // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
    $('#moreInfoCollapse').on('show.bs.collapse', function() {
        // Change the arrow icon to 'chevron-up' when the content is expanded
        $('button[data-bs-target="#moreInfoCollapse"] i').removeClass('fa-arrow-down').addClass(
            'fa-arrow-up');
    });

    $('#moreInfoCollapse').on('hide.bs.collapse', function() {
        // Change the arrow icon to 'chevron-down' when the content is collapsed
        $('button[data-bs-target="#moreInfoCollapse"] i').removeClass('fa-arrow-up').addClass(
            'fa-arrow-down');
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#store_ids').selectpicker('selectAll');

        $('#different_prices_for_stores').change();
        $('#this_product_have_variant').change();
    })
</script>
<script>
    $("#submit-btn").on("click", function(e) {
        getEditProductImages()
        e.preventDefault();
        setTimeout(() => {
            if ($("#product-edit-form").valid()) {
                tinyMCE.triggerSave();
                $.ajax({
                    type: "POST",
                    url: $("#product-edit-form").attr("action"),
                    data: $("#product-edit-form").serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success',
                                text: response.msg,
                                icon: 'success',
                            });
                            setTimeout(() => {
                                window.close()
                            }, 1000);
                        }
                    },
                    error: function(response) {
                        if (!response.success) {
                            Swal.fire({
                                title: 'Error',
                                text: response.msg,
                                icon: 'error',
                            });
                        }
                    },
                });
            }
        });
    });
    @if ($product)
        {{-- document.getElementById("cropBtn{{ $product->id }}").addEventListener('click', () => { --}}
        {{--    setTimeout(() => { --}}
        {{--        launchEditProductCropTool(document.getElementById("img{{ $product->id }}")); --}}
        {{--    }, 500); --}}
        {{-- }); --}}
        document.getElementById("deleteBtn{{ $product->id }}").addEventListener('click', () => {
            Swal.fire({
                title: '{{ __('site.Are you sure?') }}',
                text: "{{ __("site.You won't be able to delete!") }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        '{{ __('site.Your Image has been deleted.') }}',
                        'success'
                    )
                    $("#preview{{ $product->id }}").remove();
                }
            });
        });
    @endif
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    var fileEditProductInput = document.querySelector('#file-product-edit-product');
    var previewEditProductContainer = document.querySelector('.preview-edit-product-container');
    var croppieEditProductModal = document.querySelector('#croppie-edit-product-modal');
    var croppieEditProductContainer = document.querySelector('#croppie-edit-product-container');
    var croppieEditProductCancelBtn = document.querySelector('#croppie-edit-product-cancel-btn');
    var croppieEditProductSubmitBtn = document.querySelector('#croppie-edit-product-submit-btn');

    // let currentFiles = [];
    fileEditProductInput.addEventListener('change', () => {
        // let files = fileEditProductInput.files;
        previewEditProductContainer.innerHTML = '';
        let files = Array.from(fileEditProductInput.files)
        // files.concat(currentFiles)
        // currentFiles.push(...files)
        // currentFiles && (files = currentFiles)
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    const preview = document.createElement('div');
                    preview.classList.add('preview');
                    const img = document.createElement('img');
                    img.src = reader.result;
                    preview.appendChild(img);
                    const container = document.createElement('div');
                    const deleteBtn = document.createElement('span');
                    deleteBtn.classList.add('delete-btn');
                    deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                    deleteBtn.addEventListener('click', () => {

                        Swal.fire({
                            title: "Delete",
                            text: "Are you sure you want to delete this image ?",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                            buttons: ["Cancel", "Delete"],
                        }).then((addPO) => {
                            if (addPO) {
                                files.splice(file, 1)
                                preview.remove();
                                getEditProductImages()
                            }
                        });
                    });

                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#editProductModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchEditProductCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewEditProductContainer.appendChild(preview);
                });
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('site.Oops...') }}',
                    text: '{{ __('site.Sorry , You Should Upload Valid Image') }}',
                })
            }
        }

        getEditProductImages()
    });

    function launchEditProductCropTool(img) {
        getEditProductImages();
        // Set up Croppie options
        const croppieOptions = {
            viewport: {
                width: 200,
                height: 200,
                type: 'square' // or 'square'
            },
            boundary: {
                width: 300,
                height: 300,
            },
            enableOrientation: true
        };

        // Create a new Croppie instance with the selected image and options
        const croppie = new Croppie(croppieEditProductContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieEditProductModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieEditProductCancelBtn.addEventListener('click', () => {
            croppieEditProductModal.style.display = 'none';
            $('#editProductModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieEditProductSubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieEditProductModal.style.display = 'none';
                $('#editProductModal').modal('hide');
                croppie.destroy();
                getEditProductImages()
            });
        });
    }

    function getEditProductImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-edit-product-container');
            let images = [];
            $("#cropped_edit_product_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0]
                    .children[i].children[0].src);
                $("#cropped_edit_product_images").append(newInput);
                images.push(container[0].children[i].children[0].src)
            }
            console.log(images)
            return images
        }, 300);
    }
</script>


@endsection
