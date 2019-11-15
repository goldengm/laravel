@extends('layout')
@section('content')
<section class="site-content">
	<div class="container">
    	<div class="breadcum-area">
        	<div class="breadcum-inner">
            	<h3>{{$result['detail']['product_data'][0]->products_name}}</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
					@if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                    	<li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                    	<li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}">{{$result['sub_category_name']}}</a></li>
                    @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                    	<li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                    @endif               
                    <li class="breadcrumb-item active">{{$result['detail']['product_data'][0]->products_name}}</li>
                </ol>
            </div>
		</div>
		
		<div class="product-detail-area">								
			<div class="row">
            	<div class="col-12">
                    <div class="detail-area">
                        <div class="row">
                        	@if(!empty($result['detail']['product_data'][0]->products_left_banner) and $result['detail']['product_data'][0]->products_left_banner_start_date <= time() and $result['detail']['product_data'][0]->products_left_banner_expire_date >= time())
                                <div class="col-12 col-lg-2">
                                    <img class="img-thumbnail" src="{{asset('').$result['detail']['product_data'][0]->products_left_banner }}" alt="img-fluid">
                                </div>
                            @endif
                            
                             @if(!empty($result['detail']['product_data'][0]->products_left_banner) and $result['detail']['product_data'][0]->products_left_banner_start_date <= time() and $result['detail']['product_data'][0]->products_left_banner_expire_date >= time() and !empty($result['detail']['product_data'][0]->products_right_banner) and $result['detail']['product_data'][0]->products_right_banner_start_date <= time() and $result['detail']['product_data'][0]->products_right_banner_expire_date >= time())
                                <div class="col-12 col-lg-4">
                            @else
                                <div class="col-12 col-lg-5">
                            @endif
                            
                                <div id="product-slider" class="carousel slide">
                                	@php
                                    if(!empty($result['detail']['product_data'][0]->discount_price) or !empty($result['detail']['product_data'][0]->flash_price)){
										if(!empty($result['detail']['product_data'][0]->discount_price)){
                                       	 	$discount_price = $result['detail']['product_data'][0]->discount_price;	
										}else{
											$discount_price = $result['detail']['product_data'][0]->flash_price;	
										}
                                        $orignal_price = $result['detail']['product_data'][0]->products_price;	
                                        
                                        if(($orignal_price+0)>0){
                                            $discounted_price = $orignal_price-$discount_price;
                                            $discount_percentage = $discounted_price/$orignal_price*100;
                                        }else{
                                            $discount_percentage = 0;
                                        }
                                        echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                                    }
									
									@endphp
                                	<!-- main slider carousel items -->
                                    <div class="carousel-inner">
                                        <a class="carousel-control-prev" href="#product-slider" role="button" data-slide="prev">
                                            <span class="fa fa-angle-left" aria-hidden="true"></span>
                                            <span class="sr-only">@lang('website.Previous')</span>
                                        </a>
                                        <a class="carousel-control-next" href="#product-slider" role="button" data-slide="next">
                                            <span class="fa fa-angle-right" aria-hidden="true"></span>
                                            <span class="sr-only">@lang('website.Next')</span>
                                        </a>                              
                                        <!-- default image -->
                                        <div class="active item carousel-item" data-slide-number="0">
                                            <img class="img-thumbnail" src="{{asset('').$result['detail']['product_data'][0]->products_image }}" alt="img-fluid">
                                        </div>    
                                        @foreach( $result['detail']['product_data'][0]->images as $key=>$images )
                                            <div class="item carousel-item" data-slide-number="{{++$key}}">
                                                <img class="img-thumbnail" src="{{asset('').$images->image }}" alt="img-fluid">
                                            </div>
                                        @endforeach
                                        
                                    </div>
                                    
                                    
                                    <div class="carousel-indicators" style="display: inline-block;
    margin-left: 0;
    margin-right: 0;">
                                        <div class="thumbnail active" data-slide-to="0" data-target="#product-slider" style="float: left; margin-bottom:10px;">
                                            <a id="carousel-selector-0" class="selected" >
                                                <img class="img-thumbnail " src="{{asset('').$result['detail']['product_data'][0]->products_image }}" alt="img-fluid" style="    max-width: 80px;">
                                            </a>
                                        </div>
                                                    
                                        @foreach( $result['detail']['product_data'][0]->images as $key=>$images )
                                            <div class="thumbnail" data-slide-to="{{++$key}}" data-target="#product-slider" style="float: left; margin-bottom:10px;">
                                                <a id="carousel-selector-1" >
                                                    <img class="img-thumbnail " src="{{asset('').$images->image }}" alt="img-fluid" style="    max-width: 80px;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>                                    
                                </div>
                            </div>
            
                            
            			    @if(!empty($result['detail']['product_data'][0]->products_left_banner) and $result['detail']['product_data'][0]->products_left_banner_start_date <= time() and $result['detail']['product_data'][0]->products_left_banner_expire_date >= time() or !empty($result['detail']['product_data'][0]->products_right_banner) and $result['detail']['product_data'][0]->products_right_banner_start_date <= time() and $result['detail']['product_data'][0]->products_right_banner_expire_date >= time())
                            <div class="col-12 col-lg-4">
                            @else
                                <div class="col-12 col-lg-5">
                            @endif
                            
                             
                                <div class="product-summary">
                                	<div class="like-box">
                                        <span products_id='{{$result['detail']['product_data'][0]->products_id}}' class="fa @if($result['detail']['product_data'][0]->isLiked==1) fa-heart @else fa-heart-o @endif is_liked">
                                        	<span class="badge badge-secondary">{{$result['detail']['product_data'][0]->products_liked}}</span>
                                        </span>  
                                        
                                    </div>
                                    
                                	@if(!empty($result['detail']['product_data'][0]->flash_start_date))
                                                <div class="sale-counter">
                                                @if( $result['detail']['product_data'][0]->server_time >= $result['detail']['product_data'][0]->flash_start_date)
                                                   <span style="display: none" id="counter_product"></span>											@endif
                                                 </div>                                            
                                            @endif 
                                    <h3 class="product-title">{{$result['detail']['product_data'][0]->products_name}}</h3>            						
                                    <div class="product-info">
                                        @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                            
                                         <a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}" class="category">{{$result['sub_category_name']}}</a>
                                        @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                         <a href="{{ URL::to('/shop?category='.$result['category_slug'])}}" class="category">{{$result['category_name']}}</a>
                                            
                                        @endif
                                        <div class="orders">{{$result['detail']['product_data'][0]->products_ordered}}&nbsp;@lang('website.Order(s)')</div>
                                        @if($result['detail']['product_data'][0]->products_type == 0)
                                        	
                                            @if($result['detail']['product_data'][0]->defaultStock == 0)
                                            	<div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.Out of Stock') </div>
                                            @else 
                                                <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.In stock') </div>
                                            @endif
                                            
                                        @else
                                        	 <div class="availbility stock-out-cart" hidden><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.Out of Stock') </div>
                                              <div class="availbility stock-cart" hidden><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.In stock') </div>
                                        @endif
                                    </div>
                                    
                                    <div class="product-price">
                                        @if(!empty($result['detail']['product_data'][0]->flash_price))
                                        <span class="discount">
                                                    {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->flash_price+0}} 
                                         </span>
                                        @elseif(!empty($result['detail']['product_data'][0]->discount_price))
                                            <span class="discount">
                                                    {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->discount_price+0}} 
                                            </span>
                                        @endif		
                                        
                                        <!--discount_price-->
                                         <span class="price @if(!empty($result['detail']['product_data'][0]->discount_price) or !empty($result['detail']['product_data'][0]->flash_price)) line-through @else change_price @endif" >
                                            {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->products_price+0}}
                                        </span>
                                        
                                        @if($result['detail']['product_data'][0]->products_min_order>0)
                                        	<span class="min-order-tag" @if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date ) style="display: none" @endif>
                                       		&nbsp; @lang('website.Min Order Limit:') {{ $result['detail']['product_data'][0]->products_min_order }}
                                            </span>
                                        @endif                                     
                                        
                                    </div>
                                    
                                    <form name="attributes" id="add-Product-form" method="post" >
                                        <input type="hidden" name="products_id" value="{{$result['detail']['product_data'][0]->products_id}}">
                                        <input type="hidden" name="products_price" id="products_price" value="{{$result['cart'][0]->final_price+0}}">
                                        <input type="hidden" name="checkout" id="checkout_url" value="true" >	
                                        <input type="hidden" name="cart_id" id="cart_id" value="{{ app('request')->input('id') }}">
										<input type="hidden" id="max_order" value="@if(!empty($result['detail']['product_data'][0]->products_max_stock)) {{ $result['detail']['product_data'][0]->products_max_stock }} @else 0 @endif" >    
                                        @php
                                            $cart_attribiters = array();
                                        	foreach($result['cart'][0]->attributes as $key=>$attribute){
                                        		$cart_attribiters[] = $attribute->options_values_id;
                                       		}	
                                           
                                        @endphp   
                                        @if(count($result['detail']['product_data'][0]->attributes)>0)
                                        
                                            <div class="form-row">                                             
                                        		@php                                                    
                                                    $index = 0;                                                    
                                                @endphp 
                                                @foreach( $result['detail']['product_data'][0]->attributes as $key=>$attributes_data )  
                                                @php                                                    
                                                    $functionValue = 'function_'.$key++;                                                    
                                                @endphp                   
                                                <input type="hidden" name="option_name[]" value="{{ $attributes_data['option']['name'] }}" >
                                                <input type="hidden" name="option_id[]" value="{{ $attributes_data['option']['id'] }}" >
                                                <input type="hidden" name="{{ $functionValue }}" id="{{ $functionValue }}" value="0" >									
                                                <input id="attributeid_<?=$index?>" type="hidden" value="">										
                                                <input id="attribute_sign_<?=$index?>" type="hidden" value="">												
                                                <input id="attributeids_<?=$index?>" type="hidden" name="attributeid[]" value="" >
                                                <div class="form-group col-sm-4">							
                                                    <label for="values_<?=$index?>" class="col-sm-12 col-form-label">{{ $attributes_data['option']['name'] }}</label>		
                                                    <div class="col-sm-12">								
                                                      
                                                        <select name="{{ $attributes_data['option']['id'] }}" onChange="getQuantity()" class="currentstock form-control attributeid_<?=$index++?>" attributeid = "{{ $attributes_data['option']['id'] }}">
                                                            @foreach( $attributes_data['values'] as $values_data )
                                                           
														   	<option  attributes_value="{{ $values_data['products_attributes_id'] }}" 
															value="{{ $values_data['id'] }}" 
															prefix = '{{ $values_data['price_prefix'] }}'  
															value_price ="{{ $values_data['price']+0 }}" 
															@if(count($result['cart'][0]->attributes)>0) @if(in_array($values_data['products_attributes_id'], $cart_attribiters))
															selected @endif @endif >{{ $values_data['value'] }}</option>								
                                                            @endforeach								
                                                        </select>								
                                                    </div>							
                                                </div>
                                                @endforeach							
                                            </div>
                                        @endif
                                        
										@php
											if($result['detail']['product_data'][0]->products_min_order>0){
                                             	$min_order_limit = $result['detail']['product_data'][0]->products_min_order;
                                            }else{ 
                                            	$min_order_limit = 1;
                                            }
											
											if(!empty($result['detail']['product_data'][0]->products_max_stock) and $result['detail']['product_data'][0]->products_max_stock>0 and  $result['detail']['product_data'][0]->products_max_stock < $result['detail']['product_data'][0]->defaultStock){
                                             	$max_order_limit = $result['detail']['product_data'][0]->products_max_stock;
                                             }else{ 
                                             	$max_order_limit = $result['detail']['product_data'][0]->defaultStock;
                                             }
											 
										@endphp
										
                                        <div class="form-inline product-box">
                                            <div class="form-group Qty">	
                                            					
                                                <label for="quantity" class="col-form-label">@lang('website.Quantity') </label>						
                                                <div class="input-group">						
                                                    <span class="input-group-btn first qtyminus">						
                                                    	<button class="btn btn-defualt " type="button">-</button>						
                                                    </span>						
                                                    <input type="text" readonly name="quantity" value="{{$result['cart'][0]->customers_basket_quantity}}" min="{{$min_order_limit}}" max="{{$max_order_limit}}" class="form-control qty">											
                                                    <span class="input-group-btn last qtyplus">						
                                                    	<button class="btn btn-defualt " type="button">+</button>						
                                                    </span>						
                                                </div>											
                                            </div>	
                                            <div class="price-box">
                                                <span>@lang('website.Total Price')&nbsp;:</span>
                                                <span class="total_price">
                                                    <!--@if(!empty($result['detail']['product_data'][0]->flash_price))
                                                        {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->flash_price+0}}                                                	@elseif(!empty($result['detail']['product_data'][0]->discount_price))
                                                        {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->discount_price+0}}
                                                    @else
                                                        {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->products_price+0}}
                                                    @endif-->
                                                    {{$web_setting[19]->value}}{{$result['cart'][0]->final_price*$result['cart'][0]->customers_basket_quantity}}
                                                </span>				
                                            </div>
                                                                       
                                            <div class="buttons" style="margin-top:30px;">
                                                                                            
                                                @if($result['detail']['product_data'][0]->products_type == 0)                                        	
                                                     @if($result['detail']['product_data'][0]->defaultStock == 0 or $result['detail']['product_data'][0]->defaultStock < $min_order_limit )
                                                   		<button class="btn btn-danger " type="button">@lang('website.Out of Stock')</button>
                                                    @else 
                                                        <button class="btn btn-secondary update-single-Cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Update')</button>
                                                    @endif
                                                    
                                                @else
                                                     <button class="btn btn-secondary update-single-Cart stock-cart"  hidden type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Update')</button>                                                    
                                                     <button class="btn btn-danger stock-out-cart" hidden type="button">@lang('website.Out of Stock')</button>                                                     
                                                @endif
                                            </div>					
                                    	</div>	
									</form>
                                </div>	
                            </div>
                            
                           @if(!empty($result['detail']['product_data'][0]->products_right_banner) and $result['detail']['product_data'][0]->products_right_banner_start_date <= time() and $result['detail']['product_data'][0]->products_right_banner_expire_date >= time())
                                <div class="col-12 col-lg-2">
                                    <img class="img-thumbnail" src="{{asset('').$result['detail']['product_data'][0]->products_right_banner }}" alt="img-fluid">
                                </div>
                            @endif
                            
                            <div class="col-12">
                                <div class="product-tabs">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills" id="myTab" role="tablist">
                                      <li class="nav-item">
                                        <a class="nav-link active" id="product-desc-tab" data-toggle="tab" href="#product_desc" role="tab" aria-controls="product_desc" aria-selected="true">@lang('website.Products Description')</a>
                                      </li>
                                      
                                    </ul>
                                    
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="product_desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                            <p class="product-description"><?=stripslashes($result['detail']['product_data'][0]->products_description)?></p>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="related-product-area">
                    	<div class="heading">
                            <h2>@foreach($result['detail']['product_data'][0]->categories as $key=>$category)
                                    @if(++$key === 1)
                                    <small class="pull-right"><a href="{{ URL::to('/shop?category_id='.$category->categories_id)}}">@lang('website.View All')</a></small>
                                    @endif
                                @endforeach</h2>
                            <hr>
                        </div>
                        <div class="row">
                            
                            
                            <div class="products products-4x">
                                @foreach($result['simliar_products']['product_data'] as $key=>$products)
            
                                @if($result['detail']['product_data'][0]->products_id != $products->products_id)
                
                                @if(++$key<=5)
                
                                <div class="product">
                                    <article>
                                        <div class="thumb"><img class="img-fluid" src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"></div>
                                        <?php
                                            $current_date = date("Y-m-d", strtotime("now"));
                                            
                                            $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                                            $date=date_create($string);
                                            date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                                            
                                            
                                            $after_date = date_format($date,"Y-m-d");
                                            
                                            if($after_date>=$current_date){
                                                print '<span class="new-tag">New</span>';
                                            }
                                            
                                            if(!empty($products->discount_price)){
                                                $discount_price = $products->discount_price;	
                                                $orignal_price = $products->products_price;	
                                                
                                                if(($orignal_price+0)>0){
													$discounted_price = $orignal_price-$discount_price;
													$discount_percentage = $discounted_price/$orignal_price*100;
												}else{
													$discount_percentage = 0;
												}
                                                echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                                            }
                                        ?>
                                        
                                        <span class="tag text-center">
                                            @foreach($products->categories as $key=>$category)
                                    			{{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif		                                			@endforeach
                                        </span>
                                        
                                        <h2 class="title text-center">{{$products->products_name}}</h2>
                                        <div class="price text-center">
                                            @if(!empty($products->discount_price))
                                                {{$web_setting[19]->value}}{{$products->discount_price+0}}
                                                <span>{{$web_setting[19]->value}}{{$products->products_price+0}}</span> 
                                            @else
                                                {{$web_setting[19]->value}}{{$products->products_price+0}}
                                            @endif
                                        </div>                                        
                                        
                                        <div class="product-hover">
                                            <div class="icons">
                                                <div class="icon-liked">
                                                    <span products_id = '{{$products->products_id}}' class="fa @if($products->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$products->products_liked}}</span></span>
                                                </div>
                                            	@if($products->products_type!=2)
                                                	<a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="fa fa-eye"></a>
                                            	@endif
                                            </div>
                                            
                                            <div class="buttons">
                                                @if($products->products_type==0)
                                                    @if(!in_array($products->products_id,$result['cartArray']))
                                                        <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                    @else
                                                        <button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                                    @endif
                                                @elseif($products->products_type==1)
                                                    <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                @elseif($products->products_type==2)
                                                    <a href="{{$products->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
                                                @endif
                                            </div>
                                         </div>
                                    </article>
                                  </div>
                
                                @endif		
                
                                @endif
                
                                @endforeach
                            </div>
    
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>

@endsection 	


