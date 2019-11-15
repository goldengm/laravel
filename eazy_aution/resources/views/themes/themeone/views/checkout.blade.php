@extends('layout')
@section('content')
<section class="site-content">
	<div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Checkout')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">@lang('website.Checkout')</a></li>
                    <li class="breadcrumb-item">
                    	<a href="javascript:void(0)">
                    		@if(session('step')==0)
                            	@lang('website.Shipping Address')
                            @elseif(session('step')==1)
                            	@lang('website.Billing Address')
                            @elseif(session('step')==2)
                            	@lang('website.Shipping Methods')
                            @elseif(session('step')==3)
                            	@lang('website.Order Detail')
                            @endif
                    	</a>
                    </li>
                </ol>
            </div>
        </div>
		<div class="checkout-area">
            <div class="row">
				<div class="col-12 col-lg-8 checkout-left">
                <input type="hidden" id="hyperpayresponse" value="@if(!empty(session('paymentResponse'))) @if(session('paymentResponse')=='success') {{session('paymentResponse')}} @else {{session('paymentResponse')}}  @endif @endif">
                <div class="alert alert-danger alert-dismissible" id="paymentError" role="alert" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    @if(!empty(session('paymentResponse')) and session('paymentResponse')=='error') {{session('paymentResponseData') }} @endif
                </div>
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if(session('step')==0) active @elseif(session('step')>0) active-check @endif" id="shipping-tab" data-toggle="pill" href="#pills-shipping" role="tab" aria-controls="pills-shpping" aria-expanded="true">@lang('website.Shipping Address')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(session('step')==1) active @elseif(session('step')>1) active-check @endif" @if(session('step')>=1) id="billing-tab" data-toggle="pill" href="#pills-billing" role="tab" aria-controls="pills-billing" aria-expanded="true" @endif >@lang('website.Billing Address')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(session('step')==2) active @elseif(session('step')>2) active-check @endif"  @if(session('step')>=2)  id="shipping-methods-tab" data-toggle="pill" href="#pills-shipping-methods" role="tab" aria-controls="pills-shipping-methods" aria-expanded="true"  @endif>@lang('website.Shipping Methods')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(session('step')==3) active @elseif(session('step')>3) active-check @endif"  @if(session('step')>=3)  id="order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-expanded="true"  @endif>@lang('website.Order Detail')</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                      <div class="tab-pane fade @if(session('step') == 0) show active @endif" id="pills-shipping" role="tabpanel" aria-labelledby="shipping-tab">
                        
                        <form name="signup" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/checkout_shipping_address')}}" method="post">
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="firstName">@lang('website.First Name')</label>
                                <input type="text" class="form-control field-validate" id="firstname" name="firstname" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->firstname}}@endif">
                                 <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>  
                              </div>
                              <div class="form-group col-md-6">
                                <label for="lastName">@lang('website.Last Name')</label>
                                <input type="text" class="form-control field-validate" id="lastname" name="lastname" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->lastname}}@endif">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
                              </div>
                              <div class="form-group col-md-6">
                                <label for="firstName">@lang('website.Company')</label>
                                <input type="text" class="form-control field-validate" id="company" name="company" value="@if(count(session('shipping_address'))>0) {{session('shipping_address')->company}}@endif">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span> 
                              </div>
                              <div class="form-group col-md-6">
                                <label for="firstName">@lang('website.Address')</label>
                                <input type="text" class="form-control field-validate" id="street" name="street" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->street}}@endif">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span> 
                              </div>
                              <div class="form-group col-md-6">
                                <label for="lastName">@lang('website.Country')</label>
                                  <select class="form-control field-validate" id="entry_country_id" onChange="getZones();" name="countries_id">
                                      <option value="" selected>@lang('website.Select Country')</option>
                                      @if(count($result['countries'])>0)
                                        @foreach($result['countries'] as $countries)
                                            <option value="{{$countries->countries_id}}" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                                        @endforeach
                                      @endif
                                  </select>
                                <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                              </div>
                              <div class="form-group col-md-6">
                                <label for="firstName">@lang('website.State')</label>
                                <select class="form-control field-validate" id="entry_zone_id" name="zone_id">
                                      <option value="" selected>@lang('website.Select State')</option>
                                       @if(count($result['zones'])>0)
                                        @foreach($result['zones'] as $zones)
                                            <option value="{{$zones->zone_id}}" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                                        @endforeach
                                      @endif
                                      
                                       <option value="Other" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>                      
                                </select>
                                <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                              </div>
                              <div class="form-group col-md-6">
                                <label for="lastName">@lang('website.City')</label>
                                <input type="text" class="form-control field-validate" id="city" name="city" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->city}}@endif">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span> 
                              </div>
                              <div class="form-group col-md-6">
                                <label for="lastName">@lang('website.Zip/Postal Code')</label>
                                <input type="text" class="form-control" id="postcode" name="postcode" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->postcode}}@endif">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                              </div>	
                              <div class="form-group col-md-6">
                                <label for="lastName">@lang('website.Phone Number')</label>
                                <input type="text" class="form-control" id="delivery_phone" name="delivery_phone" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->delivery_phone}}@endif">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span> 
                              </div>			  
                            </div>		
                            <div class="button"><button type="submit" class="btn btn-dark">@lang('website.Continue')</button></div>
                    	</form>
                      </div>
                      
                      
                      
                      <div class="tab-pane fade @if(session('step') == 1) show active @endif" id="pills-billing" role="tabpanel" aria-labelledby="billing-tab">
                        <form name="signup" enctype="multipart/form-data" action="{{ URL::to('/checkout_billing_address')}}" method="post">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="firstName">@lang('website.First Name')</label>
                            <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_firstname" name="billing_firstname" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_firstname}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>  
                          </div>
                          <div class="form-group col-md-6">
                            <label for="lastName">@lang('website.Last Name')</label>
                            <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_lastname" name="billing_lastname" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_lastname}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
                          </div>
                          <div class="form-group col-md-6">
                            <label for="firstName">@lang('website.Company')</label>
                            <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_company" name="billing_company" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_company}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span> 
                          </div>
                          <div class="form-group col-md-6">
                            <label for="firstName">@lang('website.Address')</label>
                            <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_street" name="billing_street" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_street}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="lastName">@lang('website.Country')</label>
                              <select class="form-control same_address_select" id="billing_countries_id"  onChange="getBillingZones();" name="billing_countries_id" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif  >
                                  <option value=""  >@lang('website.Select Country')</option>
                                  @if(count($result['countries'])>0)
                                    @foreach($result['countries'] as $countries)
                                        <option value="{{$countries->countries_id}}" @if(count(session('billing_address'))>0) @if(session('billing_address')->billing_countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                                    @endforeach
                                  @endif
                              </select>
                              <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                          </div>
                          <div class="form-group col-md-6">
                            <label for="firstName">@lang('website.State')</label>
                            <select class="form-control same_address_select" id="billing_zone_id" name="billing_zone_id" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif  >
                                  <option value="" >@lang('website.Select State')</option>
                                  @if(count($result['zones'])>0)
                                    @foreach($result['zones'] as $key=>$zones)
                                        <option value="{{$zones->zone_id}}" @if(count(session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                                    @endforeach                        
                                  @endif
                                    <option value="Other" @if(count(session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>
                              </select>
                              <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                          </div>
                          <div class="form-group col-md-6">
                            <label for="lastName">@lang('website.City')</label>
                            <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_city" name="billing_city" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_city}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="lastName">@lang('website.Zip/Postal Code')</label>
                            <input type="text" class="form-control same_address"  @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_zip" name="billing_zip" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_zip}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                          </div>	
                          	
                          <div class="form-group col-md-6">
                            <label for="lastName">@lang('website.Phone Number')</label>
                            <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_phone" name="billing_phone" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_phone}}@endif">
                            <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span> 
                          </div>	  
                        </div>			
                        <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                  <input  class="form-check-input" id="same_billing_address" value="1" type="checkbox" name="same_billing_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) checked @endif @else checked  @endif > @lang('website.Same shipping and billing address')
                              </label>
                            </div>
                        </div>
                        <div class="button"><button type="submit" class="btn btn-dark"> @lang('website.Continue')</button></div>
                    </form>
              	</div>
                
                <div class="tab-pane fade @if(session('step') == 2) show active @endif" id="pills-shipping-methods" role="tabpanel" aria-labelledby="shipping-methods-tab">
                    <div class="shipping-methods">
                        <p class="title">@lang('website.Please select a prefered shipping method to use on this order')</p>
                    <form name="shipping_mehtods" method="post" id="shipping_mehtods_form" enctype="multipart/form-data" action="{{ URL::to('/checkout_payment_method')}}">
                        @if(count($result['shipping_methods'])>0)
                            <input type="hidden" name="mehtod_name" id="mehtod_name">
                            <input type="hidden" name="shipping_price" id="shipping_price">
                            
		                    @foreach($result['shipping_methods'] as $shipping_methods)
                                <div class="heading">
                                    <h2>{{$shipping_methods['name']}}</h2>
                                    <hr>
                                </div>
                                <div class="form-check">
                                    
                                    <div class="form-row">
                                        @if($shipping_methods['success']==1)
                                        <ul class="list">                              
                                            @foreach($shipping_methods['services'] as $services)
                                             <?php
                                                 if($services['shipping_method']=='upsShipping')
                                                    $method_name=$shipping_methods['name'].'('.$services['name'].')';
                                                 else{
                                                    $method_name=$services['name'];
                                                    }
                                                ?>
                                                <li>
                                                <input class="shipping_data" id="{{$method_name}}" type="radio" name="shipping_method" value="{{$services['shipping_method']}}" shipping_price="{{$services['rate']}}"  method_name="{{$method_name}}" @if(!empty(session('shipping_detail')) and count(session('shipping_detail')) > 0) 
                                                @if(session('shipping_detail')->mehtod_name == $method_name) checked @endif
                                                @elseif($shipping_methods['is_default']==1) checked @endif
                                                >                                                    
                                                 <label for="{{$method_name}}">{{$services['name']}} --- {{$web_setting[19]->value}}{{$services['rate']}}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @else
                                            <ul class="list">
                                                <li>@lang('website.Your location does not support this') {{$shipping_methods['name']}}.</li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="alert alert-danger alert-dismissible error_shipping" role="alert" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            @lang('website.Please select your shipping method')
                        </div>
                        <div class="button">
                            <button type="submit" class="btn btn-dark">@lang('website.Continue')</button>
                        </div>
                      </form>
                    </div>
                </div>
              
                <div class="tab-pane fade @if(session('step') == 3) show active @endif" id="pills-order" role="tabpanel" aria-labelledby="order-tab"> 
                	 
                    <div class="order-review">
                        <?php 
                            $price = 0;
                        ?>
                        <form method='POST' id="update_cart_form" action='{{ URL::to('/place_order')}}' >
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th align="left">@lang('website.items')</th>
                                            <th align="right">@lang('website.Price')</th>
                                            <th align="right">@lang('website.Qty')</th>
                                            <th align="right">@lang('website.SubTotal')</th>
                                        </tr>
                                    </thead>
                                  
                                    @foreach( $result['cart'] as $products)
                                    <?php 
                                        $price+= $products->final_price * $products->customers_basket_quantity;					
                                    ?>
                                     
                                    <tbody>
                                        <tr>
                                            <td align="left" class="item">
                                                <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
                                                    <img class="img-fluid" src="{{asset('').$products->image}}" alt="{{$products->products_name}}" alt="">
                                                </a>
                                                <div class="cart-product-detail">
                                                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="title">
                                                        {{$products->products_name}} {{$products->model}}
                                                    </a>
                                                    @if(count($products->attributes) >0)
                                                        <ul>
                                                            @foreach($products->attributes as $attributes)
                                                                <li>{{$attributes->attribute_name}}<span>{{$attributes->attribute_value}}</span></li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                        
                                            <td align="right" class="price"><span>{{$web_setting[19]->value}}{{$products->final_price+0}}</span></td>
                                            <td align="right" class="Qty"><span>{{$products->customers_basket_quantity}}</span></td>
                                        
                                            <td align="right" class="subtotal"><span class="cart_price_{{$products->customers_basket_id}}">{{$web_setting[19]->value}}{{$products->final_price * $products->customers_basket_quantity}}</span>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td colspan="4" class="buttons">
                                                <a href="{{ URL::to('/editcart?id='.$products->customers_basket_id)}}" class="btn btn-sm btn-secondary">@lang('website.Edit')</a>
                                                <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}" class="btn btn-sm btn-secondary">@lang('website.Remove Item')</a>
                                            </td>
                                        </tr> 
                                    </tbody>            
                                    @endforeach
                                </table>
                            </div>                   
                            <?php			
                                if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0){
									
									if(!empty(session('coupon_shipping_free')) and session('coupon_shipping_free')=='yes'){
										$shipping_price = 0;
									}else{
										$shipping_price = session('shipping_detail')->shipping_price;
									}
									$shipping_name = session('shipping_detail')->mehtod_name;
                                }else{
                                    $shipping_price = 0;
									$shipping_name = '';
                                }				
                                $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
                                $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
                                $total_price = ($price+$tax_rate+$shipping_price)-$coupon_discount;	
								session(['total_price'=>$total_price]);
											
                            ?>
                        </form>
                    </div>
                    <div class="notes-summary-area">
                    	<div class="heading">
                            <h2>@lang('website.orderNotesandSummary')</h2>
                            <hr>
                        </div>
                    	<div class="row">
                        	<div class="col-xs-12 col-sm-6 order-notes">
                            	<p class="title">@lang('website.Please write notes of your order')</p>
                                <div class="form-group">
                                    <p for="order_comments"></p>
                                    <textarea name="comments" id="order_comments" class="form-control" placeholder="Order Notes">@if(!empty(session('order_comments'))){{session('order_comments')}}@endif</textarea>
                                </div>
                            </div>
    
                            <div class="col-xs-12 col-sm-6 order-summary">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th><span>@lang('website.SubTotal')</span></th>
                                                <td align="right" id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</td>
                                            </tr>
                                            <tr>
                                                <th><span>@lang('website.Tax')</span></th>
                                                <td align="right">{{$web_setting[19]->value}}{{$tax_rate}}</td>
                                            </tr>
                                            <tr>
                                                <th><span>@lang('website.Shipping Cost')</br><small>{{$shipping_name}}</small></span></th>
                                                <td align="right">{{$web_setting[19]->value}}{{$shipping_price}}</td>
                                            </tr>
                                            <tr>
                                                <th><span>@lang('website.Discount(Coupon)')</span></th>
                                                <td align="right" id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</td>
                                            </tr>
                                            <tr>
                                                <th class="last"><span>@lang('website.Total')</span></th>
                                                <td class="last" align="right" id="total_price">{{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="payment-area">
                    	<div class="heading">
                            <h2>@lang('website.Payment Methods')</h2>
                            <hr>
                        </div>
                        <div class="payment-methods">
                        <p class="title">@lang('website.Please select a prefered payment method to use on this order')</p>
                        
                        <div class="alert alert-danger error_payment" style="display:none" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            @lang('website.Please select your payment method')
                        </div>	
                                
                        <form name="shipping_mehtods" method="post" id="payment_mehtods_form" enctype="multipart/form-data" action="{{ URL::to('/order_detail')}}">
                            <ul class="list">
                                @foreach($result['payment_methods'] as $payment_methods)
                                    @if($payment_methods['active']==1)
                                        <input id="payment_currency" type="hidden" onClick="paymentMethods();" name="payment_currency" value="{{$payment_methods['payment_currency']}}">
                                        @if($payment_methods['payment_method']=='braintree')
                                            
                                            <input id="{{$payment_methods['payment_method']}}_public_key" type="hidden" name="public_key" value="{{$payment_methods['public_key']}}">
                                            <input id="{{$payment_methods['payment_method']}}_environment" type="hidden" name="{{$payment_methods['payment_method']}}_environment" value="{{$payment_methods['environment']}}">
                                            <li>
                                            	<input type="radio" onClick="paymentMethods();" name="payment_method" class="payment_method" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                                                <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label>
                                            </li>
                
                                        @else
                                            <input id="{{$payment_methods['payment_method']}}_public_key" type="hidden" name="public_key" value="{{$payment_methods['public_key']}}">
                                            <input id="{{$payment_methods['payment_method']}}_environment" type="hidden" name="{{$payment_methods['payment_method']}}_environment" value="{{$payment_methods['environment']}}">
                                            
                                            <li>
                                            	<input onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                                            	<label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label>
                                            </li>
                                        @endif
                                        
                                    @endif
                                @endforeach
                            </ul>                             
                        </form>
                    </div>
                        
						<div class="button">
                            
                            <!--- paypal -->
                            <div id="paypal_button" class="payment_btns" style="display: none"></div>
                            
                            <button id="braintree_button" style="display: none" class="btn btn-dark payment_btns" data-toggle="modal" data-target="#braintreeModel" >@lang('website.Order Now')</button>
                            
                            <button id="stripe_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#stripeModel" >@lang('website.Order Now')</button>
                            
                            <button id="cash_on_delivery_button" class="btn btn-dark payment_btns" style="display: none">@lang('website.Order Now')</button>
                            <button id="instamojo_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#instamojoModel">@lang('website.Order Now')</button>
                            
                            <a href="{{ URL::to('/checkout/hyperpay')}}" id="hyperpay_button" class="btn btn-dark payment_btns" style="display: none">@lang('website.Order Now')</a>
                                                        
                         </div>
                    </div>
                    
                                     
                
                    <!-- The braintree Modal -->
                    <div class="modal fade" id="braintreeModel">
                      <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="checkout" method="post" action="{{ URL::to('/place_order')}}">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('website.BrainTree Payment')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                      <div id="payment-form"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                                </div>
                            </form>
                        </div>
                       </div>
                    </div>
                    
                    <!-- The instamojo Modal -->
                    <div class="modal fade" id="instamojoModel">
                      <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="instamojo_form" method="post" action="">
                            	<input type="hidden" name="amount" value="{{number_format((float)$total_price+0, 2, '.', '')}}">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('website.Instamojo Payment')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                               <div class="modal-body">
                                      <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">@lang('website.Full Name')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="fullname" class="form-control" placeholder="@lang('website.Full Name')" id="firstName">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your full name')</span>
                                        </div>
                                     </div>
                                      <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">@lang('website.Email')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="email_id" class="form-control " placeholder="@lang('website.Email')" id="email_id">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your email address')</span>
                                        </div>
                                     </div>
                                      <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">@lang('website.Phone Number')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="phone_number" class="form-control" placeholder="@lang('website.Phone Number')" id="insta_phone_number">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                                        </div>
                                     </div>
                                     <div class="alert alert-danger alert-dismissible" id="insta_mojo_error" role="alert" style="display: none">
                                        <span class="sr-only">@lang('website.Error'):</span>
                                        <span id="instamojo-error-text"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="pay_instamojo" class="btn btn-dark">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                                </div>
                            </form>
                        </div>
                       </div>
                    </div>
                    
                    <!-- The stripe Modal -->
                    <div class="modal fade" id="stripeModel">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            
                            <main>
                            <div class="container-lg">
                                <div class="cell example example2">
                                    <form>
                                      <div class="row">
                                        <div class="field">
                                          <div id="example2-card-number" class="input empty"></div>
                                          <label for="example2-card-number" data-tid="elements_examples.form.card_number_label">@lang('website.Card number')</label>
                                          <div class="baseline"></div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="field half-width">
                                          <div id="example2-card-expiry" class="input empty"></div>
                                          <label for="example2-card-expiry" data-tid="elements_examples.form.card_expiry_label">@lang('website.Expiration')</label>
                                          <div class="baseline"></div>
                                        </div>
                                        <div class="field half-width">
                                          <div id="example2-card-cvc" class="input empty"></div>
                                          <label for="example2-card-cvc" data-tid="elements_examples.form.card_cvc_label">@lang('website.CVC')</label>
                                          <div class="baseline"></div>
                                        </div>
                                      </div>
                                    <button type="submit" class="btn btn-dark" data-tid="elements_examples.form.pay_button">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                                    
                                      <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                          <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                          <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                                        </svg>
                                        <span class="message"></span></div>
                                    </form>
                                                <div class="success">
                                                  <div class="icon">
                                                    <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                      <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                                                      <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                                                    </svg>
                                                  </div>
                                                  <h3 class="title" data-tid="elements_examples.success.title">@lang('website.Payment successful')</h3>
                                                  <p class="message"><span data-tid="elements_examples.success.message">@lang('website.Thanks You Your payment has been processed successfully')</p>
                                                </div>
                            
                                            </div>
                                        </div>
                                    </main>
                                </div>
                        	</div>
                    	</div>
                	</div>
				</div>
				</div> <!--CHECKOUT LEFT CLOSE-->
                
                <div class="col-12 col-lg-4 checkout-right">    
                    <div class="order-summary-outer">
                    	<div class="order-summary">
                            <div class="table-responsive">
                                <table class="table">
                                	<thead>
                                    	<tr>
                                        	<th colspan="2">@lang('website.Order Summary') </th>
                                        </tr>
                                    </thead>
                                  	<tbody>
                                        <tr>
                                            <th><span>@lang('website.SubTotal')</span></th>
                                            <td align="right" id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</td>
                                        </tr>
                                        <tr>
                                            <th><span>@lang('website.Tax')</span></th>
                                            <td align="right">{{$web_setting[19]->value}}{{$tax_rate}}</td>
                                        </tr>
                                        <tr>
                                            <th>
                                            	<span>@lang('website.Shipping Cost')</br><small>{{$shipping_name}}</small>  @if(!empty($web_setting[82]->value))</br><small>@lang('website.Avail free shpping on') {{$web_setting[19]->value}}{{$web_setting[82]->value}}.</small>@endif</span></th>
                                            <td align="right">{{$web_setting[19]->value}}{{$shipping_price}}</td>
                                        </tr>
                                        <tr>
                                            <th><span>@lang('website.Discount(Coupon)')</span></th>
                                            <td align="right" id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</td>
                                        </tr>
                                        <tr>
                                            <th class="last"><span>@lang('website.Total')</span></th>
                                            <td class="last" align="right" id="total_price">{{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}}</td>
                                        </tr>
                                	</tbody>
                                </table>
                            </div>
                        </div> 
                        <div class="coupons">
                        	<!-- applied copuns -->
                            
                            
                            @if(count(session('coupon')) > 0 and !empty(session('coupon')))
                            
                            	<div class="form-group"> 
                                    <label>@lang('website.Coupon Applied')</label>         
                                    @foreach(session('coupon') as $coupons_show)  
                                            
                                        <div class="alert alert-success">
                                            <a href="{{ URL::to('/removeCoupon/'.$coupons_show->coupans_id)}}" class="close"><span aria-hidden="true">&times;</span></a>
                                            {{$coupons_show->code}}
                                        </div>
                                        
                                    @endforeach
                                </div>    
                            @endif  
                            <form id="apply_coupon">
                                <div class="form-group">
                                    <label for="inputPassword2" class="">@lang('website.Coupon Code')</label>
                                    <input type="text" name="coupon_code" class="form-control" id="coupon_code">
                                </div>
                                <button type="submit" class="btn btn-sm btn-dark">@lang('website.ApplyCoupon')</button>
                                <div id="coupon_error" style="display: none"></div>
                                <div id="coupon_require_error" style="display: none">@lang('website.Please enter a valid coupon code')</div>
                            </form>
                        </div>
                    </div>	
                </div>	<!--CHECKOUT RIGHT CLOSE-->
            </div>
		</div>
	</div>
</section>
  
   


@endsection 	


