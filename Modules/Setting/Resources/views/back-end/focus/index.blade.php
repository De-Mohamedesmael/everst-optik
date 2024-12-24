@extends('back-end.layouts.app')
@section('title', __('lang.foci'))
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="#">/
            @lang('lang.setting')</a>
    </li>
    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.foci')</li>
@endsection

@section('button')

    @can('settings.focus.create_and_edit')
        <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
            <a style="color: white"
               data-href="{{ route('admin.foci.create') }}"
               data-container=".view_modal" class="btn btn-modal btn-main"><i
                    class="dripicons-plus"></i>
                {{translate('add_focus')}}
            </a>
        </div>
    @endcan
@endsection
@section('content')
    <!-- Start Contentbar -->
    <section class="forms py-0">

        <div class="container-fluid">

            <!-- Start row -->
            <div class="row">
            <!-- Start col -->
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th class="col1">@lang('lang.name')</th>
                                    <th class="col7 notexport">@lang('lang.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($foci as $focus)
                                <tr>
                                    <td class="col1">{{$focus->name}}</td>
                                    <td class="col6">
                                        @can('settings.focus.create_and_edit')
                                            <a data-href="{{ route('admin.foci.edit', $focus->id) }}"
                                               data-container=".view_modal" class="btn btn-primary btn-modal  edit_job">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endcan
                                        @can('settings.focus.delete')
                                                <a data-href="{{ route('admin.foci.destroy', $focus->id) }}"
                                                   data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                   class="btn btn-danger text-white delete_item"><i class="fa fa-trash"></i>
                                                </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
<div class="no-print" ></div>
@push('javascripts')

@endpush
