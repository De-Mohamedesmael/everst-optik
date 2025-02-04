@php use Illuminate\Support\Facades\Cache; @endphp
@if($product)

    <tr class="product_row">
        @if (!empty($is_direct_sale))
            <td class="row_number"></td>
        @endif
        <td
            style="font-size: 12px;padding:3px;margin:2px;width:  17%; height:40px">
            <div
                style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;    vertical-align: middle;align-items: center;display: flex;flex-direction: column;font-size: 14px">
                @php
                        $stockLines = \Modules\AddStock\Entities\AddStockLine::where('sell_price', '>', 0)
                            ->where('product_id', $product->product_id)
                            ->latest()
                            ->first();
                        $default_sell_price = $stockLines ? $stockLines->sell_price : $product->sell_price;
                        $default_purchase_price = $stockLines
                            ? $stockLines->purchase_price
                            : $product->purchase_price;
                        $cost_ratio_per_one = $stockLines ? $stockLines->cost_ratio_per_one : 0;
                        $total_vu=0;
                        $data_len=null;
                        if($product->is_lens){
                            $edit_quantity=0;
                            $cach_lens= Cache::get($KeyLens);
                            if(isset($cach_lens['Lens']['Left']['isCheck'])){
                                $edit_quantity+=1;
                            }
                             if(isset($cach_lens['Lens']['Right']['isCheck'])){
                                $edit_quantity+=1;
                            }
                            if(isset($cach_lens['VA_amount'])){
                                $total_vu=$cach_lens['VA_amount']['total'];

                            }elseif($old_len){
                                if($old_len->data != null || $old_len->data != 'null'){
                                    $data_len=json_decode($old_len->data);
                                    if(isset($data_len->VA_amount)){
                                        $total_vu=$data_len->VA_amount->total;
                                    }
                                }


                            }
                        }


                @endphp


                    <b>{{ $product->product_name }}</b>
                    <p class="m-0">
                        @php
                            $ex = 'id' . $product->product_id;
                        @endphp

                        <input type="hidden" id="{{ $ex }}" name="old_ex" value="1">
                    </p>




                <input type="hidden" name="transaction_sell_line[{{  $index }}][is_lens]"
                    class="is_lens" value="{{ $product->is_lens }}">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][have_weight]"
                    class="have_weight" value="{{ $product->have_weight }}">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][product_id]"
                    class="product_id" value="{{ $product->product_id }}">

                <input type="hidden" name="transaction_sell_line[{{  $index }}][stock_id]"
                    class="batch_number_id"
                    value="@if ($product->stock_id) {{ $product->stock_id }}@else {{ false }} @endif">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][batch_number]"
                    class="batch_number"
                    value="@if ($product->batch_number) {{ $product->batch_number }}@else {{ false }} @endif">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][price_hidden]"
                    class="price_hidden"
                    value="@if(isset($default_sell_price)) {{ @num_format($default_sell_price / $exchange_rate) }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][purchase_price]"
                    class="purchase_price"
                    value="@if (isset($default_purchase_price)) {{ @num_format($default_purchase_price / $exchange_rate) }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][cost_ratio_per_one]"
                    class="cost_ratio_per_one"
                    value="@if (isset($cost_ratio_per_one)) {{ @num_format($cost_ratio_per_one / $exchange_rate) }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][tax_id]" class="tax_id"
                    value="{{ $product->tax_id }}">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][tax_method]"
                    class="tax_method" value="{{ $product->tax_method }}">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][tax_rate]"
                    class="tax_rate" value="{{ @num_format($product->tax_rate) }}">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][item_tax]"
                    class="item_tax" value="0">
                <input type="hidden" value="{{$KeyLens}}" name="transaction_sell_line[{{  $index }}][KeyLens]">

                <!-- after calculation actual discounted amount for row products row -->
                <input type="hidden"
                    name="transaction_sell_line[{{  $index }}][promotion_purchase_condition]"
                    class="promotion_purchase_condition"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->purchase_condition }}@else{{ 0 }} @endif">
                <input type="hidden"
                    name="transaction_sell_line[{{  $index }}][promotion_purchase_condition_amount]"
                    class="promotion_purchase_condition_amount"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->purchase_condition_amount }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{  $index }}][promotion_discount]"
                    class="promotion_discount_value"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->discount_value }}@else{{ 0 }} @endif">
                <input type="hidden"
                    name="transaction_sell_line[{{  $index }}][promotion_discount_type]"
                    class="promotion_discount_type"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->discount_type }}@else{{ 0 }} @endif">
                <input type="hidden"
                    name="transaction_sell_line[{{  $index }}][promotion_discount_amount]"
                    class="promotion_discount_amount" value="0">
                @php $loop_index=  $index @endphp
            </div>
        </td>



        <td
            style="font-size: 12px;padding:3px;margin:2px;width:  12%; height:40px">
            <div
                style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%; padding-top: 4px;padding-bottom: 4px;">
                <div class="input-group justify-content-between align-items-center flex-column flex-lg-row"
                    style="height: 100%">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-lg p-0 p-lg-1 btn-danger minus"
                            style="border-radius: 6px">
                            <span class="dripicons-minus"></span>
                        </button>
                    </span>

                    @php

                        $inputValue = !empty($edit_quantity)
                            ? $edit_quantity
                            : (isset($product->quantity)
                                ? (preg_match('/\.\d*[1-9]+/', (string) $product->quantity)
                                    ? $product->quantity
                                    : number_format($product->quantity))
                                : 1);

                        // Create a regular expression to match the whitespace between the `value` attribute and the value.
                        $regex = '/\s+/';

                        // Replace the whitespace with an empty string.
                        $convertedInputValue = preg_replace($regex, '', $inputValue);
                    @endphp


                    <input type="number" class="form-control quantity  qty numkey input-number" step="any"
                        autocomplete="off" style="width: 50px;font-size: 14px;font-weight: 600"
                        @isset($check_unit) @if ($check_unit->name == 'قطعه' || $check_unit->name == 'Piece') oninput="this.value = Math.round(this.value);" @endif @endisset
                        id="quantity" @if (!$product->is_lens) max="{{ $product->qty_available }}" @endif
                        name="transaction_sell_line[{{  $index }}][quantity]" required
                        value="{{ $convertedInputValue }}">

                    <span class="input-group-btn">
                        <button type="button"
                            style="background-color:var(--secondary-color);color: white;border-radius: 6px"
                            class="btn btn-lg plus p-0 p-lg-1">
                            <span class="dripicons-plus"></span>
                        </button>
                    </span>
                </div>
            </div>
        </td>



        <td
            style="font-size: 12px;padding:3px;margin:2px;width: 12%; height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                <input type="text" class="form-control sell_price text-center d-flex justify-content-center align-items-center"
                    style="outline: none;border: none;padding: 0 !important;width: 100%;height: 100%;font-size: 14px;font-weight: 600"
                       data-product_id="{{$product->product_id}}"
                    name="transaction_sell_line[{{  $index }}][sell_price]" required
                    @if (!auth()->user()->can('product_module.sell_price.create_and_edit')) readonly @elseif(env('IS_SUB_BRANCH', false)) readonly @endif
                    value="@if (isset($default_sell_price)) {{ @num_format(($default_sell_price+$total_vu)/ $exchange_rate) }}@else{{ 0 }} @endif ">
            </div>
        </td>


        <td
            style="font-size: 12px;padding:3px;margin:2px;width:11%; height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                @php
                    $discountType = !empty($product_discount_details->discount_type)
                        ? $product_discount_details->discount_type
                        : 0;

                    $discountAmount = !empty($product_discount_details->discount)
                        ? number_format($product_discount_details->discount)
                        : 0;

                    // Create a regular expression to match the whitespace between the `value` attribute and the value.
                    $regex = '/\s+/';

                    // Replace the whitespace with an empty string.
                    $convertedDiscountType = preg_replace($regex, '', $discountType);

                    $convertedDiscountAmount = preg_replace($regex, '', $discountAmount);
                @endphp

                <div class="input-group" style="width: 100%;height: 100%;">
                    <input type="hidden"
                        class="form-control product_discount_type  discount_type{{ $product->product_id }}"
                        name="transaction_sell_line[{{  $index }}][product_discount_type]"
                        value="{{ $convertedDiscountType }}">


                    <input type="hidden"
                        class="form-control product_discount_value  discount_value{{ $product->product_id }}"
                        name="transaction_sell_line[{{  $index }}][product_discount_value]"
                        value="{{ $convertedDiscountAmount }}">


                    <div class="d-flex justify-content-center align-items-center">

                        <input type="text"
                            style="outline: none;border: none;padding: 0!important;height: 100%;font-size: 14px;font-weight: 600;text-align: center"
                            class="form-control product_discount_amount  discount_amount{{ $product->product_id }}"
                            name="transaction_sell_line[{{  $index }}][product_discount_amount]"
                            readonly value="{{ $convertedDiscountAmount }}">

                    </div>
                </div>
            </div>
        </td>


        <td
            style="font-size: 12px;padding:3px;margin:2px;width:  11% ; height:40px; ">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                <input type="hidden" value="{{ $product->product_id }}" class="p-id" />
                @if (auth()->user()->can('sp_module.sales_promotion.view') ||
                        auth()->user()->can('sp_module.sales_promotion.create_and_edit') ||
                        auth()->user()->can('sp_module.sales_promotion.delete'))
                    <select
                        class="custom-select custom-select-lg discount_category discount_category{{ $product->product_id }}"
                        style="height:100%">
                        <option selected>select</option>
                        @if (!empty($product_all_discounts_categories))
                            @foreach ($product_all_discounts_categories as $discount)
                                <option value="{{ $discount->id }}">{{ $discount->discount_category }}</option>
                            @endforeach
                        @endif
                    </select>
                @else
                    <select
                        class="custom-select custom-select-sm discount_category discount_category{{ $product->product_id }}"
                        style="height:100% !important;font-size: 14px" disabled="disabled">
                        <option selected>select</option>
                        @if (!empty($product_all_discounts_categories))
                            @foreach ($product_all_discounts_categories as $discount)
                                <option value="{{ $discount->id }}">{{ $discount->discount_category }}</option>
                            @endforeach
                        @endif
                    </select>
                @endif
                <input type="hidden" name="transaction_sell_line[{{  $index }}][discount_category]"
                    class="discount_category_name{{ $product->product_id }}" />
            </div>
        </td>





        <td
            style="font-size: 12px;padding:3px;margin:2px;width:9%; height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">


                <span class="sub_total_span d-flex justify-content-center align-items-center"
                    style="font-weight: bold;width: 100%; height: 100%;font-size: 14px"></span>
                <input type="hidden" class="form-control sub_total"
                    name="transaction_sell_line[{{  $index }}][sub_total]">
            </div>
        </td>


            <td
                style="font-size: 12px;padding:3px;margin:2px;width:9%;height:40px">
                <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                    <div class="d-flex justify-content-center align-items-center"
                        style="width: 100%;height: 100%;font-size: 14px;font-weight: 600">

                        @if ($product->is_lens)
                            {{ '-' }}
                        @else
                            @if (isset($product->qty_available))
                                {{ preg_match('/\.\d*[1-9]+/', (string) $product->qty_available) ? $product->qty_available : @num_format($product->qty_available) }}@else{{ 0 }}
                            @endif
                        @endif
                    </div>
                </div>
            </td>
        <td
            style="font-size: 12px;padding:3px;margin:2px;width:9%; padding: 0px;height:40px;border:none;">

            <div class="d-flex justify-content-around align-items-center" style="width: 100%;height: 100%;">

                <button type="button" class="btn p-0 remove_row"
                    style="background-color: transparent;outline: none;border: none" data-index="{{  $index }}">
                    <div class="image-responsive">
                        <img style="width: 100%; border-radius: 5px" src="{{ url('images/delete.png') }}"
                            alt="">
                    </div>
                </button>

            </div>
        </td>
    </tr>
    @if($old_len && $data_len && $total_vu > 0)
        <tr class="lens-row-{{  $index }}">
            <td>
                <div  class="lens-vu" >
                    @if(isset($data_len->VA_amount->TinTing_amount) && $data_len->VA_amount->TinTing_amount > 0)
                        <div class="lens-vu-item">
                            {{translate('TinTing_amount')}}
                        </div>
                    @endif
                    @if(isset($data_len->VA_amount->Base_amount) && $data_len->VA_amount->Base_amount > 0)
                        <div class="lens-vu-item">
                            {{translate('Base_amount')}}
                        </div>
                    @endif
                    @if(isset($data_len->VA_amount->Ozel_amount) && $data_len->VA_amount->Ozel_amount > 0)
                        <div class="lens-vu-item">
                            {{translate('Ozel_amount')}}
                        </div>
                    @endif
                </div>
            </td>
            <td>
                <div  class="lens-vu" >
                    @if(isset($data_len->VA_amount->TinTing_amount) && $data_len->VA_amount->TinTing_amount > 0)
                        <div class="lens-vu-item">
                            {{$data_len->VA->TinTing->text}}
                        </div>
                    @endif
                    @if(isset($data_len->VA_amount->Base_amount) && $data_len->VA_amount->Base_amount > 0)
                        <div class="lens-vu-item">
                            {{$data_len->VA->Base->text}}
                        </div>
                    @endif
                    @if(isset($data_len->VA_amount->Ozel_amount) && $data_len->VA_amount->Ozel_amount > 0)
                        <div class="lens-vu-item">
                            {{$data_len->VA->Ozel->text}}
                        </div>
                    @endif
                </div>
            </td>
            <td
                style="font-size: 12px;padding:3px;margin:2px;width:12%; height:40px">
                <div class="lens-vu-price" style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">
                    @if(isset($data_len->VA_amount->TinTing_amount) && $data_len->VA_amount->TinTing_amount > 0)
                        <div class="lens-vu-item">
                            <span class="lens-vu-price">{{$data_len->VA_amount->TinTing_amount}}</span>
                        </div>
                    @endif
                    @if(isset($data_len->VA_amount->Base_amount) && $data_len->VA_amount->Base_amount > 0)
                        <div class="lens-vu-item">
                            <span class="lens-vu-price">{{$data_len->VA_amount->Base_amount}}</span>
                        </div>
                    @endif
                    @if(isset($data_len->VA_amount->Ozel_amount) && $data_len->VA_amount->Ozel_amount > 0)
                        <div class="lens-vu-item">
                            <span class="lens-vu-price">{{$data_len->VA_amount->Ozel_amount}}</span>
                        </div>
                    @endif
                </div>
            </td>

            <td>
                <div  class="lens-vu" >
                    @if(isset($data_len->VA->code->isCheck) && $data_len->VA->code->isCheck)
                        <div class="lens-vu-item">
                            {{translate('code_title')}}
                        </div>
                    @endif
                </div>
            </td>
            <td>
                <div  class="lens-vu" >
                    @if(isset($data_len->VA->code->isCheck) && $data_len->VA->code->isCheck)
                        <div class="lens-vu-item">
                            {{$data_len->VA->code->text}}
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @elseif($product->is_lens && $total_vu > 0)
        <tr class="lens-row-{{  $index }}">
            <td>
                <div  class="lens-vu" >
                    @if(isset($cach_lens['VA_amount']['TinTing_amount']) && $cach_lens['VA_amount']['TinTing_amount'] > 0)
                        <div class="lens-vu-item">
                            {{translate('TinTing_amount')}}
                        </div>
                    @endif
                    @if(isset($cach_lens['VA_amount']['Base_amount']) && $cach_lens['VA_amount']['Base_amount'] > 0)
                        <div class="lens-vu-item">
                            {{translate('Base_amount')}}
                        </div>
                    @endif
                    @if(isset($cach_lens['VA_amount']['Ozel_amount']) && $cach_lens['VA_amount']['Ozel_amount'] > 0)
                        <div class="lens-vu-item">
                            {{translate('Ozel_amount')}}
                        </div>
                    @endif
                </div>
            </td>
            <td>
                <div  class="lens-vu" >
                    @if(isset($cach_lens['VA_amount']['TinTing_amount']) && $cach_lens['VA_amount']['TinTing_amount'] > 0)
                        <div class="lens-vu-item">
                            {{$cach_lens['VA']['TinTing']['text']}}
                        </div>
                    @endif
                    @if(isset($cach_lens['VA_amount']['Base_amount']) && $cach_lens['VA_amount']['Base_amount'] > 0)
                        <div class="lens-vu-item">
                            {{$cach_lens['VA']['Base']['text']}}
                        </div>
                    @endif
                    @if(isset($cach_lens['VA_amount']['Ozel_amount']) && $cach_lens['VA_amount']['Ozel_amount'] > 0)
                        <div class="lens-vu-item">
                            {{$cach_lens['VA']['Ozel']['text']}}
                        </div>
                    @endif
                </div>
            </td>
            <td
                style="font-size: 12px;padding:3px;margin:2px;width:12%; height:40px">
                <div class="lens-vu-price" style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">
                    @if(isset($cach_lens['VA_amount']['TinTing_amount']) && $cach_lens['VA_amount']['TinTing_amount'] > 0)
                        <div class="lens-vu-item">
                            <span class="lens-vu-price">{{$cach_lens['VA_amount']['TinTing_amount']}}</span>
                        </div>
                    @endif
                    @if(isset($cach_lens['VA_amount']['Base_amount']) && $cach_lens['VA_amount']['Base_amount'] > 0)
                        <div class="lens-vu-item">
                            <span class="lens-vu-price">{{$cach_lens['VA_amount']['Base_amount']}}</span>
                        </div>
                    @endif
                    @if(isset($cach_lens['VA_amount']['Ozel_amount']) && $cach_lens['VA_amount']['Ozel_amount'] > 0)
                        <div class="lens-vu-item">
                            <span class="lens-vu-price">{{$cach_lens['VA_amount']['Ozel_amount']}}</span>
                        </div>
                    @endif
                </div>
            </td>

            <td>
                <div  class="lens-vu" >
                    @if(isset($cach_lens['VA']['code']['isCheck']) && $cach_lens['VA']['code']['isCheck'])
                        <div class="lens-vu-item">
                            {{translate('code_title')}}
                        </div>
                    @endif
                </div>
            </td>
            <td>
                <div  class="lens-vu" >
                    @if(isset($cach_lens['VA']['code']['isCheck']) && $cach_lens['VA']['code']['isCheck'])
                        <div class="lens-vu-item">
                            {{$cach_lens['VA']['code']['text']}}
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @endif
@endif

