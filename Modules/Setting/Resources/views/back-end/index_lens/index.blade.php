@extends('back-end.layouts.app')
@section('title', __('lang.index_lenses'))
@section('breadcrumbs')
@parent
<li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
        style="text-decoration: none;color: #476762" href="#">
        @lang('lang.setting')</a>
</li>
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    @lang('lang.index_lenses')</li>
@endsection

@section('button')

@can('settings.index_lens.create_and_edit')
<div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
    <a style="color: white" data-href="{{ route('admin.index_lenses.create') }}" data-container=".view_modal"
        class="btn btn-modal btn-main"><i class="dripicons-plus"></i>
        {{translate('add_index_lens')}}
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
                                @foreach($index_lenses as $index_lens)
                                <tr>
                                    <td class="col1">{{$index_lens->name}}</td>
                                    <td class="col6">
                                        @can('settings.index_lens.create_and_edit')
                                        <a data-href="{{ route('admin.index_lenses.edit', $index_lens->id) }}"
                                            data-container=".view_modal" class="btn btn-primary btn-modal  edit_job">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        @endcan
                                        @can('settings.index_lens.delete')
                                        <a data-href="{{ route('admin.index_lenses.destroy', $index_lens->id) }}"
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
<div class="no-print"></div>
@push('javascripts')

@endpush