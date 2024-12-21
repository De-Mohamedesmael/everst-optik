@extends('layouts.app')
@section('title', __('lang.modules'))
@section('style')

    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
@endsection
@section('content')
    <section class="forms py-0">

        <div class="container-fluid">
            <div class="col-md-12 px-1  no-print">
                <div
                    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h5 class="mb-0 position-relative print-title" style="margin-right: 30px">
                        @lang('lang.modules')
                        <span class="header-pill"></span>
                    </h5>
                </div>
                {!! Form::open([
                    'url' => action('SettingController@updateModuleSettings'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                            @foreach ($modules as $key => $name)
                                @if (session('system_mode') != 'restaurant' && session('system_mode') != 'garments' && session('system_mode') != 'pos')
                                    @if ($key == 'raw_material_module')
                                        @continue
                                    @endif
                                @endif
                                <div class="col-md-3 mb-2">
                                    <div class="i-checks">
                                        <input id="{{ $loop->index }}" name="module_settings[{{ $key }}]"
                                            type="checkbox" @if (!empty($module_settings[$key])) checked @endif value="1"
                                            class="form-control-custom">
                                        <label for="{{ $loop->index }}"><strong>{{ __('lang.' . $key) }}</strong></label>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <div class="row my-2 justify-content-center align-items-center">

                            <div class="col-md-4 w-25">
                                <button type="submit" class="btn btn-main">@lang('lang.save')</button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>

@endsection

@section('javascript')
    <script></script>
@endsection
