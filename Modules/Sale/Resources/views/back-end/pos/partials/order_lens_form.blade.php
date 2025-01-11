<style>


    :root {
        --owf-color-primary: #0057bc;
        --owf-color-primary-gradient-from: #0057bc;
        --owf-color-primary-gradient-to: #005cc6;
        --owf-color-primary-light: #e0eeff;
        --owf-color-primary-lightest: #eff6ff;
        --owf-color-primary-contrast: #fff;
        --owf-foreground-color: #373737;
        --owf-foreground-color-contrast: #373737;
        --owf-foreground-color-light: #5b5b5b;
        --owf-background-color: #f5f5f5;
        --owf-background-color-contrast: #fff;
        --owf-border-color: #cfcfcf;
        --owf-content-background-color: #fff;
        --owf-overlay-background-color: #fff;
        --owf-color-highlight-mandatory: #64ade3;
        --owf-color-highlight-ok: #059200;
        --owf-color-highlight-ok-light: #f8fff8;
        --owf-color-highlight-warning: #c49c02;
        --owf-color-highlight-warning-light: #fffae5;
        --owf-color-highlight-error: #b50000;
        --owf-color-highlight-error-light: #fff2f2;
        --owf-foreground-color-highlight-warning: #926f2a;
        --owf-font-family: "Arial",sans-serif;
        --owf-login-background-color: #f5f5f5;
        --owf-login-background-image: none;
        --owf-login-background-size: cover;
        --owf-login-background-position: center;
        --owf-login-logo-height: 7rem;
        --owf-login-logo-width: 100%;
        --owf-login-logo-margin-bottom: 3rem;
        --owf-login-logo-margin-right: 0.5rem;
        --owf-login-border-radius: 2px;
        --owf-login-container-background-color: #fff;
        --owf-login-container-box-shadow: 2px 2px 10px #38383870;
        --owf-login-container-border: none;
        --owf-login-container-position-left: 50%;
        --owf-login-container-position-top: 45%;
        --owf-login-footer-background-color: #000c;
        --owf-login-footer-shadow: none;
        --owf-login-foreground-color: var(--owf-foreground-color);
        --owf-login-button-background-color: linear-gradient(45deg,var(--owf-color-primary-gradient-from),var(--owf-color-primary-gradient-to) 50%);
        --owf-login-button-foreground-color: var(--owf-color-primary-contrast);
        --owf-rapid-background-color: var(--owf-background-color);
        --owf-rapid-foreground-color: var(--owf-foreground-color);
        --owf-rapid-component-background-color: var(--owf-content-background-color);
        --owf-rapid-component-border-color: #22242626;
        --owf-rapid-component-border-hover-color: #22242659;
        --owf-rapid-lagend-color: #535353;
        --owf-rapid-placeholder-color: #919191;
        --owf-rapid-input-disabled-foreground-color: #919191;
        --owf-rapid-input-disabled-background-color: #f6f6f6;
        --owf-rapid-input-invalid-background-color: #fff6f6;
        --owf-rapid-input-invalid-border-color: var(--owf-color-highlight-error);
        --owf-rapid-input-selected-foreground-color: var(--owf-foreground-color);
        --owf-rapid-input-selected-background-color: var(--owf-color-primary-light);
        --owf-rapid-status-incomplete-color: #bbb6;
        --owf-rapid-status-check-color: #eee600;
        --owf-rapid-status-available-color: #3cb371;
        --owf-rapid-status-error-color: #dc143c;
        --owf-rapid-status-done-color: #69c;
        --owf-grid-order-content-foreground-color: var(--owf-foreground-color);
        --owf-grid-order-content-background-color: var(--owf-content-background-color);
        --owf-grid-order-content-border-color: #d5d5d5;
        --owf-grid-order-sidebar-background-color: var(--owf-background-color);
        --owf-grid-order-basket-foreground-color: var(--owf-foreground-color);
        --owf-grid-order-basket-background-color: var(--owf-content-background-color);
        --owf-grid-order-basket-border-color: #f0f0f0;
        --owf-grid-order-basket-item-selected-foreground-color: var(--owf-foreground-color);
        --owf-grid-order-basket-item-selected-gradient-from: var(--owf-color-primary-light);
        --owf-grid-order-basket-item-selected-gradient-to: var(--owf-color-primary-light);
        --owf-grid-order-basket-item-hovered-background-color: #f0f0f0;
        --owf-grid-order-basket-additional-info-foreground-color: #6f6f6f;
        --owf-grid-order-basket-total-border-color: #bdbdbd;
        --owf-grid-order-basket-availability-initial-foreground-color: #434343;
        --owf-grid-order-basket-availability-initial-background-color: #e0e0e0;
        --owf-grid-order-basket-availability-available-foreground-color: #197b19;
        --owf-grid-order-basket-availability-available-background-color: #4eb54e36;
        --owf-grid-order-basket-availability-partial-foreground-color: #7f5c00;
        --owf-grid-order-basket-availability-partial-background-color: #d996033d;
        --owf-grid-order-basket-availability-none-foreground-color: #7f0000;
        --owf-grid-order-basket-availability-none-background-color: #d903033d;
        --owf-grid-order-input-selected-foreground-color: var(--owf-foreground-color);
        --owf-grid-order-input-selected-background-color: var(--owf-color-primary-light);
        --owf-grid-order-border-color: #22242626;
        --owf-grid-order-grid-background-color: #fff;
        --owf-grid-order-grid-cell-foreground-color: var(--owf-color-primary-contrast);
        --owf-grid-order-grid-cell-background-color: #eef3f9;
        --owf-grid-order-grid-cell-border-color: #d1d1d1;
        --owf-grid-order-grid-cell-hovered-border-color: #c4c4c4;
        --owf-grid-order-grid-cell-hovered-brightness: 1.2;
        --owf-grid-order-grid-cell-ordered-background-color: #0057bccc;
        --owf-grid-order-grid-cell-ordered-border-color: #0057bc;
        --owf-grid-order-grid-cell-ordered-and-hovered-brightness: 1.4;
        --owf-grid-order-grid-cell-alternative-background-color-from: #d7d7d7;
        --owf-grid-order-grid-cell-alternative-background-color-to: #e7e7e7;
        --owf-header-color: #fff;
        --owf-header-background-color: var(--owf-color-primary);
        --owf-header-border: none;
        --owf-header-menu-color: #000;
        --owf-header-menu-background-color: #fff;
        --owf-header-menu-title-color: var(--owf-color-primary-contrast);
        --owf-header-menu-item-selected-foreground-color: var(--owf-foreground-color);
        --owf-header-menu-item-selected-background-color: var(--owf-color-primary-light);
        --owf-header-menu-item-hovered-background-color: #f6f6f6;
        --owf-header-menu-item-disabled-color: #c1c1c1;
        --owf-header-button-forground-color: #fff;
        --owf-header-button-background-color: var(--owf-color-primary);
        --owf-header-button-background-color-hover: var(--owf-color-primary-gradient-to);
        --owf-header-logo-url: url(images/common/logos/logo-white.png);
        --owf-header-logo-position: center;
        --owf-header-logo-padding-top: 0;
        --owf-header-logo-height: 2rem;
        --owf-header-logo-width: 17rem;
        --owf-notification-bar-background-color: #cfd6ea;
        --owf-notification-bar-border: #bbb;
        --owf-notification-bar-color: #333;
        --owf-quick-user-switch-foreground-color: var(--owf-foreground-color);
        --owf-quick-user-switch-background-color: #fff;
        --owf-quick-user-switch-background-color-hover: #f2f2f2;
        --owf-quick-user-switch-border-color: #e7e7e7;
        --owf-navigation-background-color-from: var(--owf-background-color-contrast);
        --owf-navigation-background-color-to: var(--owf-background-color-contrast);
        --owf-navigation-forground-color: var(--owf-foreground-color-contrast);
        --owf-navigation-subnavigation-selected-color: var(--owf-color-primary);
        --owf-footer-color: #fff;
        --owf-footer-background-color: #2c2a2a;
        --owf-button-foreground-color: var(--owf-color-primary);
        --owf-button-border-color: var(--owf-color-primary);
        --owf-button-background-color: #fff;
        --owf-button-hover-background-color: #fafafa;
        --owf-button-hover-border-color: #2224268c;
        --owf-sidebar-button-border-color: #22242659;
        --owf-sidebar-button-background-color: #fff;
        --owf-sidebar-button-hover-background-color: #fafafa;
        --owf-sidebar-button-hover-border-color: #2224268c;
        --owf-sidebar-widget-header-color: var(--owf-background-color-contrast);
        --owf-sidebar-widget-content-color: var(--owf-foreground-color);
        --owf-haps-icon-background-color: #989ea4;
        --owf-lens-graphic-right-background-color: #968b87;
        --owf-lens-graphic-left-background-color: #c0b7b2;
        --owf-grid-background-color: #fff;
        --owf-grid-border-color: #d3d3d3;
        --owf-grid-header-foreground-color: var(--owf-color-primary-contrast);
        --owf-grid-header-background-color: var(--owf-lens-graphic-right-background-color);
        --owf-grid-footer-background-color: var(--owf-lens-graphic-right-background-color);
        --owf-grid-row-selected-background-color: var(--owf-color-primary-light);
        --owf-grid-row-hovered-background-color: #f2f2f2;
        --owf-grid-filter-selected-color: #2167b7;
        --owf-grid-icon-primary-color: var(--owf-color-primary);
        --owf-grid-icon-ok-color: var(--owf-color-highlight-ok);
        --owf-grid-icon-warning-color: var(--owf-color-highlight-warning);
        --owf-grid-icon-error-color: #7d6862;
        --owf-dialog-overlay-background-color: #333;
        --owf-dialog-overlay-background-color-rgba: #3333;
        --owf-table-highlight-background-color: var(--owf-background-color);
        --owf-2dview-controls-background-color: #f2f2f2b3;
        --owf-visualization-background-color: var(--owf-content-background-color);
        --owf-visualization-background-color-contrast: #f6f6f6;
        --owf-submit-logo-url: url(images/common/logos/logo-blue.png);
        --owf-submit-logo-width: 33rem;
        --owf-form-widget-font-color-primary: var(--owf-foreground-color);
        --owf-form-widget-font-color-secondary: var(--owf-color-primary);
        --owf-form-widget-disabled-font-color: var(--owf-foreground-color-light);
        --owf-form-widget-primary-color: #d3d3d3;
        --owf-form-widget-secondary-color: #fff;
        --owf-form-widget-mandatory-primary-color: var(--owf-color-highlight-mandatory);
        --owf-form-widget-mandatory-secondary-color: #fff;
        --owf-form-widget-error-primary-color: var(--owf-color-highlight-error);
        --owf-form-widget-error-secondary-color: var(--owf-color-highlight-error-light);
        --owf-form-widget-focus-primary-color: var(--owf-color-primary);
        --owf-form-widget-focus-secondary-color: var(--owf-color-primary-light);
        --owf-form-widget-focus-tertiary-color: var(--owf-color-primary-lightest);
        --owf-form-widget-disabled-primary-color: #8b8b8b;
        --owf-form-widget-disabled-secondary-color: #eee;
        --owf-form-widget-disabled-border-color: #dbdbdb;
        --owf-form-widget-checkbox-check-color: var(--owf-color-primary);
        --owf-form-widget-checkbox-disabled-background-color: #f0f0f0;
        --owf-form-widget-dropdown-item-separator-color: #f9f9f9;
        --owf-form-widget-dropdown-placeholder-color: #cbcbcb
    }

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


    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #ddd;
    }

    .owf-page-shapeDefinition-manual-shape {
        column-gap: 6rem;
        /*display: grid*/
    ;
        grid-template-columns: 1fr 34rem;
        grid-template-rows: 1fr;
    }


    .owf-page-shapeDefinition-manual-shape-abc {
        display: grid;
        grid-template-columns: auto auto;
        grid-template-rows: 4rem 1fr 4rem;
    }

     .owf-page-shapeDefinition-manual-shape * {
        border: 0;
        font-size: 100%;
        font-weight: inherit;
        margin: 0;
        padding: 0;
        vertical-align: initial;
    }
    .owf-page-shapeDefinition-manual-predefinedShape {
        align-content: start;
        display: grid
    ;
    }
    .owf-headline:first-child {
        padding-top: 0;
    }
    .owf-headline {
        align-items: center;
        color: var(--owf-color-primary);
        display: flex
    ;
        font-size: var(--owf-font-size-default);
        font-weight: 700;
        gap: .2rem;
        justify-content: space-between;
        line-height: 1.6;
        padding-top: calc(1rem* var(--owf-shallow-scale-75));
        -webkit-user-select: none;
        user-select: none;
    }




/*    ----- */

    .owf-page-shapeDefinition-manual-predefinedShape-container {
        column-gap: .5rem;
        display: grid
    ;
        grid-template-columns: repeat(6, auto);
        grid-template-rows: repeat(6, auto);
        row-gap: .5rem;
    }
    svg.abc {
        transition: opacity .15s ease-in-out;
    }
    svg.abc .abc-lens {
        fill: url(#abc_lensGradient);
        pointer-events: none;
    }
    svg.abc.predefinedShape .abc-lens {
        filter: opacity(1);
        pointer-events: all;
    }
    svg.abc.predefinedShape:not(.selected) .abc-lens {
        stroke-width: 1px;
        stroke: #0057bc;
    }


    .abc-lens {
        fill: url(#abc_lensGradient);
        pointer-events: none;
    }

    svg.abc.predefinedShape .abc-cCode {
        pointer-events: none;
        transform: scale(4);
        text-anchor: middle;
        dominant-baseline: central;
        fill: #0057bc;
        stroke-width: 1px;
        paint-order: stroke;
        -webkit-user-select: none;
        user-select: none;
    }
    svg.abc.predefinedShape.selected .abc-lens {
        stroke-width: 5px;
        stroke: var(--owf-foreground-color);
    }
    svg.abc.predefinedShape .abc-lens {
        filter: opacity(1);
        pointer-events: all;
    }
    svg.abc.predefinedShape:not(.selected) .abc-lens {
        stroke-width: 1px;
        stroke: #0057bc;
    }
    svg.abc.predefinedShape .abc-lens {
        filter: opacity(1);
        pointer-events: all;
    }
    svg.abc.predefinedShape:not(.selected) .abc-lens:hover {
        cursor: pointer;
        filter: brightness(1.07);
        transform: scale(1.05);
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

                                    <div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                                        <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#moreInfoCollapse" aria-expanded="false" aria-controls="moreInfoCollapse">
                                            <i class="fas fa-arrow-down"></i>
                                            {{translate('Ek işlemler')}}
                                            <span class="section-header-pill"></span>
                                        </button>
                                    </div>
                                    <div class="collapse" id="moreInfoCollapse">
                                        <div class="card mb-3">
                                            <div class="card-body p-2">
                                                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                                    <div class="col-md-3 px-5">
                                                            <div class="noBorderRight">
                                                                <div class="check-line" style="width: 100%;text-align: left;">
                                                                    <div class="icheckbox_square-orange icheck-item icheck[qepho]">
                                                                        <input type="checkbox" id="VATintingCheck" data-subs="tintingList" class="icheck-me additionalProcessChecker icheck-input icheck[qepho] valid" name="product[VA][TinTing][isCheck]" value="1" data-skin="square" data-color="orange" aria-invalid="false"></div>
                                                                    <label class="inline icheck-label icheck[qepho]" for="VATintingCheck">{{translate("Boyama")}}</label>
                                                                </div>
                                                            </div>
                                                            <div class="d-none color_class">
                                                                {!! Form::select('product[VA][TinTing][value]', $colors ,null, [
                                                                       'class' => ' selectpicker form-control',
                                                                       'data-live-search' => 'true',
                                                                       'style' => 'width: 80%',
                                                                      'data-actions-box' => 'true',
                                                                       'id' => 'color_product',
                                                                       'placeholder' => __('lang.please_select'),
                                                                   ]) !!}
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3 px-5">
                                                            <div class="noBorderRight">
                                                                <div class="check-line" style="width: 100%;text-align: left;">
                                                                    <div class="icheckbox_square-orange icheck-item icheck[wzqw0]"><input type="checkbox" id="VABaseCheck" data-subs="vaBaseList" class="icheck-me additionalProcessChecker icheck-input icheck[wzqw0]" name="product[VA][Base][isCheck]" value="1" data-skin="square" data-color="orange"></div>
                                                                    <label class="inline icheck-label icheck[wzqw0]" for="VABaseCheck">{{translate("Özel Baz")}}</label>
                                                                </div>
                                                            </div>
                                                            <div class="d-none VABaseCheck_class">
                                                                {!! Form::select('product[special_base]', $special_bases ,null, [
                                                                       'class' => ' selectpicker form-control',
                                                                       'data-live-search' => 'true',
                                                                       'style' => 'width: 80%',
                                                                      'data-actions-box' => 'true',
                                                                       'id' => 'special_base',
                                                                       'placeholder' => __('lang.please_select'),
                                                                   ]) !!}
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3 px-5">
                                                            <div class="noBorderRight">
                                                                <div class="check-line" style="width: 100%;text-align: left;">
                                                                    <div class="icheckbox_square-orange icheck-item icheck[wzqw0]"><input type="checkbox" id="specific_diameter" data-subs="vaBaseList" class="icheck-me additionalProcessChecker icheck-input icheck[wzqw0]" name="product[VA][Base][isCheck]" value="1" data-skin="square" data-color="orange"></div>
                                                                    <label class="inline icheck-label icheck[wzqw0]" for="specific_diameter">{{translate("Özel Çap")}}</label>
                                                                </div>
                                                            </div>
                                                            <div class="d-none specific_diameter_class">
                                                                {!! Form::number('product[specific_diameter]' ,null, [
                                                                       'class' => ' selectpicker form-control',
                                                                       'style' => 'width: 80%',
                                                                      'data-actions-box' => 'true',
                                                                       'id' => 'specific_diameter_input',
                                                                   ]) !!}
                                                            </div>
                                                    </div>

                                                    <div class="col-md-12 px-5">
                                                        <div class="noBorderRight">
                                                            <div class="check-line" style="width: 100%;text-align: left;">
                                                                <div class="icheckbox_square-orange icheck-item icheck[qepho]">
                                                                    <input type="checkbox" id="codeCheck" data-subs="tintingList" class="icheck-me additionalProcessChecker icheck-input icheck[qepho] valid"
                                                                           name="product[VA][TinTing][code]" value="1" data-skin="square" data-color="orange" aria-invalid="false"></div>
                                                                <label class="inline icheck-label icheck[qepho]" for="codeCheck">{{translate("Çerçeve Tipi")}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="owf-page-shapeDefinition-manual-shape d-none">
                                                            <!--SVG element which declares definitions that are used in all other SVGs by reference-->
                                                            <svg width="0" height="0" class="abc">
                                                                <defs>
                                                                    <marker id="abc_arrowhead" markerWidth="15" markerHeight="5" refX="0" refY="2.5" orient="auto">
                                                                        <polygon class="abc-indicator" points="0 0, 15 2.5, 0 5"></polygon>
                                                                    </marker>
                                                                    <linearGradient id="abc_lensGradient" gradientTransform="rotate(90)">
                                                                        <stop offset="10%" stop-color="#c4e4fa"></stop>
                                                                        <stop offset="50%" stop-color="#90bff1"></stop>
                                                                        <stop offset="50%" stop-color="#84b7ed"></stop>
                                                                        <stop offset="90%" stop-color="#bfe3fe"></stop>
                                                                    </linearGradient>
                                                                </defs>
                                                            </svg>

                                                            {{--    <div class="owf-page-shapeDefinition-manual-shape-abc">



                                                                <!-- CCode controls -->
                                                                <div class="owf-page-shapeDefinition-manual-shape-controlBox owf-page-shapeDefinition-manual-shape-controlTopLeft">
                                                                    <button type="button" class="owf-button ui basic button" data-index="1" data-value="1">
                                                                        <span class="owf-button-label">1</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="1" data-value="2">
                                                                        <span class="owf-button-label">2</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button selected" data-index="1" data-value="3">
                                                                        <span class="owf-button-label">3</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="1" data-value="4">
                                                                        <span class="owf-button-label">4</span>
                                                                    </button>      </div>
                                                                <div class="owf-page-shapeDefinition-manual-shape-controlBox owf-page-shapeDefinition-manual-shape-controlTopRight">
                                                                    <button type="button" class="owf-button ui basic button" data-index="0" data-value="1">
                                                                        <span class="owf-button-label">1</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="0" data-value="2">
                                                                        <span class="owf-button-label">2</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button selected" data-index="0" data-value="3">
                                                                        <span class="owf-button-label">3</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="0" data-value="4">
                                                                        <span class="owf-button-label">4</span>
                                                                    </button>      </div>
                                                                <div class="owf-page-shapeDefinition-manual-shape-controlBox owf-page-shapeDefinition-manual-shape-controlBottomLeft">
                                                                    <button type="button" class="owf-button ui basic button" data-index="2" data-value="1">
                                                                        <span class="owf-button-label">1</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="2" data-value="2">
                                                                        <span class="owf-button-label">2</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="2" data-value="3">
                                                                        <span class="owf-button-label">3</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button selected" data-index="2" data-value="4">
                                                                        <span class="owf-button-label">4</span>
                                                                    </button>      </div>
                                                                <div class="owf-page-shapeDefinition-manual-shape-controlBox owf-page-shapeDefinition-manual-shape-controlBottomRight">
                                                                    <button type="button" class="owf-button ui basic button" data-index="3" data-value="1">
                                                                        <span class="owf-button-label">1</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="3" data-value="2">
                                                                        <span class="owf-button-label">2</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button" data-index="3" data-value="3">
                                                                        <span class="owf-button-label">3</span>
                                                                    </button>        <button type="button" class="owf-button ui basic button selected" data-index="3" data-value="4">
                                                                        <span class="owf-button-label">4</span>
                                                                    </button>      </div>

                                                                <!-- Actual SVG box -->
                                                                <div class="owf-page-shapeDefinition-manual-shape-svg">
                                                                    <svg id="shape_view_svg" class="abc transitioning" viewBox="-575 -270 1000 720" xmlns="http://www.w3.org/2000/svg">
                                                                        <!-- Indicator text pieces -->
                                                                        <text id="abc_text_a" class="abc-indicator abc-text" x="0" y="0" transform="translate(-104.14285714285717, 325)"><tspan>A-Boyutu</tspan><title>A-Boyutu</title></text>
                                                                        <text id="abc_text_b" class="abc-indicator abc-text" x="0" y="0" transform="translate(-450, 0) rotate(90)"><tspan>B-Boyutu</tspan><title>B-Boyutu</title></text>
                                                                        <text id="abc_text_dbl" class="abc-indicator abc-text" x="0" y="0" transform="translate(300.85714285714283, 325)"><tspan>DBL</tspan><title>DBL</title></text>
                                                                        <text id="abc_text_adbl" class="abc-indicator abc-text" x="0" y="0" transform="translate(0, 400)"><tspan>A-Boyutu + DBL</tspan><title>A-Boyutu + DBL</title></text>

                                                                        <!-- Size indicator lines -->
                                                                        <line id="abc_line_shape_top_end" class="abc-indicator abc-line" x1="0" y1="0" x2="-1000" y2="0" transform="translate(-104.14285714285714, -185.14285714285714) scale(0.3958571428571429, 1)"></line>
                                                                        <line id="abc_line_shape_bottom_end" class="abc-indicator abc-line" x1="0" y1="0" x2="-1000" y2="0" transform="translate(-104.14285714285714, 185.14285714285714) scale(0.3958571428571429, 1)"></line>
                                                                        <line id="abc_line_shape_left_end" class="abc-indicator abc-line" x1="0" y1="0" x2="0" y2="450" transform="translate(-405, 0)"></line>
                                                                        <line id="abc_line_shape_right_end" class="abc-indicator abc-line" x1="0" y1="0" x2="0" y2="375" transform="translate(196.7142857142857, 0)"></line>
                                                                        <line id="abc_line_dbl_end" class="abc-indicator abc-line" x1="0" y1="0" x2="0" y2="450" transform="translate(405, 0)"></line>

                                                                        <!-- A size arrow -->
                                                                        <line id="abc_line_a" class="abc-indicator abc-line" x1="0" y1="0" x2="1000" y2="0" transform="translate(-360, 350) scale(0.5117142857142857, 1)"></line>
                                                                        <line id="abc_line_a_arrow_left" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="-1" y2="0" transform="translate(-360, 350)"></line>
                                                                        <line id="abc_line_a_arrow_right" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="1" y2="0" transform="translate(151.71428571428567, 350)"></line>

                                                                        <!-- B size arrow -->
                                                                        <line id="abc_line_b" class="abc-indicator abc-line" x1="0" y1="0" x2="0" y2="1000" transform="translate(-475, -140.14285714285714) scale(1, 0.2802857142857143)"></line>
                                                                        <line id="abc_line_b_arrow_top" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="0" y2="-1" transform="translate(-475, -140.14285714285714)"></line>
                                                                        <line id="abc_line_b_arrow_bottom" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="0" y2="1" transform="translate(-475, 140.14285714285714)"></line>

                                                                        <!-- DBL arrow -->
                                                                        <line id="abc_line_dbl" class="abc-indicator abc-line" x1="0" y1="0" x2="1000" y2="0" transform="translate(241.7142857142857, 350) scale(0.11828571428571427, 1)"></line>
                                                                        <line id="abc_line_dbl_arrow_left" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="-1" y2="0" transform="translate(241.7142857142857, 350)"></line>
                                                                        <line id="abc_line_dbl_arrow_right" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="1" y2="0" transform="translate(360, 350)"></line>

                                                                        <!-- A size + DBL arrow -->
                                                                        <line id="abc_line_adbl" class="abc-indicator abc-line" x1="0" y1="0" x2="1000" y2="0" transform="translate(-360, 425) scale(0.72, 1)"></line>
                                                                        <line id="abc_line_adbl_arrow_left" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="-1" y2="0" transform="translate(-360, 425)"></line>
                                                                        <line id="abc_line_adbl_arrow_right" class="abc-indicator abc-line abc-arrow" x1="0" y1="0" x2="1" y2="0" transform="translate(360, 425)"></line>

                                                                        <!-- Indicator text pieces -->
                                                                        <text id="abc_text_a" class="abc-indicator abc-text" x="0" y="0" transform="translate(0, 0)"><tspan></tspan><title></title></text>
                                                                        <text id="abc_text_b" class="abc-indicator abc-text" x="0" y="0" transform="translate(0, 0) rotate(90)"><tspan></tspan><title></title></text>
                                                                        <text id="abc_text_dbl" class="abc-indicator abc-text" x="0" y="0" transform="translate(0, 0)"><tspan></tspan><title></title></text>
                                                                        <text id="abc_text_adbl" class="abc-indicator abc-text" x="0" y="0" transform="translate(0, 0)"><tspan></tspan><title></title></text>

                                                                        <!-- DBL -->
                                                                        <path id="dbl_shape" class="abc-dbl abc-curve incomplete" d="M196.7142857142857,0 q104.14285714285714,-104.14285714285714 208.28571428571428,0"></path>

                                                                        <!-- Selected shape -->
                                                                        <path id="abc_shape_selected_fill" class="abc-lens" d="M196.7142857142857,0 v-18.514285714285712 q0,-166.62857142857143 -270.77142857142854,-166.62857142857143 h-30.085714285714285 h-30.085714285714285 q-270.77142857142854,0 -270.77142857142854,166.62857142857143 v18.514285714285712 v0 a300.85714285714283,185.14285714285714 0 0 0 300.85714285714283,185.14285714285714 h0 h0 a300.85714285714283,-185.14285714285714 0 0 0 300.85714285714283,-185.14285714285714 v-0"></path>

                                                                        <!-- Shape: top right -->
                                                                        <g id="abc_shape_top_right" class="abc-shape-quadrant" data-index="0">
                                                                            <path class="abc-outline abc-outline-blue" data-index="0" data-value="1" d="M196.7142857142857,0 v-99.97714285714287 q0,-85.16571428571427 -138.3942857142857,-85.16571428571427 h-162.46285714285713"></path>
                                                                            <path class="abc-outline abc-outline-red" data-index="0" data-value="2" d="M196.7142857142857,0 v-57.394285714285715 q0,-127.74857142857142 -207.59142857142854,-127.74857142857142 h-93.26571428571428"></path>
                                                                            <path class="abc-outline abc-outline-green" data-index="0" data-value="3" d="M196.7142857142857,0 v-18.514285714285712 q0,-166.62857142857143 -270.77142857142854,-166.62857142857143 h-30.085714285714285"></path>
                                                                            <path class="abc-outline abc-outline-orange" data-index="0" data-value="4" d="M196.7142857142857,0 v-0 a-300.85714285714283,-185.14285714285714 0 0 0 -300.85714285714283,-185.14285714285714 h-0"></path>
                                                                            <path class="abc-invisible" d="M186.8785714285714,0 v-0 a-285.8142857142857,-175.88571428571427 0 0 0 -285.8142857142857,-175.88571428571427 h-0"></path>
                                                                            <path class="abc-curve" d="M196.7142857142857,0 v-18.514285714285712 q0,-166.62857142857143 -270.77142857142854,-166.62857142857143 h-30.085714285714285"></path>
                                                                        </g>

                                                                        <!-- Shape: top left -->
                                                                        <g id="abc_shape_top_left" class="abc-shape-quadrant" data-index="1">
                                                                            <path class="abc-outline abc-outline-blue" data-index="1" data-value="1" d="M-104.14285714285714,-185.14285714285714 h-162.46285714285713 q-138.3942857142857,0 -138.3942857142857,85.16571428571427 v99.97714285714287"></path>
                                                                            <path class="abc-outline abc-outline-red" data-index="1" data-value="2" d="M-104.14285714285714,-185.14285714285714 h-93.26571428571428 q-207.59142857142854,0 -207.59142857142854,127.74857142857142 v57.394285714285715"></path>
                                                                            <path class="abc-outline abc-outline-green" data-index="1" data-value="3" d="M-104.14285714285714,-185.14285714285714 h-30.085714285714285 q-270.77142857142854,0 -270.77142857142854,166.62857142857143 v18.514285714285712"></path>
                                                                            <path class="abc-outline abc-outline-orange" data-index="1" data-value="4" d="M-104.14285714285714,-185.14285714285714 h-0 a-300.85714285714283,185.14285714285714 0 0 0 -300.85714285714283,185.14285714285714 v0"></path>
                                                                            <path class="abc-invisible" d="M-98.93571428571428,-175.88571428571427 h-0 a-285.8142857142857,175.88571428571427 0 0 0 -285.8142857142857,175.88571428571427 v0"></path>
                                                                            <path class="abc-curve" d="M-104.14285714285714,-185.14285714285714 h-30.085714285714285 q-270.77142857142854,0 -270.77142857142854,166.62857142857143 v18.514285714285712"></path>
                                                                        </g>

                                                                        <!-- Shape: bottom left -->
                                                                        <g id="abc_shape_bottom_left" class="abc-shape-quadrant" data-index="2">
                                                                            <path class="abc-outline abc-outline-blue" data-index="2" data-value="1" d="M-405,0 v99.97714285714287 q0,85.16571428571427 138.3942857142857,85.16571428571427 h162.46285714285713"></path>
                                                                            <path class="abc-outline abc-outline-red" data-index="2" data-value="2" d="M-405,0 v57.394285714285715 q0,127.74857142857142 207.59142857142854,127.74857142857142 h93.26571428571428"></path>
                                                                            <path class="abc-outline abc-outline-green" data-index="2" data-value="3" d="M-405,0 v18.514285714285712 q0,166.62857142857143 270.77142857142854,166.62857142857143 h30.085714285714285"></path>
                                                                            <path class="abc-outline abc-outline-orange" data-index="2" data-value="4" d="M-405,0 v0 a300.85714285714283,185.14285714285714 0 0 0 300.85714285714283,185.14285714285714 h0"></path>
                                                                            <path class="abc-invisible" d="M-384.75,0 v0 a285.8142857142857,175.88571428571427 0 0 0 285.8142857142857,175.88571428571427 h0"></path>
                                                                            <path class="abc-curve" d="M-405,0 v0 a300.85714285714283,185.14285714285714 0 0 0 300.85714285714283,185.14285714285714 h0"></path>
                                                                        </g>

                                                                        <!-- Shape: bottom right -->
                                                                        <g id="abc_shape_bottom_right" class="abc-shape-quadrant" data-index="3">
                                                                            <path class="abc-outline abc-outline-blue" data-index="3" data-value="1" d="M-104.14285714285714,185.14285714285714 h162.46285714285713 q138.3942857142857,0 138.3942857142857,-85.16571428571427 v-99.97714285714287"></path>
                                                                            <path class="abc-outline abc-outline-red" data-index="3" data-value="2" d="M-104.14285714285714,185.14285714285714 h93.26571428571428 q207.59142857142854,0 207.59142857142854,-127.74857142857142 v-57.394285714285715"></path>
                                                                            <path class="abc-outline abc-outline-green" data-index="3" data-value="3" d="M-104.14285714285714,185.14285714285714 h30.085714285714285 q270.77142857142854,0 270.77142857142854,-166.62857142857143 v-18.514285714285712"></path>
                                                                            <path class="abc-outline abc-outline-orange" data-index="3" data-value="4" d="M-104.14285714285714,185.14285714285714 h0 a300.85714285714283,-185.14285714285714 0 0 0 300.85714285714283,-185.14285714285714 v-0"></path>
                                                                            <path class="abc-invisible" d="M-98.93571428571428,175.88571428571427 h0 a285.8142857142857,-175.88571428571427 0 0 0 285.8142857142857,-175.88571428571427 v-0"></path>
                                                                            <path class="abc-curve" d="M-104.14285714285714,185.14285714285714 h0 a300.85714285714283,-185.14285714285714 0 0 0 300.85714285714283,-185.14285714285714 v-0"></path>
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                            --}}
                                                            <input type="hidden" value="" name="" id="input_predefined_shape">
                                                            <div id="predefined_shape" class="owf-page-shapeDefinition-manual-predefinedShape">
                                                                <div class="owf-page-shapeDefinition-title owf-headline">
                                                                    Önceden Tanımlanmış Şekiller    </div>

                                                                <div class="owf-page-shapeDefinition-manual-predefinedShape-container" tabindex="0">
                                                                    <svg class="predefinedShape abc" data-ccode="2223" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-34.33846153846154 q0,-76.43076923076924 -124.2,-76.43076923076924 h-55.8 h-55.8 q-124.2,0 -124.2,76.43076923076924 v34.33846153846154 v34.33846153846154 q0,76.43076923076924 124.2,76.43076923076924 h55.8 h18 q162,0 162,-99.6923076923077 v-11.076923076923077"></path>
                                                                        <text class="abc-cCode">2223</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="1112" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-59.815384615384616 q0,-50.95384615384616 -82.8,-50.95384615384616 h-97.2 h-97.2 q-82.8,0 -82.8,50.95384615384616 v59.815384615384616 v59.815384615384616 q0,50.95384615384616 82.8,50.95384615384616 h97.2 h55.8 q124.2,0 124.2,-76.43076923076924 v-34.33846153846154"></path>
                                                                        <text class="abc-cCode">1112</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="3333" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-11.076923076923077 q0,-99.6923076923077 -162,-99.6923076923077 h-18 h-18 q-162,0 -162,99.6923076923077 v11.076923076923077 v11.076923076923077 q0,99.6923076923077 162,99.6923076923077 h18 h18 q162,0 162,-99.6923076923077 v-11.076923076923077"></path>
                                                                        <text class="abc-cCode">3333</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="2144" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-34.33846153846154 q0,-76.43076923076924 -124.2,-76.43076923076924 h-55.8 h-97.2 q-82.8,0 -82.8,50.95384615384616 v59.815384615384616 v0 a180,110.76923076923077 0 0 0 180,110.76923076923077 h0 h0 a180,-110.76923076923077 0 0 0 180,-110.76923076923077 v-0"></path>
                                                                        <text class="abc-cCode">2144</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="1224" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-59.815384615384616 q0,-50.95384615384616 -82.8,-50.95384615384616 h-97.2 h-55.8 q-124.2,0 -124.2,76.43076923076924 v34.33846153846154 v34.33846153846154 q0,76.43076923076924 124.2,76.43076923076924 h55.8 h0 a180,-110.76923076923077 0 0 0 180,-110.76923076923077 v-0"></path>
                                                                        <text class="abc-cCode">1224</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="2233" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-34.33846153846154 q0,-76.43076923076924 -124.2,-76.43076923076924 h-55.8 h-55.8 q-124.2,0 -124.2,76.43076923076924 v34.33846153846154 v11.076923076923077 q0,99.6923076923077 162,99.6923076923077 h18 h18 q162,0 162,-99.6923076923077 v-11.076923076923077"></path>
                                                                        <text class="abc-cCode">2233</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc " data-ccode="3344" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-11.076923076923077 q0,-99.6923076923077 -162,-99.6923076923077 h-18 h-18 q-162,0 -162,99.6923076923077 v11.076923076923077 v0 a180,110.76923076923077 0 0 0 180,110.76923076923077 h0 h0 a180,-110.76923076923077 0 0 0 180,-110.76923076923077 v-0"></path>
                                                                        <text class="abc-cCode">3344</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="1223" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-59.815384615384616 q0,-50.95384615384616 -82.8,-50.95384615384616 h-97.2 h-55.8 q-124.2,0 -124.2,76.43076923076924 v34.33846153846154 v34.33846153846154 q0,76.43076923076924 124.2,76.43076923076924 h55.8 h18 q162,0 162,-99.6923076923077 v-11.076923076923077"></path>
                                                                        <text class="abc-cCode">1223</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="1244" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-59.815384615384616 q0,-50.95384615384616 -82.8,-50.95384615384616 h-97.2 h-55.8 q-124.2,0 -124.2,76.43076923076924 v34.33846153846154 v0 a180,110.76923076923077 0 0 0 180,110.76923076923077 h0 h0 a180,-110.76923076923077 0 0 0 180,-110.76923076923077 v-0"></path>
                                                                        <text class="abc-cCode">1244</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="1123" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-59.815384615384616 q0,-50.95384615384616 -82.8,-50.95384615384616 h-97.2 h-97.2 q-82.8,0 -82.8,50.95384615384616 v59.815384615384616 v34.33846153846154 q0,76.43076923076924 124.2,76.43076923076924 h55.8 h18 q162,0 162,-99.6923076923077 v-11.076923076923077"></path>
                                                                        <text class="abc-cCode">1123</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="1111" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-59.815384615384616 q0,-50.95384615384616 -82.8,-50.95384615384616 h-97.2 h-97.2 q-82.8,0 -82.8,50.95384615384616 v59.815384615384616 v59.815384615384616 q0,50.95384615384616 82.8,50.95384615384616 h97.2 h97.2 q82.8,0 82.8,-50.95384615384616 v-59.815384615384616"></path>
                                                                        <text class="abc-cCode">1111</text>
                                                                    </svg>
                                                                    <svg class="predefinedShape abc" data-ccode="2244" viewBox="-200 -150 400 300" width="110px" height="80px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="M180,0 v-34.33846153846154 q0,-76.43076923076924 -124.2,-76.43076923076924 h-55.8 h-55.8 q-124.2,0 -124.2,76.43076923076924 v34.33846153846154 v0 a180,110.76923076923077 0 0 0 180,110.76923076923077 h0 h0 a180,-110.76923076923077 0 0 0 180,-110.76923076923077 v-0"></path>
                                                                        <text class="abc-cCode">2244</text>
                                                                    </svg>

                                                                    <svg class="predefinedShape abc" data-ccode="4444" viewBox="-200 -180 400 360" width="110px" height="90px" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="abc-lens" d="
                                                                            M-180,0
                                                                            a180,120 0 1,0 360,0
                                                                            a180,120 0 1,0 -360,0
                                                                            " fill="#d6e9f8" stroke="#578981" stroke-width="2"></path>
                                                                        <text class="abc-cCode">4444</text>
                                                                    </svg>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 px-5">

                                                    </div>

                                                    <div class="col-md-3 px-5">

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>






                                    <div class="clearfix"></div>
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
                                                            <select name="product[Lens][Right][Far][SPHDeg]"
                                                                    class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect"
                                                                    data-rl="Right" data-signfor="Right_Far_SPH" id="Right_Far_SPHDeg">
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
                                                                   step="0.25"
                                                                   min="-30"
                                                                   max="30">

{{--                                                            <input type="number" name="product[Lens][Right][Far][SPH]" value="" id="Right_Far_SPH" data-reqval="farSPH" placeholder=" " class="form-control input-block-level lensVal lensSPH farSPH" data-rl="Right" required="" aria-required="true" step="0.5">--}}
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Right][Far][CYLDeg]"
                                                                    class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect"
                                                                    data-signfor="Right_Far_CYL" data-rl="Right"
                                                                    id="Right_Far_CYLDeg">
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
                                                                   step="0.25"
                                                                   min="-15"
                                                                   max="15">
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="number"
                                                                   name="product[Lens][Right][Far][Axis]"
                                                                data-reqval="farAX" value=""
                                                                id="Right_Far_Axis"
                                                                placeholder=" "
                                                                class="form-control input-block-level input-sm lensAxis farAX valid"
                                                                data-rl="Right"
                                                                step="1"
                                                                min="0"
                                                                max="180">
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
                                                            <select name="product[Lens][Right][Near][SPHDeg]"
                                                                    class="form-control input-block-level lensPlusMinusSelect SPHPlusMinusSelect"
                                                                    data-signfor="Right_Near_SPH"
                                                                    data-rl="Right"
                                                                    id="Right_Near_SPHDeg">
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </td>
                                                        <td>


                                                            <input type="number"
                                                                   name="product[Lens][Right][Near][SPH]"
                                                                   value=""
                                                                   id="Right_Near_SPH"
                                                                   data-reqval="nearSPH"
                                                                   placeholder=" "
                                                                   class="form-control input-block-level lensVal lensSPH"
                                                                   data-rl="Right"
                                                                   required
                                                                   aria-required="true"
                                                                   step="0.25"
                                                                   min="-30"
                                                                   max="30">
                                                        </td>
                                                        <td>
                                                            <select name="product[Lens][Right][Near][CYLDeg]"
                                                                    class="form-control input-block-level lensPlusMinusSelect CYLPlusMinusSelect"
                                                                    data-rl="Right" data-signfor="Right_Near_CYL"
                                                                    id="Right_Near_CYLDeg">
                                                                <option value="+">+</option>
                                                                <option value="-" selected="selected">-</option>

                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input
                                                                type="number"
                                                                name="product[Lens][Right][Near][CYL]"
                                                                data-reqval="nearCYL"
                                                                value=""
                                                                id="Right_Near_CYL"
                                                                placeholder=" "
                                                                class="form-control input-block-level lensVal lensCYL"
                                                                data-rl="Right"
                                                                step="0.25"
                                                                min="-15"
                                                                max="15">

                                                        </td>
                                                        <td>
                                                            <input
                                                                type="number"
                                                                name="product[Lens][Right][Near][Axis]"
                                                                   data-reqval="nearAX"
                                                                value=""
                                                                id="Right_Near_Axis"
                                                                placeholder=" "
                                                                class="form-control input-block-level lensAxis nearAX"
                                                                data-rl="Right"
                                                                step="1"
                                                                min="0"
                                                                max="180">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{translate('Adisyon')}}</td>
                                                        <td colspan="5">
                                                            <div class="col-md-5 noPadding">
                                                                <input type="number"
                                                                       name="product[Lens][Right][Addition]"
                                                                       data-reqval="addVal" value=""
                                                                       id="Right_Addition"
                                                                       placeholder=""
                                                                       class="form-control input-block-level lensAddition lensVal"
                                                                       data-rl="Right"
                                                                       required=""
                                                                       aria-required="true">
                                                            </div>
                                                            <div class="col-xs-8" style="padding-right: 0;">
                                                                <select name="product[Lens][Right][Diameter]" required="" class="select2-me text-left lensDiam" style="width: 100%"
                                                                        data-rl="Right" id="Right_Lens_Diam" aria-required="true" tabindex="-1"
                                                                        aria-hidden="true" data-select2-id="Right_Lens_Diam">

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
                                                        <td><input type="text" name="product[Lens][Left][Far][Axis]" data-reqval="farAX" value="" id="Left_Far_Axis" placeholder=" " class="form-control input-block-level input-sm lensAxis farAX valid" data-rl="Left" disabled="">
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
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

<script>

    //brand_lens => products
    //focus_id => design_id & products
    //design_id =>  products
    //index_id =>  products
    //color_id =>  products
    $(document).on("change", "#Right_Far_Axis", function (e) {
        const Far_Axis = $('#Right_Far_Axis').val();
        $('#Right_Near_Axis').val(Far_Axis);
    });
    $(document).on("change", "#Right_Far_CYL", function (e) {
        const Far_CYL = $('#Right_Far_CYL').val();
        $('#Right_Near_CYL').val(Far_CYL);
    });
    $(document).on("change", "#Right_Far_SPH,#Right_Near_SPH", function (e) {
        const Far_SPH = $('#Right_Far_SPH').val();
        const Near_SPH = $('#Right_Near_SPH').val();
        $('#Right_Addition').val(Far_SPH-Near_SPH);
    });
    $(document).on("change", "#Left_Far_SPH,#Right_Near_SPH", function (e) {
        const Far_SPH = $('#Left_Far_SPH').val();
        const Near_SPH = $('#Left_Near_SPH').val();
        $('#Left_Addition').val(Far_SPH-Near_SPH);
    });

    $('.predefinedShape').on('click',function () {
        $(this).addClass('selected');
        code =$(this).data('ccode');
        $('#input_predefined_shape').val(code);
        $(this).siblings().removeClass('selected');
    })


    $('#LeftLens').on('change', function () {
        var cke;
        if (this.checked) {
            $('#sameToRight').attr('disabled', false);
            $('#Left_Far_Axis').attr('disabled', false);
            $('#Left_Near_Axis').attr('disabled', false);
            $('#Left_Far_CYL').attr('disabled', false);
            $('#Left_Near_CYL').attr('disabled', false);
            $('#Left_Far_SPH').attr('disabled', false);
            $('#Left_Near_SPH').attr('disabled', false);
            $('#Left_Addition').attr('disabled', false);


            $('#Left_Near_CYLDeg').prop('disabled', false);
            $('#Left_Near_SPHDeg').prop('disabled', false);
            $('#Left_Far_CYLDeg').prop('disabled', false);
            $('#Left_Far_SPHDeg').prop('disabled', false);
        } else {
            cke = $('#sameToRight');
            cke.attr('disabled', true);
            cke.prop('checked', false);

            $('#Left_Near_CYLDeg').prop('disabled', true);
            $('#Left_Near_SPHDeg').prop('disabled', true);
            $('#Left_Far_CYLDeg').prop('disabled', true);
            $('#Left_Far_SPHDeg').prop('disabled', true);

            $('#Left_Far_Axis').attr('disabled', true);
            $('#Left_Near_Axis').attr('disabled', true);
            $('#Left_Far_CYL').attr('disabled', true);
            $('#Left_Near_CYL').attr('disabled', true);
            $('#Left_Far_SPH').attr('disabled', true);
            $('#Left_Near_SPH').attr('disabled', true);
            $('#Left_Addition').attr('disabled', true);


            $('#Left_Far_Axis').val('');
            $('#Left_Near_Axis').val('');
            $('#Left_Far_CYL').val('');
            $('#Left_Near_CYL').val('');
            $('#Left_Far_SPH').val('');
            $('#Left_Near_SPH').val('');
            $('#Left_Addition').val('');
        }

    });
    $('#VATintingCheck').on('change', function () {
        if (this.checked) {
           $('.color_class').removeClass('d-none')
        } else {

            $('.color_class').addClass('d-none')

        }

    });
    $('#VABaseCheck').on('change', function () {
        if (this.checked) {
           $('.VABaseCheck_class').removeClass('d-none')
        } else {

            $('.VABaseCheck_class').addClass('d-none')

        }

    });
    $('#specific_diameter').on('change', function () {
        if (this.checked) {
           $('.specific_diameter_class').removeClass('d-none')
        } else {

            $('.specific_diameter_class').addClass('d-none')

        }

    });
    $('#codeCheck').on('change', function () {
        if (this.checked) {
           $('.owf-page-shapeDefinition-manual-shape ').removeClass('d-none')
        } else {

            $('.owf-page-shapeDefinition-manual-shape ').addClass('d-none')

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

    // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
    $('#moreInfoCollapse').on('show.bs.collapse', function () {
        // Change the arrow icon to 'chevron-up' when the content is expanded
        $('button[data-bs-target="#moreInfoCollapse"] i').removeClass('fa-arrow-down').addClass(
            'fa-arrow-up');
    });

    $('#moreInfoCollapse').on('hide.bs.collapse', function () {
        // Change the arrow icon to 'chevron-down' when the content is collapsed
        $('button[data-bs-target="#moreInfoCollapse"] i').removeClass('fa-arrow-up').addClass(
            'fa-arrow-down');
    });

    $(document).on("change", "#sameToRight,#Right_Far_Axis,#Right_Near_Axis,#Right_Far_CYL,#Right_Near_CYL,#Right_Far_SPH,#Right_Near_SPH,#Right_Addition,#Right_Near_CYLDeg,#Right_Near_SPHDeg,#Right_Far_CYLDeg,#Right_Far_SPHDeg", function (e) {
        if ($('#sameToRight').prop('checked')) {
            sameToRight();
        }
    });
    function sameToRight () {
        $('#Left_Far_Axis').val( $('#Right_Far_Axis').val());
        $('#Left_Near_Axis').val( $('#Right_Near_Axis').val());
        $('#Left_Far_CYL').val( $('#Right_Far_CYL').val());
        $('#Left_Near_CYL').val( $('#Right_Near_CYL').val());
        $('#Left_Far_SPH').val( $('#Right_Far_SPH').val());
        $('#Left_Near_SPH').val( $('#Right_Near_SPH').val());
        $('#Left_Addition').val( $('#Right_Addition').val());

        $('#Left_Near_CYLDeg').val( $('#Right_Near_CYLDeg').val());
        $('#Left_Near_SPHDeg').val( $('#Right_Near_SPHDeg').val());
        $('#Left_Far_CYLDeg').val( $('#Right_Far_CYLDeg').val());
        $('#Left_Far_SPHDeg').val( $('#Right_Far_SPHDeg').val());



        $('#Left_Near_CYLDeg').selectpicker('refresh');
        $('#Left_Near_SPHDeg').selectpicker('refresh');
        $('#Left_Far_CYLDeg').selectpicker('refresh');
        $('#Left_Far_SPHDeg').selectpicker('refresh');
    }
</script>
