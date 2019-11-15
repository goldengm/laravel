@extends('layout')
@section('content')
<section class="site-content">
<div class="container">
<div class="breadcum-area">
    <div class="breadcum-inner">
        <h3>@lang('website.Signup')</h3>
        <ol class="breadcrumb">
            
            <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
            <li class="breadcrumb-item active">@lang('website.Signup')</li>
        </ol>
    </div>
</div>

<div class="registration-area">

        <div class="heading">
            <h2>@lang('website.Create An Account')</h2>
            <hr>
        </div>
		<div class="row">
			<div class="col-12 col-md-6 col-lg-7 new-customers">
				<h5 class="title-h5">@lang('website.Personal Information')</h5>
				<!-- <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p> -->

				<hr class="featurette-divider">
				@if( count($errors) > 0)
					@foreach($errors->all() as $error)
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">@lang('website.Error'):</span>
                            {{ $error }}
                          	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
						</div>
					 @endforeach
				@endif

				@if(Session::has('error'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">@lang('website.Error'):</span>
						  {!! session('error') !!}
                          
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
					</div>
				@endif

				@if(Session::has('success'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">@lang('website.Success'):</span>
						  {!! session('success') !!}
                          
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          		<span aria-hidden="true">&times;</span>
                          </button>
					</div>
				@endif

				<form name="signup" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/signupProcess')}}" method="post">
                
                	<div class="form-group row justify-content-center">
						
                        <div class="uploader">
                        	<h5 class="title-h5">@lang('website.Upload Profile Photo')</h5>
                            
                            
                            <div class="upload-picture">
                                <div class="uploaded-image" id="uploaded_image"></div>
                                <img class="upload-choose-icon" src="{{asset('').'public/images/default.png'}}" />
                                <div class="upload-choose-icon">
                                	<input name="picture" id="userImage" type="file" class="inputFile" onChange="showPreview(this);" />
                                </div>
                            </div>


                        </div>
						<!--<label for="picture" class="col-sm-4 col-form-label">@lang('website.Picture')</label>
						<div class="col-sm-8">
							<input type="file" class="form-control-file" name="picture" id="picture">
						</div>-->

					</div>
                    
					<div class="form-group row">
						<label for="staticEmail" class="col-sm-4 col-form-label"><strong>*</strong>@lang('website.First Name')</label>
						<div class="col-sm-8">
							<input type="text" name="firstName" id="firstName" class="form-control field-validate" value="{{ old('firstName') }}">
							<span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span> 
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-4 col-form-label"><strong>*</strong>@lang('website.Last Name')</label>
						<div class="col-sm-8">
							<input type="text" name="lastName" id="lastName" class="form-control field-validate"  value="{{ old('lastName') }}">
							<span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
						</div>
					</div>
					<hr class="featurette-divider">
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-4 col-form-label"><strong>*</strong>@lang('website.Email Adrress')</label>
						<div class="col-sm-8">
							<input type="text" name="email" id="email" class="form-control email-validate" value="{{ old('email') }}">
							<span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="inlineFormCustomSelect" class="col-sm-4 col-form-label"><strong>*</strong>@lang('website.Gender')</label>
						<div class="col-sm-8">
							<select class="custom-select field-validate" name="gender" id="inlineFormCustomSelect">
								<option selected value="">@lang('website.Choose...')</option>
								<option value="0" @if(!empty(old('gender')) and old('gender')==0) selected @endif)>@lang('website.Male')</option>
								<option value="1" @if(!empty(old('gender')) and old('gender')==1) selected @endif>@lang('website.Female')</option>
							</select>
							<span class="help-block error-content" hidden>@lang('website.Please select your gender')</span>
						</div>
					</div>
					

					<div class="form-group row">
						<label for="inputPassword4" class="col-sm-4 col-form-label"><strong>*</strong>@lang('website.Password')</label>
						<div class="col-sm-8">
							<input type="password" class="form-control password" name="password" id="password">
							<span class="help-block error-content" hidden>@lang('website.Please enter your password')</span>
						</div>
				  	
					</div>
					<div class="form-group row">					
						<label for="inputPassword5" class="col-sm-4 col-form-label"><strong>*</strong>@lang('website.Confirm Password')</label>
						<div class="col-sm-8 re-password-content">
							<input type="password" class="form-control password" name="re_password" id="re_password">
							<span class="help-block error-content" hidden>@lang('website.Please re-enter your password')</span>
							<span class="help-block error-content-password" hidden>@lang('website.Password does not match the confirm password')</span>
						</div>				  	
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"></label>
						<div class="col-sm-8">
							<div class="form-check checkbox-parent">
								<label class="form-check-label">
									<input class="form-check-input checkbox-validate" type="checkbox">@lang('website.Creating an account means you are okay with our')  @if(!empty($result['commonContent']['pages'][3]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][3]->slug)}}">@endif @lang('website.Terms and Services')@if(!empty($result['commonContent']['pages'][3]->slug))</a>@endif, @if(!empty($result['commonContent']['pages'][1]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][1]->slug)}}">@endif @lang('website.Privacy Policy')@if(!empty($result['commonContent']['pages'][1]->slug))</a> @endif and @if(!empty($result['commonContent']['pages'][2]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][2]->slug)}}">@endif @lang('website.Refund Policy') @if(!empty($result['commonContent']['pages'][3]->slug))</a>@endif.
								</label>
								<span class="help-block error-content" hidden>@lang('website.Please accept our terms and conditions')</span>
							</div>
                            
						</div>
					</div>
					<div class="button">
                    	<button type="submit" class="btn btn-dark pull-right">@lang('website.Sign Up')</button>
                    </div>
				</form>
			</div>
			<div class="col-12 col-md-6 col-lg-5 new-customers">
					<ul>
						<li>@lang('website.Lorem ipsum dolor sit amet, consectetur adipiscing elit')</li>
						<li>@lang('website.Duis at nisl luctus, malesuada diam non, mattis odio')</li>
						<li>@lang('website.Fusce porta neque at enim consequat, in vulputate tellus faucibus')</li>
						<br>
						<li>@lang('website.Pellentesque suscipit tortor id dui accumsan varius')</li>
						<li>@lang('website.Sed interdum purus imperdiet tortor imperdiet, et ultricies leo gravida')</li>
						<li>@lang('website.Aliquam pharetra urna vel nulla egestas, non laoreet mauris mollis')</li>
						<li>@lang('website.Integer sed velit sit amet quam pharetra ullamcorper')</li>
						<br>
						<li>@lang('website.Proin eget nulla accumsan, finibus lacus aliquam, tincidunt turpis')</li>
						<li>@lang('website.Nam at orci tempor, mollis mi ornare, accumsan risus')</li>
						<li>@lang('website.Cras vel ante vel augue convallis posuere')</li>
						<li>@lang('website.Ut quis dolor accumsan, viverra neque nec, blandit leo')</li>
					</ul>	
			</div>
		</div>
	</div>		
	</div>
   </section>
		
@endsection 	


