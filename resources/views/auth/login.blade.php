@extends('layout.loginApp')

@section('content')
<div class="container">
	<div class="table-struct full-width full-height">
		<div class="table-cell vertical-align-middle auth-form-wrap">
			<div class="row">
		        <div class="col-md-12">
					<div class="logo-wrap text-center">
						<img src="{{ asset('images/logo-dark.svg') }}" class="img-responsive"></img>
					</div>
		            <div class="card">
		                <div class="card-body">
							<form method="#" id="login-form">
		                        @csrf
		                        <div class="form-group">
		                            <div class="row">
		                                <div class="col-sm-3">
		                                    <label class="control-label " for="example-input-small">{{ __('E-Mail') }}</label>
		                                </div>
		                                <div class="col-sm-9">
		                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>
		                                    <span class="help-block" id="email-error"></span>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <div class="row">
		                                <div class="col-sm-3">
		                                    <label class="control-label " for="example-input-small">{{ __('Password') }}</label>
		                                </div>
		                                <div class="col-sm-9">
		                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
		                                    <span class="help-block" id="password-error"></span>
		                                </div>
		                            </div>
		                        </div>
		                        <!-- <div class="form-group">
		                            <div class="row">
		                                <div class="col-xs-6">
		                                    <div class="checkbox checkbox-danger pr-10 pull-left">
		                                        <input type="hidden" name="remember_me" value="0">
		                                        <input id="checkbox_2" type="checkbox" name="remember_me" value="1">
		                                        <label for="checkbox_2">Keep me logged in</label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-6 text-right">
		                                    <a href="/password/reset" class="txt-light forgot-password">forgot password</a>
		                                </div>
		                            </div>
		                        </div> -->
		                        <div class="form-group">
		                            <div class="row">
		                                <div class="col-xs-12 text-center">
		                                    <button type="button" class="btn btn-rounded login">Sign In</button>
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
</div>
@endsection
