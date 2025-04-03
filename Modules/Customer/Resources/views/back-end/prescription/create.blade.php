<style>
    textarea#note {
        height: 100px;
    }


</style>
<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex justify-content-between py-2 flex-row ">
            <h5 class="modal-title" id="edit">@lang('lang.add_brand')</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        {!! Form::open([
            'url' => route('admin.prescriptions.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_prescription_form' : 'brand_add_form',
            'files' => true,
        ]) !!}

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif align-items-center">
            <div class="form-group col-md-4 px-5">
                {!! Form::label('date', __('lang.date') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::date('date', null, [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.date'),
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-4 px-5">
                {!! Form::label('doctor_name', __('lang.doctor_name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('doctor_name', null, [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.doctor_name'),
                    'required',
                ]) !!}
            </div>
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            <input type="hidden" name="customer_id" value="{{ $customer_id }}">

            <div class="col-md-4 mb-2">
                {!! Form::label('image', translate('image_prescription'). '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::file('image', [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'style' => 'height:30px',
                ]) !!}
            </div>
            <div class="form-group col-md-6 px-5">
                {!! Form::label('note', __('lang.note') , [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::textarea('note', null, [
                    'cols'=>'10',
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.note'),
                ]) !!}
            </div>
        </div>
        <div id="cropped_brand_images"></div>


        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button id="submit-prescriptions-btn" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

    <script>

$("#submit-prescriptions-btn").on("click", function (e) {
    e.preventDefault();
    $(".modal").modal("hide");

    if ($("#quick_add_prescription_form").valid()) {
          $.ajax({
          type: "POST",
          url: $("form#quick_add_prescription_form").attr("action"),
            data: $("#quick_add_prescription_form").serialize(),
              success: function (response) {
                  let customer_id = $("#customer_id").val();
                  var prescription_id = response.prescription_id;
                  $.ajax({
                      method: "get",
                      url: "/dashboard/prescriptions/get-dropdown?customer_id="+customer_id,
                      data: {},
                      contactType: "html",
                      success: function (data_html) {
                          $("#prescription_id").empty().append(data_html);
                          $("#prescription_id").selectpicker("refresh");
                          $("#prescription_id").selectpicker(
                              "val",
                              [prescription_id]
                          );
                      },
                  });
              // Swal.fire(response.status);
              // Swal.fire("Success", response.status, "success");
                  Swal.fire({
                  title: "Success",
                  text: response.status,
                  icon: "success",
                  timer: 1000, // Set the timer to 1000 milliseconds (1 second)
              showConfirmButton: false // This will hide the "OK" button
              });

                  // location.replace('/suppliers');
              // $(".ajaxform")[0].reset();
              },
              error: function (response) {
              // Swal.fire(response.status);
              // Swal.fire("Error", response.status, "error");
                  Swal.fire({
                  title: "Error",
                  text: response.status,
                  icon: "error",
                  timer: 1000, // Set the timer to 1000 milliseconds (1 second)
              showConfirmButton: false // This will hide the "OK" button
              });
              },

              });
        }

    });

    </script>
