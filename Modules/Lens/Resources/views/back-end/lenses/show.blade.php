<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">{{ $product->name }}

            </h5>

            <button type="button" data-dismiss="modal" aria-label="Close"
                class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                    aria-hidden="true" style="border-radius: 10px !important;"><i
                        class="dripicons-cross"></i></span></button>

        </div>

        <div class="modal-body">
            <div class="row justify-content-center" style="gap: 15px">

                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important" for="">@lang('lang.sku'):
                    </label>
                    <span style="font-weight: 600;font-size: 20px">

                        {{ $product->sku }}
                    </span>
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important" for="">@lang('lang.brand'):
                    </label>
                    @if (!empty($product->brand))
                    <span style="font-weight: 600;font-size: 20px">
                        {{ $product->brand->name }}
                    </span>
                    @endif
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important"
                        for="">@lang('lang.batch_number'): </label>
                    <span style="font-weight: 600;font-size: 20px">
                        {{ $product->batch_number }}
                    </span>
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important"
                        for="">@lang('lang.selling_price'): </label>
                    <span style="font-weight: 600;font-size: 20px">

                        {{ @num_format($product->sell_price) }}
                    </span>
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important"
                        for="">@lang('lang.automatic_consumption'): </label>
                    @if (!empty($product->automatic_consumption))
                    <span style="font-weight: 600;font-size: 20px">

                        {{ __('lang.yes') }}@else{{ __('lang.no') }}
                    </span>
                    @endif
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">

                    <label style="font-weight: bold;margin: 0 15px;color: white !important" for="">@lang('lang.tax'):
                    </label>
                    @if (!empty($product->tax->name))
                    <span style="font-weight: 600;font-size: 20px">

                        {{ $product->tax->name }}
                    </span>
                    @endif
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important" for="">@lang('lang.color'):
                    </label>
                    <span style="font-weight: 600;font-size: 20px">
                        {{ $product->color->name }}
                    </span>
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important" for="">@lang('lang.size'):
                    </label>
                    <span style="font-weight: 600;font-size: 20px">

                        {{ $product->size->name }}
                    </span>
                    @can('product_module.purchase_price.view')
                </div>
                <div class="info-row d-flex justify-content-start align-items-center" style="background: #476762;
            border-radius: 8px;
            padding: 5px;
            color: white;
            width: 30%;">
                    <label style="font-weight: bold;margin: 0 15px;color: white !important"
                        for="">@lang('lang.purchase_price'): </label>
                    <span style="font-weight: 600;font-size: 20px">
                        {{ @num_format($product->purchase_price) }}
                    </span>
                    @endcan
                    {{-- <label style="font-weight: bold;margin: 0 15px;color: white !important"
                        for="">@lang('lang.is_service'): </label>--}}
                    {{-- @if (!empty($product->is_service))--}}
                    {{-- @lang('lang.yes')--}}
                    {{-- @else--}}
                    {{-- @lang('lang.no')--}}
                    {{-- @endif--}}



                </div>
            </div>




            <div class="col-md-12 mt-3">


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
                            <td>{{
                                @number_format($stock_detial->qty_available,Modules\Setting\Entities\System::getProperty('numbers_length_after_dot'))
                                }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->