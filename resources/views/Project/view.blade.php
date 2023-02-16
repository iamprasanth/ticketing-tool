@extends('layout.app',['title' => 'project_view'])
@section('content')
<?php $isAdmin = (Auth::user()->role_id == 1) ? 1 : 0; ?>
<div class="row promotiontab mt-30">
  <div class="col-md-12">
    <div class="panel panel-default card-view promotioncard">
      	<div class="panel-heading">
	        <div class="project-view-header">
	          	<div class="pull-left">
	            	<h6 class="panel-title txt-dark" id="project-heading">{{ $projects->project_name }}</h6>
	            </div>
	          	<div class="pull-right">
	            	@if (!$projectMembers['members']->isEmpty())
	              	<select class="selectpicker" data-style="form-control" name="is_active" id="project_members_dropdown">
		                <option style="display:none;visibility:hidden;" selected>Members</option>
		                @foreach ($projectMembers['members'] as $keys => $projectMember)
		                <option style="pointer-events:none;">{{$projectMember->name}}</option>
	                    @endforeach
	              	</select>
	            	@endif
                    @if ($isAdmin)
    		            <div class="pull-right">
    		              <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addMembers"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add_members') }}</span></button>
    		            </div>
                    @endif
	          	</div>
	        </div>
	      	<div class="clearfix"></div>
      	</div>
      	<div id="editProject" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        	<div class="modal-dialog" style="width:800px;">
	          	<div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		                <h5 class="modal-title">{{ trans('ticketingtool.edit_project') }}</h5>
		            </div>
		            <div class="modal-body">
			            {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'edit_project_details')) }}
			            <div class="form-group">
			            	<input type="hidden" name="project_id" value="{{$projects->id}}">
			            	<input type="hidden" id="task-no" value="<?php echo array_key_exists('task', $_GET) ? $_GET['task'] : 0 ?>">
			              	<div class="row">
				                <div class="col-xs-12">
				                  	<div class="form-group">
					                    <div class="row">
					                        <div class="col-sm-6">
						                        <label class="control-label">{{ trans('ticketingtool.project_manager') }}<sup class="mandatory">*</sup></label>
						                        <select id="project-manager" class="select2" data-style="form-control" name="project_manager">
						                            <option value="0">{{ trans('ticketingtool.please_select') }}</option>
						                            @if (!empty($users))
						                                @foreach ($users as $user)
						                                    <option value="{{ $user['get_user_name']['user_id'] }}">{{ $user['get_user_name']['user']}}</option>
						                                @endforeach
						                            @endif
						                        </select>
						                        <div class="help-block project_manager-error"></div>
					                        </div>
					                    </div>
					                    <div class="form-group">
					                        <label for="{{ trans('ticketingtool.project') }}" class="control-label">{{ trans('ticketingtool.project_name') }}<sup class="mandatory">*</sup></label>
					                        <input type="text" class="form-control" name="project_name" id="project-name">
					                        <div class="help-block project_name-error"></div>
					                    </div>
					                    <div class="form-group">
					                        <label for="{{ trans('ticketingtool.description') }}" class="control-label">{{ trans('ticketingtool.description') }}</label>
					                        <textarea class="form-control" name="description" id="project-description" rows="4" maxlength="250"></textarea>
					                        <div class="help-block description-error"></div>
					                    </div>
					                    <div class="form-group">
					                      	<div class="row">
						                        <div class="col-sm-6">
							                        <label class="control-label">{{ trans('ticketingtool.label') }}</label>
														<select id="project-label" class="select2" data-style="form-control" name="project_label">
															<option value="0" selected>{{ trans('ticketingtool.please_select') }}</option>
															@if (!empty($projectLabels))
    															@foreach ($projectLabels as $projectLabel)
    															<option value="{{ $projectLabel['id'] }}" >{{ $projectLabel['name'] }}</option>
    															@endforeach
															@endif
														</select>
						                        </div>
						                        <div class="col-sm-6">
					                        	<label class="control-label">{{ trans('ticketingtool.category') }}</label>
					                          	<select id="project-category" class="select2" data-style="form-control" name="project_category">
					                            <option value="0" selected>{{ trans('ticketingtool.please_select') }}</option>
					                            @if (!empty($projectCategories))
					                            @foreach ($projectCategories as $projectCategory)
					                            <option value="{{ $projectCategory['id'] }}">{{ $projectCategory['name'] }}</option>
					                            @endforeach
					                            @endif
					                          	</select>
					                        </div>
					                      	</div>
					                    </div>
					                    <div class="form-group">
					                      	<label for="{{ trans('ticketingtool.client_company') }}" class="control-label">{{ trans('ticketingtool.client_company') }}</label>
					                      	<input type="text" class="form-control" name="client_company" id="client-company">
					                      	<div class="help-block client_company-error"></div>
					                    </div>
                                        <div class="form-group">
                                            <label for="{{ trans('ticketingtool.additional_info') }}" class="control-label">{{ trans('ticketingtool.additional_info') }}</label>
                                            <textarea class="form-control" name="additional_info" id="additional-info" rows="4"></textarea>
                                            <div class="help-block additional_info-error"></div>
                                        </div>
					                    <div class="modal-footer">
				                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
				                        <button type="button" class="btn btn-success edit_projects_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
				                    </div>
				                  	</div>
				                </div>
			              	</div>
			            </div>
			            {{ Form::close() }}
		            </div>
	          	</div>
        	</div>
      	</div>
        <div id="addMembers" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog">
	            <div class="modal-content">
		            <div class="modal-header">
		              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		              <h5 class="modal-title">{{ trans('ticketingtool.add_members') }}</h5>
		            </div>
		            <div class="modal-body">
	            	{{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_project_members')) }}
	              	<div class="form-group">
		              	<input type="hidden" name="project_id" value="{{$projects->id}}">
		                <div class="row">
		                  	<div class="col-sm-8">
		                    	<label class="control-label">{{ trans('ticketingtool.project_members') }}<sup class="mandatory">*</sup></label>
		                      	<select id="project_members" class="multi-select" multiple data-style="form-control" name="projectMembers[]">
		                            @if (!empty($users))
		                                @foreach ($users as $user)
		                                <option value="{{ $user['get_user_name']['user_id'] }}" <?php echo (!empty($projectMembers['members']) && in_array($user['get_user_name']['user_id'], json_decode($projectMembers['member_id']))) ? 'selected' : ''; ?>>{{ $user['get_user_name']['user'] }}</option>
		                                @endforeach
		                            @endif
		                      	</select>
		                  		<div class="help-block projectMembers-error"></div>
		                  	</div>
		                </div>
		                <div class="modal-footer">
		                  <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
		                  <button type="button" class="btn btn-success add_projectMembers_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
		                </div>
	              	</div>
	            	{{ Form::close() }}
	            </div>
	            </div>
	        </div>
        </div>
      	<div  class="tab-struct custom-tab-2 mt-10">
        	<ul role="tablist" class="nav nav-tabs project-taskbar" id="myTabs_15">
            <li class="active " role="presentation"><a aria-expanded="true"  data-toggle="tab" role="tab" id="task" href="#Task">{{ trans('ticketingtool.task') }}</a></li>
            <!-- <li role="presentation"><a  data-toggle="tab" id="discussion" role="tab" href="#Discussion" aria-expanded="false" >{{ trans('ticketingtool.discussion') }}</a></li> -->
            <li role="presentation"><a  data-toggle="tab" id="files" role="tab" href="#Files" aria-expanded="false" data-id = "{{$projects->id}}" class="add-project-file-btn">{{ trans('ticketingtool.files') }}</a></li>
            <li role="presentation"><a  data-toggle="tab" id="access" role="tab" href="#Access" data-id = "{{$projects->id}}" aria-expanded="false" class="add-access-btn">{{ trans('ticketingtool.access') }}</a></li>
            @if ($isAdmin)
                <li role="presentation" style="float:right;"><button class="btn btn-primary btn-anim edit-project-btn" data-id="{{$projects->id}}"><i class="fa fa-pencil"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.edit_project') }}</span></button></li>
            @endif
        </ul>
        <div class="tab-content" id="myTabContent_15">
          <div id="Task" class="tab-pane promotiontabs active"  name="task" role="tabpanel">
            <div class="panel-wrapper collapse in">
				@if ($isAdmin)
                <div class="panel-heading">
	                <div class="pull-left">
	                    <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addTaskGroup"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add_task_list') }}</span></button>
	                </div>
	                <div class="clearfix"></div>
                </div>
				@endif
              	<div id="addTaskGroup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	                <div class="modal-dialog w-500">
	                    <div class="modal-content">
		                    <div class="modal-header">
		                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		                        <h5 class="modal-title">{{ trans('ticketingtool.add_task_list') }}</h5>
		                    </div>
		                    <div class="modal-body">
		                       {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_projectTaskList_form')) }}
		                        <input type="hidden" name="project_id" value="{{$projects->id}}">
		                        <div class="form-group">
		                          <label for="{{ trans('ticketingtool.task_list') }}" class="control-label">{{ trans('ticketingtool.task_list') }}<sup class="mandatory">*</sup></label>
		                          <input type="text" class="form-control" name="taskList" id='taskList'/>
		                          <div class="help-block taskList-error"></div>
		                        </div>
		                        <div class="modal-footer">
			                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
			                        <button type="button" class="btn btn-success add_projectTaskList_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
		                        </div>
		                    </div>
	                    	{{ Form::close() }}
	                    </div>
	                </div>
              	</div>
              	<div id="editTaskGroup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	                <div class="modal-dialog w-500">
	                    <div class="modal-content">
		                    <div class="modal-header">
		                      	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		                      	<h5 class="modal-title">{{ trans('ticketingtool.edit_task_list') }}</h5>
		                    </div>
		                    <div class="modal-body">
	                    	{{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'edit_projectTaskList_form')) }}
	                        <input type="hidden" name="task_group_id" id="task_group_id_edit">
	                        <div class="form-group">
	                            <label for="{{ trans('ticketingtool.task_list') }}" class="control-label">{{ trans('ticketingtool.task_list') }}<sup class="mandatory">*</sup></label>
	                            <input type="text" class="form-control" name="taskList" id='task-list-edit'/>
	                            <div class="help-block taskList-error"></div>
	                        </div>
	                        <div class="modal-footer">
		                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
		                        <button type="button" class="btn btn-success edit_projectTaskList_submit_btn" data-id=>{{ trans('ticketingtool.btn_save') }}</button>
	                        </div>
							{{ Form::close() }}
	                    </div>
	                    </div>
	                </div>
              	</div>
            </div>
            <?php $subtasks = []; $subTaskCount = 0?>
            <div class="task-wrap" id="reload-task">
              <div class="row">
                <div class="col-xs-12 col-sm-10 task-list-div">
                @foreach ($taskLists as $key => $taskList)
                    <div class="task-outline" id="task-card-{{$taskList->id}}">
	                    <div class="row">
	                        <div class="col-xs-12 col-md-6">
	                        	<h5 id="task-group-{{$taskList->id}}"<?php $tasklist = strlen($taskList->task_group) > 45 ? substr($taskList->task_group,0,45)."..." : $taskList->task_group;?> >{{$tasklist}}</h5>
	                        </div>
                            @if ($isAdmin)
    	                        <div class="col-xs-12 col-md-6">
    	                            <div class="dropdown pull-right">
    	                                <a class="edit-task_group dropdown-toggle fa fa-ellipsis-v" data-toggle="dropdown">
    	                                </a>
    	                                <ul class="dropdown-menu">
    		                                <li><a onclick="editTaskGroup({{ $taskList->id }})">{{ trans('ticketingtool.btn_edit') }}</a></li>
    		                                <li><a onclick="completeTaskGroup({{ $taskList->id }})">{{ trans('ticketingtool.complete') }}</a></li>
    		                               	<li><a onclick="deleteTaskGroup({{ $taskList->id }})">{{ trans('ticketingtool.btn_delete') }}</a></li>
    	                              	</ul>
    	                            </div>
    	                        </div>
                            @endif
		                    @if (!empty($taskLists))
		                    <div class="task-outline-{{ $taskList->id }}">
		                    @foreach ($taskList->getSubTask as $subTask)
		                      <?php $subtasks[$subTaskCount++] =  array('id' => $subTask->id, 'name' => $subTask->task_name) ?>
		                      	<div class="col-xs-12 projectInnerPage" id="sub-task-{{ $subTask->id }}">
			                        <div class="test">
				                        <div class="pretty p-svg p-round p-pulse">
					                        <input type="checkbox" class="complete-task" data-id="{{ $subTask->id }}" assignee-id="{{ $subTask->assignee }}"/>
					                        <div class="state">
					                            <svg class="svg svg-icon" viewBox="0 0 20 20">
					                              <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: #65b32e;fill:#65b32e;"></path>
					                            </svg>
					                            <?php
					                            $assigneeName = $subTaskLabel = '';
					                            if ($subTask->assignee || $subTask->label) {
						                            $assigneeName = getAssigneeName($subTask->id);
						                            $subTaskLabel = getSubtaskLabel($subTask->id);
					                            }
					                            ?>
					                          	<label class="subtask-radio">
                                                    <a data-toggle="modal" onclick="viewSubTask({{$subTask->id}})">
                                                        <span class="asignee-name" id="assignee-for-subtask{{ $subTask->id }}">{{$assigneeName ? $assigneeName->username : ''}}</span>&nbsp
                                                        <span class="sub-name span-block" id="name-forsubtask-{{ $subTask->id }}">{{$subTask->task_name}}</span>&nbsp&nbsp
                                                        <span class="label-name span-block" id="label-for-subtask{{ $subTask->id }}">{{$subTaskLabel ? $subTaskLabel->name : ''}}</span>
                                                        &nbsp&nbsp
                                                        <span class='<?php echo (date("Y-m-d") == $subTask->due_date) ? 'text-danger' : 'text-light' ?>' id="date-for-subtask{{ $subTask->id }}">{{ is_null($subTask->due_date) ? '' : date("d.M.Y", strtotime($subTask->due_date)) }}</span>
                                                        <i class="fa fa-exclamation-circle" id="priority-icon-{{ $subTask->id }}" style=<?php echo ($subTask->priority == 1) ? '' : 'display:none;' ?>></i>
                                                    </a>
					                          	</label>
					                        </div>
				                        </div>
				                        <span class="edit-project_view"  onclick="editSubTask({{$subTask->id}})"><svg width="14px" height="14px" viewBox="0 0 14 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				                            <title>Edit</title>
				                            <desc>Created with Sketch.</desc>
				                            <g id="Task-Modal-/-Page--❤️✅" stroke="none" stroke-width="1" fill-rule="evenodd">
				                                <path d="M2,-3.63797881e-12 C0.89,-3.63797881e-12 0,0.89 0,2 L0,12 C0,13.1045695 0.8954305,14 2,14 L12,14 C13.1045695,14 14,13.1045695 14,12 L14,5 L13,5 L13,12 C13,12.5522847 12.5522847,13 12,13 L2,13 C1.44771525,13 1,12.5522847 1,12 L1,2 C1,1.44771525 1.44771525,1 2,1 L9,1 L9,-3.63797881e-12 L2,-3.63797881e-12 Z M12.3,0.2 L11.08,1.41 L12.58,2.91 L13.8,1.7 C14.06,1.44 14.06,1 13.8,0.75 L13.25,0.2 C13.12,0.07 12.95,0 12.78,0 C12.61,0 12.43,0.07 12.3,0.2 Z M10.37,2.12 L5,7.5 L4.67748379,9.33646394 L6.5,9 L11.87,3.62 L10.37,2.12 Z" id="edit" fill-rule="nonzero"></path>
				                            </g>
				                            </svg>
				                        </span>
			                        </div>
		                      	</div>
		                    @endforeach
		                  	</div>
		                    @endif
                            @if ($isAdmin)
    		                    <div class="col-xs-12">
    		                        <div class="pull-right">
    		                            <button class="btn btn-primary btn-anim add-subtask-btn" data-toggle="modal" data-id="{{$taskList->id}}"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add_sub_task') }}</span></button>
    		                        </div>
    		                    </div>
                            @endif
	                    </div>
                    </div>
                @endforeach
                </div>
                <div id="viewSubTask" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog" style="width:950px;">
                    <div class="modal-content">
                        <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                        <h5 class="modal-title" id="subtaskHeading"></h5>
	                        <ul class="task-view" style="display:none;">
	                            <li id="view_created_by"><label id="view_created_by_label"></label></li>
	                            <li id="view_created_at"><label id="view_created_at_label"></label></li>
	                        </ul>
                        </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-sm-8 task-main-div" style="border-right: 1px solid #ddd;">
	                            <div class="task-files-div"></div><hr class="hr-grey">
                            	{{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_subTaskComment_form')) }}
								<input type="hidden" name="sub_task_id" id="sub_task_id">
								<input type="hidden" name="subTaskLabel_id" id="subTaskLabel_id">
								<input type="hidden" name="assignee" id="assignee_id" value="">
								<div class="form-group">
									<label for="{{ trans('ticketingtool.discussion') }}" class="control-label">{{ trans('ticketingtool.discussion') }}</label>
									<textarea class="form-control ckeditor" name="comment"></textarea>
                                  	<div class="help-block comment-error" id="commenterror"></div>
                                    <div class="row">
                                      	<div class="col-md-12 input_fields_container_doc">
	                                        <div class="article-first-img multiple-file-wrap">
	                                            <input type="file" name="commentFiles[]" id="comment-file-1" class="multiple-file remove_project_file comment-file">
	                                        </div>
	                                        <a href="#" class="remove-first" style="margin-right: 116px;">Remove</a>
	                                        <div class="help-block comment-file-1-error comment-file-error"></div>
                                      	</div>
                                        <div class="col-md-5">
                                        	<button class="btn btn-sm btn-primary add_comment_files" data-name="commentFiles">{{ trans('ticketingtool.more_attachments') }}</button>
                                        </div>
                                      	<div class="col-md-7">
	                                        <div class="form-group pull-right" style="padding-top:0;padding-right:0;">
	                                          	<button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">{{ trans('ticketingtool.btn_cancel') }}</button>
	                                          	<button type="button" class="btn btn-success updateSubTaskComment_submit_btn">{{ trans('ticketingtool.add_comment') }}</button>
	                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-group pull-right">
                                    <div class="row">
                                    </div>
                                </div>
                              	</div>
                            	{{ Form::close() }}
	                            <div class="form-group viewComments" style="margin-top: 50px;">
	                            </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group ticketingtool-task-label">
                                <div class="pretty p-svg p-curve priority">
                                    <input type="checkbox" name="priority" value="1" class="priority" checked onclick="return false"/>
                                    <div class="state p-warning">
                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                        </svg>
                                      <label>{{ trans('ticketingtool.priority_task') }}</label>
                                    </div>
                                </div>
                                <label class="control-label estimate-label">{{ trans('ticketingtool.task_estimate') }}</label>
                                <input type="text" class="form-control task-estimate" id="task-estimate" disabled>
                                <label class="control-label task-label">{{ trans('ticketingtool.label') }}</label>
                                <select id="subTaskLabel" class="select2 ticketingtool_project_label" data-style="form-control" name="task_label" value="">
									<option value="0">{{ trans('ticketingtool.please_select') }}</option>
									@if (!empty($taskLabels))
									@foreach ($taskLabels as $taskLabel)
									<option value="{{ $taskLabel['id'] }}" name="{{ $taskLabel['name'] }}">{{ $taskLabel['name'] }}</option>
									@endforeach
									@endif
                                </select>
                                </div>
                              <div class="form-group">
                              <input type="hidden" id="subTaskAssigneeId">
                              <label class="control-label">{{ trans('ticketingtool.assignee') }}<sup class="mandatory">*</sup></label>
                                <select id="subTaskAssignee" class="selectpicker" data-style="form-control" name="task_assignee">
                                  @if (!empty($projectMembers['members']))
                                  @foreach ($projectMembers['members'] as $keys => $projectMember)
                                  <option value="{{ $projectMember->user_id }}" name="{{ ucfirst($projectMember->name) }}">{{ ucfirst($projectMember->name) }}</option>
                                  @endforeach
                                  @endif
                                </select>
                                <div class="help-block task_assignee-error" id="task_assignee_error"></div>
                              </div>
                              <div class="form-group">
                              <label class="control-label text-left">{{ trans('ticketingtool.due_date') }}</label>
                                <div class='date'>
                                  <input id="subTask_date" class="form-control date-selector" name="due_date" type="text" autocomplete="off">
                                </div>
                              </div>
                              {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'update_subscribers_form')) }}
                                <div class="form-group subscribers" style="background:#f5f5f5; padding:10px 5px;">
                                <label class="control-label">{{ trans('ticketingtool.subscribers') }}</label>
                                  <div class="form-group">
                                      @if (!empty($projectMembers['members']))
                                      @foreach ($projectMembers['members'] as $keys => $projectMember)
                                      <div class="row">
                                        <div class="col-md-2">
                                          <div class="pretty p-svg p-curve">
                                            <input type="checkbox" class="task-subscribers" name="task-subscribers[]" value="{{ $projectMember->user_id }}">
                                            <div class="state p-success">
                                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                </svg>
                                                <label></label>
                                            </div>
                                        </div>

                                        </div>
                                        <div class="col-md-10">
                                          <span>{{ ucfirst($projectMember->name) }}</span>
                                        </div>
                                      </div>
                                      @endforeach
                                      @endif
                                  </div>
                                  <div class="help-block projectSubscribers-error"></div>
                                </div>
                              {{ Form::close() }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div id="editSubTask" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:800px;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close project-close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h5 class="modal-title">{{ trans('ticketingtool.edit_subtask') }}</h5>
                    </div>
                    <div class="modal-body">
                    {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'edit_subTask_form')) }}
                      <input type="hidden" id="edit_task_id" name="id" value="">
                      <input type="hidden" id="edit_taskgroup_id" name="task_group_id" value="">
                      <input type="hidden" id="task_file" name="project_file" value="">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-sm-6">
                              <label for="{{ trans('ticketingtool.task_name') }}" class="control-label">{{ trans('ticketingtool.task_name') }}<sup class="mandatory">*</sup></label>
                              <!-- <input type="text" class="form-control" name="task_name" id="edit_task_name" value="" maxlength="80"> -->
                              <input type="text" class="form-control form-control-static" name="task_name" id="edit_task_name" value="" maxlength="80"></text>
                              <div class="help-block task_name-error"></div>
                            </div>
                            <div class="col-sm-3">
                              <label for="{{ trans('ticketingtool.task_name') }}" class="control-label">{{ trans('ticketingtool.task_estimate') }}</label>
                              <input type="text" class="form-control task-estimate" name="task_estimate" id="task_estimate" value="">
                              <div class="help-block task_estimate-error"></div>
                            </div>
                            <div class="col-sm-3">
                              <div class="pretty p-svg p-curve priority-task">
                                  <input type="checkbox" name="priority" value="1" id="priority-edit"/>
                                  <div class="state p-warning">
                                      <svg class="svg svg-icon" viewBox="0 0 20 20">
                                          <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                      </svg>
                                      <label>{{ trans('ticketingtool.priority_task') }}</label>
                                  </div>
                              </div>
                           </div>
                          </div>
                        </div>
                      <div class="form-group">
                        <label for="{{ trans('ticketingtool.description') }}" class="control-label">{{ trans('ticketingtool.description') }}</label>
                        <textarea rows="5" class="form-control ckeditor" name="task-description" id='task-description-edit'></textarea>
                        <div class="help-block description-error"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default project-close" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                        <button type="button" class="btn btn-success update_taskList_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
                      </div>
                    {{ Form::close() }}
                    </div>
                  </div>
                </div>
              </div>
                <div id="addSubTask" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog" style="width:800px;">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h5 class="modal-title">{{ trans('ticketingtool.add_subTask') }}</h5>
                      </div>
                      <div class="modal-body">
                      {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_subTask_form')) }}
                      <input type="hidden" name="task_group_id" id="task_group_id" value="">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-sm-6">
                              <label for="{{ trans('ticketingtool.task_name') }}" class="control-label">{{ trans('ticketingtool.task_name') }}<sup class="mandatory">*</sup></label>
                              <input type="text" class="form-control" name="task_name" id="task_name" value="" maxlength="80">
                              <div class="help-block task_name-error"></div>
                            </div>
                            <div class="col-sm-3">
                              <label for="{{ trans('ticketingtool.task_name') }}" class="control-label">{{ trans('ticketingtool.task_estimate') }}</label>
                              <input type="text" class="form-control task-estimate" name="task_estimate" value="">
                              <div class="help-block task_estimate-error"></div>
                            </div>
                            <div class="col-sm-3">
                              <div class="pretty p-svg p-curve priority-task">
                                  <input type="checkbox" name="priority" value="1" id="priority"/>
                                  <div class="state p-warning">
                                      <svg class="svg svg-icon" viewBox="0 0 20 20">
                                          <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                      </svg>
                                      <label>{{ trans('ticketingtool.priority_task') }}</label>
                                  </div>
                              </div>
                           </div>
                          </div>
                          <div class="form-group">
                            <label for="{{ trans('ticketingtool.description') }}" class="control-label">{{ trans('ticketingtool.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="add_task_description" value=""></textarea>
                            <div class="help-block description-error"></div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-6">
                              <label class="control-label text-left">{{ trans('ticketingtool.due_date') }}</label>
                                <div class='date'>
                                  <input id="due_date" class="form-control date-selector project_due_date" name="due_date" type="text" autocomplete="off" value="">
                                  <div class="help-block due_date-error"></div>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.label') }}</label>
                                  <select id="project_label" class="select2" data-style="form-control" name="task_label">
                                    <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                    @if (!empty($taskLabels))
                                    @foreach ($taskLabels as $taskLabel)
                                    <option value="{{ $taskLabel['id'] }}">{{ $taskLabel['name'] }}</option>
                                    @endforeach
                                    @endif
                                  </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.assignee') }}<sup class="mandatory">*</sup></label>
                                  <select id="task_assignee" class="select2 task_assignee" data-style="form-control" name="task_assignee">
                                    <option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                    @if (!empty($projectMembers['members']))
                                    @foreach ($projectMembers['members'] as $keys => $projectMember)
                                    <option value="{{ $projectMember->user_id }}">{{ ucfirst($projectMember->name) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                  <div class="help-block task_assignee-error"></div>
                              </div>
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.subscribers') }}</label>
                                  <select id="" class="multi-select task_subscribers" multiple data-style="form-control" name="projectSubscribers[]">
                                    @if (!empty($projectMembers['members']))
                                    @foreach ($projectMembers['members'] as $keys => $projectMember)
                                      <option value="{{ $projectMember->user_id }}">{{ ucfirst($projectMember->name) }}</option>
                                    @endforeach
                                    @endif
                                  </select>
                              <div class="help-block projectSubscribers-error"></div>
                              </div>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label for="{{ trans('ticketingtool.description') }}" class="control-label">{{ trans('ticketingtool.files') }}</label>
                            <!-- <input type="file" id="taskFile" name="taskFile" class="dropify file_upload"/> -->
                            <div class="col-md-12 input_fields_container_doc_task">
                              <div class="article-first-img multiple-file-wrap">
                                <input type="file" name="taskFile[]" class="multiple-file remove_project_file task-file" id="task-file-1">
                              </div>
                              <a href="#" class="remove-first remove-two" style="margin-right: 116px;">Remove</a>
                              <div class="help-block task-file-1-error task-file-error"></div>
                              </div>
                              <div class="col-md-5">
                                <button class="btn btn-sm btn-primary add_task_files">{{ trans('ticketingtool.more_attachments') }}</button>
                              </div>
                            <div class="help-block taskFile-error"></div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                            <button type="button" class="btn btn-success add_taskList_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
                          </div>
                        </div>
                      {{ Form::close() }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                  <div class="task-sidebar">
                  @if (!empty($taskLists))
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="task-list task-list-heading" style=<?php echo ($taskLists->isEmpty()) ? "display:none" : ''?>>
                        <h5>{{ trans('ticketingtool.Task_Lists') }}</h5>
                          @foreach ($taskLists as $key => $taskList)
                            <?php
                            $subTaskCount = 0;
                            $subTaskCount = getSubTaskCount($taskList->id);
                            ?>
                          <p>{{$taskList->task_group}}&nbsp(<span  id="task-count-{{ $taskList->id }}">{{$subTaskCount}}</span>)</p>
                          @endforeach
                        </div>
                      </div>
                    @endif
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="task-list task-list-assignees">
                        <h5>{{ trans('ticketingtool.Assignees') }}</h5>
                          @if (!empty($projectMembers['members']))
                            <?php $unassigned = 0; ?>
                            @foreach ($projectMembers['members'] as $keys => $projectMember)
                            <?php
                            $membersTask = getMembersTask($projects->id, $projectMember->user_id);
                            ?>
                              @if($membersTask != 0)
                              <p class="assignees">{{ ucfirst($projectMember->name) }}&nbsp(<span id="task-assigned-for-{{$projectMember->user_id}}">{{$membersTask}}</span>)</p>
                              @endif
                            @endforeach
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12" onclick="viewCompletedTask({{$projects->id}})">
                        <div class="task-list">
                          <a href="#"><h4 style="font-size:13px!important;">{{ trans('ticketingtool.completed_tasks') }} (<span id="completed-tasks">{{ getCompletedtasksNo($projects->id) }}</span>)</h4></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="Discussion" class="tab-pane promotiontabs fade" name="discussion" role="tabpanel">
            <div class="panel-wrapper collapse in">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">{{ trans('ticketingtool.discussion') }}</h6>
                </div>
                <div class="pull-right">
                  <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addEmail"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
                </div>
              <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <div id="Files" class="tab-pane promotiontabs fade" name="files" role="tabpanel">
            <div class="panel-wrapper collapse in">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">{{ trans('ticketingtool.files') }}</h6>
                </div>

                <div class="pull-right">
                  <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addFiles"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
                </div>

              <div class="clearfix"></div>
              </div>
              <div id="project-file-div-id" class="imgblog">
                <div class="row">
                </div>
              </div>
            </div>
          </div>
          <div id="addFiles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog w-500">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h5 class="modal-title">{{ trans('ticketingtool.add_project_file') }}</h5>
                </div>
                <div class="modal-body">
                {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_projectFile_form')) }}
                  <input type="hidden" name="project_id" value="{{$projects->id}}">
                  <div class="form-group">
                    <div class="col-xs-12 col-sm-12">
                        <label for="{{ trans('ticketingtool.attachments') }}" class="control-label">{{ trans('ticketingtool.attachments') }}<sup class="mandatory">*</sup></label>
                    </div>
                      <div class="col-6 col-xs-12 input_fields_container_doc_project">
                        <div class="article-first-img multiple-file-wrap">
                          <input type="file" name="attachments[]" class="multiple-file remove-lead-file general-file" id="general-file-1">
                        </div>
                        <a href="#" class="remove-first" style="margin-left:10px; right: 82px;">Remove</a>
                        <div class="help-block general-file-1-error general-file-error"></div>
                      </div>

                      <div class="col-12 col-sm-12">
                          <div class="help-block attachments-error file_error" style="margin-bottom:10px;" id="attachments"></div>
                        <button class="btn btn-sm btn-primary add_more_project_file_doc">{{ trans('ticketingtool.add_more') }}</button>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                    <button type="button" class="btn btn-success add_projectFile_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
                  </div>
                </div>
              {{ Form::close() }}
              </div>
            </div>
          </div>
          <div id="viewCompletedTask" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog w-500">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h5 class="modal-title">{{ trans('ticketingtool.completed_tasks') }}</h5>
                </div>
                <div class="modal-body">
                  <div class="row completed-task-div" id="completed-task-div">

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="Access" class="tab-pane promotiontabs fade" name="activity" role="tabpanel">
            <div class="panel-wrapper collapse in">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">{{ trans('ticketingtool.access') }}</h6>
                </div>
                <div class="pull-right">
                  <button class="btn btn-primary btn-anim" data-toggle="modal" data-target="#addAccess"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
                </div>
              <div class="clearfix"></div>
            </div>
            <input type="hidden" id="project_id" value="{{ $projects['id'] }}">
            <div class="panel-group accordion-struct accordion-style-1 project-access-div" id="accordion_2" role="tablist" aria-multiselectable="true">
            </div>
          </div>
          </div>
          <div id="addAccess" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:800px;">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h5 class="modal-title">{{ trans('ticketingtool.add_project_access') }}</h5>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="panel-wrapper collapse in">
                      <div class="panel-body pa-0">
                        <div class="modal-body">
                        {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_projectAccess_form')) }}
                        <input type="hidden" name="project_id" value="{{ $projects['id'] }}">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-6">
                                <label for="{{ trans('ticketingtool.git_info') }}" class="control-label">{{ trans('ticketingtool.task_group') }}<sup class="mandatory">*</sup></label>
                                <input type="text" class="form-control" name="task_group">
                                <div class="help-block task_group-error"></div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-12">
                                <label for="{{ trans('ticketingtool.git_info') }}" class="control-label">{{ trans('ticketingtool.git_url') }}</label>
                                <input type="text" class="form-control" name="git_url">
                                <div class="help-block git_url-error"></div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.server_details') }}</label>
                                <textarea rows="5" class="form-control form-control-static" name="server" maxlength="800"></textarea>
                              </div>
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.domains') }}</label>
                                <textarea rows="5" class="form-control form-control-static" name="domains" maxlength="800"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.database') }}</label>
                                <textarea rows="5" class="form-control form-control-static" name="database" maxlength="800"></textarea>
                              </div>
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.backend') }}</label>
                                <textarea rows="5" class="form-control form-control-static" name="backend" maxlength="800"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-6">
                                <label class="control-label">{{ trans('ticketingtool.additional_info') }}</label>
                                <textarea rows="5" class="form-control form-control-static" name="additional_info" maxlength="800"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                            <button type="button" class="btn btn-success add_projectAccess_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
                          </div>
                        {{ Form::close() }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel-footer footer-trans mb-10">
            <div class="text-left inline-block">
              <a href="{{ asset('/projects') }}">
                <button class="btn btn-default btn-anim"><i class="fa fa-angle-left"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.btn_back') }}</span></button>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
