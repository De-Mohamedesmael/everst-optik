@extends('layouts.app')
@section('title', __('lang.modules'))
@section('breadcrumbbar')
    <div class="breadcrumbbar">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">@lang('lang.modules')</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">@lang('lang.dashboard')</a></li>
                        <li class="breadcrumb-item"><a href="#">@lang('lang.settings')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('lang.modules')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="contentbar">
        <div class="col-md-12  no-print">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4>@lang('lang.modules')</h4>
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => route('updateModule'), 'method' => 'post', 'enctype' =>
                    'multipart/form-data']) !!}
                    <div class="row">
                        @foreach ($modules as $key => $name)
                            <div class="col-md-4">
                                <div class="i-checks">
                                    <input id="{{$loop->index}}" name="module_settings[{{$key}}]" type="checkbox"
                                           @if( !empty($module_settings[$key]) ) checked @endif value="1"
                                           class=""">
                                    <label for="{{$loop->index}}"><strong>{{__('lang.'.$key)}}</strong></label>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <br>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>

    </script>
@endsection
