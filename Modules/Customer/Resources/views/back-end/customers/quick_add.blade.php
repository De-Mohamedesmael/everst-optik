<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => route('admin.customers.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_customer_form' : 'customer_add_form',
            'enctype' => 'multipart/form-data',
        ]) !!}

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title  px-2 position-relative">@lang('lang.add_customer')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                class="close  btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            @include('customer::back-end.customers.partial.create_customer_form')
        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="col-3 py-1 btn btn-main" data-dismiss="modal">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('.selectpicker').selectpicker('render');
    $('#customer_type_id').on('change', function() {
        var value = $(this).val();
        if (value >= 2) {
            $('.div-company').removeClass('d-none');
        }else{
            $('.div-company').addClass('d-none');
        }
    });
</script>
