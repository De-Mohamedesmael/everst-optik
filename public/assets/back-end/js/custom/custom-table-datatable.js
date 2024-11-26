/*
---------------------------------------
    : Custom - Table Datatable js :
---------------------------------------
*/
"use strict";
$(document).ready(function () {
    /* -- Table - Datatable -- */
    $('#datatable').DataTable({
        responsive: true
    });
    $('#default-datatable').DataTable({
        "order": [],
        responsive: true,
    });
    var table = $('#datatable-buttons').DataTable({
        "order": [],

        // lengthChange: false,
        // responsive: true,
        dom: "<'row flex-wrap my-2 justify-content-center table-top-head'<'d-flex justify-content-center col-md-2'l><'d-flex justify-content-center col-md-6 text-center 'B><'d-flex justify-content-center col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'i><'col-sm-4'p>>",
        lengthMenu: [10, 25, 50, 75, 100, 200, 300, 400],
        pageLength: 10,
        buttons:
            ['copy', 'csv', 'excel', 'pdf',
                {
                    extend: 'print',
                    exportOptions: { columns: ":visible:not(.notexport)" }
                },
                {
                    extend: "colvis",
                    columns: ":gt(0)",
                },
            ],


    });
    table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

});
