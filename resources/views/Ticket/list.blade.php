@extends('layout.app',['title' => 'ticket_list'])
@section('js_file', 'ticket/ticket.js')
@section('content')
<div class="row mt-30">
    <div class="col-md-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5 class="panel-title txt-dark">{{ trans('ticketingtool.tickets') }}</h5>
                </div>
                <div class="pull-right">
                    <button class="btn btn-primary btn-anim add-ticket-btn" data-toggle="modal" data-target="#addTicket"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
                </div>
                <div class="pull-right">
                <label class="control-label">{{ trans('ticketingtool.ticket_status') }}</label>
                    <select class="select2 filter" data-style="form-control" id="ticket_status_filter">
                        <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                        @if ($ticketStatus)
                        @foreach ($ticketStatus as $status)
                        <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <table id="tickets-module-table" class="display compact nowrap table-width" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="all">{{ trans('ticketingtool.id') }}</th>
                                <th class="desktop tablet-l">{{ trans('ticketingtool.ticket_no') }}</th>
                                <th class="desktop tablet-l">{{ trans('ticketingtool.subject') }}</th>
                                <th class="desktop tablet-l">{{ trans('ticketingtool.date') }}</th>
                                <th class="desktop tablet-l">{{ trans('ticketingtool.no_of_replies') }}</th>
                                <th class="desktop tablet-l">{{ trans('ticketingtool.ticket_status') }}</th>
                                <th class="desktop tablet-l">{{ trans('ticketingtool.operations') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="addTicket" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog w-500">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title">{{ trans('ticketingtool.add_ticket') }}</h5>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url'=>'tickets/add', 'method'=>'POST', 'id' => 'add_tickets_form')) }}
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.name') }}" class="control-label">{{ trans('ticketingtool.name') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="name" />
                            <div class="help-block name-error"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.email') }}" class="control-label">{{ trans('ticketingtool.email') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="email" />
                            <div class="help-block email-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.mobile') }}" class="control-label">{{ trans('ticketingtool.mobile') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="mobile" maxlength=12 />
                            <div class="help-block mobile-error"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.project') }}" class="control-label">{{ trans('ticketingtool.project') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="project" />
                            <div class="help-block project-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.subject') }}" class="control-label">{{ trans('ticketingtool.subject') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="subject" />
                            <div class="help-block subject-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.ticket_category') }}" class="control-label">{{ trans('ticketingtool.ticket_category') }}<sup class="mandatory">*</sup></label>
                            <select class="select2" data-style="form-control" name="categories">
                                <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                @if ($ticketCategories)
                                @foreach ($ticketCategories as $ticketCategory)
                                <option value="{{ $ticketCategory['id'] }}">{{ $ticketCategory['name'] }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="help-block categories-error"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.ticket_status') }}" class="control-label">{{ trans('ticketingtool.ticket_status') }}<sup class="mandatory">*</sup></label>
                            <select class="select2" data-style="form-control" name="ticket_status">
                                <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                @if ($ticketStatus)
                                @foreach ($ticketStatus as $status)
                                <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="help-block ticket_status-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.message') }}" class="control-label">{{ trans('ticketingtool.message') }}<sup class="mandatory">*</sup></label>
                            <textarea class="form-control" name="message" rows="3"></textarea>
                            <div class="help-block message-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <label for="{{ trans('ticketingtool.attachments') }}" class="control-label">{{ trans('ticketingtool.attachments') }}</label>
                    </div>
                    <div class="col-6 col-xs-12 col-sm-6 input_fields_container_doc">
                        <div class="article-first-img multiple-file-wrap">
                            <input type="file" name="fileupload[]" multiple>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-left:0;">
                        <div class="help-block fileupload-error file_error" style="margin-bottom:10px;" id="file_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn_cancel" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                    <button type="button" class="btn btn-success standard_form_submit" data-table="tickets-module-table">{{ trans('ticketingtool.btn_save') }}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div id="viewTicket" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="ie-jump">×</span></button>
                <h5 class="modal-title" id="myModalLabel">{{ trans('ticketingtool.view_ticket') }}</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body pa-0">
                                <div class="table-view-ticket">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label for="{{ trans('ticketingtool.name') }}" class="control-label">{{ trans('ticketingtool.name') }}</label>
                                            <input type="text" class="form-control" id="view_name" readonly />
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <label for="{{ trans('ticketingtool.email') }}" class="control-label">{{ trans('ticketingtool.email') }}</label>
                                            <input type="text" class="form-control" id="view_email" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label for="{{ trans('ticketingtool.mobile') }}" class="control-label">{{ trans('ticketingtool.mobile') }}</label>
                                            <input type="text" class="form-control" id="view_mobile" readonly />
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <label for="{{ trans('ticketingtool.project') }}" class="control-label">{{ trans('ticketingtool.project') }}</label>
                                            <input type="text" class="form-control" id="view_project" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12">
                                            <label for="{{ trans('ticketingtool.subject') }}" class="control-label">{{ trans('ticketingtool.subject') }}</label>
                                            <input type="text" class="form-control" id="view_subject" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label for="{{ trans('ticketingtool.ticket_category') }}" class="control-label">{{ trans('ticketingtool.ticket_category') }}</label>
                                            <input type="text" class="form-control" id="view_ticket_category" readonly />
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <label for="{{ trans('ticketingtool.ticket_status') }}" class="control-label">{{ trans('ticketingtool.ticket_status') }}</label>
                                            <input type="text" class="form-control" id="view_ticket_status" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12">
                                            <label for="{{ trans('ticketingtool.message') }}" class="control-label">{{ trans('ticketingtool.message') }}</label>
                                            <textarea class="form-control" name="" id="view_message" rows="3" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="row" id="ticket_attachments">
                                        <div class="col-xs-12 col-sm-12">
                                            <label for="{{ trans('ticketingtool.attachments') }}" class="control-label">{{ trans('ticketingtool.attachments') }}</label>
                                            <div class="view-ticket-wrap" style="padding:5px 0">
                                                <div id="view_ticket_model_file" style="padding: 5px 3px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="editTicket" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog w-500">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title">{{ trans('ticketingtool.edit_ticket') }}</h5>
            </div>
            {{ Form::open(array('url'=>'tickets/update', 'method'=>'POST', 'id' => 'edit_ticket_form')) }}
            <div class="modal-body">
                <input type="hidden" name="id" id="ticket_id">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.name') }}" class="control-label">{{ trans('ticketingtool.name') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="name" id="edit_name" />
                            <div class="help-block name-error"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.email') }}" class="control-label">{{ trans('ticketingtool.email') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="email" id="edit_email" />
                            <div class="help-block email-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.mobile') }}" class="control-label">{{ trans('ticketingtool.mobile') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="mobile" id="edit_mobile" maxlength=12 />
                            <div class="help-block mobile-error"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.project') }}" class="control-label">{{ trans('ticketingtool.project') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="project" id="edit_project" />
                            <div class="help-block project-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.subject') }}" class="control-label">{{ trans('ticketingtool.subject') }}<sup class="mandatory">*</sup></label>
                            <input type="text" class="form-control" name="subject" id="edit_subject" />
                            <div class="help-block subject-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.ticket_category') }}" class="control-label">{{ trans('ticketingtool.ticket_category') }}<sup class="mandatory">*</sup></label>
                            <select class="select2" data-style="form-control" name="categories" id="edit_categories">
                                <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                @if ($ticketCategories)
                                @foreach ($ticketCategories as $ticketCategory)
                                <option value="{{ $ticketCategory['id'] }}">{{ $ticketCategory['name'] }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="help-block categories-error"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.ticket_status') }}" class="control-label">{{ trans('ticketingtool.ticket_status') }}<sup class="mandatory">*</sup></label>
                            <select class="select2" data-style="form-control" name="ticket_status" id="edit_ticket_status">
                                <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                @if ($ticketStatus)
                                @foreach ($ticketStatus as $status)
                                <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="help-block ticket_status-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.message') }}" class="control-label">{{ trans('ticketingtool.message') }}<sup class="mandatory">*</sup></label>
                            <textarea class="form-control" name="message" id="edit_message" rows="3"></textarea>
                            <div class="help-block message-error"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="{{ trans('ticketingtool.attachments') }}" class="control-label">{{ trans('ticketingtool.attachments') }}</label>
                            <div id="edit_ticket_file_name_class">
                            </div>
                            <div class="input_fields_container_docs">
                                <div class="article-first-img multiple-file-wrap">
                                    <input type="file" name="fileupload[]" class="fileUpload" multiple>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-left:0;">
                                <div class="help-block fileupload-error edit_file_error" style="margin-bottom:10px;" id="edit_file_error"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_close') }}</button>
                <button type="button" class="btn btn-success  standard_form_submit" data-table="tickets-module-table">{{ trans('ticketingtool.btn_save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
