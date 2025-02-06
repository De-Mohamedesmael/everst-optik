<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.transaction-payment.store'), 'method' => 'post', 'add_payment_form' ])
        !!}
        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">@lang('lang.view_payments')
                <span class=" header-pill"></span>
            </h5>

            <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                    aria-hidden="true" style="border-radius: 10px !important;"><i class="dripicons-cross"></i></span></button>
            <span class="position-absolute modal-border"></span>
        </div>

        <div class="modal-body">

           @include('addstock::back-end.transaction_payment.partials.payment_table', ['payments' => $transaction->transaction_payments, 'show_action' => 'yes'])
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

