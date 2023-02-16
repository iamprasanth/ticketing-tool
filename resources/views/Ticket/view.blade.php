@extends('layout.app',['title' => 'ticket_view'])
@section('js_file', 'ticket/ticket.js')
@section('content')
<div class="row mt-30">
    <div class="col-md-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5 class="panel-title txt-dark">{{ trans('ticketingtool.view_ticket') }}</h5>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="form-wrap">
                        <form role="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label text-left">{{ trans('ticketingtool.name') }}</label>
                                        <input type="text" class="form-control" value="{{ $ticketInfo['name'] }}" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">{{ trans('ticketingtool.email') }}</label>
                                        <input type="text" class="form-control" value="{{ $ticketInfo['email'] }}" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">{{ trans('ticketingtool.mobile') }}</label>
                                        <input type="text" class="form-control" value="{{ $ticketInfo['telephone'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="control-label">{{ trans('ticketingtool.created_date') }}</label>
                                        <input type="text" class="form-control" value="{{ date_format($ticketInfo['created_at'],'d.M.Y') }}" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label">{{ trans('ticketingtool.project') }}</label>
                                        <input type="text" class="form-control" value="{{ $ticketInfo['project_name'] }}" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label">{{ trans('ticketingtool.ticket_category') }}</label>
                                        @if (!empty($ticketCategories))
                                        @foreach ($ticketCategories as $category)
                                        @if ($ticketInfo['category_id'] == $category['id'])
                                        <input type="text" class="form-control" value="{{ $category['name'] }}" readonly>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label">{{ trans('ticketingtool.ticket_status') }}</label>
                                        @if (!empty($ticketStatus))
                                        <select class="select2 filter" data-style="form-control" id="view_ticket_status">
                                            <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                            @if ($ticketStatus)
                                            @foreach ($ticketStatus as $status)
                                            @if ($ticketInfo['status_id'] == $status['id'])
                                            <option value="{{ $status['id'] }}" selected>{{ $status['name'] }}</option>
                                            @else
                                            <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="control-label">{{ trans('ticketingtool.subject') }}</label>
                                        <textarea class="form-control" readonly>{{ $ticketInfo['subject'] }}</textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">{{ trans('ticketingtool.message') }}</label>
                                        <textarea class="form-control" readonly>{{ $ticketInfo['message'] }}</textarea>
                                    </div>
                                </div>
                                @if($ticketInfo['attachments'])
                                <label class="control-label">{{ trans('ticketingtool.attachments') }}</label>
                                <div class="row">
                                    @foreach($ticketInfo['attachments'] as $attachment)
                                    <div class="col-sm-4">
                                        <div class="form-group view-attachment">
                                            <input type="text" class="form-control" value="{{$attachment['file_name']}}" readonly>
                                            <a class="fa fa-eye btn btn-xs btn-warning" title="{{ trans('ticketingtool.view_attachments') }}" target="_blank" href="{{ asset('storage/tickets/'.$attachment['stored_name']) }}"></a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer footer-trans mb-10">
                        <div class="text-left inline-block">
                            <a href="/tickets">
                                <button class="btn btn-default btn-anim"><i class="fa fa-angle-left"></i><span class="btn-text ie-jump">Back</span></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default card-view">
    <div class="panel-heading">
        <div class="pull-left">
            <h5 class="panel-title txt-dark">{{ trans('ticketingtool.feedbacks') }}</h5>
        </div>
        <div class="pull-right">
            <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addTicketComment"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="panel-wrapper collapse in">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    @if ($ticketInfo['comments']->count() > 0)
                    <div class="pt-20">
                        <div class="streamline user-activity" id="ticket_comments">
                            @php
                            $row = 0;
                            @endphp
                            @foreach ($ticketInfo['comments'] as $ticketComment)
                            @php
                            $files = $ticketComment->attachments;
                            $row ++;
                            @endphp
                            <div>
                                <ul class="right-ul comments-list comments-list-dynamic" id="comment-{{ $row }}">
                                    <li>
                                        @if($ticketComment['userInfo'] !=  "")
                                        <b>{{ $ticketComment['userInfo']['name'] }}</b>
                                        @else
                                        <b>{{ $ticketInfo['name'] }}</b>
                                        @endif
                                        <span class="comment-time">{{ \Carbon\Carbon::parse($ticketComment->created_at)->format('d.M.Y') }} {{ $diff = Carbon\Carbon::parse($ticketComment->created_at)->diffForHumans() }}</span>
                                        <button class="comment-delete comment-action pull-right btn btn-xs btn-delete btn-common btn-deletegradient" data-row-id="{{ $row }}" data-id="{{ $ticketComment->id }}" id="comment-delete-{{ $ticketComment->id }}"><i class="fa fa-trash" title="Delete"></i></button>
                                        <button class="comment-edit comment-action pull-right btn btn-xs btn-edit btn-common btn-editgradient" data-id="{{ $ticketComment->id }}" id="comment-edit-{{ $ticketComment->id }}"><i class="fa fa-pencil" title="' + Lang.get('ticketingtool.btn_edit') + '"></i></button>
                                    </li>
                                    <li id="comment-content-{{ $ticketComment->id }}">{{ $ticketComment->description }}</li>
                                    @if (isset($ticketComment["attachments"]))
                                    @foreach ($files as $key => $file)
                                    <li id="comment-attachment-{{ $ticketComment->id }}">{{ $file['file_name'] }}&nbsp;<a class="fa fa-eye btn btn-xs btn-warning btn-common" title="View Attachments" target="_blank" href="{{ asset('storage/tickets/'.$file['stored_name']) }}"></a></li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <span style="display: block;text-align: center;">{{ trans('ticketingtool.no_feedbacks') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div id="addTicketComment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog w-500">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title">{{ trans('ticketingtool.add_ticket_comment') }}</h5>
            </div>
            {{ Form::open(array('url'=>'tickets/add_comment', 'method'=>'POST', 'id' => 'add_ticket_comments_form')) }}
            <div class="modal-body">
                <div class="form-group ">
                    <input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticketInfo['id'] }}">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="{{ trans('ticketingtool.reply') }}" class="control-label">{{ trans('ticketingtool.reply') }}</label>
                            <textarea class="form-control" name="comment" rows="4"></textarea>
                            <div class="help-block comment-error" id="commenterror"></div>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_close') }}</button>
                <button type="button" class="btn btn-success  standard_form_submit">{{ trans('ticketingtool.btn_save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<div id="editTicketComment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog w-500">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title">{{ trans('ticketingtool.edit_ticket_comment') }}</h5>
            </div>
            {{ Form::open(array('url'=>'tickets/comment/update', 'method'=>'POST', 'id' => 'edit_ticket_comment_form')) }}
            <div class="modal-body">
                <input type="hidden" name="id" id="ticket_comment_id">
                <input type="hidden" name="ticketId" value="{{ $ticketInfo['id'] }}">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="{{ trans('ticketingtool.reply') }}" class="control-label">{{ trans('ticketingtool.reply') }}</label>
                        <textarea class="form-control" name="comment" id="edit_comment" rows="4"></textarea>
                        <div class="help-block comment-error" id="commenterror"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <label for="{{ trans('ticketingtool.attachments') }}" class="control-label">{{ trans('ticketingtool.attachments') }}</label>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <div id="edit_ticket_file_name_class">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_close') }}</button>
                <button type="button" class="btn btn-success  standard_form_submit">{{ trans('ticketingtool.btn_save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
