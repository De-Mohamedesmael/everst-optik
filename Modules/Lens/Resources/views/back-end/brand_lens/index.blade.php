@extends('back-end.layouts.app')
@section('title', __('lang.brand_lenses'))
@section('styles')
    <style>
        .preview {
            width: 200px;
            height: 89px;
        }
        .crop-btn ,.delete-btn {
            position: absolute;
            top: 100px;
        }
    </style>
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="#{{-- route('admin.lenses.index') --}}">/
            @lang('lang.lenses')</a>
    </li>
    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.brand_lenses')</li>
@endsection

@section('button')

        @can('lens_module.brand_lenses.create_and_edit')
            <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
                <a style="color: white"
                   data-href="{{ route('admin.brand_lenses.create') }}"
                   data-container=".view_modal" class="btn btn-modal btn-main"><i
                        class="dripicons-plus"></i>
                    @lang('lang.add_brand_lens')
                </a>
            </div>
        @endcan
@endsection
@section('content')
    <section class="forms px-3 py-1">
        <div class="container-fluid">
            <div class="col-md-12 px-1 no-print">
                <div class="card mb-2 mt-2">
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table id="brand_lens_table" class="table dataTable">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.image')</th>
                                        <th>@lang('lang.name')</th>
                                        <th>@lang('lang.price')</th>
                                        <th>@lang('lang.color')</th>
                                        <th class="notexport">@lang('lang.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brand_lenses as $brand_lens)
                                        <tr>
                                            <td style="text-align: center;">
                                                <img src="{{$brand_lens->icon}}"
                                                    alt="photo" width="120" height="50">
                                            </td>
                                            <td style="text-align: center;" >{{ $brand_lens->name }}</td>
                                            <td style="text-align: center;" >{{ $brand_lens->price }}</td>
                                            <td style="text-align: center;" >
                                                <div class="lens-color" style="width: 40px;height: 30px;margin: auto;border-radius:10px;background-color:  {{ $brand_lens->color }}">
                                                </div>
                                            </td>
                                            <td style="text-align: center;">

                                                @can('lens_module.brand_lenses.create_and_edit')

                                                <a data-href="{{ route('admin.brand_lenses.edit', $brand_lens->id) }}"
                                                   data-container=".view_modal"
                                                   class="btn btn-primary btn-modal text-white edit_job">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                @endcan
                                                @can('lens_module.brand_lenses.delete')
                                                    <a
                                                       data-href="{{ route('admin.brand_lenses.destroy', $brand_lens->id) }}"
                                                       class="btn btn-danger text-white delete_item">
                                                        <i class="fa fa-trash"></i>
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
