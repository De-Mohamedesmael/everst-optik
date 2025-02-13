

<div class="modal-dialog no-print" role="document" style="max-width: 70%">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">{{ trans('sale') }}
                    <span class=" header-pill"></span>
                </h5>

                <button type="button" data-dismiss="modal" aria-label="Close"
                        class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true" style="border-radius: 10px !important;"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <h5>@lang('lang.invoice_no'): {{ $sale->invoice_no }} @if (!empty($sale->return_parent))
                            <a data-href="{{ route('admin.sale-return.show', $sale->id) }}"
                                data-container=".view_modal" class="btn btn-modal" style="color: #007bff;">R</a>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-12">
                        <h5>@lang('lang.date'): {{ @format_datetime($sale->transaction_date) }}</h5>
                    </div>
                    <div class="col-md-12">
                        <h5>@lang('lang.store'): {{ $sale->store->name ?? '' }}</h5>
                    </div>
                </div>
                <br>
                <div class="col-md-6">
                    <div class="col-md-12">
                        {!! Form::label('supplier_name', __('lang.customer_name'), []) !!}:
                        <b>{{ $sale->customer->name ?? '' }}</b>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('email', __('lang.email'), []) !!}: <b>{{ $sale->customer->email ?? '' }}</b>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('mobile_number', __('lang.mobile_number'), []) !!}:
                        <b>{{ $sale->customer->mobile_number ?? '' }}</b>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('address', __('lang.address'), []) !!}: <b>{{ $sale->customer->address ?? ''
                            }}</b>
                    </div>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-condensed" id="product_sale_table">
                        <thead class="bg-success" style="color: white">
                            <tr>
                                <th style="width: 25%" class="col-sm-8">@lang('lang.image')</th>
                                <th style="width: 25%" class="col-sm-8">@lang('lang.products')</th>
                                <th style="width: 25%" class="col-sm-4">@lang('lang.sku')</th>
                                <th style="width: 25%" class="col-sm-4">@lang('lang.batch_number')</th>
                                <th style="width: 25%" class="col-sm-4">@lang('lang.quantity')</th>
                                <th style="width: 12%" class="col-sm-4">@lang('lang.sell_price')</th>
                                <th style="width: 12%" class="col-sm-4">@lang('lang.discount')</th>
                                <th style="width: 12%" class="col-sm-4">@lang('lang.sub_total')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->transaction_sell_lines as $line)
                            <tr>
                                <td><img src="@if (!empty($line->product) && !empty($line->product->getFirstMediaUrl('product'))) {{ $line->product->getFirstMediaUrl('product') }}@else{{ asset('/uploads/' . \Modules\Setting\Entities\System::getProperty('logo')) }} @endif"
                                        alt="photo" width="50" height="50"></td>
                                <td>
                                    {{ $line->product->name ?? '' }}



                                </td>
                                <td>

                                    {{ $line->product->sku ?? '' }}

                                </td>
                                <td>
                                    {{ $line->product->batch_number ?? '' }}
                                </td>
                                <td>
                                    @if (isset($line->quantity))
                                    {{ preg_match('/\.\d*[1-9]+/', (string)$line->quantity) ? $line->quantity :
                                    @num_format($line->quantity) }}@else{{ 1 }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($line->sell_price))
                                    {{ @num_format($line->sell_price) }}@else{{ 0 }}
                                    @endif
                                </td>
                                <td>
                                    @if ($line->product_discount_type != 'surplus')
                                    @if (isset($line->product_discount_amount))
                                    {{ @num_format($line->product_discount_amount) }}@else{{ 0 }}
                                    @endif
                                    @else
                                    {{ @num_format(0) }}
                                    @endif
                                </td>
                                <td>
                                    {{ @num_format($line->sub_total) }}
                                </td>
                            </tr>

                                @if($line->is_lens)
                                    @php
                                        $prescription = $line->prescription;
                                        $total_vu=0;
                                        if($prescription->data != null || $prescription->data != 'null'){
                                            $data_len=json_decode($prescription->data);
                                            if(isset($data_len->VA_amount)){
                                                $total_vu=$data_len->VA_amount->total;
                                            }
                                        }
                                    @endphp

                                    @if($data_len && $total_vu > 0)
                                        <tr class="lens-row">
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
                                                <div  class="lens-vu" >
                                                    @if(isset($data_len->VA->Special->isCheck) && $data_len->VA->Special->isCheck &&  isset($data_len->VA->Special->TV))
                                                        @foreach($data_len->VA->Special->TV as $item_sp)
                                                            <div class="lens-vu-item">
                                                                {{$item_sp->text}}
                                                            </div>
                                                        @endforeach
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
                                                <div  class="lens-vu" >
                                                    @if(isset($data_len->VA->Special->isCheck) && $data_len->VA->Special->isCheck && $data_len->VA_amount->Special_amount >0 && isset($data_len->VA->Special->TV))
                                                        @foreach($data_len->VA->Special->TV as $item_sp)
                                                            <div class="lens-vu-item">
                                                                {{$item_sp->price}}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right"> @lang('lang.total')</th>
                                <td>{{ @num_format($sale->transaction_sell_lines->where('product_discount_type', '!=',
                                    'surplus')->sum('product_discount_amount')) }}
                                </td>
                                <td>{{ @num_format($sale->grand_total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <br>
            <br>

            @if ($sale->transaction_sell_lines)
                @foreach($sale->transaction_sell_lines as $transaction_sell_line)

                    @if($transaction_sell_line->is_lens)

                        @php
                            $prescription = $transaction_sell_line->prescription;
                            $data_prescription = $prescription?json_decode($prescription->data):null;
                        @endphp
                        @if($data_prescription)
                            <div class="row orderInputs">
                                <hr class="hr-lens-show">
                                <div class="col-md-12 dataLens" >
                                    <div class="lens-name">
                                        {{$line->product->name }}
                                    </div>
                                </div>
                                @if(isset($data_prescription->Lens->Right->isCheck) && $data_prescription->Lens->Right->isCheck ==1)
                                <div class="col-md-6">
                                    <div class="form-group h-100">
                                        <table class="table table-bordered text-center" id="Right_Lens_Table"
                                               style="border: 5px solid red;">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <span class="bigLatter">R</span>
                                                </td>
                                                <td colspan="5">




                                                </td>

                                            </tr>

                                            <tr>
                                                <td rowspan="2"
                                                    class="verticalMiddle">{{translate('UZAK')}}</td>
                                                <td>+/-</td>
                                                <td>{{translate('SPH')}}</td>
                                                <td>+/-</td>
                                                <td>{{translate('CYL')}}</td>
                                                <td>{{translate('AXIS')}}</td>
                                            </tr>
                                            <tr>

                                                <td>
                                                    <select name="product[Lens][Right][Far][SPHDeg]"
                                                            class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect"
                                                            data-rl="Right" data-signfor="Right_Far_SPH"
                                                            id="Right_Far_SPHDeg" disabled>
                                                        <option value="+" @if($data_prescription->Lens->Right->Far->SPHDeg=='+') selected @endif>+</option>
                                                        <option value="-"@if($data_prescription->Lens->Right->Far->SPHDeg=='-') selected @endif>-</option>
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="number"
                                                           name="product[Lens][Right][Far][SPH]"
                                                           value="{{$data_prescription->Lens->Right->Far->SPH}}"
                                                           id="Right_Far_SPH"
                                                           data-reqval="farSPH"
                                                           placeholder=" "
                                                           class="form-control input-block-level lensVal lensSPH farSPH number-input"
                                                           data-rl="Right"
                                                           required
                                                           aria-required="true"
                                                           step="0.25"
                                                           min="-30"
                                                           max="30" disabled>

                                                </td>
                                                <td>
                                                    <select name="product[Lens][Right][Far][CYLDeg]"
                                                            class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect"
                                                            data-signfor="Right_Far_CYL" data-rl="Right"
                                                            id="Right_Far_CYLDeg" disabled>
                                                        <option value="+" @if($data_prescription->Lens->Right->Far->CYLDeg=='+') selected @endif>+</option>
                                                        <option value="-" @if($data_prescription->Lens->Right->Far->CYLDeg=='-') selected @endif>-</option>
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="number"
                                                           name="product[Lens][Right][Far][CYL]"
                                                           value="{{$data_prescription->Lens->Right->Far->CYL}}"
                                                           id="Right_Far_CYL"
                                                           data-reqval="farCYL"
                                                           placeholder=" "
                                                           class="form-control input-block-level lensVal lensCYL farCYL number-input"
                                                           data-rl="Right"
                                                           required
                                                           aria-required="true"
                                                           step="0.25"
                                                           min="-15"
                                                           max="15" disabled>
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="product[Lens][Right][Far][Axis]"
                                                        data-reqval="farAX"
                                                        value="{{$data_prescription->Lens->Right->Far->Axis}}"
                                                        id="Right_Far_Axis"
                                                        placeholder=" "
                                                        class="form-control input-block-level input-sm lensAxis farAX  number-input"
                                                        data-rl="Right"
                                                        step="1"
                                                        min="0"
                                                        max="180" disabled>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td rowspan="2"
                                                    class="verticalMiddle">{{translate('YAKIN')}}</td>
                                                <td>+/-</td>
                                                <td>{{translate('SPH')}}</td>
                                                <td>+/-</td>
                                                <td>{{translate('CYL')}}</td>
                                                <td>{{translate('AXIS')}}</td>

                                            </tr>
                                            <tr class="nearTableRow">
                                                <td>
                                                    <select name="product[Lens][Right][Near][SPHDeg]"
                                                            class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect"
                                                            data-signfor="Right_Near_SPH"
                                                            data-rl="Right"
                                                            id="Right_Near_SPHDeg" disabled>
                                                        <option value="+"@if($data_prescription->Lens->Right->Near->SPHDeg=='+') selected @endif>+</option>
                                                        <option value="-"@if($data_prescription->Lens->Right->Near->SPHDeg=='-') selected @endif>-</option>
                                                    </select>
                                                </td>
                                                <td>


                                                    <input type="number"
                                                           name="product[Lens][Right][Near][SPH]"
                                                           value="{{$data_prescription->Lens->Right->Near->SPH}}"
                                                           id="Right_Near_SPH"
                                                           data-reqval="nearSPH"
                                                           placeholder=" "
                                                           class="form-control input-block-level lensVal lensSPH  number-input"
                                                           data-rl="Right"
                                                           required
                                                           aria-required="true"
                                                           step="0.25"
                                                           min="-30"
                                                           max="30" disabled>
                                                </td>
                                                <td>
                                                    <select name="product[Lens][Right][Near][CYLDeg]"
                                                            class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect"
                                                            data-rl="Right" data-signfor="Right_Near_CYL"
                                                            id="Right_Near_CYLDeg" disabled>
                                                        <option value="+"@if($data_prescription->Lens->Right->Near->CYLDeg=='+') selected @endif>+</option>
                                                        <option value="-" @if($data_prescription->Lens->Right->Near->CYLDeg=='-') selected @endif>-</option>

                                                    </select>

                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="product[Lens][Right][Near][CYL]"
                                                        data-reqval="nearCYL"
                                                        value="{{$data_prescription->Lens->Right->Near->CYL}}"
                                                        id="Right_Near_CYL"
                                                        placeholder=" "
                                                        class="form-control input-block-level lensVal lensCYL  number-input"
                                                        data-rl="Right"
                                                        step="0.25"
                                                        min="-15"
                                                        max="15" disabled>

                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="product[Lens][Right][Near][Axis]"
                                                        data-reqval="nearAX"
                                                        value="{{$data_prescription->Lens->Right->Near->Axis}}"
                                                        id="Right_Near_Axis"
                                                        placeholder=" "
                                                        class="form-control input-block-level lensAxis nearAX  number-input"
                                                        data-rl="Right"
                                                        step="1"
                                                        min="0"
                                                        max="180" disabled>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>{{translate('Adisyon')}}</td>
                                                <td colspan="5">
                                                    <div class="col-md-5 noPadding">
                                                        <input type="number"
                                                               name="product[Lens][Right][Addition]"
                                                               data-reqval="addVal"
                                                               id="Right_Addition"
                                                               placeholder=""
                                                               value="{{ ($data_prescription->Lens->Right->Far->SPH?:0) - ($data_prescription->Lens->Right->Near->SPH?:0)}}"
                                                               class="form-control input-block-level lensAddition lensVal"
                                                               data-rl="Right"
                                                               required=""
                                                               aria-required="true"
                                                               disabled>
                                                    </div>

                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                @if(isset($data_prescription->Lens->Left->isCheck) && $data_prescription->Lens->Left->isCheck ==1)
                                    <div class="col-md-6">

                                        <div class="form-group h-100">
                                            <table class="table table-bordered text-center" id="Left_Lens_Table"
                                                   style="border: 5px solid red;">
                                                <tbody>
                                                <tr>
                                                    <td class="lensLatterCell">
                                                        <span class="bigLatter">L</span>


                                                    </td>
                                                    <td colspan="5">
                                                        <div class="row" style="padding-left: 0;">
                                                            <div class="col-xs-12">

                                                                <div class="check-line"
                                                                     style="width: 100%;text-align: left;">
                                                                    <div
                                                                        class="icheckbox_square-orange icheck-item ">
                                                                        <input type="checkbox" id="sameToRight"
                                                                               class="icheck-me popover2 icheck-input icheck[veqwi]"
                                                                               data-popover="&lt;Bilgi|Sağ cam sol cam ile aynı olsun|left"
                                                                               name="product[Lens][Left][sameToRight]"
                                                                               value="1"
                                                                               {{$data_prescription->Lens->Left->sameToRight?'checked':''}}
                                                                               data-skin="square"
                                                                               data-color="orange"
                                                                               data-original-title="" title=""
                                                                               disabled>
                                                                    </div>
                                                                    <label class="inline icheck-label "
                                                                           for="sameToRight">
                                                                        {{translate('Left glass is the same as right glass')}}
                                                                    </label>


                                                                </div>

                                                            </div>

                                                        </div>

                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td rowspan="2"
                                                        class="verticalMiddle">{{translate('UZAK')}}</td>
                                                    <td>+/-</td>
                                                    <td>{{translate('SPH')}}</td>
                                                    <td>+/-</td>
                                                    <td>{{translate('CYL')}}</td>
                                                    <td>{{translate('AXIS')}}</td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="product[Lens][Left][Far][SPHDeg]"
                                                                class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect"
                                                                id="Left_Far_SPHDeg" data-rl="Left"
                                                                data-signfor="Left_Far_SPH" disabled="">
                                                            <option value="+" @if($data_prescription->Lens->Left->Near->CYLDeg=='+') selected @endif>+</option>
                                                            <option value="-" @if($data_prescription->Lens->Left->Near->CYLDeg=='-') selected @endif>-</option>

                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input type="number" name="product[Lens][Left][Far][SPH]"
                                                               data-reqval="farSPH"
                                                               value="{{$data_prescription->Lens->Left->Far->SPH}}"
                                                               id="Left_Far_SPH"
                                                               placeholder=" "
                                                               class="form-control input-block-level lensVal lensSPH farSPH number-input"
                                                               data-rl="Left" required="" aria-required="true"
                                                               disabled=""
                                                               step="0.25"
                                                               min="-30"
                                                               max="30">
                                                    </td>
                                                    <td>
                                                        <select name="product[Lens][Left][Far][CYLDeg]"
                                                                class="form-control lensPlusMinusSelect CYLPlusMinusSelect input-block-level"
                                                                style="width: 100%" data-rl="Left"
                                                                id="Left_Far_CYLDeg" disabled="">
                                                            <option value="+" @if($data_prescription->Lens->Left->Far->CYLDeg=='+') selected @endif>+</option>
                                                            <option value="-" @if($data_prescription->Lens->Left->Far->CYLDeg=='-') selected @endif>-</option>

                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input type="number" name="product[Lens][Left][Far][CYL]"
                                                               data-reqval="farCYL"
                                                               value="{{$data_prescription->Lens->Left->Far->CYL}}"
                                                               id="Left_Far_CYL"
                                                               placeholder=" "
                                                               class="form-control input-block-level input-sm lensVal lensCYL farCYL number-input"
                                                               data-rl="Left" disabled=""
                                                               step="0.25"
                                                               min="-15"
                                                               max="15">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="product[Lens][Left][Far][Axis]"
                                                               data-reqval="farAX"
                                                               value="{{$data_prescription->Lens->Left->Far->Axis}}"
                                                               id="Left_Far_Axis"
                                                               placeholder=" "
                                                               class="form-control input-block-level input-sm lensAxis farAX number-input"
                                                               data-rl="Left" disabled=""
                                                               step="1"
                                                               min="0"
                                                               max="180">
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td rowspan="2"
                                                        class="verticalMiddle">{{translate('YAKIN')}}</td>
                                                    <td>+/-</td>
                                                    <td>{{translate('SPH')}}</td>
                                                    <td>+/-</td>
                                                    <td>{{translate('CYL')}}</td>
                                                    <td>{{translate('AXIS')}}</td>

                                                </tr>
                                                <tr class="nearTableRow">
                                                    <td>
                                                        <select name="product[Lens][Left][Near][SPHDeg]"
                                                                class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect"
                                                                id="Left_Near_SPHDeg" data-rl="Left"
                                                                data-signfor="Left_Near_SPH" disabled="">
                                                            <option value="+" @if($data_prescription->Lens->Left->Near->SPHDeg == '+') selected @endif>+</option>
                                                            <option value="-" @if($data_prescription->Lens->Left->Near->SPHDeg == '-') selected @endif>-</option>

                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input type="number" name="product[Lens][Left][Near][SPH]"
                                                               data-reqval="nearSPH"
                                                               value="{{$data_prescription->Lens->Left->Near->SPH}}"
                                                               id="Left_Near_SPH"
                                                               placeholder=" "
                                                               class="form-control input-block-level lensVal lensSPH  number-input"
                                                               data-rl="Left" disabled=""
                                                               step="0.25"
                                                               min="-30"
                                                               max="30">
                                                    </td>
                                                    <td>
                                                        <select name="product[Lens][Left][Near][CYLDeg]"
                                                                class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect"
                                                                data-rl="Left" data-signfor="Left_Near_CYL"
                                                                id="Left_Near_CYLDeg" disabled="">
                                                            <option value="+" @if($data_prescription->Lens->Left->Near->CYLDeg=='+') selected @endif>+</option>
                                                            <option value="-" @if($data_prescription->Lens->Left->Near->CYLDeg=='-') selected @endif>-</option>

                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input type="number" name="product[Lens][Left][Near][CYL]"
                                                               data-reqval="nearCYL"
                                                               value="{{$data_prescription->Lens->Left->Near->CYL}}"
                                                               id="Left_Near_CYL"
                                                               placeholder=" "
                                                               class="form-control input-block-level lensVal lensCYL number-input"
                                                               data-rl="Left"
                                                               disabled=""
                                                               step="0.25"
                                                               min="-15"
                                                               max="15">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="product[Lens][Left][Near][Axis]"
                                                               data-reqval="nearAX"
                                                               value="{{$data_prescription->Lens->Left->Near->Axis}}"
                                                               id="Left_Near_Axis"
                                                               placeholder=" "
                                                               class="form-control input-block-level lensAxis nearAX number-input"
                                                               data-rl="Left" disabled=""
                                                               step="1"
                                                               min="0"
                                                               max="180">
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>{{translate('Adisyon')}}
                                                    </td>
                                                    <td colspan="5">
                                                        <div class="col-md-5 noPadding">
                                                            <input type="number" name="product[Lens][Left][Addition]"
                                                                   data-reqval="addVal"
                                                                   value="{{ ($data_prescription->Lens->Left->Far->SPH?:0) - ($data_prescription->Lens->Left->Near->SPH?:0)}}"
                                                                   id="Left_Addition"
                                                                   placeholder=""
                                                                   class="form-control input-block-level lensAddition lensVal number-input"
                                                                   data-rl="Left" required="" aria-required="true"
                                                                   disabled

                                                            >

                                                        </div>
                                                    </td>

                                                </tr>

                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endif

                    @endif

                @endforeach
            @endif
            @include('addstock::back-end.transaction_payment.partials.payment_table', [
            'payments' => $sale->transaction_payments,
            ])

            <br>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <h4>@lang('lang.sale_note'):</h4>
                        <p>{{ $sale->sale_note }}</p>
                    </div>
                    <div class="col-md-12">
                        <h4>@lang('lang.staff_note'):</h4>
                        <p>{{ $sale->staff_note }}</p>
                    </div>
                    <div class="col-md-12">
                        <h4>@lang('lang.payment_note'):</h4>
                        @foreach($sale->transaction_payments as $payment )
                        @if(isset($payment->payment_note))
                        <p> - {{ $payment->payment_note }}</p>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>@lang('lang.total_tax'):</th>
                            <td>{{ @num_format($sale->total_tax + $sale->total_item_tax) }}</td>
                        </tr>
                        @if ($sale->transaction_sell_lines->where('product_discount_type', '!=',
                        'surplus')->sum('product_discount_amount') > 0)
                        <tr>
                            <th>@lang('lang.discount')</th>
                            <td>
                                {{ @num_format($sale->transaction_sell_lines->where('product_discount_type', '!=',
                                'surplus')->sum('product_discount_amount')) }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>@lang('lang.order_discount'):</th>
                            <td>{{ @num_format($sale->discount_amount) }}</td>
                        </tr>
                        @if (!empty($sale->rp_earned))
                        <tr>
                            <th>@lang('lang.point_earned'):</th>
                            <td>{{ @num_format($sale->rp_earned) }}</td>
                        </tr>
                        @endif
                        @if (!empty($sale->rp_redeemed_value))
                        <tr>
                            <th>@lang('lang.redeemed_point_value'):</th>
                            <td>{{ @num_format($sale->rp_redeemed_value) }}</td>
                        </tr>
                        @endif
                        @if ($sale->total_coupon_discount > 0)
                        <tr>
                            <th>@lang('lang.coupon_discount')</th>
                            <td>{{ @num_format($sale->total_coupon_discount) }}</td>
                        </tr>
                        @endif
                        @if ($sale->delivery_cost > 0)
                        <tr>
                            <th>@lang('lang.delivery_cost')</th>
                            <td>{{ @num_format($sale->delivery_cost) }}</td>
                        </tr>
                        @endif
                        @if ($sale->service_fee_value > 0)
                        <tr>
                            <th>@lang('lang.service')</th>
                            <td>{{ @num_format($sale->service_fee_value) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>@lang('lang.grand_total'):</th>
                            <td>{{ @num_format($sale->final_total) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('lang.paid_amount'):</th>
                            <td>{{ @num_format($sale->transaction_payments->sum('amount')) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('lang.due'):</th>
                            <td> {{ @num_format($sale->final_total - $sale->transaction_payments->sum('amount')) }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
                    <b>@lang('lang.terms_and_conditions'):</b>
                    @if (!empty($sale->terms_and_conditions))
                    {!! $sale->terms_and_conditions->description !!}
                    @endif
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <a data-href="{{ route('admin.sale.print', $sale->id) }}"
                class="btn btn-primary col-6 text-white print-invoice"><i class="dripicons-print"></i>
                @lang('lang.print')</a>
            <button type="button" class="btn btn-outline-danger col-6" data-dismiss="modal">@lang('lang.close')</button>
        </div>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
