@extends('back-end.layouts.app')
@section('title', __('lang.features'))
@section('styles')
    <style>

        .crop-btn ,.delete-btn {
            position: absolute;
            top: 100px;
        }
        .preview-feature-container  , .preview-icon-active-container {
            width: 80px !important;
            height:  80px !important;
        }
        .preview-icon-active-container {
              background-color: #0a67a1;
          }
         .preview-before-effect-container , .preview-after-effect-container{
             width: 160px !important;
             height: 100px !important;
          }
        .preview-feature-container img , .preview-icon-active-container img{
            width: 80px  !important;
            height:80px ;
        }
        .preview-before-effect-container img, .preview-after-effect-container img{
            width: 160px !important;
            height: 100px !important;
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
        @lang('lang.features')</li>
@endsection

@section('button')
        @can('lens_module.features.create_and_edit')
            <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
                <a style="color: white"
                   data-href="{{ route('admin.features.create') }}"
                   data-container=".view_modal" class="btn btn-modal btn-main"><i
                        class="dripicons-plus"></i>
                    @lang('lang.add_feature')
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
                            <table id="feature_table" class="table dataTable">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.image')</th>
                                        <th>@lang('lang.name')</th>
                                        <th class="notexport">@lang('lang.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($features as $feature)
                                        <tr>
                                            <td style="text-align: center;">
                                                <img src="{{$feature->icon}}"
                                                    alt="photo" width="50" height="50">
                                            </td>
                                            <td style="text-align: center;" >{{ $feature->name }}</td>

                                            <td style="text-align: center;">

                                                @can('lens_module.features.create_and_edit')
                                                    <a data-href="{{ route('admin.features.edit', $feature->id) }}"
                                                       data-container=".view_modal"
                                                       class="btn btn-primary btn-modal text-white edit_job">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                @endcan
                                                @can('lens_module.features.delete')
                                                    <a
                                                       data-href="{{ route('admin.features.destroy', $feature->id) }}"
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
