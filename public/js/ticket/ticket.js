$(document).ready(function () {

    //Turn checkboxes into toggle switches.
    var switcheryElements = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    switcheryElements.forEach(function (html) {
        var switchery = new Switchery(html);
    });

    //Select2 drop down initializations
    if ($(".select2").length) {
        $(".select2").select2({
            response: true,
            dropdownCssClass: "ticket-status"
        });
    };

    /*****Remove the error message on modal close*****/
    $('.modal').not("#editTicket").on('hidden.bs.modal', function () {
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

    // Showing loading icon for all ajax calls
    $(document).ajaxSend(function (event, jqxhr, settings) {
        $.LoadingOverlay("show");
    });
    $(document).ajaxComplete(function (event, jqxhr, settings) {
        $.LoadingOverlay("hide");
    });

    //Function for calling ticket module table
    if ($('#tickets-module-table').is(':visible')) {
        ticketsTable(ticketStatus = 0);
    }

    //General function for normal form submit
    $('.standard_form_submit').click(function (e) {
        $('.help-block').html('');
        var formId = $(this).closest('form').attr('id');
        var actionUrl = $(this).closest('form').attr('action');
        var modalId = $(this).closest('.modal').attr('id');
        var formData = new FormData($('#' + formId)[0]);
        var dataTableId = $(this).attr('data-table');
        var i = 0;
        invalid = 0;
        $('input[name^=fileupload]').each(function () {
            var filevalue = $(this).val();
            if (filevalue) {
                var fileupload = $(this).prop('files')[0];
                var id = $(this).attr('id', 'ticketfile-' + i);
                if (fileupload) {
                    if (!validateFile($(this).attr('id'))) {
                        $('.file_error').innerText = 'error';
                        invalid = 1;
                    }
                }
            }
            i++;
        });
        if (invalid) {
            var texterror = document.getElementById('file_error');
            texterror.innerText = Lang.get('ticketingtool.file_size');
            return false;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#' + modalId).modal('toggle');
                    if (dataTableId) {
                        $('#' + dataTableId).DataTable().ajax.reload();
                    } else {
                        window.location.reload();
                    }
                }
            }
        });
    });

    //Function for deleting comment in ticket
    $('body').on('click', '.comment-delete', function (e) {
        var commentId = $(this).data('id');
        var rowId = $(this).data('row-id');
        swal({
            title: Lang.get('ticketingtool.are_you_sure'),
            text: Lang.get('ticketingtool.comment_deleteconfirm'),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#F0AB00",
            confirmButtonText: Lang.get('ticketingtool.yes'),
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: baseUrl + "tickets/comment/delete/" + commentId,
                method: "GET",
                success: function (data) {
                    $("ul#comment-" + rowId).remove('#comment-' + rowId);
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.comment_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                }
            });
        });
    });

    $('body').on('click', '.comment-edit', function (e) {
        var id = $(this).data('id');
        $('#ticket_comment_id').val(id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'tickets/comment/edit/' + id,
            type: 'GET',
            success: function (data) {
                console.log(data);
                $('#edit_comment').val(data.description);
                if (data.attachments) {
                    var files = jQuery.parseJSON(JSON.stringify(data.attachments));
                }
                var filearray = [];
                var parentDiv = document.getElementById("edit_ticket_file_name_class");
                jQuery('#edit_ticket_file_name_class').html('');

                if (files) {
                    $.each(files, function (index, value) {
                        var fileicon = baseUrl + 'storage/tickets/' + value.stored_name;
                        $('#edit_ticket_file_name_class').append('<ul id ="file-' + index + '"><li><ul class="ul-ticket-view">\n\
                        <li><input readonly type="text" value ="' + value.file_name + '" data=index class="form-control remove_input"></li>\n\
                        <li><a class="fa fa-eye btn btn-xs btn-warning remove_a view-a" title="View Attachments" "value" = "' + index + '" target="_blank" href="' + fileicon + '"></a></li>\n\
                        <li> <a class="fa fa-trash btn btn-xs btn-danger btn-delete" onclick="deleteTicketCommentFile(' + index + ', ' + id + ')" title="Delete" value="' + value.stored_name + '" id="' + index + '"></a></li>\n\
                        </ul></li></ul>');
                    });
                }
                $('#editTicketComment').modal('toggle');
            }
        });
    });

    //Ticket Status filter in
    $('#ticket_status_filter').change(function () {
        $('#tickets-module-table').dataTable().fnClearTable();
        $('#tickets-module-table').dataTable().fnDestroy();
        ticketsTable($(this).val());
    });

    //Ticket Status Onchange
    $('body').on('change', '#view_ticket_status', function (e) {
        var ticketStatus = $(this).val();
        var ticketId = $('#ticket_id').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'edit-ticketstatus/' + ticketId + "/" + ticketStatus,
            type: 'GET',
            success: function (data) {
                swal({
                    title: Lang.get('ticketingtool.status_changed'),
                    text: Lang.get('ticketingtool.status_changed_successfully'),
                    type: "success",
                    confirmButtonColor: "#00cc00",
                    confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                });
            }
        });
    });

});

//Function for Ticket module data table
function ticketsTable(ticketStatus)
{
    $("#tickets-module-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        "bDestroy": true,
        "ordering": true,
        "searching": true,
        responsive: true,
        "ajax": {
            "url": baseUrl + "tickets/get/" + ticketStatus,
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'ticket-' + aData.id);
        },
        "columns": [
            {
                "width": "10%",
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
        },
            {
                "data": "ticket_number"
        },
            {
                sortable: true,
                "width": "10%",
                "render": function (data, type, content, meta) {
                    if (content.subject) {
                        var str = content.subject;
                        if (str.length > 20) {
                            var strname = str.substr(0, 20);
                            return strname + '...';
                        } else {
                            return str;
                        }
                    } else {
                        return null;
                    }

                }
        },
            {
                sortable: true,
                "width": "10%",
                "render": function (data, type, content, meta) {
                    var createdAt = moment(content.created_at).format('DD.MM.YYYY hh:mm');
                    return createdAt;
                }
        },
            {
                "data": "comments_count"
        },
            {
                "data": "ticket_status[0].name"
        },
            {
                sortable: false,
                "width": "15%",
                "render": function (data, type, content, meta) {
                    return '<a class="btn btn-xs  btn-common btn-warning btn-edit" href="/tickets/view/' + content.id + '">\n\
                            <i class="fa fa-eye" title="' + Lang.get('ticketingtool.btn_view') + '"></i></a>\n\
                            <a class="btn btn-xs btn-edit btn-common btn-editgradient" onclick="editTicket(' + content.id + ')">\n\
							<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i></a>\n\
						    <button onclick="deleteTicket(' + content.id + ')"  id="image-delete" class="btn btn-xs btn-delete btn-common btn-deletegradient" data-id="' + content.id + '">\n\
							<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
						    </button>';
                }
        }
        ]
    });
}

// Function for Edit Ticket
function editTicket(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'tickets/edit/' + id,
        type: 'GET',
        success: function (data) {
            $('#edit_name').val(data.name);
            $('#edit_email').val(data.email);
            $('#edit_mobile').val(data.telephone);
            $('#edit_project').val(data.project_name);
            $('#edit_subject').val(data.subject);
            $('#edit_categories').val(data.category_id).trigger('change');
            $('#edit_ticket_status').val(data.status_id).trigger('change');
            $('#edit_message').val(data.message);
            $('#ticket_id').attr('value', data.id);
            $('.fileUpload').val('');
            if (data.attachments) {
                var files = jQuery.parseJSON(JSON.stringify(data.attachments));
            }
            var filearray = [];
            var parentDiv = document.getElementById("edit_ticket_file_name_class");
            jQuery('#edit_ticket_file_name_class').html('');

            if (files) {
                $.each(files, function (index, value) {
                    var fileicon = baseUrl + 'storage/tickets/' + value.stored_name;
                    $('#edit_ticket_file_name_class').append('<ul id ="file-' + index + '"><li><ul class="ul-ticket-view">\n\
                    <li><input readonly type="text" value ="' + value.file_name + '" data=index class="form-control remove_input"></li>\n\
                    <li><a class="fa fa-eye btn btn-xs btn-warning remove_a view-a" title="View Attachments" "value" = "' + index + '" target="_blank" href="' + fileicon + '"></a></li>\n\
                    <li> <a class="fa fa-trash btn btn-xs btn-danger btn-delete" onclick="deleteTicketFile(' + index + ', ' + id + ')" title="Delete" value="' + value.stored_name + '" id="' + index + '"></a></li>\n\
                    </ul></li></ul>');
                });
            }
            $('#editTicket').modal('toggle');
        }
    });
}


// Function for deleting a ticket attachment
function deleteTicketFile(fileId, ticketId)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.ticket_file_deleteconfirm'),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F0AB00",
        confirmButtonText: Lang.get('ticketingtool.yes'),
        closeOnConfirm: false,
    }, function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + "ticket-file/delete/" + fileId + '/' + ticketId,
            method: "GET",
            success: function (data) {
                swal({
                    title: Lang.get('ticketingtool.message_deleted'),
                    text: Lang.get('ticketingtool.file_deleted_successfully'),
                    type: "success",
                    confirmButtonColor: "#00cc00",
                    confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                });
                $('.sa-confirm-button-container').click(function () {
                    $('#file-' + fileId).remove();
                });
            }
        });
    });
}

// Function for Delete Ticket
function deleteTicket(id)
{
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
                url: baseUrl + "tickets/delete/" + id,
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
                        $('#ticket-' + id).remove();
                    });
                }
            }
        );
    });
}

// Function for deleting a ticket comment attachment
function deleteTicketCommentFile(fileId, ticketCommentId)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.ticket_file_deleteconfirm'),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F0AB00",
        confirmButtonText: Lang.get('ticketingtool.yes'),
        closeOnConfirm: false,
    }, function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + "ticket-comment-file/delete/" + fileId + '/' + ticketCommentId,
            method: "GET",
            success: function (data) {
                swal({
                    title: Lang.get('ticketingtool.message_deleted'),
                    text: Lang.get('ticketingtool.file_deleted_successfully'),
                    type: "success",
                    confirmButtonColor: "#00cc00",
                    confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                });
                $('.sa-confirm-button-container').click(function () {
                    $('#file-' + fileId).remove();
                });
            }
        });
    });
}

//function for validating File and Image upload.
function validateFile(id)
{
    $('.' + id + '-error').html('');
    //var allowedExtension = ['jpeg', 'jpg', 'png', 'svg', 'pdf', 'docx', 'doc', 'txt', 'xlsx', 'xml', 'mp4'];
    // var fileExtension = $('#' + id).val().split('.').pop().toLowerCase();
    var imageId = $('#' + id)[0];
    // var validFormat = 0;
    // for (var index in allowedExtension) {
    //     if (fileExtension === allowedExtension[index]) {
    //         validFormat = 1;
    //     }
    //     if (fileExtension === 'xls') {
    //         validFormat = 3;
    //         break;
    //     }
    // }
    // if (!fileExtension || validFormat == 0) {
    //     $('.' + id + '-error').html(Lang.get('ticketingtool.valid_file'));
    //     return false;
    // }
    // if (validFormat == 3) {
    //     $('.' + id + '-error').html(Lang.get('ticketingtool.valid_file_xlsx'));
    //     return false;
    // }
    var fileSize = (imageId.files[0].size / 1024);
    if (fileSize / 1024 > 1) {
        if (((fileSize / 1024) / 1024) > 1) {
            $('.' + id + '-error').html(Lang.get('ticketingtool.image_size'));

            return false;
        } else {
            fileSize = (Math.round((fileSize / 1024) * 100) / 100);
            if (fileSize > 32) {
                $('.' + id + '-error').html(Lang.get('ticketingtool.image_size'));

                return false;
            } else {
                return true;
            }
        }
    } else {
        return true;
    }
}
