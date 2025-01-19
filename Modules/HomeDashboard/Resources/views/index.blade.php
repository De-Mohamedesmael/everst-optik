@extends('back-end.layouts.app')
@section('title', __('lang.dashboard'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@php
    $module_settings = \Modules\Setting\Entities\System::getProperty('module_settings');
    $module_settings = !empty($module_settings) ? json_decode($module_settings, true) : [];
@endphp
@section('content')
    @if (!empty($module_settings['dashboard']))
        <div class="animate-in-page">

            <div class="contentbar">
                <!-- Start row -->
                <div class="row justify-content-evenly  item-list-a">
                    {{-- ################ نظرة عامة ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 0.7s">
                        <a href="#">
                            <div class="card home-card-deck p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/dashboard (1).png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                <span class="font-weight-bold text-decoration-none card-title font-16">
                                    {{ translate('dashboard') }}
                                </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ المنتجات ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 0.8s">
                        <a href={{  route('admin.products.index')  }}>
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/dairy-products.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                       href="{{-- route('products.create') --}}">{{ __('lang.products') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ المشتريات ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 0.9s">
                        <a href="{{ route('admin.pos.create') }}">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/cash-machine.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                       href="{{-- route('pos.index') --}}">{{ translate('sells') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ المشتريات ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 1s">
                        <a href="{{ route('admin.add-stock.create') }}">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/warehouse.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                       href="{{-- route('stocks.create') --}}">{{ __('lang.stock') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ المرتجعات ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 1.1s">
                        <a href="{{--route('returns') --}}">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/return.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                       href="{{-- route('returns') --}}">{{ translate('returns') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ الموظفين ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 1.2s">
                        <a href="{{ route('admin.hr.employees.index') }}">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/employment.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                       href="{{-- route('employees.create') --}}">{{ __('lang.employees') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ العملاء ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 1.3s">
                        <a href="{{ route('admin.customers.create') }}">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/customer-satisfaction.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center ">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                       href="{{-- route('customers.create') --}}">{{ __('lang.customers') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ الموردين ################ --}}
                    {{--                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"--}}
                    {{--                         style="animation-delay: 1.4s">--}}
                    {{--                        <a href="--}}{{-- route('suppliers.create') --}}{{--">--}}
                    {{--                            <div class="card p-3">--}}
                    {{--                                <img class="card-img-top" src="{{ asset('assets/back-end/images/dashboard-icon/inventory.png') }}"--}}
                    {{--                                     alt="Card image cap">--}}
                    {{--                                <div class="card-body pt-2 p-0 text-center">--}}
                    {{--                                    <a class="font-weight-bold text-decoration-none card-title font-16"--}}
                    {{--                                       href="--}}{{-- route('suppliers.create') --}}{{--">{{ __('lang.suppliers') }}</a>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                    {{-- ################ الاعدادات ################ --}}
                    <div class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn"
                         style="animation-delay: 1.5s">
                        <a href="#">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/settings.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title font-16"
                                    >{{ __('lang.settings') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- ################ التفارير ################ --}}
                    <div
                        class="card-deck home-card-deck col-6 col-md-4 col-lg-2 animate__animated  animate__bounceIn align-content-center"
                        style="animation-delay: 1.6s">
                        <a href="#">
                            <div class="card p-3">
                                <img class="card-img-top"
                                     src="{{ asset('assets/back-end/images/dashboard-icon/report.png') }}"
                                     alt="Card image cap">
                                <div class="card-body pt-2 p-0 text-center">
                                    <a class="font-weight-bold text-decoration-none card-title ont-16 "
                                       href="{{-- route('reports.all') --}}">{{ __('lang.reports') }}</a>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="row justify-content-evenly  item-list-a">
                        <div class="container-fluid">
                            <div class="col-md-12">

                            </div>
                        </div>
                    </div>
                    {{-- End customer Filter --}}

                    {{-- Show Customers --}}


                    <div class="row">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div
                                    class="brand-text d-flex item-list-a">
                                    <h3 class="d-flex item-list-a">
                                        @lang('lang.welcome')
                                        <span class="mx-1">
                                        {{ Auth::guard('admin')->user()->name }}
                                    </span>
                                    </h3>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End row -->

                </div>
            </div>
        </div>
    @endif
@endsection

@section('style')

@endsection
@section('javascript')
@endsection
