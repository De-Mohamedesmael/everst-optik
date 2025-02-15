@extends('back-end.layouts.app')
@section('title', __('lang.print_barcode'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@section('content')
    <section class="forms py-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-22 px-1">
                    <div
                        class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <h5 class="mb-0 position-relative" style="margin-right: 30px">
                            @lang('lang.print_barcode')
                            <span class="header-pill"></span>
                        </h5>
                    </div>
                    {!! Form::open(['url' => '#', 'method' => 'post', 'id' => 'preview_setting_form', 'onsubmit' => 'return false']) !!}
                    <div class="card mb-2">

                        <div class="card-body">
                            <input type="hidden" name="is_add_stock" id="is_add_stock" value="1">
                            <input type="hidden" name="row_count" id="row_count" value="0">
                            <div class="row mb-3">

                                <div class="col-md-10">
                                    <div class="search-box input-group"
                                        style="background-color: #e6e6e6;border-radius: 6px!important">
                                        <button type="button" class="select-button " style="height: auto !important"><i
                                                class="fa fa-search"></i></button>
                                        <input type="text" name="search_product" id="search_product_for_label"
                                            placeholder="@lang('lang.enter_product_name_to_print_labels')"
                                            class="form-control  modal-input @if (app()->isLocale('ar')) text-end @else  text-start @endif ui-autocomplete-input"
                                            style="width: 80% !important" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex justify-content-center align-items-center">
                                    @include('product::back-end.products.partial.product_selection')
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <table class="table table-bordered table-striped table-condensed" id="product_table">
                                        <thead>
                                            <tr>
                                                <th style="width: 33%" class="col-sm-8">@lang('lang.products')</th>
                                                <th style="width: 33%" class="col-sm-4">@lang('lang.sku')</th>
                                                <th style="width: 33%" class="col-sm-4">@lang('lang.no_of_labels')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="i-checks">
                                        <input id="product_name" name="product_name" type="checkbox" checked value="1"
                                            class="form-control-custom">
                                        <label for="product_name"><strong>@lang('lang.product_name')</strong></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="i-checks">
                                        <input id="price" name="price" type="checkbox" checked value="1"
                                            class="form-control-custom">
                                        <label for="price"><strong>@lang('lang.price')</strong></label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="i-checks">
                                        <input id="size" name="size" type="checkbox" checked value="1"
                                            class="form-control-custom">
                                        <label for="size"><strong>@lang('lang.size')</strong></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="i-checks">
                                        <input id="color" name="color" type="checkbox" checked value="1"
                                            class="form-control-custom">
                                        <label for="color"><strong>@lang('lang.color')</strong></label>
                                    </div>
                                </div>

                                @foreach ($stores as $key => $store)
                                    <div class="col-md-2">
                                        <div class="i-checks">
                                            <input id="store{{ $key }}" name="store[{{ $key }}]"
                                                type="checkbox" value="{{ $key }}"
                                                @if ($loop->index == 0) checked @endif
                                                class="form-control-custom">
                                            <label
                                                for="store{{ $key }}"><strong>{{ $store }}</strong></label>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-md-3">
                                    <div class="i-checks">
                                        <input id="site_title" name="site_title" type="checkbox" checked value="1"
                                            class="form-control-custom">
                                        <label
                                            for="site_title"><strong>{{ \Modules\Setting\Entities\System::getProperty('site_title') }}</strong></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="">@lang('lang.text')</label>
                                        <input
                                            class="form-control  modal-input @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            type="text" name="free_text" id="free_text" value="">
                                    </div>
                                </div>




                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="">@lang('lang.paper_size')</label>
                                        <select class="form-control" name="paper_size" required id="paper-size"
                                            tabindex="-98">
                                            <option value="0">Select paper size...</option>
                                            <option value="36">36 mm (1.4 inch)</option>
                                            <option value="24">24 mm (0.94 inch)</option>
                                            <option value="18">18 mm (0.7 inch)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-2 justify-content-center align-items-center">
                            <div class="col-md-4">
                                <button type="button" id="labels_preview"
                                    class="btn submit-btn py-1 btn-primary">@lang('lang.submit')</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('js/barcode.js') }}"></script>
    <script src="{{ asset('js/product_selection.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '#add-selected-btn', function() {
            $('#select_products_modal').modal('hide');
            $.each(product_selected, function(index, value) {
                get_label_product_row(value.product_id);
            });
            product_selected = [];
            product_table.ajax.reload();
        });
        $('#product_selection_table').on('change', '.product_select_all', function() {
            var isChecked = $(this).prop('checked');
            product_table.rows().nodes().to$().find('.product_selected').prop('checked', isChecked);
            $('.product_selected').change();
        });
        $(document).on('click', '.remove_row', function() {
            $(this).closest('tr').remove();
        });
    </script>
@endsection
