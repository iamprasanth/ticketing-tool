@extends('layout.app',['title' => 'project_taskLabel'])
@section('content')
<div class="row mt-30">
    <div class="col-md-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5 class="panel-title txt-dark">{{ trans('ticketingtool.task_label') }}</h5>
                </div>
                <div class="pull-right">
                    <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addTaskLabel"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                   <table id="task-label-table" class="display compact nowrap table-width" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="all">{{ trans('ticketingtool.id') }}</th>
                            <th class="desktop tablet-l">{{ trans('ticketingtool.task_label') }}</th>
                            <th class="desktop tablet-l">{{ trans('ticketingtool.status') }}</th>
                            <th class="desktop tablet-l">{{ trans('ticketingtool.operations') }}</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="addTaskLabel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog w-500">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title">{{ trans('ticketingtool.add_task_label') }}</h5>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_task_label_form')) }}
                <div class="form-group">
                    <label for="{{ trans('ticketingtool.department') }}" class="control-label">{{ trans('ticketingtool.task_label') }}<sup class="mandatory">*</sup></label>
                    <input type="text" class="form-control" name="name" id='name'/>
                    <div class="help-block name-error"></div>
                </div>
                <div class="form-group">
                    <label class="mw-unset">{{ trans('ticketingtool.status') }}</label>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="js-switch js-switch-1"  data-color="#65b32e" data-secondary-color="#ccc" name="is_active" value="1" checked="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                    <button type="button" class="btn btn-success add_task_label_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
                </div>
            </div>
                {{ Form::close() }}
        </div>
    </div>
</div>
<div id="editTaskLabel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog w-500">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h5 class="modal-title">{{ trans('ticketingtool.edit_task_label') }}</h5>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'edit_task_label_form')) }}
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="{{ trans('ticketingtool.name') }}" class="control-label">{{ trans('ticketingtool.task_label') }}<sup class="mandatory">*</sup></label>
								<input type="text" class="form-control" name="name" id="task_label">
								<div class="help-block name-error"></div>
							</div>
						</div>
						<input type="hidden" name="id" id="task-label-id">
					</div>
				</div>
				<div class='form-group edit'>
					<label class="mw-unset">{{ trans('ticketingtool.status') }}</label>
					<input type="hidden" name="is_active" value="0">
					<input type="checkbox" class="js-switch js-switch-1"  data-color="#65b32e" data-secondary-color="#ccc" name="is_active"  id="is_active" value="1" >
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
					<button type="button" class="btn btn-success edit_task_label_submit_btn" >{{ trans('ticketingtool.btn_save') }}</button>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
@endsection
