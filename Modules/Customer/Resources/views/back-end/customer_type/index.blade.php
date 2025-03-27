@extends('back-end.layouts.app')
@section('title', __('lang.customer_type'))
@section('style')

@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.customers.index') }}">
            {{translate('customers')}}</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        {{translate('customer_types')}}</li>
@endsection
@section('button')
    <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
        <a class="btn btn-primary" href="{{ route('admin.customer-type.create') }}">{{translate('add_customer_type')}}</a>
    </div>
@endsection
@section('content')
    <section class="forms py-1 ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div class="card mb-2 mt-2">
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table id="store_table" class="table dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.name')</th>
                                            <th>@lang('lang.stores')</th>
                                            <th>@lang('lang.number_of_customer')</th>
                                            <th class="sum">@lang('lang.discount')</th>
                                            <th>@lang('lang.date_and_time')</th>
                                            <th>@lang('lang.created_by')</th>
                                            <th class="notexport">@lang('lang.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_discounts = 0;
                                        @endphp
                                        @foreach ($customer_types as $customer_type)
                                            <tr>
                                                <td>{{ $customer_type->name }}</td>
                                                <td>
                                                    @php
                                                        $this_stores = [];
                                                    @endphp
                                                    @foreach ($customer_type->customer_type_store as $item)
                                                        @php
                                                            if (!empty($item->store->name)) {
                                                                $this_stores[] = $item->store->name;
                                                            }
                                                        @endphp
                                                    @endforeach
                                                    {{ implode(',', $this_stores) }}
                                                </td>
                                                <td>
                                                    @if (!empty($customer_type->customers))
                                                        <a href="{{ route('admin.customer-type.show', $customer_type->id) }}?show=customers"
                                                            class="btn">{{ $customer_type->customers->count() }}</a>
                                                    @endif
                                                </td>
                                                <td><a href="{{ route('admin.customer-type.show', $customer_type->id) }}?show=discounts"
                                                        class="btn">{{ @num_format($customer_type->total_sp_discount + $customer_type->total_product_discount + $customer_type->total_coupon_discount) }}</a>
                                                </td>
                                                <td>{{ $customer_type->created_at }}</td>
                                                <td>{{ ucfirst($customer_type->created_by_user->name ?? '') }}</td>
                                                <td>
                                                    @can('customer_module.customer_type.view')
                                                        <a href="{{ route('admin.customer-type.show', $customer_type->id) }}"
                                                           class="btn btn-secondary"><i class="dripicons-document"></i>
                                                            @lang('lang.view')</a>

                                                    @endcan
                                                    @if ($customer_type->name != 'Walk in' && $customer_type->name != 'Online customers')
                                                        @can('customer_module.customer_type.create_and_edit')
                                                                <a  class="btn btn-primary btn-modal text-white edit_job"
                                                                    href="{{ route('admin.customer-type.edit', $customer_type->id) }}"><i
                                                                        class="fa fa-pencil-square-o"></i>@lang('lang.edit')</a>
                                                        @endcan

                                                        @can('customer_module.customer_type.delete')
                                                            <a data-href="{{ route('admin.customer-type.destroy', $customer_type->id) }}"
                                                               data-check_password="{{   route('admin.check-password', auth('admin')->user()->id) }}"
                                                               class="btn btn-danger text-white delete_item"><i
                                                                    class="fa fa-trash"></i>
                                                                @lang('lang.delete')</a>
                                                        @endcan
                                                    @endif



                                                </td>
                                            </tr>
                                            @php
                                                $total_discounts +=
                                                    $customer_type->total_sp_discount +
                                                    $customer_type->total_product_discount +
                                                    $customer_type->total_coupon_discount;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <th style="text-align: right">@lang('lang.total')</th>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script></script>
@endsection
