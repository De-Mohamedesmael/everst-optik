@extends('back-end.layouts.app')
@section('title', __('lang.special_addition'))

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="#">/
            @lang('lang.setting')</a>
    </li>
    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.special_additions')</li>
@endsection

@section('button')

{{--    @can('product_module.special_addition.create_and_edit')--}}
        <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
            <a style="color: white"
               data-href="{{ route('admin.special_additions.create') }}"
               data-container=".view_modal" class="btn btn-modal btn-main"><i
                    class="dripicons-plus"></i>
                {{translate('add_special_addition')}}
            </a>
        </div>
{{--    @endcan--}}
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
                                        <th>@lang('lang.price')</th>
                                        <th class="notexport">@lang('lang.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($special_additions as $special_addition)
                                        <tr>
                                            <td>{{ $special_addition->name }}</td>
                                            <td>{{ $special_addition->price }}</td>

                                            <td>

{{--                                                @can('product_module.special_addition.create_and_edit')--}}


                                                    <a data-href="{{ route('admin.special_additions.edit', $special_addition->id) }}"
                                                        data-container=".view_modal" class="btn btn-primary btn-modal  edit_job">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>

{{--                                                @endcan--}}
{{--                                                @can('product_module.special_addition.delete')--}}
                                                        <a data-href="{{ route('admin.special_additions.destroy', $special_addition->id) }}"
                                                            data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                            class="btn btn-danger text-white delete_item"><i class="fa fa-trash"></i>
                                                            </a>

{{--                                                @endcan--}}
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
