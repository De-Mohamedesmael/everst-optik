@extends('layouts.app')
@section('title', __('lang.color'))

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="#">/
            @lang('lang.setting')</a>
    </li>
    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.colors')</li>
@endsection

@section('button')

    @can('product_module.color.create_and_edit')
        <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
            <a style="color: white"
               data-href="{{ route('admin.colors.create') }}"
               data-container=".view_modal" class="btn btn-modal btn-main"><i
                    class="dripicons-plus"></i>
                {{translate('add_color')}}
            </a>
        </div>
    @endcan
@endsection

@section('content')
    <section class="forms py-0">

        <div class="container-fluid">

            <div class="col-md-12  px-1 no-print">
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table id="store_table" class="table dataTable">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.name')</th>
                                        <th>@lang('lang.color_hex')</th>
                                        <th class="notexport">@lang('lang.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($colors as $color)
                                        <tr>
                                            <td>{{ $color->name }}</td>
                                            <td>{{ $color->color_hex }}</td>

                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">@lang('lang.action')
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                                        user="menu">
                                                        @can('product_module.color.create_and_edit')
                                                            <li>

                                                                <a data-href="{{ route('admin.colors.edit', $color->id) }}"
                                                                    data-container=".view_modal" class="btn btn-modal"><i
                                                                        class="dripicons-document-edit"></i>
                                                                    @lang('lang.edit')</a>
                                                            </li>
                                                            <li class="divider"></li>
                                                        @endcan
                                                        @can('product_module.color.delete')
                                                            <li>
                                                                <a data-href="{{ route('admin.colors.destroy', $color->id) }}"
                                                                    data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                                    class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                                                    @lang('lang.delete')</a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
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
