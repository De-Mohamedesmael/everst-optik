<!DOCTYPE html>
<html lang="{{app()->getLocale()  }}">
@php
    $logo = \Modules\Setting\Entities\System::getProperty('logo');
    $site_title =\Modules\Setting\Entities\System::getProperty('site_title');
@endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow"/>
    <link rel="icon" type="image/png" href="{{ asset('assets/back-end/system/' . $logo) }}"/>
    <meta name="author" content="Themesbox17">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ $site_title .' | '}}@yield('title')</title>

    <!-- Fevicon -->
    @include('back-end.layouts.partials.css')
    <link href="{{asset('assets/back-end/css/bootstrap5-3.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('assets/back-end/css/front-style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/back-end/css/animation.css') }}">
    <!-- End css -->
    <style>
        button.btn.table-btns.buttons-collection.dropdown-toggle.buttons-colvis, .btn-group > .btn-group:not(:last-child) > .btn, .btn-group > .btn.dropdown-toggle-split:first-child, .btn-group > .btn:not(:last-child):not(.dropdown-toggle) {
            background: #3e5d58;
        }

        .dt-buttons.btn-group {
            direction: ltr;
        }

        .dataTables_filter, .dataTables_length, .dt-buttons {
            padding: 0 10px;
        }

        .dt-button-collection.dropdown-menu, .dropdown-menu.show {
            z-index: 10000;
            background: #ffffff;
        }
        nav.navbar .dropdown-menu {
            min-width: 200px !important;
        }
        .navbar_item {
            justify-content: start !important;
        }
    </style>
    <style>
        :root {
            --primary-color: rgba(69, 147, 134, 0.38);
            /* Light Blue */
            --secondary-color: #578981;
            /* Bright Blue */
            --tertiary-color: #3e5d58;
            /* Dark Blue */
            --complementary-color-1: #1f675c;
            /* Muted Blue-Green */
            --complementary-color-2: #a5d6a7;
            /* Light Muted Blue-Green */
            --text-color: #333;
            /* Dark Gray for Text */
            --white: #fff;
            /* Dark Gray for Text */
            --accent-color: #e57373;
            /* Soft Muted Red */

        }


        .ui-menu-item-wrapper.ui-state-active {
            background-color: var(--tertiary-color) !important;
            color: #fff !important;
        }

        .ui-menu.ui-widget.ui-widget-content.ui-autocomplete.ui-front li.ui-menu-item {
            color: var(--tertiary-color) !important;
            opacity: 1 !important;
        }

        .ui-state-disabled, .ui-widget-content .ui-state-disabled, .ui-widget-header .ui-state-disabled {
            opacity: 1 !important;
        }

        .modal {
            --bs-modal-width: 80% !important;
            /* max modal width */
        }

        div#ui-datepicker-div {
            width: revert;
        }

        div#select_products_modal .modal-dialog {
            max-width: 90% !important;
        }

        .card {
            border-radius: 5px;
            border: none;
            background-color: #ffffff;
            box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        }

        .btn.btn-main {
            background-color: #3e5d58;
            border-color: #3e5d58;
            color: #fff;
        }

        div.ui-datepicker {
            z-index: 1000 !important;
        }

        button.btn.table-btns {
            margin-right: .25rem !important;
            margin-left: .25rem !important;
            border-radius: var(--bs-border-radius-lg) !important;
        }

        input#show_zero_stocks {
            appearance: auto !important;
            -webkit-appearance: auto !important;
        }

        button.select-button.btn-flat.translation_btn .dripicons-web:before {
            color: #fff;
        }

        .header-pill,
        .toggle-pill,
        .header-modal-pill {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: var(--secondary-color);

        }

        .section-header-pill {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--secondary-color);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.8rem !important;
        }

        .modal-input {
            width: 100% !important;
            background-color: #e6e6e6 !important;
            outline: none !important;
            border: 1px solid #e6e6e6 !important;
            padding: 2px 5px !important;
            border-radius: 6px !important;
        }

        .bootstrap-select {
            width: 100% !important;
            background-color: #e6e6e6 !important;
            outline: none !important;
            border: 1px solid #e6e6e6 !important;
            padding: 2px 5px !important;
            border-radius: 6px !important;
        }

        .bootstrap-select button {
            height: 100%;
            width: 100%;
            padding: 0 15px;
        }

        .text-end {
            text-align: end;
        }

        .toggle-button {
            color: black !important;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 5px 20px;
            background-color: var(--primary-color);
            border-radius: 8px;
            outline: none !important;
            border: none !important;
            transition: 0.6s;
        }

        .toggle-button:hover {
            color: black !important;
            background-color: rgba(69, 147, 134, 0.38);;
        }

        .select-button {
            outline: none;
            border: none;
            color: white;
            background-color: var(--complementary-color-1);
            border-radius: 6px;
            height: 100%;
        }

        .select-button-group {
            background-color: #e6e6e6 !important;
            border-radius: 6px !important;
        }

        #submit-btn {
            background-color: var(--secondary-color);
            color: white;
            width: 100%;
            border-radius: 6px;
            font-weight: 500;
            font-size: 1rem;
        }

        .btn-main {
            background-color: var(--secondary-color) !important;
            color: white !important;
        }

        .close {
            margin: 0 !important;
            padding: 0 !important;
            width: 30px;
            height: 30px;
            /*background-color: #d70007 !important;*/
            opacity: 1;
        }

        .modal-header {
            padding: 10px 40px;
        }

        .modal-border {
            bottom: -12%;
            left: 15%;
            background-color: #dadada;
            width: 70%;
            height: 3px;
        }

        .modal-footer {
            border: none !important;
            width: 70%;
            margin: auto;
        }

        .card {
            box-shadow: 0 6px 8px -6px #999 !important;
        }

        .btn-light {
            background-color: transparent !important;
        }


        .select_product_button {
            border: none;
            outline: none;
            background-color: var(--primary-color);
            border-radius: 6px;
            padding: 0px 12px;
            transition: 0.6s;
            font-size: 14px;
            font-weight: 600;
        }

        .select_product_button:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .select-button-group .bootstrap-select {
            width: 85% !important;
        }

        .select-button-group input {
            width: fit-content !important;
        }


        input.form-control-custom[checked] + label::before, input.form-control-custom[checked] + label::after {
            border: none;
        }

        input.form-control-custom:checked + label::after {
            opacity: 1 !important;
        }

        input.form-control-custom + label::after {
            content: "\f00c";
            display: block;
            font-size: 8px;
            color: #fff !important;
            position: absolute;
            top: 4px;
            left: 4px;
            -webkit-transition: all 0.1s;
            transition: all 0.1s;
            opacity: 0;
            cursor: pointer;
        }

        input.form-control-custom:checked + label::before {
            background: var(--complementary-color-1) !important;
        }

        input.form-control-custom[checked] + label::before, input.form-control-custom[checked] + label::after {
            border: none;
        }

        input.form-control-custom + label::before {
            content: "";
            display: block;
            width: 16px;
            height: 16px;
            line-height: 16px;
            background: #ddd !important;
            color: #fff !important;
            text-align: center;
            position: absolute;
            top: 2px;
            left: 0;
            -webkit-transition: all 0.1s;
            transition: all 0.1s;
            cursor: pointer;
        }

        input.form-control-custom + label {
            font-size: 0.75em !important;
            margin-bottom: 0;
            margin-left: 0;
            color: #999 !important;
            padding-left: 25px;
            padding-top: 2px;
            position: relative;
        }

        @media (min-width: 768px) {
            .forms label {
                font-size: 0.9rem;
            }
        }

        .table-responsive .table a.btn.btn-modal {
            color: #3e5d58;
            background: #3e5d5821;
            border: 1px solid #3e5d586b;
        }

        table .btn-group button.dropdown-toggle {
            background-color: #3e5d58 !important;
            color: white !important;
        }

        span.category_name {
            color: #3e5d58 !important;
        }

        a.btn.text-red {
            color: #a30c0c !important;
        }

        .home-card-deck {
            margin-bottom: 10px;
        }
    </style>
    <style>
        .variants {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .variants > div {
            margin-right: 5px;
        }

        .variants > div:last-of-type {
            margin-right: 0;
        }

        .file {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file > input[type='file'] {
            display: none
        }

        .file > label {
            font-size: 1rem;
            font-weight: 300;
            cursor: pointer;
            outline: 0;
            user-select: none;
            border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
            border-style: solid;
            border-radius: 4px;
            border-width: 1px;
            background-color: hsl(0, 0%, 100%);
            color: hsl(0, 0%, 29%);
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file > label:hover {
            border-color: hsl(0, 0%, 21%);
        }

        .file > label:active {
            background-color: hsl(0, 0%, 96%);
        }

        .file > label > i {
            padding-right: 5px;
        }

        .file--upload > label {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .file--upload > label:hover {
            border-color: var(--secondary-color);
            background-color: #145d1a
        }

        .file--upload > label:active {
            background-color: hsl(204, 86%, 91%);
        }

        .file--uploading > label {
            color: hsl(48, 100%, 67%);
            border-color: hsl(48, 100%, 67%);
        }

        /*  */
        .file--uploading > label > i {
            animation: pulse 5s infinite;
        }

        .file--uploading > label:hover {
            border-color: hsl(48, 100%, 67%);
            background-color: hsl(48, 100%, 96%);
        }

        .file--uploading > label:active {
            background-color: hsl(48, 100%, 91%);
        }

        .file--success > label {
            color: hsl(141, 71%, 48%);
            border-color: hsl(141, 71%, 48%);
        }

        .file--success > label:hover {
            border-color: hsl(141, 71%, 48%);
            background-color: hsl(141, 71%, 96%);
        }

        .file--success > label:active {
            background-color: hsl(141, 71%, 91%);
        }

        .file--danger > label {
            color: hsl(348, 100%, 61%);
            border-color: hsl(348, 100%, 61%);
        }

        .file--danger > label:hover {
            border-color: hsl(348, 100%, 61%);
            background-color: hsl(348, 100%, 96%);
        }

        .file--danger > label:active {
            background-color: hsl(348, 100%, 91%);
        }

        .file--disabled {
            cursor: not-allowed;
        }

        .file--disabled > label {
            border-color: #e6e7ef;
            color: #e6e7ef;
            pointer-events: none;
        }

        .file .modal-input {

            padding: 25px 25px !important;
        }


        @keyframes pulse {
            0% {
                color: hsl(48, 100%, 67%);
            }

            50% {
                color: hsl(48, 100%, 38%);
            }

            100% {
                color: hsl(48, 100%, 67%);
            }
        }

        .home-card-deck {
            margin: 5px 0;
        }
    </style>
    @yield('styles')
    @stack('style')
</head>
<section class="invoice print_section print-only" id="receipt_section"> </section>

<div class="horizontal-layout relative  no-print">
    <div id="loader" style="display: none;"></div>
    <div class="overlay">
        <div style="width: 55%;overflow: hidden;position: relative;">
            <img style="width: 100%;z-index: 10;position: relative;"
                 src="{{ asset('assets/back-end/images/logo3.png') }}"
                 alt="logo">
            <span class="box"></span>
        </div>

    </div>

    <div id="content  no-print">
        <div id="infobar-notifications-sidebar" class="infobar-notifications-sidebar">
            <div class="infobar-notifications-sidebar-head d-flex w-100 justify-content-between">
                <h4>Notifications</h4><a href="javascript:void(0)" id="infobar-notifications-close"
                                         class="infobar-notifications-close"><img
                        src="{{ asset('assets/back-end/images/svg-icon/close.svg') }}"
                        class="img-fluid menu-hamburger-close" alt="close"></a>
            </div>
            <div class="infobar-notifications-sidebar-body">
                <ul class="nav nav-pills nav-justified" id="infobar-pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-messages-tab" data-toggle="pill" href="#pills-messages"
                           role="tab" aria-controls="pills-messages" aria-selected="true">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-emails-tab" data-toggle="pill" href="#pills-emails" role="tab"
                           aria-controls="pills-emails" aria-selected="false">Emails</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-actions-tab" data-toggle="pill" href="#pills-actions" role="tab"
                           aria-controls="pills-actions" aria-selected="false">Actions</a>
                    </li>
                </ul>
                <div class="tab-content" id="infobar-pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-messages" role="tabpanel"
                         aria-labelledby="pills-messages-tab">
                        <ul class="list-unstyled">
                            <li class="media">
                                <img class="mr-3 align-self-center rounded-circle"
                                     src="{{ asset('assets/back-end/images/users/girl.svg') }}"
                                     alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5>Amy Adams<span class="badge badge-success">1</span><span class="timing">Jan
                                            22</span></h5>
                                    <p>Hey!! What are you doing tonight ?</p>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 align-self-center rounded-circle"
                                     src="{{ asset('assets/back-end/images/users/boy.svg') }}"
                                     alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5>James Simpsons<span class="badge badge-success">2</span><span class="timing">Feb
                                            15</span></h5>
                                    <p>What's up ???</p>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 align-self-center rounded-circle"
                                     src="{{ asset('assets/back-end/images/users/men.svg') }}"
                                     alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5>Mark Witherspoon<span class="badge badge-success">3</span><span class="timing">Mar
                                            03</span></h5>
                                    <p>I will be late today in office.</p>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 align-self-center rounded-circle"
                                     src="{{ asset('assets/back-end/images/users/women.svg') }}"
                                     alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5>Jenniffer Wills<span class="badge badge-success">4</span><span class="timing">Apr
                                            05</span></h5>
                                    <p>Venture presentation is ready.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pills-emails" role="tabpanel" aria-labelledby="pills-emails-tab">
                        <ul class="list-unstyled">
                            <li class="media">
                                <span class="mr-3 align-self-center img-icon">N</span>
                                <div class="media-body">
                                    <h5>Nelson Smith<span class="timing">Jan 22</span></h5>
                                    <p><span class="badge badge-danger-inverse">WORK</span>Salary has been processed.
                                    </p>
                                </div>
                            </li>
                            <li class="media">
                                <span class="mr-3 align-self-center img-icon">C</span>
                                <div class="media-body">
                                    <h5>Courtney Cox<i class="feather icon-star text-warning ml-2"></i><span
                                            class="timing">Feb 15</span></h5>
                                    <p><span class="badge badge-success-inverse">URGENT</span>New product launching...
                                    </p>
                                </div>
                            </li>
                            <li class="media">
                                <span class="mr-3 align-self-center img-icon">R</span>
                                <div class="media-body">
                                    <h5>Rachel White<span class="timing">Mar 03</span></h5>
                                    <p><span class="badge badge-secondary-inverse">ORDER</span><span
                                            class="badge badge-info-inverse">SHOPPING</span>Your order has been...</p>
                                </div>
                            </li>
                            <li class="media">
                                <span class="mr-3 align-self-center img-icon">F</span>
                                <div class="media-body">
                                    <h5>Freepik<span class="timing">Mar 03</span></h5>
                                    <p><a href="#" class="badge badge-primary mr-2">VERIFY NOW</a>New Sign
                                        verification req...</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pills-actions" role="tabpanel" aria-labelledby="pills-actions-tab">
                        <ul class="list-unstyled">
                            <li class="media">
                                <span class="mr-3 action-icon badge badge-success-inverse"><i
                                        class="feather icon-check"></i></span>
                                <div class="media-body">
                                    <h5 class="action-title">Payment Success !!!</h5>
                                    <p class="my-3">We have received your payment toward ad Account : 9876543210.
                                        Your Ad
                                        is Running.</p>
                                    <p><span class="badge badge-danger-inverse">INFO</span><span
                                            class="badge badge-info-inverse">STATUS</span><span class="timing">Today,
                                            09:39 PM</span></p>
                                </div>
                            </li>
                            <li class="media">
                                <span class="mr-3 action-icon badge badge-primary-inverse"><i
                                        class="feather icon-calendar"></i></span>
                                <div class="media-body">
                                    <h5 class="action-title">Nobita Applied for Leave.</h5>
                                    <p class="my-3">Nobita applied for leave due to personal reasons on 22nd Feb.</p>
                                    <p><span class="badge badge-success">APPROVE</span><span
                                            class="badge badge-danger">REJECT</span><span class="timing">Yesterday,
                                            05:25
                                            PM</span></p>
                                </div>
                            </li>
                            <li class="media">
                                <span class="mr-3 action-icon badge badge-danger-inverse"><i
                                        class="feather icon-alert-triangle"></i></span>
                                <div class="media-body">
                                    <h5 class="action-title">Alert</h5>
                                    <p class="my-3">There has been new Log in fron your account at Melbourne. Mark it
                                        safe or report.</p>
                                    <p><i class="feather icon-check text-success mr-3"></i><a href="#"
                                                                                              class="text-muted">Report
                                            Now</a><span class="timing">5 Jan 2019, 02:13
                                            PM</span></p>
                                </div>
                            </li>
                            <li class="media">
                                <span class="mr-3 action-icon badge badge-warning-inverse"><i
                                        class="feather icon-award"></i></span>
                                <div class="media-body">
                                    <h5 class="action-title">Congratulations !!!</h5>
                                    <p class="my-3">Your role in the organization has been changed from Editor to
                                        Chief
                                        Strategist.</p>
                                    <p><span class="badge badge-danger-inverse">ACTIVITY</span><span class="timing">10
                                            Jan
                                            2019, 08:49 PM</span></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Containerbar -->
        <div id="containerbar " class=" bg-white">

            @include('back-end.layouts.partials.header')
            <div id="closing_cash_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                 class="modal">
            </div>


            @include('back-end.layouts.partials.leftbar')

            <!-- Start Rightbar -->
            <div class="rightbar">

                @yield('breadcrumbbar')

                <div class="animate-in-page  no-print">
                    <div class="breadcrumbbar m-0 px-3 py-0">
                        <div
                            class="d-flex align-items-center justify-content-between @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div>
                                <h4 class="page-title @if (app()->isLocale('ar')) text-end @else text-start @endif">
                                    @yield('page_title')
                                </h4>
                                <div class="breadcrumb-list">
                                    <ul
                                        class="breadcrumb m-0 p-0  d-flex @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                        @section('breadcrumbs')
                                            <li
                                                class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif ">
                                                <a style="text-decoration: none;color: #3e5d58" href="{{ url('/') }}">/
                                                    @lang('lang.dashboard')</a>
                                            </li>
                                        @show
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @yield('button')
                            </div>
                        </div>
                    </div>
                </div>
                <div id="content">
                   @yield('content')
                </div>
                <div class="modal modal-jobs-edit animate__animated  no-print" data-animate-in="animate__rollIn"
                     data-animate-out="animate__rollOut"
                     id="editModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
                     style="display: none;"
                     aria-hidden="true">
                    <div class="view_modal no-print">


                    </div>
                </div>
            </div>
            <!-- End Rightbar -->

            @include('back-end.layouts.partials.footer')
            @livewireScripts
            <button id="toTopBtn" onclick="scrollToTop()">
                <i class="fas fa-arrow-up"></i>
            </button>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            var modelEl = $('.modal-jobs-edit');

            modelEl.addClass(modelEl.attr('data-animate-in'));

            modelEl.on('hide.bs.modal', function (event) {
                console.log('ddd');
                if (!$(this).attr('is-from-animation-end')) {
                    event.preventDefault();
                    $(this).addClass($(this).attr('data-animate-out'))
                    $(this).removeClass($(this).attr('data-animate-in'))
                }
                $(this).removeAttr('is-from-animation-end')
            })
                .on('animationend', function () {
                    if ($(this).hasClass($(this).attr('data-animate-out'))) {
                        $(this).attr('is-from-animation-end', true);
                        $(this).modal('hide')
                        $(this).removeClass($(this).attr('data-animate-out'))
                        $(this).addClass($(this).attr('data-animate-in'))
                    }
                })
        })
    </script>
    <!-- End Containerbar -->
    @if (app()->isLocale('ar'))
        <script>
            const element = document.querySelector('.item-list-a');

            if (element) {
                element.classList.add('flex-row-reverse');
            } else {
                console.error('Element with class "item-list-a" not found.');
            }
        </script>
    @else
        <script>
            const element = document.querySelector('.item-list-a');

            if (element) {
                element.classList.add('flex-row');
            } else {
                console.error('Element with class "item-list-a" not found.');
            }
        </script>
    @endif

    <input type="hidden" id="__language" value="{{ session('language') }}">
    <input type="hidden" id="__decimal" value=".">
    <input type="hidden" id="__currency_precision"
           value="{{ !empty(\Modules\Setting\Entities\System::getProperty('numbers_length_after_dot')) ? \Modules\Setting\Entities\System::getProperty('numbers_length_after_dot') : 5 }}">
    <input type="hidden" id="__currency_symbol" value="$">
    <input type="hidden" id="__currency_thousand_separator" value=",">
    <input type="hidden" id="__currency_symbol_placement" value="before">
    <input type="hidden" id="__precision"
           value="{{ !empty(\Modules\Setting\Entities\System::getProperty('numbers_length_after_dot')) ? \Modules\Setting\Entities\System::getProperty('numbers_length_after_dot') : 5 }}">
    <input type="hidden" id="__quantity_precision"
           value="{{ !empty(\Modules\Setting\Entities\System::getProperty('numbers_length_after_dot')) ? \Modules\Setting\Entities\System::getProperty('numbers_length_after_dot') : 5 }}">
    <script type="text/javascript">
        base_path = "{{ url('/') }}";
        current_url = "{{ url()->current() }}";
    </script>

    <!-- Start js -->
    @include('back-end.layouts.partials.javascript')
    @yield('javascript')


    <script>
        // Define the __write_number function to write a number to an input field
        function __write_number(outputElement, value) {
            outputElement.val(value);
        }

        $(document).ready(function () {
            if ($('.toggle_dollar').val() == "1") {
                $('#toggleDollar').click();
            }
        });
        $(document).ready(function () {
            // Event handler for key press
            $(document).on('keydown', function (event) {
                // Check if Ctrl+G is pressed
                if (event.ctrlKey && event.key === $('.keyboord_letter_to_toggle_dollar').val()) {
                    // Prevent the default Ctrl+G behavior (e.g., find)
                    event.preventDefault();
                    $.ajax({
                        url: '/toggle-dollar',
                        method: 'GET',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire("Success", response.msg, "success");
                                $('#toggleDollar').click();
                                location.reload(true);
                            }
                        }
                    });
                }
            });
        });

        window.addEventListener('swal:modal', event => {
            Swal.fire({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                showConfirmButton: false,
                timer: 2000,
            });

        });

        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        window.livewire.emit('remove');
                    }
                });
        });

        $(document).on("change", "#branch_id", function () {
            $.ajax({
                type: "get",
                url: "/get_branch_stores/" + $(this).val().join(','),
                dataType: "html",
                success: function (response) {
                    console.log(response)
                    $("#store_id").empty().append(response).change();
                }
            });
        });

        $(document).on('click', "#power_off_btn", function (e) {
            let cash_register_id = $('#cash_register_id').val();
            let is_register_close = parseInt($('#is_register_close').val());
            if (!is_register_close) {
                getClosingModal(cash_register_id);
                return 'Please enter the closing cash';
            } else {
                return;
            }
        });

        function getClosingModal(cash_register_id, type = 'close') {
            $.ajax({
                method: 'get',
                url: '/cash/add-closing-cash/' + cash_register_id,
                data: {
                    type
                },
                contentType: 'html',
                success: function (result) {
                    $('#closing_cash_modal').empty().append(result);
                    $('#closing_cash_modal').modal('show');
                },
            });
        }

        window.addEventListener('load', function () {
            var loaderWrapper = document.querySelector('.loading');
            if (loaderWrapper) {
                loaderWrapper.style.display = 'none'; // Hide the loader once the page is fully loaded
            }
        });

        // window.addEventListener("beforeunload", (event) => {
        //     document.body.classList.add('animated-element');
        // });
        let toggleButton = document.getElementById('toggle-responsive-nav')
        let navbarMenu = document.getElementById('navbar-menu')
    </script>
    <script>
        // Wait for the DOM content to be fully loaded
        document.addEventListener("DOMContentLoaded", function () {
            // Set overflow to hidden initially
            document.body.style.overflowY = "hidden";
            document.body.style.height = "1000vh";

            // Remove overflow hidden after 1.5 seconds
            setTimeout(function () {
                document.body.style.overflowY = "auto"; // Or "visible" depending on your requirements
                document.body.style.height = "fit-content"; // Or "visible" depending on your requirements
            }, 500);
        });
    </script>
    @stack('js')
    @push('javascripts')
        <script>
            document.addEventListener('livewire:load', function () {
                Livewire.on('printInvoice', function (htmlContent) {
                    // Set the generated HTML content
                    $("#receipt_section").html(htmlContent);
                    // Trigger the print action
                    window.print("#receipt_section");
                });
            });
            $(document).on("click", ".print-invoice", function () {
                // $(".modal").modal("hide");
                $.ajax({
                    method: "get",
                    url: $(this).data("href"),
                    data: {},
                    success: function (result) {
                        if (result.success) {
                            Livewire.emit('printInvoice', result.html_content);
                        }
                    },
                });
            });
        </script>
        @endpush
        </body>
</html>
