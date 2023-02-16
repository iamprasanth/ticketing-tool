/* Custom Javascript for ticketingtool */

$(document).ready(function () {

    //Turn checkboxes into toggle switches.
    var switcheryElements = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    switcheryElements.forEach(function (html) {
        var switchery = new Switchery(html);
    });

    // login user on enter key up
    $(':input').not('textarea').keypress(function (e) {
        if (e.which == 13) {
            $('.login').click();
            return false;
        }
    });

    //Project grid-tabs
    if ($('.project-grid-view').length) {
        $('.project-grid-view').matchHeight({
            byRow:false
        });
    };
    $('.list').on('click touchstart', function () {
        if ($('.project-grid').hasClass('list-view')) {
            if ($(this).hasClass('active')) {
                return;
            }
            return;
        } else if ($('.project-grid').hasClass('column-view')) {
            $('.project-grid').removeClass('column-view');
            $('.project-grid').addClass('list-view');
            $(this).addClass('active');
            $('.card').removeClass('active');
        } else {
            $('.project-grid').addClass('list-view');
            $(this).addClass('active');
            $('.card').removeClass('active');
        }
    });

    $('.card').on('click touchstart', function () {
        if ($('.project-grid').hasClass('column-view')) {
            if ($(this).hasClass('active')) {
                return;
            }
            return;
        } else if ($('.project-grid').hasClass('list-view')) {
            $('.project-grid').removeClass('list-view');
            $('.project-grid').addClass('column-view');
            $(this).addClass('active');
            $('.list').removeClass('active');
        } else {
            $('.project-grid').addClass('column-view');
            $(this).addClass('active');
            $('.list').removeClass('active');
        }
    });

    $(".task-outline .label-name ").each(function () {
        if ($(this).text()=="") {
            $(this).hide();
        }
    });

    $(".task-outline .viewComments ").each(function () {
        if ($(this).text()=="") {
            $(this).hide();
        }
    });

    $(".task-outline .asignee-name ").each(function () {
        if ($(this).text()=="") {
            $(this).hide();
        }
    });


    $('body').on('click', '.add-subtask-btn', function (e) {
        $('#task_group_id').val($(this).data('id'));
        CKEDITOR.instances['add_task_description'].setData('');
        $('#addSubTask').modal('toggle');
    });

    // For login functionality
    $(document).on('click', '.login', function () {
        var formData = $('#login-form').serialize();
        $("#login-form :input").each(function () {
            var input = $(this).attr('name');
            var id = input + "-error";
            $('#' + id).html('');
        });
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: 'login',
            method:'POST',
            data:formData,
            error:function (jqXhr) {
                if ( jqXhr.status === 422 ) {
                    var data = jqXhr.responseJSON;
                    $.each(data.errors, function (key, val) {
                        $("#" + key + "-error").text(val[0]);
                    });
                }
                if ( jqXhr.status === 401) {
                    var data = jqXhr.responseJSON;
                    $.each(data, function (key, val) {
                         $("#password-error").text(val);
                    });
                }
                if ( jqXhr.status === 429) {
                    var data = jqXhr.responseJSON;
                    $.each(data, function (key, val) {
                        $("#password-error").text(data.message);
                    });
                }
            },
            success:function () {
                window.location.href = '/';
            }
        });
    });

    //function for viewing entered password in change password form
    $(document).on('click', '.show-password', function () {
        if ($('#' + $(this).data('id')).attr('type') == 'password') {
            $('#' + $(this).data('id')).attr('type', 'text');
            $(this).attr('class', 'fa fa-eye show-password');
        } else {
            $('#' + $(this).data('id')).attr('type', 'password');
            $(this).attr('class', 'fa fa-eye-slash show-password');
        }
    })

    //function for submitting change password form
    $('.change_password_submit_btn').click(function (e) {
        var formData = $("#change_password_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'password/change',
            type: 'POST',
            data: formData,
            success:function (data) {
                if ($('#current_password').val() == '') {
                    $(".current_password-error").text(Lang.get('ticketingtool.please_fill_this_field'));
                } else if (data.password_validity == 0) {
                    $(".current_password-error").text(Lang.get('ticketingtool.incorrect_password'));
                }
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#changePassword').modal('toggle');
                    swal({
                        title: Lang.get('ticketingtool.password_changed_successfully'),
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#F0AB00",
                        confirmButtonText: Lang.get('ticketingtool.ok'),
                        closeOnConfirm: false,
                    });
                }
            }
        });
    });

    /************Tabbed content*******************/
    $('.tab-struct li a').click(function () {
        location.hash = this.id;
    });

    $(function () {
        if (window.location.hash) {
            $(window.location.hash).trigger('click');
        }
    });
    /************Tabbed content*******************/

    //Function for calling users table
    if (! $('#login-form').is(':visible')) {
        if ($(".dateofbirth").length) {
            $(".dateofbirth").datepicker({
                pick12HourFormat: true,
                minView: 2,
                format: constants.DATEPICKER_DATEFORMAT,
                dateonly: true,
                weekStart: 0,
                calendarWeeks: false,
                autoclose: true,
                todayHighlight: false,
                rtl: true,
                orientation: "bottom",
                });
        };
    };

    if ($('.project_due_date').length) {
        $(".project_due_date").datepicker({
            pick12HourFormat: true,
            minView: 2,
            format: constants.DATEPICKER_DATEFORMAT,
            dateonly: true,
            weekStart: 0,
            autoclose: true,
            calendarWeeks: false,
            todayHighlight: false,
            orientation: "auto",
        });
    };

    //Calendar for due date
    if ($('#subTask_date').length) {
        $('#subTask_date').datepicker({
            pick12HourFormat: true,
            minView: 2,
            format: constants.DATEPICKER_DATEFORMAT,
            dateonly: true,
            weekStart: 0,
            calendarWeeks: true,
            todayHighlight: false,
            autoclose: true,
            rtl: true,
            startDate: new Date(),
            orientation: "auto"
        });
    };

    if ($('.task-estimate').length) {
        $('.task-estimate').inputmask(
            "99 : 99",
            {
                placeholder: "HH : MM",
                insertMode: false,
                showMaskOnHover: false,
            }
        );
    };

  //**************For docs add more******************************************
    var x = 1;
    $('.add_task_files').click(function (e) {
        e.preventDefault();
        x++;
        $('.input_fields_container_doc_task').append('<div class="document-upload multiple-file-wrap "><input type="file" name="taskFile[]" id="task-file-'+x+'" class="article-img-upload multiple-file task-file"><a href="#" class="remove_field remov-cls" id="task-file-'+x+'-error" style="margin-left:10px;">' + Lang.get('ticketingtool.remove') + '</a></div><div class="help-block task-file-'+x+'-error task-file-error"></div>');
    });

    $('.input_fields_container_doc_task').on("click",".remove_field", function (e) {
        e.preventDefault(); $(this).parent('div').remove(); x--;
        $('.' + $(this).attr('id')).html('');
    });

    $('.input_fields_container_doc_task').on("click",".remove-first", function (e) {
        e.preventDefault();
        $(this).next(".task-file-error").html('');
        $('.remove_project_file').val('');
        $('.remove-lead-file').val('');
        $('.remove-first').hide();
    });
    //**************For docs add more ends******************************************
    //**************For comment docs add more******************************************
    var x = 1;
    $('.add_comment_files').click(function (e) {
        e.preventDefault();
            x++;
            $('.input_fields_container_doc').append('<div class="document-upload multiple-file-wrap"><input type="file" id="comment-file-'+x+'" name="commentFiles[]" class="article-img-upload multiple-file comment-file"><a href="#" class="remove_field" id="comment-file-'+x+'-error" style="margin-left:10px;">' + Lang.get('Remove') + '</a></div><div class="help-block comment-file-'+x+'-error comment-file-error"></div>');
    });

    $('.input_fields_container_doc').on("click",".remove_field", function (e) {
        e.preventDefault(); $(this).parent('div').remove(); x--;
        $('.' + $(this).attr('id')).html('');
    });

    $('.input_fields_container_doc').on("click",".remove-first", function (e) {
        e.preventDefault();
        $(this).next(".comment-file-error").remove();
        $('.remove_project_file').val('');
        $('.remove-lead-file').val('');
        $('.remove-first').hide();
    });

    $('body').on('change', '.task-file', function (e) {
        validateFile($(this).attr('id'));
    });

    //**************For docs add more ends******************************************
    //**************For docs add more start******************************************
    var x = 1;
    $('.add_more_project_file_doc').click(function (e) {
        e.preventDefault();
            x++;
            $('.input_fields_container_doc_project').append('<div class="document-upload edit_leads_file_cls multiple-file-wrap"><input type="file" name="attachments[]" id="fileuploads" class="article-img-upload multiple-file"><a href="#" class="remove_field remov-cls" style="margin-left:10px;">' + Lang.get('ticketingtool.remove') + '</a></div>');
    });
    $('.input_fields_container_doc_project').on("click",".remove_field", function (e) {
        e.preventDefault();
          $(this).parent('div').remove(); x--;
    });
    $('.input_fields_container_doc_project').on("click",".remove-first", function (e) {
        e.preventDefault();
        $('.remove_project_file').val('');
        $('.remove-lead-file').val('');
        $('.remove-first').hide();
    });
    $('.input_fields_container_doc_project').on("click",".remove-first", function (e) {
        e.preventDefault();
        $('.remove-lead-file').val('');
        $('.remove-first').hide();
    });
    //**************For docs add more ends******************************************


    // add project task list
    $('.add_taskList_submit_btn').click(function (e) {
        $.LoadingOverlay("show");
        e.preventDefault();
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        var formData = new FormData($('#add_subTask_form')[0]);
        var taskGroupId = $('#task_group_id').val();
        var invalid = 0;
        $('input[name^=taskFile]').each(function () {
            var filevalue = $(this).val();
            if (filevalue) {
                var fileupload = $(this).prop('files')[0];
                if (fileupload) {
                    if (!validateFile($(this).attr('id'))) {
                        invalid = 1;
                    }
                }
                formData.append('file', fileupload);
            }
        });
        if (invalid == 1) {
            return false;
        }
        $('.help-block').not('.task-file-error').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'addsubTask',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $.LoadingOverlay("hide");
                    $('.task-outline-' + $('#task_group_id').val()).append('\n\
					<div class="col-xs-12 projectInnerPage" id="sub-task-' + data.success + '">\n\
						<div class="pretty p-svg p-round p-pulse">\n\
							<input type="checkbox" class="complete-task" data-id="' + data.success + '"/>\n\
							<div class="state">\n\
								<svg class="svg svg-icon" viewBox="0 0 20 20">\n\
									<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: #65b32e;fill:#65b32e;"></path>\n\
								</svg>\n\
								<label class="subtask-radio ">\n\
									<a data-toggle="modal"  onclick="viewSubTask('+ data.success + ')"><span class="asignee-name" id="assignee-for-subtask'+ data.success + '">' + $('option:selected', '#task_assignee').html() + '</span>&nbsp;<span class="sub-name" id="name-forsubtask-'+ data.success + '">' + $('#task_name').val() + '</span>&nbsp;&nbsp;<span class="label-name" id="label-for-subtask'+ data.success + '">' + $('option:selected', '#project_label').html() + '</span>\n\
										&nbsp;&nbsp;<span class="text-light" id="date-for-subtask' + data.success + '"></span>\n\
										<i class="fa fa-exclamation-circle" id="priority-icon-' + data.success + '"></i>\n\
									</a>\n\
								</label>\n\
							</div>\n\
						</div>\n\
						<span class="edit-project_view" onclick="editSubTask('+ data.success + ')"><svg width="14px" height="14px" viewBox="0 0 14 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n\
							<title>Edit</title>\n\
							<desc>Created with Sketch.</desc>\n\
							<g id="Task-Modal-/-Page--❤️✅" stroke="none" stroke-width="1" fill-rule="evenodd">\n\
								<path d="M2,-3.63797881e-12 C0.89,-3.63797881e-12 0,0.89 0,2 L0,12 C0,13.1045695 0.8954305,14 2,14 L12,14 C13.1045695,14 14,13.1045695 14,12 L14,5 L13,5 L13,12 C13,12.5522847 12.5522847,13 12,13 L2,13 C1.44771525,13 1,12.5522847 1,12 L1,2 C1,1.44771525 1.44771525,1 2,1 L9,1 L9,-3.63797881e-12 L2,-3.63797881e-12 Z M12.3,0.2 L11.08,1.41 L12.58,2.91 L13.8,1.7 C14.06,1.44 14.06,1 13.8,0.75 L13.25,0.2 C13.12,0.07 12.95,0 12.78,0 C12.61,0 12.43,0.07 12.3,0.2 Z M10.37,2.12 L5,7.5 L4.67748379,9.33646394 L6.5,9 L11.87,3.62 L10.37,2.12 Z" id="edit" fill-rule="nonzero"></path>\n\
							</g>\n\
							</svg>\n\
						</span>\n\
					</div>');
                    if ($('option:selected', '#project_label').val() == 0) {
                        $('#label-for-subtask' + data.success).hide();
                    }
                    if ($('#due_date').val() == '') {
                        $('#date-for-subtask' + data.success).html('');
                    } else {
                        var dateArray = ($('#due_date').val()).split("-");
                        var formattedDate = dateArray[1] + '-' + dateArray[0] + '-' + dateArray[2];
                        $('#date-for-subtask' + data.success).html(new moment(formattedDate).format("DD.MMM.YYYY"));
                        if ($('#due_date').val() === moment().format('DD-MM-YYYY')) {
                            $('#date-for-subtask' + data.success).removeClass('text-light');
                            $('#date-for-subtask' + data.success).addClass('text-danger');
                        } else {
                            $('#date-for-subtask' + data.success).removeClass('text-danger');
                            $('#date-for-subtask' + data.success).addClass('text-light');
                        }
                    }
                    $('#addSubTask').modal('toggle');
                    $('#task-count-' + taskGroupId).html(parseInt($('#task-count-' + taskGroupId).html()) + 1);
                    $('.task-list-assignees').show();
                    if (typeof $('#task-assigned-for-' + $('option:selected', '#task_assignee').val()).html() == 'undefined') {
                        $('.task-list-assignees').append('<p>' + $('option:selected', '#task_assignee').html() + ' (<span id="task-assigned-for-' + $('option:selected', '#task_assignee').val() + '">1</span>)</p>')
                    } else {
                        $('#task-assigned-for-' + $('option:selected', '#task_assignee').val()).html(parseInt($('#task-assigned-for-' + $('option:selected', '#task_assignee').val()).html()) + 1);
                    }
                    if ($('#priority').prop('checked')) {
                        $('#priority-icon-' + data.success).show();
                    } else {
                        $('#priority-icon-' + data.success).hide();
                    }
                }
            }
        });
    });

    //Open SubTask model on clicking link from Mail
    $('a[onclick="viewSubTask(' + $('#task-no').val() + ')"]').trigger('click');
    var newURL = location.href.split("?")[0];
    window.history.pushState('object', document.title, newURL);

    //Function for adding a new comment for a sub task
    $('.updateSubTaskComment_submit_btn').click(function (e) {
        e.preventDefault();
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        var taskGroupId = $('#sub_task_id').val();
        var formData = new FormData($('#add_subTaskComment_form')[0]);
        var attachments = $('#commentFiles').val();
        invalid = 0;
        $('input[name^=commentFiles]').each(function () {
            var filevalue = $(this).val();
            if (filevalue) {
                    var fileupload = $(this).prop('files')[0];
                if (fileupload) {
                    if (!validateFile($(this).attr('id'))) {
                        invalid = 1;
                    }
                }
                formData.append('file', fileupload);
            }
        });
        if (invalid == 1) {
            return false;
        }
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'addTaskcomment'+'/'+taskGroupId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            enctype:'multipart/form-data',
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.commenterror) {
                    var errormeggase = document.getElementById('commenterror');
                    errormeggase.innerText = 'Please fill this feild';
                }
                if (data.success) {
                    CKEDITOR.instances['comment'].setData('');
                    $('.document-upload.multiple-file-wrap').remove();
                    $('#comment-file-1').val();
                    $('.remove-first').hide();
                    if (data.comments) {
                        $('.task-comments').remove();
                        $('.viewComments').html('');
                        if (data.comments != null) {
                            $('.viewComments').show();
                            var comments = data.comments;
                            var row = 0;
                            $.each(comments , function (index, value) {
                                row++;
                                if (row == 1) {
                                    $('.viewComments').append("\
							<ul class='right-ul comments-list comments-list-dynamic' id='comment-" + row + "'>\n\
							</ul>\n\
							<span id='comment-row-" + row + "'></span>");
                                    $("#comment-" + row).append($("<li>").html(value['employee'].bold()+' '+'<span class="comment-time">'+ moment(value['created_at']).fromNow() + '</span>'));
                                    $("#comment-" + row).append($("<li>").html(value['description']));
                                } else {
                                    $('#comment-row-' + (row - 1)).append("\
							<ul class='right-ul comments-list comments-list-dynamic' id='comment-" + row + "'>\n\
							</ul>\n\
							<span id='comment-row-" + row + "'></span>");
                                    $("#comment-" + row).append($("<li>").html(value['employee'].bold()+' '+' <span class="comment-time">'+ moment(value['created_at']).fromNow()+ '</span>'));
                                    $("#comment-" + row).append($("<li>").html(value['description']));
                                }
                                if (value['get_task_files']) {
                                    $.each(value['get_task_files']['file'] , function (index, files) {
                                        var fileicon = baseUrl + 'storage/project-' + value['get_task_files']['task_id'] + '/' + files;
                                        $("#comment-" + row).append('<li>' + files +'<a class="fa fa-eye btn btn-xs btn-warning eye-view" title="View Attachments" target="_blank" href="' + fileicon +'"></a></li>')
                                    });
                                }
                            });
                        } else {
                            $('.viewComments').hide();
                        }
                    }
                }
            }
        });
    });

    $('#subTaskAssignee').on('change', function () {
        if ($("#viewSubTask").is(':visible')) {
            var id = $(this).val();
            var name = $('option:selected', this).attr('name');
            var subTaskId =  $('#sub_task_id').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + 'update/subtaskAssignee/' + id + '/' + subTaskId,
                type: 'POST',
                success:function (data) {
                    if (data.success) {
                        $('#assignee-for-subtask' + subTaskId).html(name);
                        if (typeof $('#task-assigned-for-' + id).html() == 'undefined') {
                            $('.task-list-assignees').append('<p>' + name + ' (<span id="task-assigned-for-' + id + '">1</span>)</p>');
                        } else {
                            $('#task-assigned-for-' + id).html(parseInt($('#task-assigned-for-' + id).html()) + 1);
                        }
                            $('#task-assigned-for-' + $('#subTaskAssigneeId').val()).html(parseInt($('#task-assigned-for-' + $('#subTaskAssigneeId').val()).html()) - 1);
                        $('#subTaskAssigneeId').val(id);
                    }
                }
            });
        }
    });

    $('#subTaskLabel').on('change', function () {
        if ($("#viewSubTask").is(':visible')) {
            var id = $(this).val();
            var name = $('option:selected', this).attr('name');
            var subTaskId =  $('#sub_task_id').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + 'update/subtaskLabel/' + id+ '/' + subTaskId,
                type: 'POST',
                success:function (data) {
                    if (data.success) {
                        $('#label-for-subtask' + subTaskId).show();
                        $('#label-for-subtask' + subTaskId).html(name);
                    }
                }
            });
        }
    });

    $('#subTask_date').on('change', function () {
        var date = $(this).val();
        var dateArray = date.split("-");
        var formattedDate = dateArray[1] + '-' + dateArray[0] + '-' + dateArray[2];
        var subTaskId =  $('#sub_task_id').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'update/subtaskDate/' + date + '/' + subTaskId,
            type: 'POST',
            success:function (data) {
                if (data.success) {
                    $('#date-for-subtask' + subTaskId).html(new moment(formattedDate).format("DD.MMM.YYYY"));
                    if (date === moment().format('DD-MM-YYYY')) {
                        $('#date-for-subtask' + subTaskId).removeClass('text-light');
                        $('#date-for-subtask' + subTaskId).addClass('text-danger');
                    } else {
                        $('#date-for-subtask' + subTaskId).removeClass('text-danger');
                        $('#date-for-subtask' + subTaskId).addClass('text-light');
                    }
                }
            }
        });
    });

    // update project
    $('.edit_projects_submit_btn').click(function (e) {
        e.preventDefault();
        var formData = $("#edit_project_details").serialize();
        $('.help-block').html('');
        $.ajax({
            url: baseUrl + 'updateProjectDetails',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#project-heading').html($('#project-name').val());
                    $('#editProject').modal('toggle');
                }
            }
        });
    });

    //Function for auto-saving subscribers of a Subtask
    $('body').on('click', '.task-subscribers', function (e) {
        var taskId = $('#sub_task_id').val();
        var formData = $("#update_subscribers_form").serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'updateSubscribersTask/'+ taskId,
            type: 'POST',
            data: formData
        });
    });

    //Function for reverting a complete subtask to incomplete
    $('body').on('click', '.incomplete-task', function (e) {
        var id = $(this).data('id');
        var assigneeId = $(this).attr('assignee-id');
        $.ajax({
            url: baseUrl + 'subtask/incomplete/'+ id,
            type: 'GET',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success:function (data) {
                window.location.reload();
            }
        });
    });

    //Function for marking a subtask as complete
    $('body').on('click', '.complete-task', function (e) {
        var id = $(this).data('id');
        var assigneeId = $(this).attr('assignee-id');
        $.ajax({
            url: baseUrl + 'subtask/complete/'+ id,
            type: 'GET',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success:function (data) {
                $('#completed-tasks').html(parseInt($('#completed-tasks').html()) + 1);
                $('#task-assigned-for-' + assigneeId).html(parseInt($('#task-assigned-for-' + assigneeId).html()) - 1);
                $('#sub-task-' + id).hide();
            }
        });
    });

    // add project files
    $('.add_projectFile_submit_btn').click(function (e) {
        $.LoadingOverlay("show");
        e.preventDefault();
        var formData =  new FormData($('#add_projectFile_form')[0]);
        var i = 0;invalid = 0;
        $('input[name^=attachments]').each(function () {
            var filevalue = $(this).val();
            if (filevalue) {
                var fileupload = $(this).prop('files')[0];
                if (fileupload) {
                    if (!validateFile($(this).attr('id'))) {
                        invalid = 1;
                    }
                }
                formData.append('file', fileupload);
            }
        });
        if (invalid) {
            return false;
        }
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + '/addprojectfile',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success:function (data) {
                $.LoadingOverlay("hide");
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $('#attachments').html(val[0]);
                    });
                }
                if (data.no_file_error) {
                    $('#attachments').html('Please upload a file');
                }
                if (data.success) {
                    window.location.hash = 'files';
                    window.location.reload();
                }
            }
        });
    });

    $('body').on('click', '.add-project-file-btn', function (e) {
        var projectId = $(this).data('id');
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "url": baseUrl + "getProjectFiles/"+projectId,
            type: 'GET',
            success:function (data) {
                $('#project-file-div-id').empty();
                if (data.length != 0) {
                    $.each(data , function (index, value) {
                        var fileicon = baseUrl + 'storage/projectfile-' + value.project_id +'/'+ value.attachments;
                        $('#project-file-div-id').append('<div class="col-sm-12 col-md-4" id="file-'+value.id+'"><div id="'+value.id+'"><div class="wrap-image-pro"><p class="wrap-p">'+value.created_at+'</p>\n\
						<p><a href="'+fileicon+'" target="_blank" class="img-v"><img src="'+fileicon+'"style="width:110px; height:110px;"></img></a></p>\n\
						<div class="img-t"><div class="row"><div class="col-sm-8"><p style="float:right;">By: '+value.created_by+'</p></div><div class="col-sm-4"><button onclick="deleteProjectFile(' + value.id + ')"  id="image-delete" class="btn-danger btn-delete btn-hide" style="padding: 2px 5px;float:left;border:0;" data-id="' + value.id + '">\n\
						<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
						</button></div></div></div></div></div></div>');
                    });
                } else {
                    $('#project-file-div-id').hide();
                }
            }
        });
    });

    $('body').on('click', '.add-access-btn', function (e) {
        var projectId = $(this).data('id');
        $('.help-block').html('');
        listProjectAccess(projectId);
    });

    // add new project access for a task group
    $('.add_projectAccess_submit_btn').click(function (e) {
        e.preventDefault();
        var formData = $("#add_projectAccess_form").serialize();
        $('.help-block').html('');
        $.ajax({
            url: baseUrl + 'addProjectAccess',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    listProjectAccess($('#project_id').val());
                    $('#addAccess').modal('toggle');
                }
            }
        });
    });

// update a project access for a task group
    $('body').on('click', '.update_project_access_submit_btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var formData = $("#project-access-" + id).serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'project-access/update',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + '-' + id + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    listProjectAccess($('#project_id').val());
                    swal({
                        title: Lang.get('ticketingtool.project_access_updated'),
                        button: Lang.get('ticketingtool.ok'),
                        type: "success",
                        confirmButtonColor: "#65b32e",
                        customClass: ".ok-message"
                    }, function () {
                        $('.sa-confirm-button-container').click(function () {
                            $('#collapse_' + id).collapse('toggle');
                        });
                    });
                }
            }
        });
    });



    if ($('.temporary_address').is(":checked")) {
        $('.temporary_address_form').show();
    } else {
        $('.temporary_address_form').hide();
    }
    $('.temporary_address').click(function () {
        if ($('.temporary_address').is(":checked")) {
            $('.temporary_address_form').show();
        } else {
            $('.temporary_address_form').hide();
        }
    });


    //Function for calling users table
    if ($('#users-table').is(':visible')) {
        UsersTable();
    }

    //Function for adding a user
    $(document).on('click', '.add_user_submit_button', function () {
        var formData = $("#add_user_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'addUser',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#users-table').dataTable().fnClearTable();
                    $('#users-table').dataTable().fnDestroy();
                    UsersTable();
                    $('#addUser').modal('toggle');
                }
            }
        });
    });

    //Function for adding a user
    $(document).on('click', '.update_user_submit_button', function () {
        var userId = $('#edit_user_id').val();
        var formData = $("#edit_user_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'updateUser/' + userId,
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    var pageInfo = $('#users-table').DataTable().page.info();
                    $('#users-table').dataTable().fnClearTable();
                    $('#users-table').dataTable().fnDestroy();
                    UsersTable();
                    setTimeout(function () {
                        $('#users-table').dataTable().fnPageChange(pageInfo.page);}, 500);
                    $('#editUser').modal('toggle');
                }
            }
        });
    });



    //Select2 drop down initializations
    if ($(".select2").length) {
        $(".select2").select2({
            response:true
        });
    };

    //Multi select drop down initializations
    if ($(".multi-select").length) {
        $(".multi-select").select2({
            allowClear: true ,
            response:true
        });
    };

    //function for reseting form after Model close
    $('.modal').not("#viewSubTask").on('hidden.bs.modal', function () {
        $('.help-block').html('');
        $(this).find('form').trigger('reset');
        $(".dropify-clear").trigger("click");
        $('.select2').val(0).trigger('change');
        $('.multi-select').not('#project_members').val(0).trigger('change');
    });

    //Function to reset subscribers list in subt task
    $('#viewSubTask').on('hidden.bs.modal', function () {
        $('.task-subscribers').removeAttr('checked');
    });

    //Function for calling function for project category table
    if ($('#project-category-table').is(':visible')) {
        projectCategoryTable();
    }

    //Function for adding a project category
    $(document).on('click', '.add_projectcategory_submit_btn', function () {
        var formData = $("#add_projectCategory_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'project-category/add',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#project-category-table').dataTable().fnClearTable();
                    $('#project-category-table').dataTable().fnDestroy();
                    projectCategoryTable();
                    $('#addProjectCategory').modal('toggle');
                }
            }
        });
    });

    //Function for editing a project category
    $('.edit_projectcategory_submit_btn').click(function () {
        var formData = $("#edit_projectcategory_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'project-category/update',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#project-category-table').dataTable().fnClearTable();
                    $('#project-category-table').dataTable().fnDestroy();
                    projectCategoryTable();
                    $('#editProjectCategory').modal('toggle');
                }
            }
        });
    });

    //Function for updating a subtask
    $('.update_taskList_submit_btn').click(function (e) {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        var formData = new FormData($('#edit_subTask_form')[0]);
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'editprojectsubtask',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#name-forsubtask-' + $('#edit_task_id').val()).html($('#edit_task_name').val());
                    $('#editSubTask').modal('toggle');
                    if ($('#priority-edit').prop('checked')) {
                        $('#priority-icon-' + $('#edit_task_id').val()).show();
                    } else {
                        $('#priority-icon-' + $('#edit_task_id').val()).hide();
                    }
                }
            }
        });
    });

    //Function for calling function for project label table
    if ($('#project-label-table').is(':visible')) {
        projectLabelTable();
    }

    // Function for adding a new project label
    $('.add_projectlabel_submit_btn').click(function (e) {
        e.preventDefault();
        var formData = $("#add_projectLabel_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'project-label/add',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#project-label-table').dataTable().fnClearTable();
                    $('#project-label-table').dataTable().fnDestroy();
                    projectLabelTable();
                    $('#addProjectLabel').modal('toggle');
                }
            }
        });
    });

    // Function for editing a new project label
    $('.edit_project_label_submit_btn').click(function (e) {
        e.preventDefault();
        id = $(this).data('id');
        var formData = $("#edit_project_label_form").serialize();
        $('.help-block').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + 'project-label/update',
            type: 'POST',
            data: formData,
            success:function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, val) {
                        $("." + key + "-error").text(val[0]);
                    });
                }
                if (data.success) {
                    $('#project-label-table').dataTable().fnClearTable();
                    $('#project-label-table').dataTable().fnDestroy();
                    projectLabelTable();
                    $('#editProjectLabel').modal('toggle');
                }
            }
        });
    });

    // add project task group
        $('.edit_projectTaskList_submit_btn').click(function (e) {
            e.preventDefault();
            var formData = $("#edit_projectTaskList_form").serialize();
            $('.help-block').html('');
            $.ajax({
                url: baseUrl + 'editProjectTaskList',
                type: 'POST',
                data: formData,
                success:function (data) {
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $("." + key + "-error").text(val[0]);
                        });
                    }
                    if (data.success) {
                        $('#task-group-' + $('#task_group_id_edit').val()).html($('#task-list-edit').val());
                        $('#editTaskGroup').modal('toggle');
                    }
                }
            });
        });

    //Function for calling function for task label table
        if ($('#task-label-table').is(':visible')) {
            taskLabelTable();
        }

    //Function for adding a new project label
        $('.add_task_label_submit_btn').click(function (e) {
            e.preventDefault();
            var formData = $("#add_task_label_form").serialize();
            $('.help-block').html('');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: baseUrl + 'task-label/add',
                type: 'POST',
                data: formData,
                success:function (data) {
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $("." + key + "-error").text(val[0]);
                        });
                    }
                    if (data.success) {
                        $('#task-label-table').dataTable().fnClearTable();
                        $('#task-label-table').dataTable().fnDestroy();
                        taskLabelTable();
                        $('#addTaskLabel').modal('toggle');
                    }
                }
            });
        });

    // Function for editing a new project label
        $('.edit_task_label_submit_btn').click(function (e) {
            e.preventDefault();
            var formData = $("#edit_task_label_form").serialize();
            $('.help-block').html('');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + 'task-label/update',
                type: 'POST',
                data: formData,
                success:function (data) {
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $("." + key + "-error").text(val[0]);
                        });
                    }
                    if (data.success) {
                        $('#task-label-table').dataTable().fnClearTable();
                        $('#task-label-table').dataTable().fnDestroy();
                        taskLabelTable();
                        $('#editTaskLabel').modal('toggle');
                    }
                }
            });
        });

    // add project category
        $('.add_project_submit_btn').click(function (e) {
            e.preventDefault();
            $(this).attr('disabled', 'disabled');
            var formData = $("#add_project_form").serialize();
            $('.help-block').html('');
            if ($('#project_label').val() > 0) {
                label = $("#project_label option:selected").attr("name");
            } else {
                label = '';
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + 'project/add',
                type: 'POST',
                async: false,
                data: formData,
                success:function (data) {
                    $(this).removeAttr('disabled');
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $("." + key + "-error").text(val[0]);
                        });
                    }
                    if (data.success) {
                        var description = $('#project_decription').val();
                        var descriptionDisplay = (description.length > 150) ? description.substr(0, 150) + '...' : description
                        $('.project-row').append(
                            '<div class="col-xs-12 col-sm-4 col-lg-3 project-view-wrap" data-id="' + data.success  + '" id="project-"' + data.success +'">\n\
					<div class="project-card">\n\
						<a href="/projects/view/'+ data.success +'" class="project-link"></a>\n\
					  	<div class="project-grid-view" style="height: 250px;">\n\
								<div class="row">\n\
									<div class="col-xs-10">\n\
										<h3>' + $('#project_name').val() + '</h3>\n\
									</div>\n\
									<div class="col-xs-2">\n\
										<div class="dropdown pull-right">\n\
                                            <a class="edit-task_group dropdown-toggle fa fa-ellipsis-v" data-toggle="dropdown"></a>\n\
                                            <ul class="dropdown-menu">\n\
                                            <li><a onclick="completeProject('+ data.success +')">Complete</a></li>\n\
                                            <li><a onclick="deleteProject('+ data.success +')">Delete</a></li>\n\
                                          </ul>\n\
                                        </div>\n\
									</div>\n\
								</div>\n\
								<div class="pro-view-p">\n\
								   <p id="client-'+ data.success +'">for <span>' + $('#client_company').val() + '</span></p>\n\
								   <p>' + descriptionDisplay + '</p>\n\
	                            </div>\n\
	  	                        <div class="btn-project" id="project-label-div-'+ data.success +'">\n\
							  	    <p>' + label + '</p>\n\
								</div>\n\
						    </div>\n\
						</div>\n\
				    </div>'
                        );
                        if ($('#client_company').val() == '') {
                            $('#client-' + data.success).hide();
                        }
                        if (label == '') {
                            $('#project-label-div-' + data.success).hide();
                        }
                        $('#addProject').modal('toggle');
                    }
                }
            });
        });

    //Function for viewing comleted projects
        $('body').on('click', '#completed-projects-btn', function (e) {
            $('#comleted-projects-table').dataTable().fnClearTable();
            $('#comleted-projects-table').dataTable().fnDestroy();
            $("#comleted-projects-table").DataTable({
                "aProcessing": true,
                "deferRender": true,
                "bStateSave": true,
                "ordering": true,
                "searching": false,
                "bPaginate": true,
                "bLengthChange": true,
                "bDestroy" : true,
                "bInfo": true,
                "pageLength": 10,
                responsive: true,
                "ajax": {
                    "url": "completedprojects/get",
                    "dataSrc": ""
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', 'role-' + aData.id);
                },
                "columns": [
                    {
                        "width": "10%",
                        "render": function (data, type, row, meta) {
                                return meta.row + 1;
                        }
                },
                { "data": "name" },
                { "data": "client_company" },
                {
                    "render": function (data, type, content, meta) {
                        return moment(content.updated_at).format("DD.MMM.YYYY");
                    }
                },
                    {
                        sortable: false,
                        "render": function (data, type, content, meta) {
                            return '<a onclick=reopenProject(' + content.id + ')><button class="btn btn-success">reopen</button></a>';
                        }
                }
                ]
            });
            $('#viewCompletedProjects').modal('toggle');
        });

    // add project members
        $('.add_projectMembers_submit_btn').click(function (e) {
            e.preventDefault();
            var formData = $("#add_project_members").serialize();
            $('.help-block').html('');
            $.ajax({
                url: baseUrl + 'addProjectMembers',
                type: 'POST',
                data: formData,
                success:function (data) {
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $("." + key + "-error").text(val[0]);
                        });
                    }
                    if (data.success) {
                        window.location.hash = 'task';
                        window.location.reload();
                    }
                }
            });
        });

    //Function for filling project edit form
        $('.edit-project-btn').click(function (e) {
            $.ajax({
                url: baseUrl + 'project/edit/' + $(this).data('id'),
                type: 'GET',
                success:function (data) {
                    $("#project-manager").val(data.project_manager).trigger('change');
                    if (data.label != null) {
                        $("#project-label").val(data.label).trigger('change');
                    }
                    if (data.category != null) {
                        $("#project-category").val(data.category).trigger('change');
                    }
                    $('#project-name').val(data.project_name);
                    $('#project-description').html(data.description);
                    $('#client-company').val(data.client_company);
                    $('#additional-info').html(data.additional_info);
                    $('#editProject').modal('toggle');
                }
            });
        });

    // add project task group
        $('.add_projectTaskList_submit_btn').click(function (e) {
            e.preventDefault();
            var formData = $("#add_projectTaskList_form").serialize();
            $('.help-block').html('');
            $.ajax({
                url: baseUrl + 'addProjectTaskList',
                type: 'POST',
                data: formData,
                success:function (data) {
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $("." + key + "-error").text(val[0]);
                        });
                    }
                    if (data.success) {
                        $('.task-list-heading').show();
                        $('.task-list-div').append('<div class="task-outline" id="task-card-' + data.success + '">\n\
							<div class="row">\n\
							  <div class="col-xs-12 col-md-6">\n\
								<h4 id="task-group-' + data.success + '">'+ $('#taskList').val() + '</h4>\n\
							  </div>\n\
								<div class="col-xs-12 col-md-6">\n\
									  	<div class="dropdown pull-right">\n\
											<a class="edit-task_group dropdown-toggle fa fa-ellipsis-v" data-toggle="dropdown">\n\
											</a>\n\
											<ul class="dropdown-menu">\n\
												<li><a onclick="editTaskGroup(' + data.success + ')">Edit</a></li>\n\
												<li><a onclick="completeTaskGroup(' + data.success + ')">Complete</a></li>\n\
												<li><a onclick="deleteTaskGroup(' + data.success + ')">Delete</a></li>\n\
											</ul>\n\
									  	</div>\n\
								</div>\n\
						  	<div class="task-outline-' + data.success + '">\n\
						  </div>\n\
						  <div class="col-xs-12">\n\
							<div class="pull-right">\n\
							  <button class="btn btn-primary btn-anim add-subtask-btn" data-toggle="modal" data-id="' + data.success + '"><i class="fa fa-plus"></i><span class="btn-text ie-jump">Add a task to this list</span></button>\n\
							</div>\n\
						  </div>\n\
						</div>\n\
					  </div>');
                        $('#addTaskGroup').modal('hide');
                        $('.task-list-heading').append('<p>' + $('#taskList').val() + ' (<span id="task-count-' + data.success  + '">0</span>)</p>')
                    }
                }
            });
        });

});


//Function for Project category data table
function UsersTable()
{
    // datatable for users list
    $("#users-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        responsive: true,
        "ajax": {
            "url": baseUrl + "getUsers",
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'users-' + aData.id);
        },
        "columns": [
            {
                "width": "10%",
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
        },
                {
                    "width": "25%",
                    "render": function (data, type, content, meta) {
                        return content.get_user_name.user;
                    }
        },
            {
                sortable: true,
                "width": "10%",
                "render": function (data, type, content, meta) {
                    if (content.role_id == 1) {
                        return Lang.get('ticketingtool.admin');
                    } else if (content.role_id == 2) {
                        return Lang.get('ticketingtool.employee');
                    } else {
                        return Lang.get('ticketingtool.client');
                    }
                }
        },{
            sortable:false,
            "width": "15%",
            "render": function (data, type, content, meta) {
                return '<a class="btn btn-xs btn-viewgradient btn-edit btn-common"  onclick="viewUser('+ content.id +')">\n\
						    <i class="fa fa-eye" title="' + Lang.get('ticketingtool.btn_view') + '"></i>\n\
                        </a>\n\
					    <a class="btn btn-xs  btn-editgradient btn-edit btn-common" onclick="editUser('+ content.id +')">\n\
							<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i>\n\
                        </a>\n\
					    <button onclick="deleteUser(' + content.id + ')"  id="image-delete" class="btn btn-xs  btn-deletegradient btn-delete btn-common" data-id="' + content.id + '">\n\
						    <i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
						</button>';
            }
        }
        ]
    });
}

function viewUser(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'viewUser/' + id,
        type: 'POST',
        success:function (data) {
            var dob = data.date_of_birth ? new moment(data.date_of_birth).format("DD.MM.YYYY") : 'Nil';
                $('#view_name').html(data.name);
                $('#view_role').html(data.role);
                $('#view_email').html(data.email);
                $('#view_dob').html(dob);
                $('#view_gender').html(data.gender);
                $('#view_address').html(data.address);
                $('#view_secondary_address').html(data.secondary_address);
                $('#view_primary_phone').html(data.primary_phone);
                $('#view_secondary_phone').html(data.secondary_phone);
            $('#viewUsers').modal('show');
        }
    });
}

function editUser(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'editUser/' + id,
        type: 'POST',
        success:function (data) {
            $('.temporary_address_form').hide();
            var dob = data.get_user_info.date_of_birth ? new moment(data.get_user_info.date_of_birth).format("DD-MM-YYYY") : '';
            $('#edit_user_id').val(data.id);
            $('#edit_first_name').val(data.get_user_info.first_name);
            $('#edit_middle_name').val(data.get_user_info.middle_name);
            $('#edit_last_name').val(data.get_user_info.last_name);
            $('#edit_email').val(data.email);
            $('#edit_dob').val(dob);
            $("#role_id").val(data.role_id).trigger('change');
            if (data.get_user_info.gender == 0) {
                $('#male_radio').attr('checked',true);
            } else {
                $('#feamle_radio').attr('checked',true);
            }
            $('#edit_address').val(data.get_user_info.address);
            $('#edit_primary_phone').val(data.get_user_info.contact_no);
            $('#edit_secondary_phone').val(data.get_user_info.secondary_contact_no);
            if (data.get_user_info.secondary_address) {
                $(".edit_temporary_address").prop("checked", true);
                $('#edit_secondary_address').val(data.get_user_info.secondary_address);
                $('.temporary_address_form').show();
            }
            $('.edit_temporary_address').click(function () {
                if ($('.edit_temporary_address').is(":checked")) {
                    $('.temporary_address_form').show();
                } else {
                    $('.temporary_address_form').hide();
                }
            });
            $('#editUser').modal('show');
        }
    });
}

// Function for delete user
function deleteUser(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.user_deleteconfirm'),
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
                url: baseUrl + "deleteUser/" + id,
                method: "GET",
                data: {
                    "id": id,
                    "_method": 'DELETE'
                },
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.user_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#users-' + id).remove();
                    });
                }
            }
        );
    });
}

// Function for sub task
function viewSubTask(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'view/subtask/' + id,
        type: 'GET',
        success:function (data) {
            $('#first-task-file').hide();
            $('#subtaskHeading').html(data.task_name);
            $('#taskGroup').html(data.task_group_name);
            $('#sub_task_id').val(data.id);
            $('#task-estimate').val(data.estimate);
            $('#assignee_id').val(data.assignee_id);
            $('#subTaskLabel').val(data.label);
            $('#subTaskLabel_id').val(data.label_id);
            $('#name').val(data.name);
            $('#subTask_date').val(data.due_date);
            $('#project_status').val(data.project_status_id);
            if (data.priority == 1) {
                $('.priority').show();
            } else {
                $('.priority').hide();
            }
            if (data.created_by !=null) {
                $('.task-view').show();
                $('#view_created_by').html('Created by ' + data.created_by);
                $('#view_created_at_label').html('on ' + data.created_at);
            } else {
                $('#view_created_by').html('');
                $('#view_created_at_label').html('');
                $('.task-view').hide();
            }
            $('[data-id=project_status] span.filter-option').text(data.project_status);
            $('.task-files-div').html('');
            if (data.description) {
                $('.task-files-div').prepend('\n\
				<p readonly id="subtask-decription-view">' + data.description + '</p>');
            }
            if (data.project_file != 'null') {
                var files = jQuery.parseJSON(data.project_file);
            }
            var filearray = [];
            if (files) {
                $.each(files , function (index, value) {
                    var fileicon = baseUrl + 'storage/project-' + data.task_group_id + '/' + value;
                    $('.task-files-div').append('<ul id ="file-'+index+'">\n\
					<li>\n\
					<ul class="ul-lead-view">\n\
					<li><input type="text" value ="' + value + '" data=index class="form-control remove_input" readonly="true"></li>\n\
					<li class="view-btn-comment"> <a class="fa fa-eye btn btn-xs btn-warning remove_a view-a" title="View Attachments" "value" = "'+index+'" target="_blank" href="' + fileicon +'"></a></li>\n\
					</ul>\n\
					</li>\n\
					</ul>');
                });
            }
            if (data.subTaskLabel_id == 0) {
                $("#subTaskLabel").val("").trigger('change');
            } else {
                $("#subTaskLabel").val(data.label_id).trigger('change');
            }
            if (data.assignee_id == 0) {
                $("#subTaskAssignee").val("").trigger('change');
            } else {
                $("#subTaskAssignee").val(data.assignee_id).trigger('change');
            }
            $("#subTaskAssigneeId").val(data.assignee_id);
            if (data.subscribers_ids != 0) {
                var selectedValues = data.subscribers_ids;
                $("input[name='task-subscribers[]']").each(function () {
                    if (selectedValues.indexOf($(this).val()) > -1) {
                        $(this).prop('checked', true);
                    }
                });
            }
            $('.task-comments').remove();
            $('.viewComments').html('');
            if (data.comments != null) {
                $('.viewComments').show();
                var comments = data.comments;
                var row = 0;
                $.each(comments , function (index, value) {
                    row++;
                    if (row == 1) {
                        $('.viewComments').append("\
						<ul class='right-ul comments-list comments-list-dynamic' id='comment-" + row + "'>\n\
						</ul>\n\
						<span id='comment-row-" + row + "'></span>");
                        $("#comment-" + row).append($("<li>").html(value['employee'].bold()+' '+'<span class="comment-time">'+ moment(value['updated_at']).fromNow() + '</span>'));
                        $("#comment-" + row).append($("<li>").html(value['description']));
                    } else {
                        $('#comment-row-' + (row - 1)).append("\
						<ul class='right-ul comments-list comments-list-dynamic' id='comment-" + row + "'>\n\
						</ul>\n\
						<span id='comment-row-" + row + "'></span>");
                        $("#comment-" + row).append($("<li>").html(value['employee'].bold()+' '+'<span class="comment-time">'+ moment(value['updated_at']).fromNow() + '</span>'));
                        $("#comment-" + row).append($("<li>").html(value['description']));
                    }
                    if (value['get_task_files']) {
                        $.each(value['get_task_files']['file'] , function (index, files) {
                        // var fileicon = baseUrl + 'storage/project-' + value['get_task_files']['comment_id'] + '/' + files;
                            var fileicon = baseUrl + 'storage/project-' + value['get_task_files']['task_id'] + '/' + files;
                        //$("#comment-" + row).append($("<li>").text(files));
                            $("#comment-" + row).append('<li>' + files +'<a class="fa fa-eye btn btn-xs btn-primary eye-view" title="View Attachments" target="_blank" href="' + fileicon +'"></a></li>')
                        //$("#comment-" + row).append('<ul class="ul-lead-view"><li><a class="fa fa-eye btn btn-xs btn-warning eye-view" title="View Attachments" target="_blank" href="' + fileicon +'"></a></li></ul>');
                        });
                    }
                });
            } else {
                $('.viewComments').hide();
            }
            $('#viewSubTask').modal('show');
        }
    });
    $.each(files , function (index, value) {
        var fileicon = baseUrl + 'storage/leads/' + value;
        $('#view_leads_model_file').append('<ul class="ul-lead-view"><li><input type="text" value ="' + value + '" data=index class="form-control remove_input_view_leads"></li>\n\
		<li class="view-btn-comment"><a class="fa fa-eye btn btn-xs btn-warning remove_a_view_leads eye-view" title="View Attachments" target="_blank" href="' + fileicon +'"></a></li></ul>');
    });
}

function viewCompletedTask(projectId)
{
    $.ajax(
        {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + "completed-tasks/" + projectId,
            method: "GET",
            success: function (data) {
                $('#completed-task-div').html('');
                if (data == '') {
                    $('#completed-task-div').append('<label class="help-block" style="margin-left: 15px;">No tasks</label>');
                } else {
                    $.each(data , function (index, value) {
                        $('#completed-task-div').append('\n\
							<div class="col-xs-12">\n\
									<div class="pretty p-svg p-round p-pulse">\n\
									<input type="checkbox" class="incomplete-task" data-id="' + value.id + '" checked/>\n\
										<div class="state complete-state">\n\
											<svg class="svg svg-icon" viewBox="0 0 20 20">\n\
												<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: #65b32e;fill:#65b32e;"></path>\n\
											</svg>\n\
												<label class="subtask-radio"><p>' + value.task_name + '</p></label>\n\
										</div>\n\
									</div>\n\
							</div>');
                    });
                }
                $('#viewCompletedTask').modal('toggle');
            }
            }
    );
}

function editTaskGroup(id)
{
    $('#task-list-edit').val($('#task-group-' + id).html());
    $('#task_group_id_edit').val(id)
    $('#editTaskGroup').modal('toggle');
}

// Function for completing a task group
function completeTaskGroup(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.complete_taskgroup_confirm'),
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
                url: baseUrl + "taskgroup/complete/" + id,
                method: "GEt",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.done'),
                        text: Lang.get('ticketingtool.subtsks_moved_to_completed_list'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#task-card-' + id).remove();
                                                $('#completed-tasks').html(parseInt($('#completed-tasks').html()) + parseInt(data));
                    });
                }
            }
        );
    });
}

//Function for sub task
function editSubTask(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'edit/subtask/' + id,
        type: 'GET',
        success:function (data) {
            $('#edit_task_id').val(data.id);
            $('#edit_taskgroup_id').val(data.task_group_id);
            $('#edit_task_name').val(data.task_name);
            $('#task_estimate').val(data.estimate);
            if (data.priority == 1) {
                $('#priority-edit').prop('checked', true);
            }
            CKEDITOR.instances['task-description-edit'].setData(data.description);
            $('#task-description-edit').val(data.description);
            $('#editSubTask').modal('show');
        }
    });
}

// Function for delete project file
function deleteProjectFile(id)
{
    $.ajax(
        {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: baseUrl + "deleteprojectfile/" + id,
            method: "DELETE",
            data: {
                "id": id,
                "_method": 'DELETE'
            },
            success: function (data) {
                    $('#file-' + id).slideUp();
            }
        }
    );
}

function listProjectAccess(projectId)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        "url": baseUrl + "getProjectAccess/"+projectId,
        type: 'GET',
        success:function (data) {
            $('.project-access-div').html('');
            $.each(data , function (index, value) {
                $('.project-access-div').append('\n\
				<div class="panel panel-default">\n\
						<div class="panel-heading" role="tab" id="heading_10">\n\
								<a role="button" data-toggle="collapse" data-parent="#accordion_2" id="expand-access-' + value.id + '" href="#collapse_' + value.id + '" aria-expanded="true" ><div class="icon-ac-wrap pr-20"><span class="plus-ac"><i class="ti-plus"></i></span><span class="minus-ac"><i class="ti-minus"></i></span></div>' + value.task_group + '</a>\n\
						</div>\n\
						<div id="collapse_' + value.id + '" class="panel-collapse test-col collapse accordian-' + value.id + '" role="tabpanel">\n\
								<div class="panel-body pa-15">\n\
										<form id="project-access-' + value.id +'">\n\
												<input type="hidden" name="id" value="' + value.id + '">\n\
												<div class="form-group">\n\
														<div class="row">\n\
																<div class="col-sm-4">\n\
																	<label class="control-label">' + Lang.get('ticketingtool.task_group') + '<sup class="mandatory">*</sup></label>\n\
																	<input type="text" class="form-control" name="task_group" value="' + value.task_group + '">\n\
																	<div class="help-block task_group-' + value.id + '-error"></div>\n\
																</div>\n\
																<div class="col-sm-8">\n\
																		<label class="control-label">' + Lang.get('ticketingtool.git_url') + '</label>\n\
																		<input type="text" class="form-control" name="git_url" value="' + value.git_url + '">\n\
																		<div class="help-block"></div>\n\
																</div>\n\
														</div>\n\
												</div>\n\
												<div class="form-group">\n\
													<div class="row">\n\
														<div class="col-sm-6">\n\
															<label class="control-label">' + Lang.get('ticketingtool.server_details') + '</label>\n\
															<textarea rows="5" class="form-control form-control-static" name="server" maxlength="800">' + value.server + '</textarea>\n\
														</div>\n\
														<div class="col-sm-6">\n\
															<label class="control-label">' + Lang.get('ticketingtool.domains') + '</label>\n\
															<textarea rows="5" class="form-control form-control-static" name="domains" maxlength="800">' + value.domains + '</textarea>\n\
														</div>\n\
													</div>\n\
												</div>\n\
												<div class="form-group">\n\
													<div class="row">\n\
														<div class="col-sm-6">\n\
															<label class="control-label">' + Lang.get('ticketingtool.database') + '</label>\n\
															<textarea rows="5" class="form-control form-control-static" name="database" maxlength="800">' + value.database + '</textarea>\n\
														</div>\n\
														<div class="col-sm-6">\n\
															<label class="control-label">' + Lang.get('ticketingtool.backend') + '</label>\n\
															<textarea rows="5" class="form-control form-control-static" name="backend" maxlength="800">' + value.backend + '</textarea>\n\
														</div>\n\
													</div>\n\
												</div>\n\
												<div class="form-group">\n\
													<div class="row">\n\
														<div class="col-sm-6">\n\
															<label class="control-label">' + Lang.get('ticketingtool.additional_info') + '</label>\n\
															<textarea rows="5" class="form-control form-control-static" name="additional_info" maxlength="800">' + value.additional_info + '</textarea>\n\
														</div>\n\
													</div>\n\
												</div>\n\
												<div class="panel-footer footer-trans mb-10">\n\
														<div class="form-actions text-right button-list">\n\
																<button type="button" class="btn btn-success update_project_access_submit_btn" data-dismiss="modal" data-id="' + value.id +'">' + Lang.get('ticketingtool.btn_save') + '</button>\n\
																<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteProjectAccess(' + value.id + ')">' + Lang.get('ticketingtool.btn_delete') + '</button>\n\
														</div>\n\
												</div>\n\
										</form>\n\
								</div>\n\
						</div>\n\
				</div>');
            });
        }
    });
}





//Function for Project category data table
function projectCategoryTable()
{
    // datatable for project category
    $("#project-category-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        "bDestroy": true,
        responsive: true,
        "ajax": {
            "url": "project-category/get",
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'project-category-' + aData.id);
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
        },{
            sortable:false,
            "width": "15%",
            "render": function (data, type, content, meta) {
                return '<a class="btn btn-xs btn-edit btn-common btn-editgradient" onclick="editProjectCategory('+ content.id +')">\n\
							<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i>\n\
						</a>\n\
						<button onclick="deleteProjectCategory(' + content.id + ')"  id="image-delete" class="btn btn-xs btn-delete btn-common btn-deletegradient" data-id="' + content.id + '">\n\
							<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
						</button>';
            }
        }
        ]
    });
}

//Function for filling edit project category form
function editProjectCategory(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'project-category/edit/' + id,
        type: 'GET',
        success:function (data) {
            $('.edit_projectLabel_submit_btn').attr('data-id', data.id);
            $('#project_category').val(data.name);
            $('#projectcategory_id').attr('value', data.id);
            if (data.is_active == '1' && !$('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            if (data.is_active == '0' && $('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            $('#editProjectCategory').modal('show');
        }
    });
}

// Function for delete project list
function deleteProjectCategory(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.projectcategory_deleteconfirm'),
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
                url: baseUrl + "project-category/delete/" + id,
                method: "GET",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.project_category_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#project-category-' + id).remove();
                    });
                }
            }
        );
    });
}

//Function for Project label data table
function projectLabelTable()
{
    $("#project-label-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        responsive: true,
        "ajax": {
            "url": "project-label/get",
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'project-label-' + aData.id);
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
        },{
            sortable:false,
            "width": "15%",
            "render": function (data, type, content, meta) {
                return '<a class="btn btn-xs btn-edit btn-common btn-editgradient" onclick="editProjectLabel('+ content.id +')">\n\
						   	<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i>\n\
						</a>\n\
					   	<button onclick="deleteProjectLabel(' + content.id + ')"  id="image-delete" class="btn btn-xs btn-delete btn-common btn-deletegradient" data-id="' + content.id + '">\n\
						   	<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
					   	</button>';
            }
        }
        ]
    });
}

//Function for filling edit project label form
function editProjectLabel(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'project-label/edit/' + id,
        type: 'GET',
        success:function (data) {
            $('#project_label').val(data.name);
            $('#project-label-id').attr('value', data.id);
            if (data.is_active == 1 && !$('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            if (data.is_active == 0 && $('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            $('#editProjectLabel').modal('show');
        }
    });
}

// Function for deleting a project label
function deleteProjectLabel(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.project_label_delete_confirm'),
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
                url: baseUrl + "project-label/delete/" + id,
                method: "GET",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.project_label_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#project-label-table').dataTable().fnDeleteRow('table#project-label-table tr#project-label-' + id);
                    });
                }
            }
        );
    });
}

//Function for Project label data table
function taskLabelTable()
{
    $("#task-label-table").DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "deferRender": true,
        responsive: true,
        "ajax": {
            "url": "task-label/get",
            "dataSrc": ""
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'task-label-' + aData.id);
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
        },{
            sortable:false,
            "width": "15%",
            "render": function (data, type, content, meta) {
                return '<a class="btn btn-xs btn-edit btn-common btn-editgradient" onclick="editTaskLabel('+ content.id +')">\n\
						   	<i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i>\n\
						</a>\n\
					   	<button onclick="deleteTaskLabel(' + content.id + ')"  id="image-delete" class="btn btn-xs btn-delete btn-common btn-deletegradient" data-id="' + content.id + '">\n\
						   	<i class="fa fa-trash" title="' + Lang.get('ticketingtool.label_delete') + '"></i>\n\
					   	</button>';
            }
        }
        ]
    });
}

//Function for filling edit project label form
function editTaskLabel(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + 'task-label/edit/' + id,
        type: 'GET',
        success:function (data) {
            $('#task_label').val(data.name);
            $('#task-label-id').attr('value', data.id);
            if (data.is_active == 1 && !$('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            if (data.is_active == 0 && $('#is_active').prop('checked')) {
                $('.edit .switchery').trigger('click');
            }
            $('#editTaskLabel').modal('show');
        }
    });
}

// Function for deleting a project label
function deleteTaskLabel(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.task_label_delete_confirm'),
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
                url: baseUrl + "task-label/delete/" + id,
                method: "GET",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.task_label_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#task-label-' + id).remove();
                    });
                }
            }
        );
    });
}

// Function for completing a project
function completeProject(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.complete_project_confirm'),
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
                url: baseUrl + "project/complete/" + id,
                method: "GEt",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.done'),
                        text: Lang.get('ticketingtool.project_moved_to_completed_list'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        loadProjectList();
                    });
                }
            }
        );
    });
}

function loadProjectList()
{
    $.ajax({
        url: baseUrl + 'getprojects',
        type: 'GET',
        success:function (data) {
            if (data.projects) {
                $('.project-row').html('');
                $.each(data.projects, function (key, project) {
                    var projDescription = (project.description == null) ? '' : project.description;
                    var descriptionDisplay = (projDescription.length > 150) ? projDescription.substr(0, 150) + '...' : projDescription;
                    $('.project-row').append(
                        '<div class="col-xs-12 col-sm-4 col-lg-3 project-view-wrap" id="project-' + project.id +'">\n\
						<div class="project-card">\n\
							<a href="/projects/view/'+ project.id +'" class="project-link"></a>\n\
							<div class="project-grid-view" style="height: 250px;">\n\
								<div class="row">\n\
									<div class="col-xs-10">\n\
										<h3>' + project.name + '</h3>\n\
									</div>\n\
									<div class="col-xs-2">\n\
										<div class="dropdown pull-right">\n\
                                            <a class="edit-task_group dropdown-toggle fa fa-ellipsis-v" data-toggle="dropdown"></a>\n\
                                            <ul class="dropdown-menu">\n\
                                                <li><a onclick="completeProject('+ project.id +')">Complete</a></li>\n\
                                                <li><a onclick="deleteProject('+ project.id +')">Delete</a></li>\n\
                                            </ul>\n\
                                        </div>\n\
									</div>\n\
								</div>\n\
								<div class="pro-view-p">\n\
									<p id="client-'+ project.id +'">for <span>' + project.client_company + '</span></p>\n\
									<p>' + descriptionDisplay + '</p>\n\
								</div>\n\
								<div class="btn-project" id="project-label-div-'+ project.id +'">\n\
									<p></p>\n\
								</div>\n\
							</div>\n\
						</div>\n\
					</div>'
                    );
                    if (project.client_company == null) {
                        $('#client-' + project.id).hide();
                    }
                    if (project.get_project_label == null) {
                        $('#project-label-div-' + project.id).hide();
                    } else {
                        $('#project-label-div-' + project.id + ' p:first').html(project.get_project_label.name)
                    }
                    if (project.is_favourite == 1) {
                        $('[data-id="'+ project.id +'"]').prop('checked', true);
                    }
                });
            }
        }
    });
}

// Function marking a project as incompleting
function reopenProject(id)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + "project/reopen/" + id,
        method: "GEt",
        success: function (data) {
            $('#viewCompletedProjects').modal('toggle');
            loadProjectList();
        }
    });
}

// Function for deleting a project
function deleteProject(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.project_delete_confirm'),
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
                url: baseUrl + "project/delete/" + id,
                method: "GET",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.project_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        loadProjectList();
                    });
                }
            }
        );
    });
}

//function for validating File and Image upload.
function validateFile(id)
{
    $('.' + id + '-error').html('');
    if (id == 'punchrecord') {
        var allowedExtension = ['xlsx'];
    } else {
        var allowedExtension = ['jpeg', 'jpg', 'png', 'svg', 'pdf', 'docx', 'doc', 'txt', 'xlsx', 'zip','mp4','mov','mpeg','avi','mkv','ch'];
    }
    var fileExtension = $('#' + id).val().split('.').pop().toLowerCase();
    var imageId = $('#' + id)[0];
    var isValidFile = false;
    var validSize = 1;
    var validFormat = 0;
    for (var index in allowedExtension) {
        if (fileExtension === allowedExtension[index]) {
            validFormat = 1;
        }
        if (fileExtension === 'pdf') {
            validFormat = 2;
            break;
        }
        if (fileExtension === 'xls') {
            validFormat = 3;
            break;
        }
    }
    if (!fileExtension || validFormat == 0) {
        $('.' + id + '-error').html(Lang.get('ticketingtool.valid_file'));
        return false;
    }
    if (validFormat == 3) {
        $('.' + id + '-error').html(Lang.get('ticketingtool.valid_file_xlsx'));
        return false;
    }
    var fileSize = (imageId.files[0].size / 1024);
    if (fileSize / 1024 > 1) {
        if (((fileSize / 1024) / 1024) > 1) {
            $('.' + id + '-error').html(Lang.get('ticketingtool.image_size'));
            return false;
        } else {
            fileSize = (Math.round((fileSize / 1024) * 100) / 100);
            if (fileSize > 8) {
                $('.' + id + '-error').html(Lang.get('ticketingtool.image_size'));

                return false;
            }
        }
    }

    return validFormat;
}

// Function for deleting project access
function deleteProjectAccess(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.project_access_deleteconfirm'),
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
                url: baseUrl + "project-access/delete/" + id,
                method: "DELETE",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.project_access_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        listProjectAccess($('#project_id').val());
                    });
                }
            }
        );
    });
}

// Function for deleting task Group
function deleteTaskGroup(id)
{
    swal({
        title: Lang.get('ticketingtool.are_you_sure'),
        text: Lang.get('ticketingtool.task_group_deleteconfirm'),
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
                url: baseUrl + "taskgroup/delete/" + id,
                method: "DELETE",
                success: function (data) {
                    swal({
                        title: Lang.get('ticketingtool.message_deleted'),
                        text: Lang.get('ticketingtool.task_group_deleted_successfully'),
                        type: "success",
                        confirmButtonColor: "#00cc00",
                        confirmButtonText: Lang.get('ticketingtool.confirm_button'),
                    });
                    $('.sa-confirm-button-container').click(function () {
                        $('#task-card-' + id).remove();
                    });
                }
            }
        );
    });
}
