@extends('layout')
@section('content')

<section class="site-content">
<div class="container">
	<div class="col-lg-12"><br>

	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
	  <li class="breadcrumb-item active">CheckOut</li>
	</ol>
	<br>
	
		<h4>Sign Up</h4>
		<form name="signup" enctype="multipart/form-data" action="{{ URL::to('/processSignup')}}" method="post">
			
			<div class="form-row">
				<div class="form-group col-md-12">
				  <label for="inputEmail4" class="col-form-label">Email</label>
				  <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
				</div>
				<div class="form-group col-md-12">
				  <label for="inputPassword4" class="col-form-label">Password</label>
				  <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
				</div>
			</div>
			
			<button type="submit" class="btn btn-primary">Login</button>
			<p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Sign in with:</p>

			<div class="row my-3 d-flex justify-content-center">
				<!--Facebook-->
				<button type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a"><i class="fa fa-facebook blue-text text-center"></i></button>

				<!--Google +-->
				<button type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fa fa-google-plus blue-text"></i></button>
			</div>
		</form>


		
	</div>
   </div>
 </section>	
		
@endsection 	


