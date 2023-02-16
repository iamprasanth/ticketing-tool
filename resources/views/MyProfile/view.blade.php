@extends('layout.app',['title' => 'Profile-View'])
@section('content')
<div class="row mt-30">
	<div class="col-lg-9 col-xs-12">
    	<div class="panel panel-default card-view pa-0">
	        <div class="panel-wrapper collapse in">
	            <div  class="panel-body pa-20 ">
	                <div class="form-wrap form-readonly">
	                    <form class="form-horizontal" role="form">
	                        <div class="form-body">
	                            <div class="form-group">
	                                <div class="row">
	                                    <div class="col-sm-4">
	                                        <label class="control-label">{{ trans('ticketingtool.firstname') }}</label>
	                                        <p class="form-control-static">{{$data['get_user_info']['first_name']}}</p>
	                                    </div>
	                                    <div class="col-sm-4">
	                                        <label class="control-label">{{ trans('ticketingtool.middlename') }}</label>
	                                        <p class="form-control-static">{{$data['get_user_info']['middle_name']}}</p>
	                                    </div>
	                                    <div class="col-sm-4">
	                                        <label class="control-label">{{ trans('ticketingtool.lastname') }}</label>
	                                        <p class="form-control-static">{{$data['get_user_info']['last_name']}}</p>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div class="row">
										<div class="col-sm-4">
	                                        <label class="control-label">{{ trans('ticketingtool.email') }}</label>
	                                        <p class="form-control-static">{{$data['email']}}</p>
										</div>
	                                    <div class="col-sm-4">
	                                        <label class="control-label">{{ trans('ticketingtool.gender') }}</label>
	                                        <p class="form-control-static">{{$data['get_user_info']['last_name'] == 0 ? trans('ticketingtool.label_male') : trans('ticketingtool.label_female')}}</p>
	                                    </div>
	                                    <div class="col-sm-4">
	                                        <label class="control-label">{{ trans('ticketingtool.dob') }}</label>
	                                        <p class="form-control-static">{{date("d.m.Y", strtotime($data['get_user_info']['date_of_birth'])) }}</p>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
									<div class="row">
										<div class="col-sm-6">
			                                <label class="control-label">{{ trans('ticketingtool.address') }}</label>
			                                <p class="form-control-static">{{$data['get_user_info']['address']}}</p>
										</div>
										@if($data['get_user_info']['secondary_address'])
										<div class="col-sm-6">
											<label class="control-label">{{ trans('ticketingtool.secondary_address') }}</label>
			                                <p class="form-control-static">{{$data['get_user_info']['secondary_address']}}</p>
										</div>
										@endif
									</div>
	                            </div>
	                            <div class="form-group">
	                                <div class="row">
	                                    <div class="col-sm-6">
	                                        <label class="control-label">{{ trans('ticketingtool.mobile') }}</label>
	                                        <p class="form-control-static">{{$data['get_user_info']['contact_no']}}</p>
	                                    </div>
	                                    <div class="col-sm-6">
	                                        <label class="control-label">{{ trans('ticketingtool.emergency_contact') }}</label>
	                                        <p class="form-control-static">{{$data['get_user_info']['secondary_contact_no']}}</p>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
    	</div>
	</div>
</div>
@endsection
