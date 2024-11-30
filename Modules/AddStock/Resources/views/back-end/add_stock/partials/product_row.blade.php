@php
    use Modules\AddStock\Entities\AddStockLine;use Modules\Product\Entities\ProductStore;$i = $index;
@endphp
@forelse ($products as $product)
    @php
        $i = $i + 1;
        $current_stock = ProductStore::where('product_id', $product->id)
            ->first();
        $stock = AddStockLine::where('product_id', $product->id)
            ->latest()
            ->first();
        if ($stock) {
            $purchase_price = str_replace(',', '', $stock->purchase_price);
            $sell_price = str_replace(',', '', $stock->sell_price);

        }
    @endphp
    <tr class="product_row">
        <td class="row_number"></td>
        <td><img
                src="@if (!empty($product->getFirstMediaUrl('products'))) {{ $product->getFirstMediaUrl('products') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                alt="photo" width="50" height="50"></td>
        <td>
            <h6 style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-center">

                {{ $product->product_name }}  {{-- $product->sku --}}
                <input type="hidden" name="is_batch_product" class="is_batch_product"
                       value="{{ isset($is_batch) ? $is_batch : null }}">
                <input type="hidden" name="row_count" class="row_count" value="{{ $i }}">
                <input type="hidden" name="add_stock_lines[{{ $i }}][product_id]" class="product_id"
                       value="{{ $product->product_id }}">

            </h6>

        </td>
        <td>
            {{-- @if ($sku_sub)
            {{$sku_sub}}
            <input type="hidden" name="add_stock_lines[{{$i}}][sku_sub]" value="{{$sku_sub}}">
        @else --}}
            <h6 style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-center">
                {{ $product->sku }}
            </h6>
            {{-- @endif --}}

        </td>

        <td>

            <input type="text"
                   class="form-control modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif quantity quantity_{{ $i }}"
                   data-val="0" name="add_stock_lines[{{ $i }}][quantity]" required
                   min="1"
                   value="0" index_id="{{ $i }}">
        </td>
        <td>
            <div style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-start">
                <span class="text-secondary font-weight-bold pr-1">*</span>
                <input type="hidden" class="purchase_price_submit" value="0"/>
                <input type="text"
                       class="form-control modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif purchase_price purchase_price_{{ $i }}"
                       name="add_stock_lines[{{ $i }}][purchase_price]" required
                       value="@if (isset($purchase_price)) {{ @num_format($purchase_price) }}@else @if ($product->purchase_price_depends == null) {{ @num_format($product->default_purchase_price / $exchange_rate) }} @else {{ @num_format($product->purchase_price_depends / $exchange_rate) }} @endif @endif"
                       index_id="{{ $i }}">
                <input class="final_cost" type="hidden" name="add_stock_lines[{{ $i }}][final_cost]"
                       value="@if (isset($product->default_purchase_price)) {{ @num_format($product->default_purchase_price / $exchange_rate) }}@else{{ 0 }} @endif">
            </div>
        </td>
        <td>
            <div style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-start">
                <span class="text-secondary font-weight-bold pr-1">*</span>
                <input type="hidden" class="selling_price_submit" value="0"/>
                <input type="text"
                       class="form-control modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif selling_price selling_price_{{ $i }}"
                       name="add_stock_lines[{{ $i }}][selling_price]" required index_id="{{ $i }}"
                       value="@if (isset($sell_price)) {{ @num_format($sell_price) }}@else @if ($product->selling_price_depends == null) {{ @num_format($product->sell_price) }} @else {{ @num_format($product->selling_price_depends) }} @endif @endif">
                {{--        <input class="final_cost" type="hidden" name="add_stock_lines[{{$i}}][final_cost]" value="@if (isset($products->default_purchase_price)){{@num_format($products->default_purchase_price / $exchange_rate)}}@else{{0}}@endif"> --}}
            </div>
        </td>
        <td>
            <h6 style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-center">
                <span class="sub_total_span"></span>
            </h6>
            <input type="hidden" class="form-control sub_total" name="add_stock_lines[{{ $i }}][sub_total]"
                   value="">
        </td>
        <td>
            <input type="hidden" name="current_stock" class="current_stock current_stock{{ $product->id }}"
                   value="@if (isset($current_stock->qty_available)){{ $current_stock->qty_available }}@else{{ 0 }} @endif ">
            <h6 style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-center">

                <span class="current_stock_text current_stock_text{{ $product->id }}">

                        @if (isset($current_stock->qty_available))
                        {{ @num_format($current_stock->qty_available) }}
                    @else
                        {{ 0 }}
                    @endif
                </span>
            </h6>
        </td>
        <td>
            <div class="i-checks"><input name="add_stock_lines[{{ $i }}][stock_pricechange]" id="active"
                                         type="checkbox" class="stock_pricechange stockId{{ $i }}" checked value="1">
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm p-1 mb-1 btn-danger remove_row"
                    data-index="{{ $i }}"><i class="fa fa-times"></i></button>

        </td>
    </tr>

@empty
@endforelse

<script>

</script>
