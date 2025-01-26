    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">{{ $product->name }}
                    <span class=" header-pill"></span>
                </h5>

                <button type="button" data-dismiss="modal" aria-label="Close"
                        class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true" style="border-radius: 10px !important;"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <label style="font-weight: bold;" for="">@lang('lang.sku'): </label>
                                {{ $product->sku }} <br>

                                <label style="font-weight: bold;" for="">@lang('lang.categories'): </label>
                                @if (!empty($product->categories))
                                    @foreach($product->categories as $category)
                                        <span class="cat-name">{{ $category->name }}</span>
                                    @endforeach
                                @endif <br>

                                <label style="font-weight: bold;" for="">@lang('lang.brand'): </label>
                                @if (!empty($product->brand))
                                    {{ $product->brand->name }}
                                @endif
                                <br>
                                <label style="font-weight: bold;" for="">@lang('lang.batch_number'): </label>
                                {{ $product->batch_number }}<br>
                                <label style="font-weight: bold;" for="">@lang('lang.selling_price'): </label>
                                {{ @num_format($product->sell_price) }}<br>
                                <label style="font-weight: bold;" for="">@lang('lang.automatic_consumption'): </label>
                                @if (!empty($product->automatic_consumption))
                                    {{ __('lang.yes') }}@else{{ __('lang.no') }}
                                @endif
                                <br>
                            </div>
                            <div class="col-md-6">
                                <label style="font-weight: bold;" for="">@lang('lang.tax'): </label>
                                @if (!empty($product->tax->name))
                                    {{ $product->tax->name }}
                                @endif <br>
                                <label style="font-weight: bold;" for="">@lang('lang.color'): </label>
                                {{ $product->color->name }}<br>
                                <label style="font-weight: bold;" for="">@lang('lang.size'): </label>
                                {{ $product->size->name }}<br>
                               @can('product_module.purchase_price.view')
                                    <label style="font-weight: bold;" for="">@lang('lang.purchase_price'): </label>
                                    {{ @num_format($product->purchase_price) }}<br>
                                @endcan
    {{--                            <label style="font-weight: bold;" for="">@lang('lang.is_service'): </label>--}}
    {{--                            @if (!empty($product->is_service))--}}
    {{--                                @lang('lang.yes')--}}
    {{--                            @else--}}
    {{--                                @lang('lang.no')--}}
    {{--                            @endif--}}
                                <br>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-12">
                        <h4>@lang('lang.stock_details')</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.sku')</th>
                                    <th>@lang('lang.store_name')</th>
                                    <th>@lang('lang.current_stock')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock_detials as $stock_detial)
                                    <tr>
                                        <td>
                                            {{ $stock_detial->product->name }}
                                        </td>
                                        <td>{{ $stock_detial->product->sku ?? '' }}</td>
                                        <td>{{ $stock_detial->store->name ?? '' }}</td>
                                        <td>{{ @number_format($stock_detial->qty_available,Modules\Setting\Entities\System::getProperty('numbers_length_after_dot')) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
