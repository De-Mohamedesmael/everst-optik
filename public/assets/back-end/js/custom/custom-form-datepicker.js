/*
---------------------------------------
    : Custom - Form Datepicker js :
---------------------------------------
*/
"use strict";
$(document).ready(function() {
    /* --- Form - Datepicker -- */
    $('#default-date').datepicker({
	    language: 'en',
	    dateFormat: 'DD/MM/YYYY',
	});
    $('#autoclose-date').datepicker({
	    language: 'en',
	    autoClose: true,
	    dateFormat: 'DD/MM/YYYY',
	});
    $('#month-view-date').datepicker({
	    language: 'en',
	    minView: 'months',
	    view: 'months',
	    dateFormat: 'MM yyyy'
	});
    $('#time-format').datepicker({
    	language: 'en',
	    timeFormat: 'hh:ii aa',
	    timepicker: true,
	    dateTimeSeparator: ' - '
	});
    $('#multi-date').datepicker({
	    language: 'en',
	    dateFormat: 'DD/MM/YYYY',
	    multipleDates: 3,
	});
    $('#range-date').datepicker({
	    language: 'en',
	    dateFormat: 'DD/MM/YYYY',
	    range: true,
	    multipleDatesSeparator: ' - ',
	});
    $('#min-max-date').datepicker({
	    language: 'en',
	    dateFormat: 'DD/MM/YYYY',
	    minDate: new Date(),
	    position: 'top left',
	});
    var disabledDays = [0, 6];
	$('#disable-day-date').datepicker({
	    language: 'en',
	    dateFormat: 'DD/MM/YYYY',
	    position: 'top left',
	    onRenderCell: function (date, cellType) {
	        if (cellType == 'day') {
	            var day = date.getDay(),
	                isDisabled = disabledDays.indexOf(day) != -1;

	            return {
	                disabled: isDisabled
	            }
	        }
	    }
	});
});
