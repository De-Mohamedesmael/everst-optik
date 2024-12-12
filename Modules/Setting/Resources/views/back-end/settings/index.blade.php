    @extends('layouts.app')
@section('title', __('lang.settings'))
@section('breadcrumbbar')
    <div class="breadcrumbbar">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">@lang('lang.settings')</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="font-weight-bold text-decoration-none text-dark font-18"href="{{url('/')}}">@lang('lang.dashboard')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('lang.settings')</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">

            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="contentbar">
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h5 class="card-title">@lang('lang.settings')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <ul>
                                {{-- ////// اخفاء واظهار اقسام البرنامج ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('getModules')}}">
                                        @lang('lang.modules')
                                    </a>
                                </li>
                                {{-- ////// الاعدادات العامة ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('settings.index')}}">
                                        @lang('lang.general_settings')
                                    </a>
                                </li>
                                {{-- ////// الخزائن ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('moneysafe.index')}}">
                                        @lang('lang.moneysafes')
                                    </a>
                                </li>

                                {{-- ////// المخازن ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('store.index')}}">
                                        @lang('lang.stores')
                                    </a>
                                </li>
                                {{-- ////// العلامة التجاية ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('brands.index')}}">
                                        @lang('lang.brands')
                                    </a>
                                </li>
                                {{-- ////// الاقسام ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('sub-categories', 'category')}}">
                                        @lang('categories.categories')
                                    </a>
                                </li>
                                {{-- ////// الالوان ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('colors.index')}}">
                                        @lang('colors.colors')
                                    </a>
                                </li>
                                {{-- ////// المقاسات ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('sizes.index')}}">
                                        @lang('sizes.sizes')
                                    </a>
                                </li>
                                {{-- ////// الوحدات ////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('units.index')}}">
                                        @lang('units.units')
                                    </a>
                                </li>

                                {{-- ////////// نقاط البيع للصرافين ////////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('store-pos.index')}}">
                                        @lang('lang.store_pos')
                                    </a>
                                </li>
                                {{-- ////////// الضرائب العامة ////////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('general-tax.index')}}">
                                        @lang('lang.general_tax')
                                    </a>
                                </li>
                                {{-- ////////// ضرائب المنتجات ////////// --}}
                                <li>
                                    <a class="font-weight-bold text-decoration-none text-dark font-18"href="{{route('product-tax.index')}}">
                                        @lang('lang.product_tax')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End col -->
        </div>
        <!-- End row -->
    </div>
@endsection
