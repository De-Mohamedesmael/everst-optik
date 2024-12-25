@php
    $index = $index ?? '';
@endphp

<button type="button" class="btn select_product_button d-flex justify-content-center align-items-center"
    data-toggle="modal" data-target="#select_products_modal{{ $index ?? '' }}">
    @lang('lang.select_products')
</button>
<div class="modal fade" id="select_products_modal{{ $index }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="width: 100%;">
    <div class="modal-dialog modal-lg" role="document" id="select_products_modal">
        <div class="modal-content">
            <div
                class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">


                <h5 class=" modal-title position-relative  d-flex align-items-center" style="gap: 5px;">
                    @lang('lang.select_products')
                    <span class=" header-pill"></span>
                </h5>
                <button type="button"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                    data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                            class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>

            <div class="modal-body">
                <div class="col-md-12">
                    <div class="card mt-3">
                        <div class="col-md-12">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_category_id', __('lang.category'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_category_id', $categories, request()->category_id, [
                                            'class' =>
                                                'form-control filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_category_id' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_brand_id', __('lang.brand'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_brand_id', $brands, request()->brand_id, [
                                            'class' =>
                                                'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_brand_id' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_color_id', __('lang.color'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_color_id', $colors, request()->color_id, [
                                            'class' =>
                                                'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_color_id' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_size_id', __('lang.size'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_size_id', $sizes, request()->size_id, [
                                            'class' =>
                                                'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_size_id' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_tax_id', __('lang.tax'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_tax_id', $taxes_array, request()->tax_id, [
                                            'class' =>
                                                'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_tax_id' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_store_id', __('lang.store'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_store_id', $stores, request()->store_id, [
                                            'class' => 'form-control filter_product' . $index,
                                            'id' => 'filter_store_id' . $index,
                                            'placeholder' => __('lang.all'),
                                            'data-live-search' => 'true',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_customer_type_id', __('lang.customer_type'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_customer_type_id', $customer_types, request()->customer_type_id, [
                                            'class' =>
                                                'form-control filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_customer_type_id' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('filter_created_by', __('lang.created_by'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('filter_created_by', $admins, request()->created_by, [
                                            'class' =>
                                                'form-control filter_product' .
                                                $index .
                                                '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'id' => 'filter_created_by' . $index,
                                            'placeholder' => __('lang.all'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button"
                                        class="btn btn-danger mb-3 clear_filters{{ $index }}">@lang('lang.clear_filters')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table id="product_selection_table{{ $index ?? '' }}" class="table" style="width: auto">
                        <thead>
                            <tr>
                                <th>@lang('lang.select')<br>
                                    <input type="checkbox" name="product_select_all" class="product_select_all" />
                                </th>
                                <th>@lang('lang.image')</th>
                                <th>@lang('lang.name')</th>
                                <th>@lang('lang.product_code')</th>
                                <th>@lang('lang.category')</th>
                                <th>@lang('lang.purchase_history')</th>
                                <th>@lang('lang.batch_number')</th>
                                <th>@lang('lang.selling_price')</th>
                                <th>@lang('lang.tax')</th>
                                <th>@lang('lang.brand')</th>
                                <th>@lang('lang.color')</th>
                                <th>@lang('lang.size')</th>
                                <th class="sum">@lang('lang.current_stock')</th>
                                <th>@lang('lang.customer_type')</th>
                                <th>@lang('lang.manufacturing_date')</th>
                                <th>@lang('lang.discount')</th>
                                @can('product_module.purchase_price.view')
                                    <th>@lang('lang.purchase_price')</th>
                                @endcan
                                <th>@lang('lang.created_by')</th>
                                <th>@lang('lang.edited_by')</th>
                                <th class="notexport">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="16" style="text-align: right">@lang('lang.total')</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    id="add-selected-btn{{ $index ?? '' }}">@lang('lang.add')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
            </div>

        </div>
    </div>
</div>
