@php
    $data_prescription = $prescription?json_decode($prescription->data):null;
@endphp
<style>
    .row {
        width: 100%;
        display: inline-flex;
    }
    .table-bordered>:not(caption)>*>* {
        border-width: 0 var(--bs-border-width);
    }
    .table>:not(caption)>*>* {
        padding: .5rem .5rem;
        color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
        background-color: var(--bs-table-bg);
        border-bottom-width: var(--bs-border-width);
        box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
    }
    .table-bordered td, .table-bordered th {
        border-color: #e4e6fc;
    }

    .table td {
        border-bottom: 1px solid #4e0505;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
    }
    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    .table td, .table th {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
    }
    .table-bordered td, .table-bordered th {
        border-color: #e4e6fc;
    }
    .table-bordered td {
        border: 1px solid rgb(203, 32, 32);
    }
</style>

@if($data_prescription)
    <div class="row dataLens" style="padding-bottom: 15px">
        <div class="lens-name">
            &ensp;&ensp;&ensp; {{translate('lens')}} : {{$prescription->product?->name }}
            &ensp;&ensp;&ensp; | &ensp;&ensp;&ensp;
            @if(isset($data_prescription->VA))
                @if(isset($data_prescription->VA->TinTing))
                &ensp;&ensp;&ensp; {{translate('TinTing')}} : {{$data_prescription->VA->TinTing?->text }}
                &ensp;&ensp;&ensp; | &ensp;&ensp;&ensp;
               @endif
               @if(isset($data_prescription->VA->Base))
                   &ensp;&ensp;&ensp; {{translate('Base')}} : {{$data_prescription->VA->Base?->text }}
                    &ensp;&ensp;&ensp; | &ensp;&ensp;&ensp;
               @endif
               @if(isset($data_prescription->VA->Ozel))
                    &ensp;&ensp;&ensp; {{translate('Ozel')}} : {{$data_prescription->VA->Ozel?->text }}
                    &ensp;&ensp;&ensp; | &ensp;&ensp;&ensp;
               @endif
            @endif
        </div>
    </div>
    <hr class="hr-lens-show">
    @if((isset($data_prescription->VA->code) && isset($data_prescription->VA->code->isCheck)  ) || isset($data_prescription->VA->Special))
        <div class="row dataLens" style="padding-bottom: 15px">
            <div class="lens-name">
                @if(isset($data_prescription->VA))
                    @if(isset($data_prescription->VA->code) && isset($data_prescription->VA->code->isCheck))
                        &ensp;&ensp;&ensp; {{translate('code')}} : {{$data_prescription->VA->code?->text }}
                &ensp;&ensp;&ensp; | &ensp;&ensp;&ensp;
                @endif
                @if(isset($data_prescription->VA->Special))
                    @if(isset($data_prescription->VA->Special->TV))
                        @foreach($data_prescription->VA->Special->TV as $key => $value)
                            &ensp;&ensp;&ensp; {{$value->text }}
                            &ensp;&ensp;&ensp; | &ensp;&ensp;&ensp;
                        @endforeach
                   @endif
                @endif
                @endif
            </div>
        </div>
        <hr class="hr-lens-show">
    @endif
    <div class="row orderInputs">

        @if((isset($data_prescription->Lens->Right->isCheck) && $data_prescription->Lens->Right->isCheck ==1) || isset($data_prescription->Lens->Right->Far->SPH))
            <div class="col-md-6"style="padding: 0 15px;">
                <div class="form-group h-100">
                    <table class="table table-bordered text-center" id="Right_Lens_Table"
                           style="border: 5px solid red;">
                        <tbody>
                        <tr>
                            <td style="

                            border: none !important;

                            ">
                                <span class="bigLatter">R</span>
                            </td>

                            <td colspan="5" style="

    border: none !important;

">




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
                                    <option value="" @if($data_prescription->Lens->Right->Far->SPHDeg=='') selected @endif></option>
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
                                    <option value="" @if($data_prescription->Lens->Right->Far->CYLDeg=='') selected @endif></option>
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
                                    <option value="" @if($data_prescription->Lens->Right->Near->SPHDeg=='') selected @endif></option>
                                    <option value="+" @if($data_prescription->Lens->Right->Near->SPHDeg=='+') selected @endif>+</option>
                                    <option value="-" @if($data_prescription->Lens->Right->Near->SPHDeg=='-') selected @endif>-</option>
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
                                    <option value=""@if($data_prescription->Lens->Right->Near->CYLDeg=='') selected @endif></option>
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
            <div class="col-md-6" style="padding: 0 15px;">

                <div class="form-group h-100">
                    <table class="table table-bordered text-center" id="Left_Lens_Table"
                           style="border: 5px solid red;">
                        <tbody>
                        <tr>
                            <td class="lensLatterCell" style="

    border: none !important;

">
                                <span class="bigLatter">L</span>


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
                                    <option value="" @if($data_prescription->Lens->Left->Near->CYLDeg=='') selected @endif></option>
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
                                    <option value="" @if($data_prescription->Lens->Left->Far->CYLDeg=='') selected @endif></option>
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
                                    <option value="" @if($data_prescription->Lens->Left->Near->SPHDeg == '') selected @endif></option>
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
                                    <option value="" @if($data_prescription->Lens->Left->Near->CYLDeg=='') selected @endif></option>
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
