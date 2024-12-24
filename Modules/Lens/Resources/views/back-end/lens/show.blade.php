    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div
                class="modal-header d-flex justify-content-between py-0 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title" id="exampleLargeModalLabel">{{ $product->name }}</h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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


                    @if ($add_stocks->count() > 0)
                        <div class="col-md-12">
                            <br>
                            <br>
                            <h4>@lang('lang.expiry_details')</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-success text-white">
                                        <th>@lang('lang.store_name')</th>
                                        <th>@lang('lang.current_stock')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($add_stocks as $add_stock)
                                        <tr>
                                            <td>{{ $add_stock->store->name ?? '' }}</td>
                                            <td>{{ number_format($add_stock->current_stock,Modules\Setting\Entities\System::getProperty('numbers_length_after_dot')) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <br>
                        <br>
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
