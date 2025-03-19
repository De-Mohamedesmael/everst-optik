@extends('back-end.layouts.app')
@section('title', __('lang.cash_in_hand'))

@section('breadcrumbs')
@parent
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif"><a
        style="text-decoration: none;color: #59d7d7" href="#"> @lang('lang.sales')</a></li>
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    @lang('lang.cash_in_hand')</li>
@endsection
@section('content')
<div class="animate-in-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.cash-register.store'), 'id' => 'cash-register-form',
                        'method' => 'POST', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('amount', __('lang.amount') . ':*') !!}
                                {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' =>
                                __('lang.amount'), 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('source_type', __('lang.source_type'), []) !!} <br>
                                {!! Form::select('source_type', ['user' => __('lang.user'), 'safe' => __('lang.safe')],
                                'user', ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'style'
                                => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('source_id', __('lang.source'), []) !!}
                                {!! Form::select('source_id', $admins, false, ['class' => 'selectpicker form-control',
                                'data-live-search' => 'true', 'required', 'placeholder' => __('lang.please_select')])
                                !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('notes', __('lang.notes'), []) !!}
                                {!! Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' =>
                                __('lang.notes'), 'rows' => 3]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('image', __('lang.upload_picture'), []) !!} <br>
                                {!! Form::file('image', []) !!}
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="submit" value="{{ trans('lang.submit') }}" id="submit-btn"
                                    class="btn btn-primary">
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 form-group">
                                <input type="submit" value="{{ trans('lang.cancel') }}" id="cancel-submit-btn"
                                    class="btn btn-danger" style="width: 100%;">
                            </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $('#supplier-type-form').submit(function() {
            $(this).validate();
            if ($(this).valid()) {
                $(this).submit();
            }
        });
        $('#source_type').change(function() {
            if ($(this).val() !== '') {
                $.ajax({
                    method: 'get',
                    url: '{{route('admin.getSourceByTypeDropdown','')}}/' + $(this).val(),
                    data: {},
                    success: function(result) {
                        $("#source_id").empty().append(result);
                        $("#source_id").selectpicker("refresh");
                    },
                });
            }
        });
</script>
@endsection