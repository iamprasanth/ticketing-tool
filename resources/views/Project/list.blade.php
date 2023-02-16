@extends('layout.app',['title' => 'project_list'])
@section('content')
<?php $isAdmin = (Auth::user()->role_id == 1) ? 1 : 0; ?>
<div class="row mt-30" id="ticketingtool-projects">
    <div class="col-md-12">
        <div class="panel panel-default project-view card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5 class="panel-title txt-dark">{{ trans('ticketingtool.projects') }}</h5>
                </div>
                <div class="pull-right">
                  	<button class="btn btn-primary btn-pink btn-anim" id="completed-projects-btn" style="margin-right:10px;"><i class="fa fa-eye"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.completed_projects') }}</span></button>
                    @if ($isAdmin)
                        <button class="btn btn-primary btn-pink btn-anim" data-toggle="modal" data-target="#addProject"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.start_new_projects') }}</span></button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
					<div  class="tab-struct custom-tab-2">
						<ul role="tablist" class="nav nav-tabs pull-right nav-project" id="myTabs_8">
							<li class="active card" role="presentation">
								<a class="projects-align"><span class="glyphicon glyphicon-th"></span></a>
							</li>
							<li role="presentation" class="list">
								<a><span class="glyphicon glyphicon-menu-hamburger"></span></a>
							</li>
						</ul>
						<div>
						</div>
					</div>
                </div>
            </div>
	         <div id="addProject" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:800px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h5 class="modal-title">{{ trans('ticketingtool.add_project') }}</h5>
                        </div>
                        <div class="modal-body">
                          {{ Form::open(array('url'=>'#', 'method'=>'POST', 'id' => 'add_project_form')) }}
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label class="control-label">{{ trans('ticketingtool.project_manager') }}<sup class="mandatory">*</sup></label>
										<select class="select2" data-style="form-control" name="project_manager">
											<option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                            @if ($users)
                                                @foreach ($users as $user)
    				                                <option value="{{ $user['id'] }}">{{ $user['get_user_name']['user'] }}</option>
                                                @endforeach
                                            @endif
										</select>
										<div class="help-block project_manager-error"></div>
									</div>
                                    <div class="col-md-6">
                                        <label for="{{ trans('ticketingtool.project') }}" class="control-label">{{ trans('ticketingtool.project_name') }}<sup class="mandatory">*</sup></label>
                                        <input type="text" class="form-control" name="project_name" id="project_name">
                                        <div class="help-block project_name-error"></div>
                                    </div>
            					</div>
    					    </div>
                            <div class="form-group">
                                <div class="row">
  		                            <div class="col-sm-6">
                                        <div class="project-wrap-color">
                                            <label class="control-label">{{ trans('ticketingtool.label') }}</label>
        									<select id="project_label" class="select2" data-style="form-control" name="project_label">
        										<option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                                @if ($projectLabels)
                                                    @foreach ($projectLabels as $projectLabel)
					                                    <option value="{{ $projectLabel['id'] }}" name="{{ $projectLabel['name'] }}">{{ $projectLabel['name'] }}</option>
                                                    @endforeach
                                                @endif
        									</select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">{{ trans('ticketingtool.category') }}</label>
    									<select id="ticketingtool_project_category" class="select2" data-style="form-control" name="project_category">
    										<option value="0">{{ trans('ticketingtool.please_select') }}</option>
                                            @if ($projectLabels)
                                                @foreach ($projectCategories as $projectCategory)
    										        <option value="{{ $projectCategory['id'] }}">{{ $projectCategory['name'] }}</option>
                                                @endforeach
                                            @endif
    									 </select>
                                    </div>
                                </div>
                            </div>
			                <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="{{ trans('ticketingtool.description') }}" class="control-label">{{ trans('ticketingtool.description') }}</label>
                                        <textarea class="form-control" rows="4" name="description" id="project_decription" maxlength="250"></textarea>
                                        <div class="help-block description-error"></div>
                                    </div>
                                </div>
                            </div>
			                <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="{{ trans('ticketingtool.client_company') }}" class="control-label">{{ trans('ticketingtool.client_company') }}</label>
                                        <input type="text" class="form-control" name="client_company" id="client_company">
                                        <div class="help-block client_company-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <div class="col-sm-12">
                                    <label class="control-label">{{ trans('ticketingtool.project_members') }}</label>
                                       <select id="project_members" class="multi-select" multiple data-style="form-control" name="projectMembers[]">
                                            @if ($users)
                                                @foreach ($users as $user)
                                                    <option value="{{ $user['id'] }}">{{ $user['get_user_name']['user'] }}</option>
                                                @endforeach
                                            @endif
                                       </select>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="{{ trans('ticketingtool.additional_info') }}" class="control-label">{{ trans('ticketingtool.additional_info') }}</label>
                                        <textarea class="form-control" rows="4" name="additional_info" maxlength="400"></textarea>
                                        <div class="help-block additional_info-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
                                <button type="button" class="btn btn-success add_project_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
                            </div>
	                     {{ Form::close() }}
                    	</div>
                	</div>
            	</div>
        	</div>
          <div id="viewCompletedProjects" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                           <h5 class="modal-title">{{ trans('ticketingtool.completed_projects') }}</h5>
                      </div>
                      <div class="modal-body">
                        <table id="comleted-projects-table" class="display compact nowrap table-width" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th class="all">{{ trans('ticketingtool.id') }}</th>
                              <th class="desktop tablet-l">{{ trans('ticketingtool.project_name') }}</td>
                              <th class="desktop tablet-l">{{ trans('ticketingtool.client_company') }}</td>
                              <th class="desktop tablet-l">{{ trans('ticketingtool.completed_on') }}</td>
                              <th class="desktop tablet-l">{{ trans('ticketingtool.operations') }}</td>
                            </tr>
                          </thead>
                        </table>
                    </div>
                </div>
            </div>
         </div>
		<div class="project-grid column-view">
			<div class="row project-row">
                @foreach ($userProjects as $project)
				<div class="col-xs-12 col-sm-4 col-lg-3 project-view-wrap" id="project-{{ $project['id'] }}">
                    <div class="project-card">
    					<a href="/projects/view/{{ $project['id'] }}" class="project-link" data-id="{{ $project['id'] }}"></a>
						<div class="project-grid-view">
                            <div class="row">
                                <div class="col-xs-10">
                  					<h4>{{ $project['name'] }}</h4>
                                </div>
                                <div class="col-xs-2">
                                    <div class="dropdown pull-right">
                                        <span class="edit-task_group dropdown-toggle fa fa-ellipsis-v" data-toggle="dropdown">
                                        </span>
                                        <ul class="dropdown-menu">
                                            <li><a onclick="completeProject({{$project['id']}})">{{ trans('ticketingtool.complete') }}</a></li>
                                            <li><a onclick="deleteProject({{$project['id']}})">{{ trans('ticketingtool.btn_delete') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="pro-view-p">
      							@if($project['client_company'])
      								<p style="word-break: break-all;">for <span><?php $clientcompany = strlen($project['client_company']) > 35 ? substr($project['client_company'],0,35)."..." : $project['client_company'];?>{{$clientcompany}}</span></</p>
      							@endif
                                <p><?php $projectDescription = strlen($project['description']) > 150 ? substr($project['description'],0,150)."..." : $project['description'];?>{{$projectDescription}}</p>
                            </div>
                            @if($project['get_project_label'])
    					        <div class="btn-project">
    								<p>{{ $project['get_project_label']['name'] }}</p>
    							</div>
    						@endif
                        </div>
    		        </div>
			    </div>
                @endforeach
		    </div>
    	</div>
	</div>
</div>
@endsection
