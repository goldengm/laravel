@extends('layout')
@section('content')

<section class="site-content">
	<div class="container">
    	<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Shipping Address')</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item active">@lang('website.Shipping Address')</li>
                </ol>
            </div>
        </div>
        <div class="my-shipping-area">
            <div class="row"> 
               	<div class="col-12 col-lg-3 spaceright-0">
                    @include('common.sidebar_account')
                </div>
            	<div class="col-12 col-lg-9 my-shipping">
                	<div class="col-12 spaceright-0">
                        <div class="heading">
                            <h2>@lang('website.Shipping Address')</h2>
                            <hr>
                        </div>
                        <div class="row">
                
                            <div class="col-12">
                            	@if(!empty($result['action']) and $result['action']=='detele')
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        @lang('website.Your address has been deteled successfully')
                                        
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                @endif
                
                                @if(!empty($result['action']) and $result['action']=='default')
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        @lang('website.Your address has been chnaged successfully')	
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>					
                                    </div>
                                @endif
                                
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                      <thead>
                                        <tr>
                                          <th scope="col" align="left">@lang('website.Default')</th>
                                          <th scope="col" align="left" width="65%">@lang('website.Address Info')</th>
                                          <th scope="col" align="left">@lang('website.Action')</th>
                                        </tr>
                                      </thead>
                                      <tbody class="table-default">
                                      @if(!empty($result['address']) and count($result['address'])>0)
                                      @foreach($result['address'] as $address_data)
                                        <tr>
                                          <td scope="row" align="center" valign="center"><input class="form-control default_address" address_id="{{$address_data->address_id}}" type="radio" name="default" @if($address_data->default_address == $address_data->address_id) checked @endif></td>
                                          <td align="left">{{$address_data->firstname}}, {{$address_data->lastname}}, {{$address_data->street}}, {{$address_data->city}}, {{$address_data->zone_name}}, {{$address_data->country_name}}, {{$address_data->postcode}}</td>
                                          <td align="left"><a class="badge badge-light" href="{{ URL::to('/shipping-address?address_id='.$address_data->address_id)}}"><i class="fa fa-pencil" aria-hidden="true"></i> </a>
                                          @if($address_data->default_address != $address_data->address_id) 
                                          <a href="#" class="badge badge-danger deleteMyAddress" address_id ="{{ $address_data->address_id }}"><i class="fa fa-trash" aria-hidden="true"></i></a> @endif
                                          </td>
                                        </tr>
                                     @endforeach
                                     @else
                                     	<tr>
                                          <td valign="center">@lang('website.Shipping addresses are not added yet')</td>
                                        </tr>
                                     @endif
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    	
                        <hr class="featurette-divider">
                        
                        <div class="row">
                        	<div class="col-12">
                                    <h5 class="title-h5">@if(!empty($result['editAddress'])) @lang('website.Edit Address') @else @lang('website.Add Address') @endif </h5>
                                    <hr class="featurette-divider">
                                    <form name="addMyAddress" class="form-validate" enctype="multipart/form-data" action="@if(!empty($result['editAddress'])) {{ URL::to('/update-address')}} @else {{ URL::to('/addMyAddress')}} @endif  " method="post">
                                     @if(!empty($result['editAddress']))
                                     <input type="hidden" name="address_book_id" value="{{$result['editAddress'][0]->address_id}}">
                                     @endif
                                         @if( count($errors) > 0)
                                            @foreach($errors->all() as $error)
                                                <div class="alert alert-danger" role="alert">
                                                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                      <span class="sr-only">@lang('website.Error'):</span>
                                                      {{ $error }}
                                                </div>
                                             @endforeach
                                        @endif
                                       @if(session()->has('error'))
                                        <div class="alert alert-success">
                                            {{ session()->get('error') }}
                                        </div>
                                    @endif
                                        @if(Session::has('error'))
                                            
                                            <div class="alert alert-danger" role="alert">
                                                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                  <span class="sr-only">@lang('website.Error'):</span>
                                                  {{ session()->get('error') }}
                                              </div>
                                        
                                        @endif
                                        
                                        @if(Session::has('error'))
                                            <div class="alert alert-danger" role="alert">
                                                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                  <span class="sr-only">@lang('website.Error'):</span>
                                                  {!! session('loginError') !!}
                                            </div>
                                        @endif
                                        
                                        @if(session()->has('success') )
                                            <div class="alert alert-success">
                                                {{ session()->get('success') }}
                                            </div>
                                        @endif
                                     
                                       @if(!empty($result['action']) and $result['action']=='update')
                                            <div class="alert alert-success">
                                                
                                                @lang('website.Your address has been updated successfully')
                                            </div>
                                        @endif
                                        
                                        <div class="form-group row">
                                            <label for="entry_firstname" class="col-sm-4 col-form-label">@lang('website.First Name')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="entry_firstname" class="form-control field-validate" id="entry_firstname" @if(!empty($result['editAddress'])) value="{{$result['editAddress'][0]->firstname}}" @endif>
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span> 
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="entry_lastname" class="col-sm-4 col-form-label">@lang('website.Last Name')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="entry_lastname" class="form-control field-validate" id="entry_lastname" @if(!empty($result['editAddress'])) value="{{$result['editAddress'][0]->lastname}}" @endif>
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="entry_street_address" class="col-sm-4 col-form-label">@lang('website.Address')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="entry_street_address" class="form-control field-validate" id="entry_street_address" @if(!empty($result['editAddress'])) value="{{$result['editAddress'][0]->street}}" @endif>
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="entry_country_id" class="col-sm-4 col-form-label">@lang('website.Country')</label>
                                            <div class="col-sm-8">
                                                <select name="entry_country_id" onChange="getZones();" id="entry_country_id" class="form-control field-validate">
                                                    <option value="">@lang('website.select Country')</option>
                                                    @foreach($result['countries'] as $countries)
                                                    <option value="{{$countries->countries_id}}" @if(!empty($result['editAddress'])) @if($countries->countries_id==$result['editAddress'][0]->countries_id) selected @endif @endif>{{$countries->countries_name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="entry_zone_id" class="col-sm-4 col-form-label">@lang('website.State')</label>
                                            <div class="col-sm-8">
                                                <select name="entry_zone_id" id="entry_zone_id" class="form-control field-validate">
                                                    <option value="">@lang('website.Select Zone')</option>
                                                    @if(!empty($result['zones']))
                                                    @foreach($result['zones'] as $zones)
                                                    <option value="{{$zones->zone_id}}" @if(!empty($result['editAddress'])) @if($zones->zone_id==$result['editAddress'][0]->zone_id) selected @endif @endif>{{$zones->zone_name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="entry_city" class="col-sm-4 col-form-label">@lang('website.City')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="entry_city" class="form-control field-validate" id="entry_city" @if(!empty($result['editAddress'])) value="{{$result['editAddress'][0]->city}}" @endif>
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label for="entry_postcode" class="col-sm-4 col-form-label">@lang('website.Zip/Postal Code')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="entry_postcode" class="form-control field-validate" id="entry_postcode" @if(!empty($result['editAddress'])) value="{{$result['editAddress'][0]->postcode}}" @endif>
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                                            </div>
                                        </div>	 
                                        
                                        <div class="button">
                                        @if(!empty($result['editAddress']))
                                            <a href="{{ URL::to('/myAddress')}}" class="btn btn-default">@lang('website.cancel')</a>
                                        @endif
                                            <button type="submit" class="btn btn-dark">@if(!empty($result['editAddress']))  @lang('website.Update')  @else @lang('website.Add Address') @endif </button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
        </div>
	</div>
</section>	
		
@endsection 	


