@extends('back-end.layouts.app')
@section('title', __('lang.sales_list'))
@section('styles')
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
        --owf-font-family: "Arial", sans-serif;
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
        --owf-login-button-background-color: linear-gradient(45deg, var(--owf-color-primary-gradient-from), var(--owf-color-primary-gradient-to) 50%);
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

    .breadcrumbbar {
        display: none
    }

    .check-line {
        display: flex;
    }

    table#Right_Lens_Table,
    table#Left_Lens_Table {
        height: 100%;
    }

    span.bigLatter {
        font-size: 30px;
        font-weight: 700;
        color: #696969;
        text-shadow: -1px 2px 4px #979797;
    }

    .icheckbox_square-orange {
        padding: 0;
    }

    .tab-pane .table-bordered> :not(caption)>* {
        border-width: 0 !important;
    }

    .tab-pane input[type="number"]::-webkit-inner-spin-button,
    .tab-pane input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }


    .table-bordered>tbody>tr>td,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
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
        display: grid;
    }

    .owf-headline:first-child {
        padding-top: 0;
    }

    .owf-headline {
        align-items: center;
        color: var(--owf-color-primary);
        display: flex;
        font-size: var(--owf-font-size-default);
        font-weight: 700;
        gap: .2rem;
        justify-content: space-between;
        line-height: 1.6;
        padding-top: calc(1rem * var(--owf-shallow-scale-75));
        -webkit-user-select: none;
        user-select: none;
    }


    /*    ----- */

    .owf-page-shapeDefinition-manual-predefinedShape-container {
        column-gap: .5rem;
        display: grid;
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

    .dropdown.bootstrap-select.form-control.input-block-level.lensPlusMinusSelect.CYLPlusMinusSelect,
    .dropdown.bootstrap-select.form-control.input-block-level.lensPlusMinusSelect.SPHPlusMinusSelect {
        background: #fff !important;
    }

    .dropdown.bootstrap-select.disabled.form-control.input-block-level.lensPlusMinusSelect.SPHPlusMinusSelect,
    .dropdown.bootstrap-select.disabled.form-control.lensPlusMinusSelect.CYLPlusMinusSelect.input-block-level {
        background: #eaecef !important;
    }

    .lens-vu-per {
        margin: 25px auto;
        background: #bbd6d175 !important;
        border-radius: 15px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .lens-vu-item-per {
        color: #015b4d;
        font-weight: 600;
    }

    .lens-vu-item-per.total-lens {
        color: #339a40;
    }

    span.price-lens {
        float: inline-end;
    }
</style>
@endsection
@section('content')
<section class="forms px-3 py-1 pos-section no-print">
    <div class="container-fluid px-2">
        <div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
            <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#moreInfoCollapseFiltter" aria-expanded="false" aria-controls="moreInfoCollapseFiltter">
                <div style="width: 20px">
                    <img class="w-100" src="{{ asset('front/white-filter.png') }}" alt="">
                </div>
                {{translate('Filter')}}
                <span class="section-header-pill"></span>
            </button>
        </div>
        <div class="collapse" id="moreInfoCollapseFiltter">
            <div class="row card my-2 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <input type="hidden" name="type_trans" id="type_trans" value="{{request()->type_trans}}">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('category_id', __('lang.category') . ':', ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('category_id[]', $categories, request()->category_id, ['class'
                        =>
                        'form-control sale_filter selectpicker', 'data-live-search' => 'true',
                        'data-actions-box' => 'true', 'placeholder' => __('lang.all'), 'multiple', 'id'
                        =>
                        'category_id']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('brand_id', __('lang.brand') . ':', ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('brand_id[]', $brands, request()->brand_id, ['class' =>
                        'form-control sale_filter selectpicker', 'data-live-search' => 'true',
                        'data-actions-box' => 'true', 'placeholder' => __('lang.all'), 'multiple', 'id'
                        =>
                        'brand_id']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('customer_id', __('lang.customer'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('customer_id', $customers, request()->customer_id, ['class' =>
                        'form-control sale_filter', 'placeholder' => __('lang.all'), 'data-live-search'
                        =>
                        'true']) !!}
                    </div>
                </div>
                @if (session('system_mode') == 'restaurant')
                @php
                $customer_types = $customer_types->toArray() + ['dining_in' => __('lang.dining_in')];
                @endphp
                @endif
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('customer_type_id', __('lang.customer_type'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('customer_type_id', $customer_types,
                        request()->customer_type_id,
                        ['class' => 'form-control sale_filter', 'placeholder' => __('lang.all'),
                        'data-live-search' => 'true']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('store_id', __('lang.store'), ['class' => app()->isLocale('ar') ?
                        'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('store_id', $stores, request()->store_id, ['class' =>
                        'form-control
                        sale_filter', 'placeholder' => __('lang.all'), 'data-live-search' => 'true'])
                        !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('status', __('lang.status'), ['class' => app()->isLocale('ar') ?
                        'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('status', ['final' => 'Completed', 'pending' => 'Pending'],
                        request()->status, ['class' => 'form-control sale_filter', 'placeholder' =>
                        __('lang.all'), 'data-live-search' => 'true']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('method', __('lang.payment_type'), ['class' => app()->isLocale('ar')
                        ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('method', $payment_types, request()->method, ['class' =>
                        'form-control sale_filter', 'placeholder' => __('lang.all'), 'data-live-search'
                        =>
                        'true']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('payment_status', __('lang.payment_status'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('payment_status', $payment_status_array,
                        request()->payment_status,
                        ['class' => 'form-control sale_filter', 'placeholder' => __('lang.all'),
                        'data-live-search' => 'true']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('tax_id', __('lang.tax') . ':', ['class' => app()->isLocale('ar') ?
                        'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('tax_id[]', $taxes, request()->tax_id, ['class' =>
                        'form-control
                        sale_filter selectpicker', 'data-live-search' => 'true', 'data-actions-box' =>
                        'true', 'placeholder' => __('lang.all'), 'multiple', 'id' => 'tax_id']) !!}
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('start_date', __('lang.generation_start_date'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('start_date', request()->start_date, ['class' => 'form-control
                        sale_filter']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('start_time', __('lang.generation_start_time'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('start_time', null, ['class' => 'form-control time_picker
                        sale_filter']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('end_date', __('lang.generation_end_date'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('end_date', request()->end_date, ['class' => 'form-control
                        sale_filter']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('end_time', __('lang.generation_end_time'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('end_time', null, ['class' => 'form-control time_picker
                        sale_filter']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('payment_start_date', __('lang.payment_start_date'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('start_date', request()->start_date, ['class' => 'form-control
                        datepicker sale_filter', 'id' => 'payment_start_date']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('payment_start_time', __('lang.payment_start_time'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('payment_start_time', null, ['class' => 'form-control time_picker
                        sale_filter']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('payment_end_date', __('lang.payment_end_date'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('end_date', request()->payment_end_date, ['class' => 'form-control
                        sale_filter', 'id' => 'payment_end_date']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('payment_end_time', __('lang.payment_end_time'), ['class' =>
                        app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::text('payment_end_time', null, ['class' => 'form-control time_picker
                        sale_filter']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('created_by', __('lang.cashier'), ['class' => app()->isLocale('ar')
                        ? 'mb-1 label-ar' : 'mb-1 label-en'
                        ]) !!}
                        {!! Form::select('created_by', $cashiers, false, ['class' => 'form-control
                        sale_filter selectpicker', 'id' => 'created_by', 'data-live-search' => 'true',
                        'placeholder' => __('lang.all')]) !!}
                    </div>
                </div>
                <div class="col-md-2 d-flex justify-content-center align-items-end mb-11px">
                    <button type="submit" class="btn btn-primary w-100" id="submit-filter">@lang('lang.filter')</button>
                </div>
                <div class="col-md-2 d-flex justify-content-center align-items-end mb-11px">
                    <button type="button" class="btn btn-danger w-100 clear_filter">@lang('lang.clear_filter')</button>
                </div>
            </div>
        </div>



        <div class="card my-2">

            <div class="table-responsive" style="height: 60vh">
                <table id="sales_table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('lang.date_and_time')</th>
                            <th>@lang('lang.reference')</th>
                            <th>@lang('lang.store')</th>
                            <th>@lang('lang.customer')</th>
                            <th>@lang('lang.phone')</th>
                            <th>@lang('lang.sale_status')</th>
                            <th>@lang('lang.payment_status')</th>
                            <th>@lang('lang.payment_type')</th>
                            <th>@lang('lang.ref_number')</th>
                            <th class="currencies">@lang('lang.received_currency')</th>
                            <th class="sum">@lang('lang.grand_total')</th>
                            <th class="sum">@lang('lang.paid')</th>
                            <th class="sum">@lang('lang.due_sale_list')</th>
                            <th class="sum">{{translate('total_item_tax')}}</th>
                            <th>@lang('lang.payment_date')</th>
                            <th>@lang('lang.cashier')</th>
                            <th>@lang('lang.commission')</th>
                            <th>@lang('lang.products')</th>
                            <th>@lang('lang.sku')</th>
                            <th>@lang('lang.sale_note')</th>
                            <th>@lang('lang.files')</th>
                            <th class="notexport">@lang('lang.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th class="table_totals" style="text-align: right">@lang('lang.totals')</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div
            class="bottom-controls mt-1 p-1 d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap">
            <!-- Pagination and other controls can go here -->
            <div class="col-md-8 mb-0 text-center">
                <h4 class="mb-0">@lang('lang.number_of_orders'): <span class="number_of_orders_span"
                        style="margin-right: 15px;">0</span>
                    @lang('lang.number_of_customer'): <span class="number_of_customer_span"
                        style="margin-right: 15px;">0</span>
                </h4>
            </div>
        </div>
    </div>
</section>
<!-- This will be printed -->
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

<script>
    $(document).ready(function() {

            get_total_details();
            sales_table = $("#sales_table").DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                // order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                stateSave: true,
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, "desc"]
                ],
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent()
                        .attr('autocomplete', 'off');
                },
                ajax: {
                    url: "{{route('admin.sale.index')}}",
                    data: function(d) {
                        d.category_id = $("#category_id").val();
                        d.brand_id = $("#brand_id").val();
                        d.tax_id = $("#tax_id").val();
                        d.customer_id = $("#customer_id").val();
                        d.customer_type_id = $("#customer_type_id").val();
                        d.store_id = $("#store_id").val();
                        d.status = $("#status").val();
                        d.method = $("#method").val();
                        d.payment_status = $("#payment_status").val();
                        d.start_date = $("#start_date").val();
                        d.start_time = $("#start_time").val();
                        d.end_date = $("#end_date").val();
                        d.end_time = $("#end_time").val();
                        d.payment_start_date = $("#payment_start_date").val();
                        d.payment_start_time = $("#payment_start_time").val();
                        d.payment_end_date = $("#payment_end_date").val();
                        d.payment_end_time = $("#payment_end_time").val();
                        d.created_by = $("#created_by").val();
                        d.type_trans = $("#type_trans").val();
                    },
                },
                columnDefs: [{
                        targets: "date",
                        type: "date-eu",
                    },

                        {
                            targets: [14],
                            orderable: false,
                            searchable: false,
                        }
                ],
                columns: [{
                        data: "transaction_date",
                        name: "transaction_date"
                    },
                    {
                        data: "invoice_no",
                        name: "invoice_no"
                    },
                    {
                        data: "store_name",
                        name: "stores.name"
                    },
                    {
                        data: "customer_name",
                        name: "customers.name"
                    },
                    {
                        data: "mobile_number",
                        name: "customers.mobile_number"
                    },
                    {
                        data: "status",
                        name: "transactions.status"
                    },
                    {
                        data: "payment_status",
                        name: "transactions.payment_status"
                    },
                    {
                        data: "method",
                        name: "transaction_payments.method"
                    },
                    {
                        data: "ref_number",
                        name: "transaction_payments.ref_number"
                    },
                    {
                        data: "received_currency_symbol",
                        name: "received_currency_symbol",
                        searchable: false
                    },
                    {
                        data: "final_total",
                        name: "final_total"
                    },
                    {
                        data: "paid",
                        name: "transaction_payments.amount",
                        searchable: false
                    },
                    {
                        data: "due",
                        name: "transaction_payments.amount",
                        searchable: false
                    },{
                        data: "total_item_tax",
                        name: "total_item_tax"
                    },
                    {
                        data: "paid_on",
                        name: "transaction_payments.paid_on"
                    },
                    {
                        data: "created_by",
                        name: "admins.name"
                    },

                    {
                        data: "commissions",
                        name: "commissions",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "products",
                        name: "products.name"
                    },
                    {
                        data: "sku",
                        name: "products.sku",
                        visible: false
                    },
                    {
                        data: "sale_note",
                        name: "sale_note",
                    },
                    {
                        data: "files",
                        name: "files",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "action",
                        name: "action"
                    },
                ],
                createdRow: function(row, data, dataIndex) {},
                footerCallback: function(row, data, start, end, display) {
                    var intVal = function(i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                            i :
                            0;
                    };

                    this.api()
                        .columns(".currencies", {
                            page: "current"
                        }).every(function() {
                            var column = this;
                            let currencies_html = '';
                            $.each(currency_obj, function(key, value) {
                                currencies_html +=
                                    `<h6 class="footer_currency" data-is_default="${value.is_default}"  data-currency_id="${value.currency_id}">${value.symbol}</h6>`
                                $(column.footer()).html(currencies_html);
                            });
                        })
                    this.api()
                        .columns(".sum", {
                            page: "current"
                        })
                        .every(function() {
                            var column = this;
                            var currency_total = [];

                            $.each(currency_obj, function(key, value) {
                                currency_total[value.currency_id] = 0;
                            });

                            function parseEuroNumber(val) {
                                if (typeof val === "string") {
                                    val = val.replace(/\./g, '').replace(',', '.');
                                }
                                return parseFloat(val) || 0;
                            }

                            column.data().each(function(group, i) {
                                b = $(group).text();
                                currency_id = $(group).data('currency_id');

                                $.each(currency_obj, function(key, value) {
                                    if (currency_id == value.currency_id) {
                                        currency_total[value.currency_id] += parseEuroNumber(b);
                                    }
                                });
                            });

                            var footer_html = '';
                            $.each(currency_obj, function(key, value) {
                                footer_html +=
                                    `<h6 class="currency_total currency_total_${value.currency_id}" data-currency_id="${value.currency_id}" data-is_default="${value.is_default}" data-conversion_rate="${value.conversion_rate}" data-base_conversion="${currency_total[value.currency_id] * value.conversion_rate}" data-orig_value="${currency_total[value.currency_id]}">${__currency_trans_from_en(currency_total[value.currency_id], false)}</h6>`;
                            });

                            $(column.footer()).html(footer_html);
                        });

                },
                initComplete: function (settings, json) {
                // Move elements into the .top-controls div after DataTable initializes
                $('.top-controls').append($('.dataTables_length').addClass('d-flex col-lg-3 col-9 mb-3 mb-lg-0 justify-content-center'));
                $('.top-controls').append($('.dt-buttons').addClass('col-lg-6 col-12 mb-3 mb-lg-0 d-flex dt-gap justify-content-center'));
                $('.top-controls').append($('.dataTables_filter').addClass('col-lg-3 col-9'));


                $('.bottom-controls').append($('.dataTables_paginate').addClass('col-lg-2 col-9 p-0'));
                $('.bottom-controls').append($('.dataTables_info'));
                }
            });

        $('#store_table_filter input').attr('autocomplete', 'off');
            $(document).on('click', '#submit-filter', function() {
                sales_table.ajax.reload();
                get_total_details();
            });
            $('#sales_table').on('column-visibility.dt', function (e, settings, column, state) {
                // Here, you can use cookies or local storage to save the column visibility state
                // Example using local storage:
                var savedColumnPreferences = JSON.parse(localStorage.getItem('columnPreferences')) || {};
                savedColumnPreferences[column] = state;
                localStorage.setItem('columnPreferences', JSON.stringify(savedColumnPreferences));
            });
            // Restore column visibility preferences on page load
            var savedColumnPreferences = JSON.parse(localStorage.getItem('columnPreferences')) || {};
            for (var column in savedColumnPreferences) {
                var columnIdx = dataTable.column(column).index();
                dataTable.column(columnIdx).visible(savedColumnPreferences[column]);
            }
        })

        $(document).on('change', '#customer_type_id', function() {
            let customer_type_id = $(this).val();
            if (customer_type_id === 'dining_in') {
                $('.dining_filters').removeClass('hide');
            } else {
                $('.dining_filters').addClass('hide');
            }
        })
        $(document).on('click', '.clear_filter', function() {
            $('.sale_filter').val('');
            $('.sale_filter').selectpicker('refresh');
            sales_table.ajax.reload();
        });
        $(document).on('click', '.print-invoice', function() {
            $(".modal").modal("hide");
            $.ajax({
                method: "get",
                url: $(this).data("href"),
                data: {},
                success: function(result) {
                    if (result.success) {
                        pos_print(result.html_content);
                    }
                },
            });
        })

        function pos_print(receipt) {
            $("#receipt_section").html(receipt);
            __currency_convert_recursively($("#receipt_section"));
            __print_receipt("receipt_section");
        }

        function get_total_details() {
            $.ajax({
                method: 'GET',
                url: "{{route('admin.sale.getTotalDetails')}}",
                data: {
                    category_id : $("#category_id").val(),
                    brand_id : $("#brand_id").val(),
                    tax_id : $("#tax_id").val(),
                    customer_id : $("#customer_id").val(),
                    customer_type_id : $("#customer_type_id").val(),
                    store_id : $("#store_id").val(),
                    status_ : $("#status").val(),
                    method : $("#method").val(),
                    payment_status : $("#payment_status").val(),
                    start_date : $("#start_date").val(),
                    start_time : $("#start_time").val(),
                    end_date : $("#end_date").val(),
                    end_time : $("#end_time").val(),
                    payment_start_date : $("#payment_start_date").val(),
                    payment_start_time : $("#payment_start_time").val(),
                    payment_end_date : $("#payment_end_date").val(),
                    payment_end_time : $("#payment_end_time").val(),
                    created_by : $("#created_by").val(),
                    type_trans : $("#type_trans").val(),
                },
                success: function(result) {
                    $('.number_of_customer_span').text(result.customer_count)
                    $('.number_of_orders_span').text(result.sales_count)
                },
            });
        }


</script>
@endsection
