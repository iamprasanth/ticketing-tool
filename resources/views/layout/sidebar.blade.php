<!-- Left Sidebar Menu -->
<div class="fixed-sidebar-left">
	<ul class="nav navbar-nav side-nav nicescroll-bar">
		<!-- <li class="treeview ">
            <a href="/myprofile">
                <div class="pull-left">
                    <i class="fa fa-user mr-5" aria-hidden="true"></i>
                    <span class="right-nav-text">My Profile</span>
                </div>
                <div class="clearfix"></div>
            </a>
		</li> -->
		<li class="treeview {{ areActiveRoutes(['backend.users']) ? 'active' : '' }}">
            <a href="/users">
                <div class="pull-left">
                    <i class="fa fa-users mr-5" aria-hidden="true"></i>
                    <span class="right-nav-text">Users</span>
                </div>
                <div class="clearfix"></div>
            </a>
        </li>
		<li class="treeview {{ areActiveRoutes(['backend.projects.list', 'backend.projects.view']) ? 'active' : '' }}">
            <a href="/projects">
                <div class="pull-left">
                    <i class="fa fa-bookmark mr-5" aria-hidden="true"></i>
                    <span class="right-nav-text">Projects</span>
                </div>
                <div class="clearfix"></div>
            </a>
		</li>
		<li class="treeview {{ areActiveRoutes(['backend.tickets.list', 'backend.tickets.view']) ? 'active' : '' }}">
            <a href="/tickets">
                <div class="pull-left">
                    <i class="fa fa-ticket mr-5" aria-hidden="true"></i>
                    <span class="right-nav-text">{{ trans('ticketingtool.tickets') }}</span>
                </div>
                <div class="clearfix"></div>
            </a>
        </li>
		@if (Auth::user()->role_id == 1)
			<?php $settingsTabActive = areActiveRoutes(['backend.ticketcategory.list', 'backend.ticket-status', 'backend.tasklabels.list', 'backend.projectlabels.list', 'backend.projectcategories.list']) ?>
			<li class="treeview {{ $settingsTabActive ? 'active' : '' }}">
	            <a class="active" href="javascript:void(0);" data-toggle="collapse" data-target="#settings_tab">
	                <div class="pull-left">
	                    <i class="fa fa-cogs mr-5" aria-hidden="true"></i>
	                    <span class="right-nav-text">{{ trans('ticketingtool.settings') }}</span>
	                </div>
	                <div class="pull-right">
	                    <i class="zmdi zmdi-caret-down"></i>
	                </div>
	                <div class="clearfix"></div>
	            </a>
	            <ul id="settings_tab" class="collapse collapse-level-1 {{ $settingsTabActive ? 'active in' : '' }}">
	                <li class="treeview {{ areActiveRoutes(['backend.projectcategories.list']) ? 'active' : '' }}">
	                    <a class="active-page" href="{{ asset('/project-category') }}">
	                        <i class="fa fa-file-text-o mr-5" aria-hidden="true"></i>{{ trans('ticketingtool.project_category') }}
	                    </a>
	                </li>

		            <li class="treeview {{ areActiveRoutes(['backend.projectlabels.list']) ? 'active' : '' }}">
		                <a class="active-page" href="{{ asset('/project-label') }}">
		                  <i class="fa fa-circle-o-notch mr-5" aria-hidden="true"></i>{{ trans('ticketingtool.project_label') }}
		                </a>
		            </li>

	                <li class="treeview {{ areActiveRoutes(['backend.tasklabels.list']) ? 'active' : '' }}">
	                    <a class="active-page" href="{{ asset('/task-label') }}">
	                        <i class="fa fa-tasks mr-5" aria-hidden="true"></i>{{ trans('ticketingtool.task_label') }}
	                    </a>
	                </li>
					<li class="treeview {{ areActiveRoutes(['backend.ticketcategory.list']) }}">
	                    <a class="active-page" href="{{ asset('/ticket-category') }}">
	                        <i class="fa fa-th-list mr-5" aria-hidden="true"></i>{{ trans('ticketingtool.ticket_category') }}
	                    </a>
	                </li>
					<li class="treeview {{ areActiveRoutes(['backend.ticket-status']) }}" >
	                    <a class="active-page" href="{{ asset('/ticket-status') }}">
	                        <i class="fa fa-certificate mr-5" aria-hidden="true"></i>{{ trans('ticketingtool.ticket_status') }}
	                    </a>
	                </li>
	            </ul>
        	</li>
		@endif
	</ul>
</div>
<!-- /Left Sidebar Menu -->
