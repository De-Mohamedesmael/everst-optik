<style>
    .check-line {
        display: flex;
    }
    table#Right_Lens_Table ,  table#Left_Lens_Table{
        height: 100%;
    }
    span.bigLatter {
        font-size: 30px;
        font-weight: 700;
        color: #696969;
        text-shadow: -1px 2px 4px #979797;
    }
    .icheckbox_square-orange {
        padding: 0 10px;
    }
    .tab-pane .table-bordered>:not(caption)>* {
        border-width:  0 !important;
    }
    .tab-pane input[type="number"]::-webkit-inner-spin-button,
    .tab-pane input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
<div id="navigation">
    <div class="container-fluid" id="content">
        <div id="main" style="margin-left: 0px;">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="col-xs-12" id="formHorizontalDiv">
                        <div class="box box-color ">
                            <div class="box-content">

                                <form action="#" method="post" id="orderForm" class="form-horizontal form-validate" novalidate="novalidate">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('brand_id', translate('Marka'), [
                                                    'class' => 'form-label d-block mb-1 ',
                                                ]) !!}
                                                {!! Form::select('brand_id', $brand_lenses,null, [
                                                    'class' => ' selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                   'data-actions-box' => 'true',
                                                    'id' => 'brand_id',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('focus_id', translate('focus'), [
                                                   'class' => 'form-label d-block mb-1 ',
                                               ]) !!}
                                                {!! Form::select('focus_id', $foci,null, [
                                                    'class' => ' selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                   'data-actions-box' => 'true',
                                                    'id' => 'focus_id',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                {!! Form::label('design_id', translate('design'), [
                                                   'class' => 'form-label d-block mb-1 ',
                                               ]) !!}
                                                {!! Form::select('design_id', $design_lenses,null, [
                                                    'class' => ' selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                   'data-actions-box' => 'true',
                                                    'id' => 'design_id',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                {!! Form::label('index_id', translate('index'), [
                                                    'class' => 'form-label d-block mb-1 ',
                                                ]) !!}
                                                {!! Form::select('index_id', $index_lenses,null, [
                                                    'class' => ' selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                   'data-actions-box' => 'true',
                                                    'id' => 'index_id',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('color_id', translate('color'), [
                                                    'class' => 'form-label d-block mb-1 ',
                                                ]) !!}
                                                {!! Form::select('color_id', $colors,null, [
                                                    'class' => ' selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                   'data-actions-box' => 'true',
                                                    'id' => 'color_id',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>
{{--                                        lenses--}}
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                {!! Form::label('lens_id', translate('lens'), [
                                                   'class' => 'form-label d-block mb-1 ',
                                               ]) !!}
                                                {!! Form::select('lens_id', $lenses,null, [
                                                    'class' => ' selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                   'data-actions-box' => 'true',
                                                    'id' => 'lens_id',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <!--product filter END-->
                                    <!--middle row-->
                                    <div class="row orderInputs">
                                        <div class="col-md-6">
                                            <div class="form-group h-100">
                                                <table class="table table-bordered text-center" id="Right_Lens_Table" style="border: 5px solid red;">
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="bigLatter">R</span>
                                                        </td>
                                                        <td colspan="5">


                                                            <div class="check-line" style="width: 100%;text-align: left;">
                                                                <div class="icheckbox_square-orange icheck-item  checked">
                                                                    <input type="checkbox" id="RightLens" class="icheck-me checkForLens icheck-input icheck[ho025]" checked="" name="product[Lens][Right][isCheck]" value="1" data-skin="square" data-color="orange" data-rl="Right" >
                                                                </div>
                                                                <label class="inline icheck-label " for="RightLens">
                                                                    {{translate('I Want  Right Glass')}}
                                                                </label>

                                                            </div>

                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">{{translate('UZAK')}}</td>
                                                        <td>+/-</td>
                                                        <td>{{translate('SPH')}}</td>
                                                        <td>+/-</td>
                                                        <td>{{translate('CYL')}}</td>
                                                        <td>{{translate('AXIS')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="product[Lens][Right][Far][SPHDeg]" class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect" data-rl="Right" data-signfor="Right_Far_SPH" id="Right_Far_SPHDeg">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>
                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                   name="product[Lens][Right][Far][SPH]"
                                                                   value=""
                                                                   id="Right_Far_SPH"
                                                                   data-reqval="farSPH"
                                                                   placeholder=" "
                                                                   class="form-control input-block-level lensVal lensSPH farSPH number-input"
                                                                   data-rl="Right"
                                                                   required
                                                                   aria-required="true"
                                                                   step="0.5"
                                                                   min="-16"
                                                                   max="16">

{{--                                                            <input type="number" name="product[Lens][Right][Far][SPH]" value="" id="Right_Far_SPH" data-reqval="farSPH" placeholder=" " class="form-control input-block-level lensVal lensSPH farSPH" data-rl="Right" required="" aria-required="true" step="0.5">--}}
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Right][Far][CYLDeg]" class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect" data-signfor="Right_Far_CYL" data-rl="Right" id="Right_Far_CYLDeg">
                                                                <option value="+">+</option>
                                                                <option value="-" selected="selected">-</option>
                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                   name="product[Lens][Right][Far][CYL]"
                                                                   value=""
                                                                   id="Right_Far_CYL"
                                                                   data-reqval="farCYL"
                                                                   placeholder=" "
                                                                   class="form-control input-block-level lensVal lensCYL farCYL number-input"
                                                                   data-rl="Right"
                                                                   required
                                                                   aria-required="true"
                                                                   step="0.5"
                                                                   min="-15"
                                                                   max="15">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="product[Lens][Right][Far][Axis]" data-reqval="farAX" value="0" id="Right_Far_Axis" placeholder=" " class="form-control input-block-level input-sm lensAxis farAX valid" data-rl="Right">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">{{translate('YAKIN')}}</td>
                                                        <td>+/-</td>
                                                        <td>SPH</td>
                                                        <td>+/-</td>
                                                        <td>CYL</td>
                                                        <td>AXIS</td>

                                                    </tr>
                                                    <tr class="nearTableRow">
                                                        <td>
                                                            <select name="product[Lens][Right][Near][SPHDeg]" class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect" data-signfor="Right_Near_SPH" data-rl="Right" id="Right_Near_SPHDeg">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="product[Lens][Right][Near][SPH]" data-reqval="nearSPH" value="" id="Right_Near_SPH" placeholder=" " class="form-control input-block-level lensVal lensSPH" data-rl="Right">
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Right][Near][CYLDeg]" class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect" data-rl="Right" data-signfor="Right_Near_CYL" id="Right_Near_CYLDeg">
                                                                <option value="+">+</option>
                                                                <option value="-" selected="selected">-</option>

                                                            </select>

                                                        </td>
                                                        <td><input type="text" name="product[Lens][Right][Near][CYL]" data-reqval="nearCYL" value="" id="Right_Near_CYL" placeholder=" " class="form-control input-block-level lensVal lensCYL" data-rl="Right">
                                                        </td>
                                                        <td><input type="text" name="product[Lens][Right][Near][Axis]" data-reqval="nearAX" value="" id="Right_Near_Axis" placeholder=" " class="form-control input-block-level lensAxis nearAX" data-rl="Right">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{translate('Adisyon')}}</td>
                                                        <td colspan="5">
                                                            <div class="col-md-5 noPadding">
                                                                <input type="text" name="product[Lens][Right][Addition]" data-reqval="addVal" value="" id="Right_Addition" placeholder="" class="form-control input-block-level lensAddition lensVal" data-rl="Right" required="" aria-required="true">
                                                            </div>
                                                            <div class="col-xs-8" style="padding-right: 0;">
                                                                <select name="product[Lens][Right][Diameter]" required="" class="select2-me text-left lensDiam select2-hidden-accessible" style="width: 100%" data-rl="Right" id="Right_Lens_Diam" aria-required="true" tabindex="-1" aria-hidden="true" data-select2-id="Right_Lens_Diam">
                                                                    <option value="" data-select2-id="135">{{translate('Çap Seçiniz')}}</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group h-100">
                                                <table class="table table-bordered text-center" id="Left_Lens_Table" style="border: 5px solid red;">
                                                    <tbody><tr>
                                                        <td class="lensLatterCell">
                                                            <span class="bigLatter">L</span>


                                                        </td>
                                                        <td colspan="5">
                                                            <div class="row" style="padding-left: 0;">
                                                                <div class="col-xs-12">
                                                                    <div class="check-line" style="width: 100%;text-align: left;">
                                                                        <div class="icheckbox_square-orange icheck-item ">
                                                                            <input type="checkbox" id="LeftLens" class="icheck-me checkForLens icheck-input " name="product[Lens][Left][isCheck]" value="1" data-skin="square" data-color="orange" data-rl="Left">
                                                                        </div>
                                                                        <label class="inline icheck-label " for="LeftLens">
                                                                            {{translate('I Want Left Glass')}}
                                                                        </label>

                                                                    </div>
                                                                    <div class="check-line" style="width: 100%;text-align: left;">
                                                                        <div class="icheckbox_square-orange icheck-item ">
                                                                            <input type="checkbox" id="sameToRight" class="icheck-me popover2 icheck-input icheck[veqwi]" data-popover="&lt;Bilgi|Sağ cam sol cam ile aynı olsun|left" name="product[Lens][Left][sameToRight]" value="1" data-skin="square" data-color="orange" data-original-title="" title="" disabled="">
                                                                        </div>
                                                                        <label class="inline icheck-label " for="sameToRight">
                                                                            {{translate('Left glass is the same as right glass')}}
                                                                        </label>


                                                                    </div>

                                                                </div>

                                                            </div>


                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">{{translate('UZAK')}}</td>
                                                        <td>+/-</td>
                                                        <td>{{translate('SPH')}}</td>
                                                        <td>+/-</td>
                                                        <td>{{translate('CYL')}}</td>
                                                        <td>{{translate('AXIS')}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="product[Lens][Left][Far][SPHDeg]" class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect" id="Left_Far_SPHDeg" data-rl="Left" data-signfor="Left_Far_SPH" disabled="">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>

                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input type="text" name="product[Lens][Left][Far][SPH]" data-reqval="farSPH" value="" id="Left_Far_SPH" placeholder=" " class="form-control input-block-level lensVal lensSPH farSPH" data-rl="Left" required="" aria-required="true" disabled="">
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Left][Far][CYLDeg]" class="form-control lensPlusMinusSelect CYLPlusMinusSelect input-block-level" style="width: 100%" data-rl="Left" id="Left_Far_CYLDeg" disabled="">
                                                                <option value="+">+</option>
                                                                <option value="-" selected="selected">-</option>

                                                            </select>

                                                        </td>
                                                        <td><input type="text" name="product[Lens][Left][Far][CYL]" data-reqval="farCYL" value="" id="Left_Far_CYL" placeholder=" " class="form-control input-block-level input-sm lensVal lensCYL farCYL" data-rl="Left" disabled="">
                                                        </td>
                                                        <td><input type="text" name="product[Lens][Left][Far][Axis]" data-reqval="farAX" value="0" id="Left_Far_Axis" placeholder=" " class="form-control input-block-level input-sm lensAxis farAX valid" data-rl="Left" disabled="">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">{{translate('YAKIN')}}</td>
                                                        <td>+/-</td>
                                                        <td>{{translate('SPH')}}</td>
                                                        <td>+/-</td>
                                                        <td>{{translate('CYL')}}</td>
                                                        <td>{{translate('AXIS')}}</td>

                                                    </tr>
                                                    <tr class="nearTableRow">
                                                        <td>
                                                            <select name="product[Lens][Left][Near][SPHDeg]" class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect" id="Left_Near_SPHDeg" data-rl="Left" data-signfor="Left_Near_SPH" disabled="">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>

                                                            </select>

                                                        </td>
                                                        <td><input type="text" name="product[Lens][Left][Near][SPH]" data-reqval="nearSPH" value="" id="Left_Near_SPH" placeholder=" " class="form-control input-block-level lensVal lensSPH" data-rl="Left" disabled="">
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Left][Near][CYLDeg]" class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect" data-rl="Left" data-signfor="Left_Near_CYL" id="Left_Near_CYLDeg" disabled="">
                                                                <option value="+">+</option>
                                                                <option value="-" selected="selected">-</option>

                                                            </select>

                                                        </td>
                                                        <td><input type="text" name="product[Lens][Left][Near][CYL]" data-reqval="nearCYL" value="" id="Left_Near_CYL" placeholder=" " class="form-control input-block-level lensVal lensCYL" data-rl="Left" disabled="">
                                                        </td>
                                                        <td><input type="text" name="product[Lens][Left][Near][Axis]" data-reqval="nearAX" value="" id="Left_Near_Axis" placeholder=" " class="form-control input-block-level lensAxis nearAX" data-rl="Left" disabled="">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{translate('Adisyon')}}
                                                        </td>
                                                        <td colspan="5">
                                                            <div class="col-md-5 noPadding">
                                                                <input type="text" name="product[Lens][Left][Addition]" data-reqval="addVal" value="" id="Left_Addition" placeholder="" class="form-control input-block-level lensAddition lensVal" data-rl="Left" required="" aria-required="true" disabled="">

                                                            </div>
                                                            <div>
                                                                <div class="col-xs-8" style="padding-right: 0;">
                                                                    <select name="product[Lens][Left][Diameter]" required="" class="select2-me text-left lensDiam select2-hidden-accessible" style="width: 100%" data-rl="Right" id="Left_Lens_Diam" aria-required="true" tabindex="-1" aria-hidden="true" disabled="" data-select2-id="Left_Lens_Diam">
                                                                        <option value="" data-select2-id="137">{{translate('Çap Seçiniz')}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                        </td>

                                                    </tr>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    //brand_lens => products
    //focus_id => design_id & products
    //design_id =>  products
    //index_id =>  products
    //color_id =>  products

    $('#LeftLens').on('change', function () {
        var cke;
        if (this.checked) {
            $('#sameToRight').attr('disabled', false);
            $('#Left_Far_SPH').attr('disabled', false);
        } else {
            cke = $('#sameToRight');
            cke.attr('disabled', true);
            cke.prop('checked', false);
            $('#Left_Far_SPH').attr('disabled', true);
            $('#Left_Far_SPH').val('');

        }

    });
    $(document).on("change", "#brand_id , #focus_id,#design_id,#index_id,#color_id", function () {

        var brand_id = $('#brand_id').val();
        var focus_id = $('#focus_id').val();
        var design_id = $('#design_id').val();
        var index_id = $('#index_id').val();
        var color_id = $('#color_id').val();


        $.ajax({
            method: "get",
            url: "/dashboard/lenses/get-dropdown-filter-lenses",
            data: {
                brand_id:brand_id,
                focus_id:focus_id,
                design_id:design_id,
                index_id:index_id,
                color_id:color_id,
            },
            // contactType: "html",
            success: function (result) {
                if(result.success){
                    $("#lens_id").empty().append(result.data.lenses);
                    $("#lens_id").selectpicker("refresh");
                    $("#design_id").empty().append(result.data.designs);
                    $("#design_id").selectpicker("refresh");
                }
                if (design_id) {
                    $("#design_id").selectpicker("val", design_id);
                }

                // $("#brand_id").empty().append(data_html);
                // $("#brand_id").selectpicker("refresh");
                // if (brand_id) {
                //     $("#brand_id").selectpicker("val", brand_id);
                // }
            },
        });
    })
    // document.getElementById('Right_Far_SPH').addEventListener('input', function() {
    //     const min = parseFloat(this.min);
    //     const max = parseFloat(this.max);
    //     const value = parseFloat(this.value);
    //
    //     if (value < min) {
    //         this.value = min;
    //     } else if (value > max) {
    //         this.value = max;
    //     }
    // });

    document.querySelectorAll('.number-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const min = parseFloat(this.min);
            const max = parseFloat(this.max);
            const value = parseFloat(this.value);

            if (value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
            }
        });
    });


</script>
