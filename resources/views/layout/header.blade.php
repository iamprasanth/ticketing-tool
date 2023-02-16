<!-- Top Menu Items -->
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="mobile-only-brand pull-left">
		<div class="nav-header pull-left">
			<div class="logo-wrap">
				<a href="{{ asset('/') }}" alt="logo">
					<!-- <img class="brand-img" src="../img/logo.png" alt="brand"/> -->
						<img src="{{ asset('images/logo-dark.svg') }}" class=""/>
				</a>
			</div>
		</div>
		<a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
		<a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-search"></i></a>
		<a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
	</div>
	<div id="mobile_only_nav" class="mobile-only-nav pull-right">
		<ul class="nav navbar-right top-nav pull-right header-user-menu">
			<li><p>Hi, {{ getUserName() }}</p></li>
			<li class="dropdown auth-drp">
				<a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown"><img src="{{ asset('images/personal.png') }}" alt="user_auth" class="user-auth-img img-circle"/><span class="user-online-status"></span></a>
				<ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
					<li>
						<a class="btn btn-default btn-flat" data-toggle="modal" data-target="#changePassword">
                               {{ trans('ticketingtool.change_password') }}
                    	</a>
					</li>
					<li>
						<a href="{{ url('/logout') }}" class="btn btn-default btn-flat"
                                  onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                                   Logout
                    	</a>
						<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                   {{ csrf_field() }}
                    	</form>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</nav>
<div id="changePassword" class="modal fade mt-10" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:400px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h5 class="modal-title">{{ trans('ticketingtool.change_password') }}</h5>
			</div>
			<div class="modal-body">
				{{ Form::open(array('id' => 'change_password_form')) }}
				<div class="form-group">
					<label for="{{ trans('sims.current_password') }}" class="control-label">{{ trans('ticketingtool.current_password') }}<sup class="mandatory">*</sup></label>
					<div class="input-group">
						<input type="password" class="form-control" name="current_password" id='current_password'>
						<span class="input-group-addon unhide" field="current_password"><i class="fa fa-eye-slash show-password" data-id="current_password"></i></span>
					</div>
					<div class="help-block current_password-error"></div>
				</div>
				<div class="form-group">
					<label for="{{ trans('sims.description') }}" class="control-label">{{ trans('ticketingtool.new_password') }}<sup class="mandatory">*</sup></label>
					<div class="input-group">
						<input type="password" class="form-control" name="new_password" id='new_password'>
						<span class="input-group-addon unhide" field="new_password"><i class="fa fa-eye-slash show-password" data-id="new_password"></i></span>
					</div>
					<div class="help-block new_password-error"></div>
				</div>
				<div class="form-group">
					<label for="{{ trans('sims.description') }}" class="control-label">{{ trans('ticketingtool.confirm_password') }}<sup class="mandatory">*</sup></label>
					<div class="input-group">
						<input type="password" class="form-control" name="confirm_password" id='confirm_password'>
						<span class="input-group-addon unhide" field="confirm_password"><i class="fa fa-eye-slash show-password" data-id="confirm_password"></i></span>
					</div>
					<div class="help-block confirm_password-error"></div>
				</div>
				{{ Form::close() }}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
				<button type="button" class="btn btn-success change_password_submit_btn">{{ trans('ticketingtool.btn_save') }}</button>
			</div>
		</div>
	</div>
</div>
<!-- /Top Menu Items -->
