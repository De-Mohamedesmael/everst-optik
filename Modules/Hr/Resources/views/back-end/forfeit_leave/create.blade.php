<!-- Modal -->
<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="forfeit_leave">@lang('lang.forfeit_leave')</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open(['url' => action('ForfeitLeaveController@store'), 'method' => 'post', 'enctype' =>
        'multipart/form-data']) !!}
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employee_id">@lang('lang.employee')</label>
                        @if (auth()->user()->can('superadmin') || auth()->user()->is_admin == 1)
                        {!! Form::select('employee_id', $employees, $this_employee_id, ['class' => 'form-control select2', 'id' =>
                        'employee_id', 'required', 'placeholder' => 'Please Select']) !!}
                        @else
                        {!! Form::select('employee_id', $employees, $this_employee_id, ['class' => 'form-control
                        select2',
                        'id' =>
                        'employee_id', 'required', 'placeholder' => 'Please Select', 'readonly']) !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mb-2 jobtypes hide">
                <h5 id="employee_name" class="col-md-6"></h5>
                <h5 id="joing_date" class="col-md-6"></h5>
                <h5 id="job_title" class="col-md-6"></h5>
                <h5 id="no_of_emplyee_same_job" class="col-md-6"></h5>
                <h5 id="leave_balance" class="col-md-6"></h5>

                <div class="leave_balance_details col-md-12"></div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_type_id">@lang('lang.select_type_of_leave')</label>
                        {!! Form::select('leave_type_id', $leave_types, false, ['class' => 'form-control', 'required',
                        'placeholder' => 'Please Select', 'id' => 'leave_type_id']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="number_of_days">@lang('lang.number_of_days')</label>
                    <input class="form-control" type="number" id="number_of_days" step=".01" name="number_of_days" required>
                </div>
                <div class="col-md-4">
                    <label for="start_date">@lang('lang.year')</label>
                    <input class="form-control datepicker" type="text" id="start_date" name="start_date" required>
                </div>

                <div class="col-md-4">
                    <label for="upload_files">@lang('lang.upload_files')</label><br>
                    {!! Form::file('upload_files', null, ['class' => 'form-control', 'placeholder' =>
                    __('lang.upload_files'), 'id' => 'upload_files']) !!}
                </div>
                <div class="col-md-12">
                    <label for="reason">@lang('lang.reason')</label>
                    {!! Form::textarea('reason', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' =>
                    __('lang.reason'), 'id' => 'reason']) !!}
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('.datepicker').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        language: '{{session('language')}}',
        todayHighlight: true,
    });
    $('#employee_id').change(function(){
        employee_id = $(this).val();
        if(employee_id != '' &&  employee_id != null &&  employee_id != undefined){
            $('.jobtypes').removeClass('hide');
        }else{
            $('.jobtypes').addClass('hide');
        }

        $.ajax({
            method: 'get',
            url: '/get-employee-details-by-id/'+employee_id,
            data: {  },
            success: function(result) {
                $('#employee_name').text('Name: '+result.employee.name);
                $('#joing_date').text('Joining Date: '+result.employee.date_of_start_working);
                $('#job_title').text('Job Type: ' + result.employee.job_title);
                $('#no_of_emplyee_same_job').html(`Number of colleagues in same job: <a style="cursor: pointer;" data-href="/get-same-job-employee-details/${employee_id}" data-container=".second_modal" class="btn-modal"> ${result.no_of_emplyee_same_job} </a>`) ;
                $('#leave_balance').text('Leave Balance: ' + result.leave_balance);
            },
        });
        $.ajax({
            method: 'get',
            url: '/get-balance-leave-details/'+employee_id,
            data: {  },
            contentType: 'html',
            success: function(result) {
                $('.leave_balance_details').empty().append(result);
            },
        });
    })

    $(document).on('change', '#leave_type_id', function(){
        let leave_type_id = $(this).val();

        $.ajax({
            method: 'get',
            url: '/hr-management/forfeit-leaves/get-leave-type-balance-for-employee/'+$('#employee_id').val()+'/'+leave_type_id,
            data: {  },
            success: function(result) {
                console.log(result.balance_number_of_days);
                $('#number_of_days').attr({'max': result.balance_number_of_days});
            },
        });
    })

    $(document).ready(function(){
        $('#employee_id').change()
    })

    $('#paid_or_not_paid').change(function(){
        if($(this).val() === 'paid'){
            $('#amount_to_paid').attr('required', true);
            $('#payment_date').attr('required', true);
            $('.if_paid').removeClass('hide');
        }else{
            $('#amount_to_paid').attr('required', false);
            $('#payment_date').attr('required', false);
            $('.if_paid').addClass('hide');
        }
    })
</script>
