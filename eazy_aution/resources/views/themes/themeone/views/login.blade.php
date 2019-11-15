@extends('layout')
@section('content')
<section class="site-content">
<div class="container">
<div class="breadcum-area">
        <div class="breadcum-inner">
            <h3>@lang('website.Login')</h3>
            <ol class="breadcrumb">
                
                <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                <li class="breadcrumb-item active">@lang('website.Login')</li>
            </ol>
        </div>
    </div>
    
<div class="registration-area">
	<div class="heading">
        <h2>@lang('website.Login Or Create An Account')</h2>
        <hr>
    </div>

	<div class="row justify-content-center">
		<div class="col-12 col-md-6 col-lg-7 new-customers">
			<h5 class="title-h5">@lang('website.New Customers')</h5>
			<p>@lang('website.login page text for customer')</p>

			<hr class="featurette-divider">
			<p class="text-center">@lang('website.Dont have account') <a href="{{ URL::to('/signup')}}" class="btn btn-link ml-1"><b>@lang('website.Sign Up')</b></a></p>
			@if($result['commonContent']['setting'][2]->value==1 and $result['commonContent']['setting'][61]->value==1)
			<p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> @lang('website.or Sign in with'):</p>

			<div class="row my-3 d-flex justify-content-center">
				<!--Facebook-->
                @if($result['commonContent']['setting'][2]->value==1)
				<a href="login/facebook" class="btn btn-light facebook"><i class="fa fa-facebook"></i>@lang('website.Login with Facebook')</a>
                @endif
                @if($result['commonContent']['setting'][61]->value==1)
				<!--Google +-->
				<a href="login/google" class="btn btn-light google"><i class="fa fa-google-plus"></i>@lang('website.Login with Google')</a>
                @endif
			</div>
            @endif
		</div>

		<div class="col-12 col-md-6 col-lg-5 registered-customers">
            @if(Session::has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">@lang('website.Error'):</span>
                    {!! session('loginError') !!}
                    
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">@lang('website.success'):</span>
                    {!! session('success') !!}
                    
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            
			<h5 class="title-h5">
				@lang('website.Registered Customers')
			</h5>
			<p>@lang('website.If you have an account with us, please log in')</p>

			<form name="signup" enctype="multipart/form-data" class="form-validate"  action="{{ URL::to('/process-login')}}" method="post">
		
				<div class="form-group row">
					<label for="staticEmail" class="col-sm-3 col-form-label">@lang('website.Email')</label>
					<div class="col-sm-9">
						<input type="email" name="email" id="email" class="form-control email-validate">
						<span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="inputPassword" class="col-sm-3 col-form-label">@lang('website.Password')</label>
					<div class="col-sm-9">
						<input type="password" name="password" id="password" class="form-control field-validate">
						<span class="help-block error-content" hidden>@lang('website.This field is required')</span>
					</div>
				</div>

				<div class="button">
                	<a href="{{ URL::to('/forgotPassword')}}" class="btn btn-link ml-1 mr-4">@lang('website.Forgot Password')</a>
                	<button type="submit" class="btn btn-dark" style="min-width:90px;">@lang('website.Login')</button>
                </div>
				

				
			</form>

		</div>
	</div>
</div> 
</div>
</section>
	
@endsection 	


