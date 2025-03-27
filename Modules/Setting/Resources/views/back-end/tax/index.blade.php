@extends('back-end.layouts.app')
@section('title')
@if ($type == 'product_tax')
@lang('lang.product_taxes')
@else
@lang('lang.general_tax')
@endif
@endsection
@section('breadcrumbs')
@parent
<li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
        style="text-decoration: none;color: #476762" href="#">
        @lang('lang.setting')</a>
</li>
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    @if ($type == 'product_tax')
    <h5 class="mb-0 position-relative  print-title">@lang('lang.product_taxes')
        <span class="header-pill"></span>
    </h5>
    @else
    <h5 class="mb-0 position-relative  print-title">@lang('lang.general_tax')
        <span class="header-pill"></span>
    </h5>
    @endif

</li>
@endsection

@section('button')

@can('product_module.tax.create_and_edit')
<div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
    <a style="color: white" data-href="{{ route('admin.tax.create') }}?type={{ $type }}" data-container=".view_modal"
        class="btn btn-modal btn-main"><i class="dripicons-plus"></i>
        {{translate('add')}}
    </a>
</div>
@endcan
@endsection
@section('content')
<section class="forms px-3 py-1">
    <div class="col-md-12 px-1 no-print">



        <div class="card mb-2">
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table id="store_table" class="table dataTable">
                        <thead>
                            <tr>
                                <th>@lang('lang.name')</th>
                                <th>@lang('lang.rate_percentage')</th>
                                @if ($type == 'general_tax')
                                <th>@lang('lang.tax_method')</th>
                                <th>@lang('lang.status')</th>
                                <th>@lang('lang.status_for_stores')</th>
                                @endif
                                <th class="notexport">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->rate }}</td>
                                @if ($type == 'general_tax')
                                <td>{{ ucfirst($tax->tax_method) }}</td>
                                <td>
                                    @if ($tax->status == 1)
                                    @lang('lang.enable')
                                    @else
                                    @lang('lang.disabled')
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($tax->store_ids))
                                    {{ implode(',', $tax->stores->pluck('name')->toArray()) }}
                                    @else
                                    @lang('lang.all_stores')
                                    @endif
                                </td>
                                @endif
                                <td class="col6">
                                    @can('product_module.tax.create_and_edit')
                                    <a data-href="{{ route('admin.tax.edit', $tax->id) }}" data-container=".view_modal"
                                        class="btn btn-primary btn-modal  edit_job">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    @endcan
                                    @can('product_module.tax.delete')
                                    <a data-href="{{ route('admin.tax.destroy', $tax->id) }}"
                                        data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                        class="btn btn-danger text-white delete_item"><i class="fa fa-trash"></i>
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
</section>
@endsection

@section('javascript')
<script></script>
@endsection