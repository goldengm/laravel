@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>{{ trans('labels.PaymentSetting') }} <small>{{ trans('labels.PaymentSetting') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.PaymentSetting') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- Info boxes --> 
    
    <!-- /.row -->
    <div class="row">
      <div class="col-md-12">
        
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ trans('labels.PaymentSetting') }}</h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
              <div class="row">
                  <div class="col-xs-12">              		
                      @if (count($errors) > 0)
                          @if($errors->any())
                            <div class="alert alert-success alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              {{$errors->first()}}
                            </div>
                          @endif
                      @endif
                  </div>
                </div>
            <div class="row">
              <div class="col-xs-12">
              	  <div class="box box-info">
                        <!-- form start -->                        
                         <div class="box-body">
                            {!! Form::open(array('url' =>'admin/updatePaymentSetting', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            
                            <div class="form-group">
                           		<label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.PaymentMetods') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                          <input type="checkbox" name="brantree_active" id="brantree_active" value="1" class="checkboxess" @if($result['shipping_methods'][0]->brantree_active==1) checked @endif > &nbsp;{{ trans('labels.Brantree') }}
                                    </label><br>

                                    <label class=" control-label">
                                          <input type="checkbox" name="stripe_active" id="stripe_active" value="1" class="checkboxess" @if($result['shipping_methods'][0]->stripe_active==1) checked @endif > &nbsp;{{ trans('labels.Stripe') }}
                                    </label><br>

                                    
                                    <label class=" control-label">
                                          <input type="checkbox" name="cash_on_delivery" id="cash_on_delivery" value="1" class="checkboxess " @if($result['shipping_methods'][0]->cash_on_delivery==1) checked @endif > &nbsp;{{ trans('labels.CashOnDelivery') }}
                                    </label><br>

                                    
                                    <label class=" control-label">
                                          <input type="checkbox" name="paypal_status" id="paypal_status" value="1" class="checkboxess " @if($result['shipping_methods'][0]->paypal_status==1) checked @endif > &nbsp;{{ trans('labels.paypal') }}
                                    </label><br>
                                    <div style="display: none">
                                    <label class=" control-label">
                                          <input type="checkbox" name="cybersource_status" id="cybersource_status" value="1" class="checkboxess " @if($result['shipping_methods'][0]->cybersource_status==1) checked @endif > &nbsp;{{ trans('labels.cybersource') }}
                                    </label><br>
                                    
                                    
                                    </div>
                                    <label class=" control-label">
                                          <input type="checkbox" name="instamojo_active" id="instamojo_active" value="1" class="checkboxess " @if($result['shipping_methods'][0]->instamojo_active==1) checked @endif > &nbsp;{{ trans('labels.instamojo') }}
                                    </label><br>
                                    <label class=" control-label">
                                          <input type="checkbox" name="hyperpay_active" id="hyperpay_active" value="1" class="checkboxess " @if($result['shipping_methods'][0]->hyperpay_active==1) checked @endif > &nbsp;{{ trans('labels.Hyperpay') }}
                                    </label>
                                                                        
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PaymentMetodsText') }}</span>
                                </div>
                            </div>
                            <hr>
                            <h4>{{ trans('labels.Braintree') }}</h4>
                            <hr>
                            <div class="form-group">
                           		<label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.BraintreeAccountType') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                          <input type="radio" name="braintree_enviroment" value="0" class="flat-red" @if($result['shipping_methods'][0]->braintree_enviroment==0) checked @endif > &nbsp;{{ trans('labels.Sanbox') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
                                    <label class=" control-label">
                                          <input type="radio" name="braintree_enviroment" value="1" class="flat-red" @if($result['shipping_methods'][0]->braintree_enviroment==1) checked @endif >  &nbsp;{{ trans('labels.Live') }}
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.BraintreeAccountTypeText') }}</span>
                                </div>
                            </div>
                            
                             {!! Form::hidden('braintree_name',  $result['shipping_methods'][0]->braintree_name , array('class'=>'form-control', 'id'=>'braintree_name')) !!}
                                @foreach($result['braintree_description'] as $description_data)
                                    <div class="form-group">
                                      <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.braintreename') }} ({{ $description_data['language_name'] }})</label>
                                      <div class="col-sm-10 col-md-4">
                                        <input type="text" name="briantree_name_<?=$description_data['languages_id']?>" class="form-control brantree_active @if($result["shipping_methods"][0]->brantree_active==1) field-validate @endif" value="{{$description_data['name']}}">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.braintreename') }} ({{ $description_data['language_name'] }}).</span>          
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                      </div>
                                    </div>
                                     <div class="form-group">
                                      <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.braintreeCard') }}-{{ trans('labels.Braintree') }} ({{ $description_data['language_name'] }})</label>
                                      <div class="col-sm-10 col-md-4">
                                        <input type="text" name="sub_name_1_<?=$description_data['languages_id']?>" class="form-control brantree_active @if($result["shipping_methods"][0]->brantree_active==1) field-validate @endif" value="{{$description_data['sub_name_1']}}">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.braintreeCard') }}-{{ trans('labels.Braintree') }} ({{ $description_data['language_name'] }}).</span>          
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.paypal') }}-{{ trans('labels.Braintree') }} ({{ $description_data['language_name'] }})</label>
                                      <div class="col-sm-10 col-md-4">
                                        <input type="text" name="sub_name_2_<?=$description_data['languages_id']?>" class="form-control brantree_active @if($result["shipping_methods"][0]->brantree_active==1) field-validate @endif" value="{{$description_data['sub_name_2']}}">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.paypal') }}-{{ trans('labels.Braintree') }} ({{ $description_data['language_name'] }}).</span>          
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                      </div>
                                    </div>
                                     
                                 
                              	@endforeach
                             
							<div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantID') }}</label>
								<div class="col-sm-10 col-md-4">
                                    <input type="text" name="braintree_merchant_id" id="braintree_merchant_id" value="{{$result['shipping_methods'][0]->braintree_merchant_id}}" class="form-control brantree_active @if($result["shipping_methods"][0]->brantree_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.MerchantIDText') }}</span>
								</div>
							</div>						
							<div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.PublicKey') }}</label>
								<div class="col-sm-10 col-md-4">
                                	<input type="text" name="braintree_public_key" id="braintree_public_key" value="{{$result['shipping_methods'][0]->braintree_public_key}}" class="form-control brantree_active @if($result["shipping_methods"][0]->brantree_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PublicKeyText') }}</span>
								</div>
							</div>	
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.PrivateKey') }}</label>
								<div class="col-sm-10 col-md-4">
                                <input type="text" name="braintree_private_key" id="braintree_private_key" value="{{$result['shipping_methods'][0]->braintree_private_key}}" class="form-control brantree_active @if($result["shipping_methods"][0]->brantree_active==1) field-validate @endif">
									<span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PrivateKeyText') }}</span>
								</div>
							</div>
                            <hr>
                            <h4>{{ trans('labels.Stripe') }}</h4>
                            <hr>
                            <div class="form-group">
                           		<label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.StripeEnviroment') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                          <input type="radio" name="stripe_enviroment" value="0" class="flat-red" @if($result['shipping_methods'][0]->stripe_enviroment==0) checked @endif > &nbsp;{{ trans('labels.Sanbox') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
                                    <label class=" control-label">
                                          <input type="radio" name="stripe_enviroment" value="1" class="flat-red" @if($result['shipping_methods'][0]->stripe_enviroment==1) checked @endif >  &nbsp;{{ trans('labels.Live') }}
                                    </label>
                                    
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StripeEnviromentText') }}</span>
                                </div>
                            </div>
                            
                            {!! Form::hidden('stripe_name',  $result['shipping_methods'][0]->stripe_name , array('class'=>'form-control', 'id'=>'stripe_name')) !!}
                                @foreach($result['stripe_description'] as $description_data)
                                    <div class="form-group">
                                      <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.StripeName') }} ({{ $description_data['language_name'] }})</label>
                                      <div class="col-sm-10 col-md-4">
                                        <input type="text" name="stripe_name_<?=$description_data['languages_id']?>" class="form-control stripe_active @if($result["shipping_methods"][0]->stripe_active==1) field-validate @endif" value="{{$description_data['name']}}">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StripeName') }} ({{ $description_data['language_name'] }}).</span>          
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                      </div>
                                    </div>
                                 
                              	@endforeach
                                                         
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.SecretKey') }}</label>
								<div class="col-sm-10 col-md-4">
									<input type="text" name="secret_key" id="secret_key" value="{{$result['shipping_methods'][0]->secret_key}}" class="form-control stripe_active @if($result["shipping_methods"][0]->stripe_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SecretKeyText') }}</span>
								</div>
							</div>	
													
							<div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Key') }} </label>
								<div class="col-sm-10 col-md-4">
                               		 <input type="text" name="publishable_key" id="publishable_key" value="{{$result['shipping_methods'][0]->publishable_key}}" class="form-control stripe_active @if($result["shipping_methods"][0]->stripe_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StripeKeyText') }}</span>
								</div>
							</div>	
                           	
                           	<hr>
                           	<h4>{{ trans('labels.paypal') }}</h4>
                            <hr>
                           	<div class="form-group">
                           		<label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.paypalEnviroment') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                          <input type="radio" name="paypal_enviroment" value="0" class="flat-red" @if($result['shipping_methods'][0]->paypal_enviroment==0) checked @endif > &nbsp;{{ trans('labels.Sanbox') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
                                    <label class=" control-label">
                                          <input type="radio" name="paypal_enviroment" value="1" class="flat-red" @if($result['shipping_methods'][0]->paypal_enviroment==1) checked @endif >  &nbsp;{{ trans('labels.Live') }}
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PaypalEnviromentText') }}</span>
                                </div>
                            </div>
                             
                            {!! Form::hidden('paypal_name',  $result['shipping_methods'][0]->paypal_name , array('class'=>'form-control', 'id'=>'paypal_name')) !!}
                            @foreach($result['paypal_description'] as $description_data)
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.paypalName') }} ({{ $description_data['language_name'] }})</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input type="text" name="paypal_name_<?=$description_data['languages_id']?>" class="form-control paypal_status @if($result["shipping_methods"][0]->paypal_status==1) field-validate @endif" value="{{$description_data['name']}}">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.paypalName') }} ({{ $description_data['language_name'] }}).</span>          
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>                             
                            @endforeach
                             
                            
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.paypalId') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="paypal_id" id="paypal_id" value="{{$result['shipping_methods'][0]->paypal_id}}" class="form-control paypal_status @if($result["shipping_methods"][0]->paypal_status==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.paypalIdText') }}</span>
								</div>
							</div>	
                           	
                           	<hr>
                            <div  style="display: none">
                            <h4>{{ trans('labels.cybersource') }}</h4>
                            <hr>
                            {!! Form::hidden('cybersource_enviroment',  $result['shipping_methods'][0]->cybersource_enviroment , array('class'=>'form-control', 'id'=>'paypal_name')) !!}
                             {!! Form::hidden('cybersource_name',  $result['shipping_methods'][0]->cybersource_name , array('class'=>'form-control', 'id'=>'paypal_name')) !!}
                            @foreach($result['cybersource_description'] as $description_data)
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CybersourceName') }} ({{ $description_data['language_name'] }})</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input type="text" name="cybersource_name_<?=$description_data['languages_id']?>" class="form-control cybersource_status @if($result["shipping_methods"][0]->cybersource_status==1) field-validate @endif" value="{{$description_data['name']}}">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CybersourceName') }} ({{ $description_data['language_name'] }}).</span>          
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>                             
                            @endforeach
                           	<hr>
                              </div>                
                            <h4>{{ trans('labels.Hyperpay') }}</h4>
                            <hr>                            
                             
                            {!! Form::hidden('hyperpay_name',  $result['shipping_methods'][0]->hyperpay_name , array('class'=>'form-control', 'id'=>'hyperpay_name')) !!}
                            <div class="form-group">
                           		<label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.hyperpayEnviroment') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                          <input type="radio" name="hyperpay_enviroment" value="0" class="flat-red" @if($result['shipping_methods'][0]->hyperpay_enviroment==0) checked @endif > &nbsp;{{ trans('labels.Sanbox') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
                                    <label class=" control-label">
                                          <input type="radio" name="hyperpay_enviroment" value="1" class="flat-red" @if($result['shipping_methods'][0]->hyperpay_enviroment==1) checked @endif >  &nbsp;{{ trans('labels.Live') }}
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PaypalEnviromentText') }}</span>
                                </div>
                            </div>
                            
                            @foreach($result['hyperpay_description'] as $description_data)
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.hyperpayName') }} ({{ $description_data['language_name'] }})</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input type="text" name="hyperpay_name_<?=$description_data['languages_id']?>" class="form-control hyperpay_active @if($result["shipping_methods"][0]->hyperpay_active==1) field-validate @endif" value="{{$description_data['name']}}">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.hyperpayName') }} ({{ $description_data['language_name'] }}).</span>          
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>                             
                            @endforeach
                             
                            
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.userId') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="hyperpay_userid" id="hyperpay_userid" value="{{$result['shipping_methods'][0]->hyperpay_userid}}" class="form-control hyperpay_active @if($result["shipping_methods"][0]->hyperpay_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.userId') }}</span>
								</div>
							</div>	
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Password') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="hyperpay_password" id="hyperpay_password" value="{{$result['shipping_methods'][0]->hyperpay_password}}" class="form-control hyperpay_active @if($result["shipping_methods"][0]->hyperpay_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Password') }}</span>
								</div>
							</div>	
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.entityid') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="hyperpay_entityid" id="hyperpay_entityid" value="{{$result['shipping_methods'][0]->hyperpay_entityid}}" class="form-control hyperpay_active @if($result["shipping_methods"][0]->hyperpay_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.entityid') }}</span>
								</div>
							</div>	
                           	<hr>                            
                            <h4>{{ trans('labels.CashOnDelivery') }}</h4>
                            <hr>
                            {!! Form::hidden('cod_name',  $result['shipping_methods'][0]->cod_name , array('class'=>'form-control', 'id'=>'cod_name')) !!}
                            @foreach($result['cod_description'] as $description_data)
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CashOnDeliveryName') }} ({{ $description_data['language_name'] }})</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input type="text" name="cod_name_<?=$description_data['languages_id']?>" class="form-control cash_on_delivery @if($result["shipping_methods"][0]->cash_on_delivery==1) field-validate @endif" value="{{$description_data['name']}}">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CashOnDeliveryName') }} ({{ $description_data['language_name'] }}).</span>          
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                             
                            @endforeach
                           	
                            <hr>
                           	<div>
                           	<div class="form-group">
                           		<label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.instamojoEnviroment') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                          <input type="radio" name="instamojo_enviroment" value="0" class="flat-red" @if($result['shipping_methods'][0]->instamojo_enviroment==0) checked @endif > &nbsp;{{ trans('labels.Sanbox') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
                                    <label class=" control-label">
                                          <input type="radio" name="instamojo_enviroment" value="1" class="flat-red" @if($result['shipping_methods'][0]->instamojo_enviroment==1) checked @endif >  &nbsp;{{ trans('labels.Live') }}
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.instamojoEnviromentText') }}</span>
                                </div>
                            </div>
                             
                            {!! Form::hidden('instamojo_name',  $result['shipping_methods'][0]->instamojo_name , array('class'=>'form-control', 'id'=>'instamojo_name')) !!}
                            @foreach($result['instamojo_description'] as $description_data)
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.instamojoName') }} ({{ $description_data['language_name'] }})</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input type="text" name="instamojo_name_<?=$description_data['languages_id']?>" class="form-control instamojo_active @if($result["shipping_methods"][0]->instamojo_active==1) field-validate @endif" value="{{$description_data['name']}}">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.instamojoName') }} ({{ $description_data['language_name'] }}).</span>          
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>                             
                            @endforeach                             
                            
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.instamojoapikey') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="instamojo_api_key" id="instamojo_api_key" value="{{$result['shipping_methods'][0]->instamojo_api_key}}" class="form-control instamojo_active @if($result["shipping_methods"][0]->instamojo_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.instamojoapikeyText') }}</span>
								</div>
							</div>	
                            
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.instamojoAuthToken') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="instamojo_auth_token" id="instamojo_auth_token" value="{{$result['shipping_methods'][0]->instamojo_auth_token}}" class="form-control instamojo_active @if($result["shipping_methods"][0]->instamojo_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.instamojoAuthTokenText') }}</span>
								</div>
							</div>	
                            
                             <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.instamoclientid') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="instamojo_client_id" id="instamojo_client_id" value="{{$result['shipping_methods'][0]->instamojo_client_id}}" class="form-control instamojo_active @if($result["shipping_methods"][0]->instamojo_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.instamoclientidText') }}</span>
								</div>
							</div>	
                            
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.instamoclientsecrect') }}</label>
								<div class="col-sm-10 col-md-4">
                               		<input type="text" name="instamojo_client_secret" id="instamojo_client_secret" value="{{$result['shipping_methods'][0]->instamojo_client_secret}}" class="form-control instamojo_active @if($result["shipping_methods"][0]->instamojo_active==1) field-validate @endif">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.instamoclientsecrectText') }}</span>
								</div>
							</div>	
                            
                            <hr>
                            </div>
                                                        
                            <div class="form-group">
								<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.PaymentCurrency') }}</label>
								<div class="col-sm-10 col-md-4">
                                	<input type="text" name="payment_currency" id="payment_currency" value="{{$result['shipping_methods'][0]->payment_currency}}" class="form-control field-validate">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PaymentCurrencyText') }}</span>
								</div>
							</div>	
                            
                            			
                            									
							<!-- /.box-body -->
							<div class="box-footer text-center">
								<button type="submit" class="btn btn-primary payment-checkbox">{{ trans('labels.Update') }} </button>
								<a href="{{ URL::to('admin/dashboard/this_month')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
							</div>
                              <!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>
                  </div>
              </div>
            </div>
            
          </div>
          	
          
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row --> 
    
    <!-- Main row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 