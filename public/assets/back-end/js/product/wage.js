
$(document).ready(function(){
    $('.js-example-basic-multiple').select2(
        {
            placeholder: LANG.please_select,
            tags: true
        }
    );
    $(function() {
        $( ".datepicker" ).datepicker({
        });
    });
});
$('#payment_type').change(function() {
    if ($(this).val() === 'salary') {
        $('#account_period').attr('required', true);
        $('.account_period_dates').addClass('hide');
        $('.account_period').removeClass('hide');
    } else {
        $('#account_period').attr('required', false);
        $('.account_period').addClass('hide');
        $('.account_period_dates').removeClass('hide');
    }
})

$('.calculate_salary').change(function() {
    let employee_id = $('#employee_id').val();
    let payment_type = $('#payment_type').val();

    if (employee_id != null && employee_id != undefined && payment_type != null && payment_type !=
        undefined) {

        if (payment_type === 'salary' || payment_type === 'commission') {
            // alert(1);
            $.ajax({
                method: 'get',
                url: `/wages/calculate-salary-and-commission/${employee_id}/${payment_type}`,
                data: {
                    acount_period_end_date: $('#acount_period_end_date').val(),
                    acount_period_start_date: $('#acount_period_start_date').val(),
                },
                success: function(result) {
                    if (result.amount)
                    {
                        // +++++++++++++ convert [ result.amount ] from [ 1,000.00 format ] to [ 1000.00 format ] +++++++++++++
                        const stringWithCommas = result.amount;
                        const stringWithoutCommas = stringWithCommas.replace(/,/g, ''); // Remove commas
                        const decimalAmount = parseFloat(stringWithoutCommas); // Parse as a decimal
                        // console.log(decimalValue);
                        $('#amount').val(decimalAmount);
                        // alert(decimalAmount);
                        let amount = decimalAmount
                        if ($('#deductibles').val() != '' && $('#deductibles').val() !=
                            undefined) {
                            let deductibles = parseFloat( $('#deductibles').val());
                            amount = amount - deductibles;
                        }
                        $('#net_amount').val(amount);
                    } else {
                        $('#amount').val(0);
                    }
                },
            });
        }
    }
})

$('#net_amount').change(function() {
    if ($('#payment_type').val() !== 'salary' && $('#payment_type').val() !== 'commission') {
        $('#amount').val($(this).val());
    }
})
$('#deductibles').change(function() {
    if ($('#deductibles').val() != '' && $('#deductibles').val() != undefined) {
        let deductibles = parseFloat($('#deductibles').val());
        let amount = 0;
        if ($('#amount').val() != '' && $('#amount').val() != undefined) {
            amount = $('#amount').val();
        }
        amount = amount - deductibles;
        $('#net_amount').val(amount);
    }
})

$(document).ready(function() {
    $('#payment_status').change();
    $('#source_type').change();
})
