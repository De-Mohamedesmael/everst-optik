@extends('back-end.layouts.app')
@section('title', __('lang.tax_location'))

@section('breadcrumbs')
@parent
<li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
        style="text-decoration: none;tax_location: #476762" href="#">
        @lang('lang.setting')</a>
</li>
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    @lang('lang.tax_locations')</li>
@endsection

@section('button')

@can('product_module.tax_location.create_and_edit')
<div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
    <a style="color: white" data-href="{{ route('admin.tax_locations.create') }}" data-container=".view_modal"
        class="btn btn-modal btn-main"><i class="dripicons-plus"></i>
        {{translate('add_tax_location')}}
    </a>
</div>
@endcan
@endsection

@section('content')
<section class="forms px-3 py-1">

    <div class="container-fluid">

        <div class="col-md-12  px-1 no-print">
            <div class="card mb-2">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="store_table" class="table dataTable">
                            <thead>
                                <tr>
                                    <th>@lang('lang.name')</th>
                                    <th class="notexport">@lang('lang.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tax_locations as $tax_location)
                                <tr>
                                    <td>{{ $tax_location->name }}</td>

                                    <td>

                                        @can('product_module.tax_location.create_and_edit')


                                        <a data-href="{{ route('admin.tax_locations.edit', $tax_location->id) }}"
                                            data-container=".view_modal" class="btn btn-primary btn-modal  edit_job">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        @endcan
                                        @can('product_module.tax_location.delete')
                                        <a data-href="{{ route('admin.tax_locations.destroy', $tax_location->id) }}"
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

@section('javascript')

@endsection
