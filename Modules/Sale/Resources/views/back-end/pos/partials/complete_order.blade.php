<div class="modal-dialog" id="complete_order" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.transaction.complete.store',$transaction_id), 'method' => 'post', 'id' =>
        'complete_order_form', 'enctype' => 'multipart/form-data']) !!}

        {{-- <div class="modal-content">--}}
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">
                    {{translate('complete_order')}}

                </h5>

                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true" style="border-radius: 10px !important;"><i
                            class="dripicons-cross"></i></span></button>

            </div>
            {{-- <div class="modal-body">--}}
                <div class="modal-body">
                    <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">
                    @if($transaction->is_lens && (!$transaction->is_send_to_uts || !$transaction->is_delivered))
                        <div class="col-5 d-flex  flex-row justify-content-between align-items-center">
                            @if(!$transaction->is_send_to_uts)
                                <div class="col-md-5 px-0 d-flex justify-content-center">
                                    <div class="i-checks toggle-pill-color flex-col-centered">
                                        <input id="is_send_to_uts" name="is_send_to_uts" type="checkbox" checked value="1" class="form-control-custom">
                                        <label for="is_send_to_uts">
                                        </label>
                                        <span>
                                            <strong>
                                                {{translate('send_to_uts')}}
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                            @endif
                                @if(!$transaction->is_delivered)
                                    <div class="col-md-5 px-0 d-flex justify-content-center">
                                        <div class="i-checks toggle-pill-color flex-col-centered">
                                            <input id="is_delivered" name="is_delivered" type="checkbox" checked value="1" class="form-control-custom">
                                            <label for="is_delivered">
                                            </label>
                                            <span>
                                            <strong>{{translate('Delivered')}}</strong>
                                        </span>
                                        </div>
                                    </div>
                                @endif
                        </div>
                    @endif

                </div>

                <div class="modal-footer row">
                    <button type="button" id="complete_order_button"
                        class="btn btn-primary col-6">@lang('lang.save')</button>
                    <button type="button" id="close_modal_button" class="btn btn-outline-danger col-6"
                        data-dismiss="modal">@lang('lang.close')</button>
                </div>

                {!! Form::close() !!}

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
<script>
    $(document).ready(function() {

        var pageTitle = window.location.pathname;
        console.log(pageTitle);
        $('#complete_order_button').click(function() {
            $('#complete_order_form').submit();
        });

        if(pageTitle!=="/pos/create"){
            $('#complete_order_form').submit(function(e) {
                e.preventDefault()
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response here

                        $('#complete_order_form')[0].reset();
                        $('#close_modal_button').click();
                        $('#sales_table').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        // Handle error response here
                        console.log(error);
                    }
                });
            });
        }
    });
</script>
