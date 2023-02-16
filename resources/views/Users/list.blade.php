@extends('layout.app',['title' => 'users_list'])
@section('content')
<div class="row mt-30">
    <div class="col-md-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5 class="panel-title txt-dark">{{ trans('ticketingtool.users') }}</h5>
                </div>
				@if(Auth::user()->role_id == 1)
                <div class="pull-right">
                    <button class="btn btn-primary btn-anim btn-pink" data-toggle="modal" data-target="#addUser"><i class="fa fa-plus"></i><span class="btn-text ie-jump">{{ trans('ticketingtool.add') }}</span></button>
                </div>
				@endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                   <table id="users-table" class="display compact nowrap table-width" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="all">{{ trans('ticketingtool.id') }}</th>
                            <th class="desktop tablet-l">{{ trans('ticketingtool.name') }}</th>
                            <th class="desktop tablet-l">{{ trans('ticketingtool.role') }}</th>
                            <th class="desktop tablet-l">{{ trans('ticketingtool.operations') }}</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="addUser" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			  <h5 class="modal-title" id="myModalLabel">{{ trans('ticketingtool.add_user') }}</h5>
			</div>
			<div class="modal-body">
				<!-- Row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="">
							<div class="panel-wrapper collapse in">
								<div class="panel-body pa-0">
									<div class="col-sm-12 col-xs-12">
										<div class="form-wrap">
											<form action="#" id="add_user_form" method="POST">
												<div class="row">
													<div class="col-xs-12 col-md-4">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.firstname') }}">{{ trans('ticketingtool.firstname') }}<sup class="mandatory">*</sup></label>
															<input type="text" class="form-control" name="first_name" value="" placeholder="{{ trans('ticketingtool.firstname') }}" maxlength="35">
															<div class="help-block first_name-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-4">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.middlename') }}">{{ trans('ticketingtool.middlename') }}</label>
															<input type="text" class="form-control" name="middle_name" value="" placeholder="{{ trans('ticketingtool.middlename') }}" maxlength="35">
															<div class="help-block middle_name-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-4">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.lastname') }}">{{ trans('ticketingtool.lastname') }}</label>
															<input type="text" class="form-control" name="last_name" value="" placeholder="{{ trans('ticketingtool.lastname') }}" maxlength="35">
															<div class="help-block last_name-error"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.email') }}">{{ trans('ticketingtool.email') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="email" value="" class="form-control" placeholder="{{ trans('ticketingtool.email') }}" maxlength="35">
															<div class="help-block email-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.dob') }}">{{ trans('ticketingtool.dob') }}</label>
															<input type="text" name="dateofbirth" id="" value="" class="form-control date-selector dateofbirth" placeholder="{{ trans('ticketingtool.dob') }}">
															<div class="help-block dateofbirth-error"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-sm-6">
														<div class="form-group">
															<label class="control-label">{{ trans('ticketingtool.gender') }}</label>
															<div class="radio-list">
																<div class="radio-inline pl-0">
																	<span class="radio radio-primary">
																		<input type="radio" name="gender" value="0" checked="checked">
																		<label for="gender">
																		{{ trans('ticketingtool.label_male') }}
																		</label>
																	</span>
																</div>
																<div class="radio-inline">
																	<span class="radio radio-info">
																		<input type="radio" name="gender" value="1">
																		<label for="gender">
																		{{ trans('ticketingtool.label_female') }}
																		</label>
																	</span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<label class="control-label">{{ trans('ticketingtool.role') }}</label>
														<select class="selectpicker" data-style="form-control" name="role_id">
															<option value="1">{{ trans('ticketingtool.admin') }}</option>
															<option value="2">{{ trans('ticketingtool.employee') }}</option>
															<option value="3">{{ trans('ticketingtool.client') }}</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label" for="{{ trans('ticketingtool.address') }}">{{ trans('ticketingtool.address') }}</label>
													<textarea name="address" wrap="hard" class="form-control" placeholder="{{ trans('ticketingtool.address') }}" rows="3" maxlength="250"></textarea>
													<div class="help-block address-error"></div>
												</div>
												<!-- <div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.city') }}">{{ trans('ticketingtool.city') }}</label>
															<input type="text" name="city" value="" class="form-control" placeholder="{{ trans('ticketingtool.city') }}" maxlength="35">
															<div class="help-block city-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.pincode') }}">{{ trans('ticketingtool.pincode') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="zip" value="" class="form-control" placeholder="{{ trans('ticketingtool.pincode') }}" maxlength="6">
															<div class="help-block zip-error"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.state') }}">{{ trans('ticketingtool.state') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="state" value="" class="form-control" placeholder="{{ trans('ticketingtool.state') }}" maxlength="35">
															<div class="help-block state-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.country') }}">{{ trans('ticketingtool.country') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="country" value="" class="form-control" placeholder="{{ trans('ticketingtool.country') }}" maxlength="35">
															<div class="help-block country-error"></div>
														</div>
													</div>
												</div> -->
												<div class="form-group">
													<div class="checkbox checkbox-info">
														<input class="temporary_address" name="temporary_address_checkbox" type="checkbox">
														<label for="temporary_address">
														{{ trans('ticketingtool.temporary_address_not_same') }}
														</label>
													</div>
												</div>
												<div class="temporary_address_form">
													<div class="form-group">
														<label class="control-label" for="{{ trans('ticketingtool.secondary_address') }}">{{ trans('ticketingtool.secondary_address') }}</label>
														<textarea name="temporary_address" wrap="hard" class="form-control" placeholder="{{ trans('ticketingtool.secondary_address') }}" rows="3" maxlength="250"></textarea>
														<div class="help-block temporary_address-error"></div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
														<label class="control-label" for="{{ trans('ticketingtool.mobile') }}">{{ trans('ticketingtool.mobile') }}</label>
														<input type="text" name="telephone" value="" class="form-control" placeholder="{{ trans('ticketingtool.mobile') }}" maxlength="12">
														<div class="help-block telephone-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
														<label class="control-label" for="{{ trans('ticketingtool.emergency_contact') }}">{{ trans('ticketingtool.emergency_contact') }}</label>
														<input type="text" name="emergency_contact" value="" class="form-control" placeholder="{{ trans('ticketingtool.emergency_contact') }}" maxlength="12">
														<div class="help-block emergency_contact-error"></div>
														</div>
													</div>
												</div>
												<div class="modal-footer mt-45">
													<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
													<button type="button" class="btn btn-success add_user_submit_button">{{ trans('ticketingtool.save') }}</button>
												</div>
											</form>
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
<div id="editUser" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			  <h5 class="modal-title" id="myModalLabel">{{ trans('ticketingtool.edit_user') }}</h5>
			</div>
			<div class="modal-body">
				<!-- Row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="">
							<div class="panel-wrapper collapse in">
								<div class="panel-body pa-0">
									<div class="col-sm-12 col-xs-12">
										<div class="form-wrap">
											<form action="#" id="edit_user_form" method="POST">
												<input type="hidden" name="id" id="edit_user_id" value="">
												<div class="row">
													<div class="col-xs-12 col-md-4">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.firstname') }}">{{ trans('ticketingtool.firstname') }}<sup class="mandatory">*</sup></label>
															<input type="text" class="form-control" name="first_name" id="edit_first_name" value="" placeholder="{{ trans('ticketingtool.firstname') }}" maxlength="35">
															<div class="help-block first_name-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-4">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.middlename') }}">{{ trans('ticketingtool.middlename') }}</label>
															<input type="text" class="form-control" name="middle_name" id="edit_middle_name" value="" placeholder="{{ trans('ticketingtool.middlename') }}" maxlength="35">
															<div class="help-block middle_name-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-4">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.lastname') }}">{{ trans('ticketingtool.lastname') }}</label>
															<input type="text" class="form-control" name="last_name" id="edit_last_name" value="" placeholder="{{ trans('ticketingtool.lastname') }}" maxlength="35">
															<div class="help-block last_name-error"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.email') }}">{{ trans('ticketingtool.email') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="email" value="" id="edit_email" class="form-control" placeholder="{{ trans('ticketingtool.email') }}" maxlength="35">
															<div class="help-block email-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.dob') }}">{{ trans('ticketingtool.dob') }}</label>
															<input type="text" name="dateofbirth" id="edit_dob" value="" class="form-control date-selector dateofbirth" placeholder="{{ trans('ticketingtool.dob') }}">
															<div class="help-block dateofbirth-error"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-sm-6">
														<div class="form-group">
															<label class="control-label">{{ trans('ticketingtool.gender') }}</label>
															<div class="radio-list">
																<div class="radio-inline pl-0">
																	<span class="radio radio-primary">
																		<input type="radio" id="male_radio" name="gender" value="0">
																		<label for="gender">
																		{{ trans('ticketingtool.label_male') }}
																		</label>
																	</span>
																</div>
																<div class="radio-inline">
																	<span class="radio radio-info">
																		<input type="radio" id="female_radio" name="gender" value="1">
																		<label for="gender">
																		{{ trans('ticketingtool.label_female') }}
																		</label>
																	</span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<label class="control-label">{{ trans('ticketingtool.role') }}<sup class="mandatory">*</sup></label>
														<select class="selectpicker" id="role_id" data-style="form-control" name="role_id">
															<option value="1">{{ trans('ticketingtool.admin') }}</option>
															<option value="2">{{ trans('ticketingtool.employee') }}</option>
															<option value="3">{{ trans('ticketingtool.client') }}</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label" for="{{ trans('ticketingtool.address') }}">{{ trans('ticketingtool.address') }}</label>
													<textarea name="address" id="edit_address" wrap="hard" class="form-control" placeholder="{{ trans('ticketingtool.address') }}" rows="3" maxlength="250"></textarea>
													<div class="help-block address-error"></div>
												</div>
												<!-- <div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.city') }}">{{ trans('ticketingtool.city') }}</label>
															<input type="text" name="city" value="" class="form-control" placeholder="{{ trans('ticketingtool.city') }}" maxlength="35">
															<div class="help-block city-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.pincode') }}">{{ trans('ticketingtool.pincode') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="zip" value="" class="form-control" placeholder="{{ trans('ticketingtool.pincode') }}" maxlength="6">
															<div class="help-block zip-error"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.state') }}">{{ trans('ticketingtool.state') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="state" value="" class="form-control" placeholder="{{ trans('ticketingtool.state') }}" maxlength="35">
															<div class="help-block state-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label" for="{{ trans('ticketingtool.country') }}">{{ trans('ticketingtool.country') }}<sup class="mandatory">*</sup></label>
															<input type="text" name="country" value="" class="form-control" placeholder="{{ trans('ticketingtool.country') }}" maxlength="35">
															<div class="help-block country-error"></div>
														</div>
													</div>
												</div> -->
												<div class="form-group">
													<div class="checkbox checkbox-info">
														<input class="edit_temporary_address" name="temporary_address_checkbox" type="checkbox">
														<label for="temporary_address">
														{{ trans('ticketingtool.temporary_address_not_same') }}
														</label>
													</div>
												</div>
												<div class="temporary_address_form">
													<div class="form-group">
														<label class="control-label" for="{{ trans('ticketingtool.secondary_address') }}">{{ trans('ticketingtool.secondary_address') }}</label>
														<textarea name="secondary_address" id="edit_secondary_address" wrap="hard" class="form-control" placeholder="{{ trans('ticketingtool.secondary_address') }}" rows="3" maxlength="250"></textarea>
														<div class="help-block temporary_address-error"></div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
														<label class="control-label" for="{{ trans('ticketingtool.mobile') }}">{{ trans('ticketingtool.mobile') }}</label>
														<input type="text" name="telephone"id="edit_primary_phone" value="" class="form-control" placeholder="{{ trans('ticketingtool.mobile') }}" maxlength="12">
														<div class="help-block telephone-error"></div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
														<label class="control-label" for="{{ trans('ticketingtool.emergency_contact') }}">{{ trans('ticketingtool.emergency_contact') }}</label>
														<input type="text" name="emergency_contact" id="edit_secondary_phone" value="" class="form-control" placeholder="{{ trans('ticketingtool.emergency_contact') }}" maxlength="12">
														<div class="help-block emergency_contact-error"></div>
														</div>
													</div>
												</div>
												<div class="modal-footer mt-45">
													<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketingtool.btn_cancel') }}</button>
													<button type="button" class="btn btn-success update_user_submit_button">{{ trans('ticketingtool.save') }}</button>
												</div>
											</form>
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
<div id="viewUsers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content network">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="ie-jump">×</span></button>
				<h5 class="modal-title" id="myModalLabel">View User</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel-wrapper collapse in">
							<div class="panel-body pa-0">
								<div class="table-user-information network">
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.name') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_name"></div></div>
									    </div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.role') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_role"></div></div>
									    </div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.email') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_email" ></div></div>
										</div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.dob') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_dob"></div></div>
										</div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.gender') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_gender"></div></div>
										</div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.address') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_address"></div></div>
										</div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.secondary_address') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div name="" id="view_secondary_address"></div></div>
										</div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.mobile') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div id="view_primary_phone"></div></div>
										</div>
									</div>
									<div class="row">
										<div class="user-view-wrap">
											<div class="col-xs-12 col-sm-5"><p><b>{{ trans('ticketingtool.emergency_contact') }}</b></p></div>
											<div class="col-xs-12 col-sm-7"><div id="view_secondary_phone"></div></div>
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
@endsection
