@extends('back-end.layouts.app')
@section('title', __('lang.store_pos'))


@section('breadcrumbs')
@parent
<li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
        style="text-decoration: none;color: #476762" href="#">
        @lang('lang.setting')</a>
</li>
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    @lang('lang.store_pos')</li>
@endsection

@section('button')

@can('settings.store_pos.create_and_edit')
<div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
    <a style="color: white" data-href="{{ route('admin.store-pos.create') }}" data-container=".view_modal"
        class="btn btn-modal btn-main"><i class="dripicons-plus"></i>
        {{translate('add_store_pos')}}
    </a>
</div>
@endcan
@endsection
@section('content')
<!-- Start Contentbar -->
<section class="forms px-3 py-1">

    <div class="container-fluid">

        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12">
                <div class="card m-b-30">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table datatable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col1">#</th>
                                        <th class="col2">@lang('lang.name')</th>
                                        <th class="col3">@lang('lang.admin')</th>
                                        <th class="col4">@lang('lang.email')</th>
                                        <th class="col5">@lang('lang.date_and_time')</th>
                                        <th class="col6 notexport">@lang('lang.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($store_poses as $key => $store_pos)
                                    <tr>
                                        <td class="col1">{{ $key+1 }}</td>
                                        <td class="col2">{{$store_pos->name}}</td>
                                        <td class="col3">{{$store_pos->admin->name}}</td>
                                        <td class="col4">{{$store_pos->admin->email}}</td>
                                        <td class="col5">{{@format_datetime($store_pos->created_at)}}</td>
                                        <td class="col6">
                                            @can('settings.store_pos.create_and_edit')
                                            <a data-href="{{ route('admin.store-pos.edit', $store_pos->id) }}"
                                                data-container=".view_modal"
                                                class="btn btn-primary btn-modal  edit_job">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @endcan
                                            @can('settings.store_pos.delete')
                                            @if($store_pos->id != 1)
                                            <a data-href="{{ route('admin.store-pos.destroy', $store_pos->id) }}"
                                                data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                class="btn btn-danger text-white delete_item"><i
                                                    class="fa fa-trash"></i>
                                            </a>
                                            @endif

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
            <!-- End col -->
        </div>
    </div>
    <!-- End Contentbar -->
</section>
@endsection
<div class="no-print"></div>
@section('javascript')

@endsection