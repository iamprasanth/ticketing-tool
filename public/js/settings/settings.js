$(document).ready(function () {

    //Turn checkboxes into toggle switches.
    var switcheryElements = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    switcheryElements.forEach(function (html) {
        var switchery = new Switchery(html);
    });

    /*****Remove the error message on modal close*****/
    $('.modal').not("#editTicketStatus,#editTicketCategory").on('hidden.bs.modal', function () {
        $('.help-block').html('');
        if ($(this).hasClass('add-modal')) {
            $(this).find('input, select, textarea').not("input[type=hidden]").not("input[type=checkbox]").val('');
        } else {
            $(this).find('form').trigger('reset');
        }
        $(".dropify-clear").trigger("click");
        $('.select2').not('.filter').val(0).trigger('change');
        $('.selectpicker').not('.filter').val(0).trigger('change');
    });
    /*****Remove the error message on modal close*****/

    //Function for calling function for ticket category table
    if ($('#ticket-category-table').is(':visible')) {
        ticketCategoryTable();
    }

    //General function for normal form submit
    $('.standard_form_submit').click(function (e) {
        $('.help-block').html('');
        var formId = $(this).closest('form').attr('id');
        var actionUrl = $(this).closest('form').attr('action');
        var modalId = $(this).closest('.modal').attr('id');
        var formData = new FormData($('#' + formId)[0]);
        var dataTableId = $(this).attr('data-table');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#' + modalId).modal('toggle');
                    $('#' + dataTableId).DataTable().ajax.reload();
                }
            }
        });
    });

    //Function for calling function for ticket status table
    if ($('#ticket-status-table').is(':visible')) {
        ticketStatusTable();
    }
});

//Function for Ticket category data table
function ticketCategoryTable() {
    $("#ticket-category-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        "bDestroy": true,
        "ordering": true,
        "searching": true,
        responsive: true,
        "ajax": {
            "url": baseUrl + "ticket-category/get",
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'ticket-category-' + aData.id);
        },
        "columns": [
            {
                "width": "10%",
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                sortable: true,
                "width": "25%",
                "render": function (data, type, content, meta) {
                    var name = content.name;

                    return (name.length > 45) ? name.substr(0, 45) + '...' : name;
                }
            },
            {
                sortable: true,
                "width": "10%",
                "render": function (data, type, content, meta) {
                    if (content.is_active == 0) {
                        return Lang.get('ticketingtool.inactive');
                    } else if (content.is_active == 1) {
                        return Lang.get('ticketingtool.active');
                    }
                }
            }, {
                sortable: false,
                "width": "15%",
                "render": function (data, type, content, meta) {
                    return '<a class="btn btn-xs btn-edit btn-common btn-editgradient" onclick="editTicketCategory(' + content.id + ')">\n\
							<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i>\n\
						</a>\n\
						<button onclick="deleteTicketCategory(' + content.id + ')"  id="image-delete" class="btn btn-xs btn-delete btn-common btn-deletegradient" data-id="' + content.id + '">\n\
							<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
						</button>';
                }
            }
        ]
    });
}

//Function for filling edit Ticket category form
function editTicketCategory(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'ticket-category/edit/' + id,
        type: 'GET',
        success: function (data) {
            $('.edit_ticketCategory_submit_btn').attr('data-id', data.id);
            $('#ticket_category').val(data.name);
            $('#ticketcategory_id').attr('value', data.id);
            if (data.is_active == '1' && !$('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            if (data.is_active == '0' && $('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            $('#editTicketCategory').modal('show');
        }
    });
}

// Function for delete Ticket category
function deleteTicketCategory(id) {
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.ticketcategory_deleteconfirm'),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F0AB00",
        confirmButtonText: Lang.get('ticketingtool.yes'),
        closeOnConfirm: false,
    }, function () {
        $.ajax(
            {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + "ticket-category/delete/" + id,
                method: "GET",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.ticket_category_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#ticket-category-' + id).remove();
                    });
                }
            }
        );
    });
}

//Function for Ticket status data table
function ticketStatusTable() {
    $("#ticket-status-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        "bDestroy": true,
        "ordering": true,
        "searching": true,
        responsive: true,
        "ajax": {
            "url": baseUrl + "ticket-status/get",
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'ticket-status-' + aData.id);
        },
        "columns": [
            {
                "width": "10%",
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                sortable: true,
                "width": "25%",
                "render": function (data, type, content, meta) {
                    var name = content.name;

                    return (name.length > 45) ? name.substr(0, 45) + '...' : name;
                }
            },
            {
                sortable: true,
                "width": "10%",
                "render": function (data, type, content, meta) {
                    if (content.is_active == 0) {
                        return Lang.get('ticketingtool.inactive');
                    } else if (content.is_active == 1) {
                        return Lang.get('ticketingtool.active');
                    }
                }
            }, {
                sortable: false,
                "width": "15%",
                "render": function (data, type, content, meta) {
                    return '<a class="btn btn-xs btn-edit btn-common btn-editgradient" onclick="editTicketStatus(' + content.id + ')">\n\
							<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i>\n\
						</a>\n\
						<button onclick="deleteTicketStatus(' + content.id + ')"  id="image-delete" class="btn btn-xs btn-delete btn-common btn-deletegradient" data-id="' + content.id + '">\n\
							<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
						</button>';
                }
            }
        ]
    });
}

//Function for filling edit Ticket Status form
function editTicketStatus(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'ticket-status/edit/' + id,
        type: 'GET',
        success: function (data) {
            $('.edit_ticketStatus_submit_btn').attr('data-id', data.id);
            $('#ticket_status').val(data.name);
            $('#ticketstatus_id').attr('value', data.id);
            if (data.is_active == '1' && !$('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            if (data.is_active == '0' && $('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            $('#editTicketStatus').modal('show');
        }
    });
}


// Function for delete Ticket status
function deleteTicketStatus(id) {
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.ticketstatus_deleteconfirm'),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F0AB00",
        confirmButtonText: Lang.get('ticketingtool.yes'),
        closeOnConfirm: false,
    }, function () {
        $.ajax(
            {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + "ticket-status/delete/" + id,
                method: "GET",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.ticket_status_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#ticket-status-' + id).remove();
                    });
                }
            }
        );
    });
}
