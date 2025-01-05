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
                                                        <td><span class="bigLatter">R</span></td>
                                                        <td colspan="5">


                                                            <div class="check-line" style="width: 100%;text-align: left;">
                                                                <div class="icheckbox_square-orange icheck-item icheck[ho025] checked"><input type="checkbox" id="RightLens" class="icheck-me checkForLens icheck-input icheck[ho025]" checked="" name="product[Lens][Right][isCheck]" value="1" data-skin="square" data-color="orange" data-rl="Right">
                                                                </div>
                                                                <label class="inline icheck-label icheck[ho025]" for="RightLens">Sağ Cam İstiyorum
                                                                </label>

                                                            </div>

                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">UZAK
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>SPH
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>CYL
                                                        </td>
                                                        <td>AXIS
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="product[Lens][Right][Far][SPHDeg]" class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect" data-rl="Right" data-signfor="Right_Far_SPH" id="Right_Far_SPHDeg">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>
                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input type="text" name="product[Lens][Right][Far][SPH]" value="" id="Right_Far_SPH" data-reqval="farSPH" placeholder=" " class="form-control input-block-level lensVal lensSPH farSPH" data-rl="Right" required="" aria-required="true">
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Right][Far][CYLDeg]" class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect" data-signfor="Right_Far_CYL" data-rl="Right" id="Right_Far_CYLDeg">
                                                                <option value="+">+</option>
                                                                <option value="-" selected="selected">-</option>
                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input type="text" name="product[Lens][Right][Far][CYL]" data-reqval="farCYL" value="" id="Right_Far_CYL" placeholder=" " class="form-control input-block-level input-sm lensVal lensCYL farCYL" data-rl="Right">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="product[Lens][Right][Far][Axis]" data-reqval="farAX" value="0" id="Right_Far_Axis" placeholder=" " class="form-control input-block-level input-sm lensAxis farAX valid" data-rl="Right">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">YAKIN
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>SPH
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>CYL
                                                        </td>
                                                        <td>AXIS
                                                        </td>

                                                    </tr>
                                                    <tr class="nearTableRow">
                                                        <td>
                                                            <select name="product[Lens][Right][Near][SPHDeg]" class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect" data-signfor="Right_Near_SPH" data-rl="Right" id="Right_Near_SPHDeg">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>

                                                            </select>

                                                        </td>
                                                        <td><input type="text" name="product[Lens][Right][Near][SPH]" data-reqval="nearSPH" value="" id="Right_Near_SPH" placeholder=" " class="form-control input-block-level lensVal lensSPH" data-rl="Right">
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
                                                        <td>Adisyon
                                                        </td>
                                                        <td colspan="5">
                                                            <div class="col-md-5 noPadding">
                                                                <input type="text" name="product[Lens][Right][Addition]" data-reqval="addVal" value="" id="Right_Addition" placeholder="" class="form-control input-block-level lensAddition lensVal" data-rl="Right" required="" aria-required="true">
                                                            </div>
                                                            <div class="col-xs-8" style="padding-right: 0;">
                                                                <select name="product[Lens][Right][Diameter]" required="" class="select2-me text-left lensDiam select2-hidden-accessible" style="width: 100%" data-rl="Right" id="Right_Lens_Diam" aria-required="true" tabindex="-1" aria-hidden="true" data-select2-id="Right_Lens_Diam">
                                                                    <option value="" data-select2-id="135">Çap Seçiniz</option>
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
                                                        <td class="lensLatterCell"><span class="bigLatter">L</span>


                                                        </td>
                                                        <td colspan="5">
                                                            <div class="row" style="padding-left: 0;">
                                                                <div class="col-xs-12">
                                                                    <div class="check-line" style="width: 100%;text-align: left;">
                                                                        <div class="icheckbox_square-orange icheck-item icheck[e2yun]"><input type="checkbox" id="LeftLens" class="icheck-me checkForLens icheck-input icheck[e2yun]" name="product[Lens][Left][isCheck]" value="1" data-skin="square" data-color="orange" data-rl="Left">
                                                                        </div>
                                                                        <label class="inline icheck-label icheck[e2yun]" for="LeftLens">Sol Cam İstiyorum
                                                                        </label>

                                                                    </div>
                                                                    <div class="check-line" style="width: 100%;text-align: left;">
                                                                        <div class="icheckbox_square-orange icheck-item icheck[veqwi]"><input type="checkbox" id="sameToRight" class="icheck-me popover2 icheck-input icheck[veqwi]" data-popover="&lt;Bilgi|Sağ cam sol cam ile aynı olsun|left" name="product[Lens][Left][sameToRight]" value="1" data-skin="square" data-color="orange" data-original-title="" title="" disabled="">
                                                                        </div>
                                                                        <label class="inline icheck-label icheck[veqwi]" for="sameToRight">Sol cam sağ cam ile aynı
                                                                        </label>


                                                                    </div>

                                                                </div>

                                                            </div>


                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td rowspan="2" class="verticalMiddle">UZAK
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>SPH
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>CYL
                                                        </td>
                                                        <td>AXIS
                                                        </td>

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
                                                        <td rowspan="2" class="verticalMiddle">YAKIN
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>SPH
                                                        </td>
                                                        <td>+/-
                                                        </td>
                                                        <td>CYL
                                                        </td>
                                                        <td>AXIS
                                                        </td>

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
                                                        <td>Adisyon
                                                        </td>
                                                        <td colspan="5">
                                                            <div class="col-md-5 noPadding">
                                                                <input type="text" name="product[Lens][Left][Addition]" data-reqval="addVal" value="" id="Left_Addition" placeholder="" class="form-control input-block-level lensAddition lensVal" data-rl="Left" required="" aria-required="true" disabled="">

                                                            </div>
                                                            <div>
                                                                <div class="col-xs-8" style="padding-right: 0;">
                                                                    <select name="product[Lens][Left][Diameter]" required="" class="select2-me text-left lensDiam select2-hidden-accessible" style="width: 100%" data-rl="Right" id="Left_Lens_Diam" aria-required="true" tabindex="-1" aria-hidden="true" disabled="" data-select2-id="Left_Lens_Diam">
                                                                        <option value="" data-select2-id="137">Çap Seçiniz</option>
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
