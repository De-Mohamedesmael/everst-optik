@forelse ($products->chunk(3) as $chunk)
<tr style="font-size: 11px; padding: 5px;" class="p-0">
    @foreach ($chunk as $product)
    @php

    $stockLines = \Modules\AddStock\Entities\AddStockLine::where('product_id', $product->id)
    ->whereColumn('quantity', '>', 'quantity_sold')
    ->first();
    $default_sell_price = $stockLines ? $stockLines->sell_price : $product->default_sell_price;


    @endphp
    <td style="padding-top: 0px; padding-bottom: 0px;width:100px;min-width:100px;max-width:100px"
        class="product-img sound-btn filter_product_add px-2" data-is_service="{{ $product->is_service }}"
        data-qty_available="{{ $product->qty_available - $product->block_qty }}"
        data-product_id="{{ $product->id }}"
        title="{{ $product->name }}"
        data-product="{{ $product->name . ' (' . $product->color->name . ')' }}">
        <div class="w-100">
            <img src="@if (!empty($product->getFirstMediaUrl('products'))) {{ $product->getFirstMediaUrl('products') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                width="100%" />
        </div>
        <p><span style="font-size:12px !important; font-weight: bold; color: black;">{{$product->name }}</span>
            <br> <span style="color: black; font-weight: bold;">{{ $product->sku }}</span> <br>
            @if (!empty($currency))
            <span {{-- style="font-size:12px !important; font-weight: bold; color: black;">{{
                @num_format($default_sell_price / $exchange_rate) . ' ' . $currency->symbol }} --}}
                style="font-size:12px !important; font-weight: bold; color: black;">{{ @num_format($default_sell_price /
                $exchange_rate) }}
                {{ ' ' . $currency->symbol }}
            </span>
            @else
            <span style="font-size:12px !important; font-weight: bold; color: black;">{{ @num_format($default_sell_price
                / $exchange_rate) }}
                {{ ' ' . session('currency.symbol') }}
            </span>
            @endif
        </p>
    </td>
    @endforeach
</tr>
@empty
<tr class="text-center">
    <td colspan="5">@lang('lang.no_item_found')</td>
</tr>
@endforelse
