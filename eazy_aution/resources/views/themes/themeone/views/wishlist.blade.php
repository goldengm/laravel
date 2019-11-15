@extends('layout')
@section('content')
<section class="site-content">
	<div class="container">
  		<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Wishlist')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
            		<li class="breadcrumb-item active">@lang('website.Wishlist')</li>
                </ol>
            </div>
        </div>
    	<div class="shop-area">
        	<form method="get" enctype="multipart/form-data" id="load_wishlist_form" style="width:100%;">
            <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
            <input type="hidden"  name="category_id" value="{{ app('request')->input('category_id') }}">
            <input type="hidden"  name="load_wishlist" value="1">
            <input type="hidden"  name="type" value="wishlist">
        	<div class="row">
            	<div class="col-12 col-lg-3 spaceright-0">
                    @include('common.sidebar_account')
                </div>
            	<div class="col-12 col-lg-9 new-customers">
                	
                	<div class="col-12 spaceright-0">
                        <div class="heading">
                            <h2>@lang('website.Wishlist')</h2>
                            <hr>
                        </div>
                        <div class="row">
                        	@if($result['products']['success']==1)
                            <div class="toolbar mb-3 loaded_content">
                                <div class="form-inline">
                                    <div class="form-group col-12 col-md-4">
                                        <label class="col-12 col-lg-5 col-form-label">@lang('website.Display')</label>
                                        <div class="col-12 col-lg-7 btn-group">
                                            <a href="#" id="grid_wishlist" class="btn btn-default active"> <i class="fa fa-th-large" aria-hidden="true"></i></a>
                                            <a href="#" id="list_wishlist" class="btn btn-default"><i class="fa fa-list" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-4"></div>
                                    <div class="form-group col-12 col-md-4">
                                        <label class="col-12 col-lg-4 col-form-label">@lang('website.Limit')</label>
                                        <select class="col-12 col-lg-3 form-control sortbywishlist" name="limit">
                                            <option value="15" @if(app('request')->input('limit')=='15') selected @endif">15</option>
                                            <option value="30" @if(app('request')->input('limit')=='30') selected @endif>30</option>
                                            <option value="45" @if(app('request')->input('limit')=='45') selected @endif>45</option>
                                        </select>
                                        <label class="col-12 col-lg-5 col-form-label">@lang('website.per page')</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="products products-3x loaded_content" id="listing-wishlist">
                                @foreach($result['products']['product_data'] as $key=>$products)
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
                                        
                                        <div class="block-panel">
                                            <span class="tag">
                                                @foreach($products->categories as $key=>$category)
                                                	{{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif                                                	
                                                @endforeach
                                            </span>
                                            <h2 class="title wrap-dot-1">{{$products->products_name}}</h2> 
                                                                                 
                                            <div class="description">
                                                <?=stripslashes($products->products_description)?>
                                                <p class="read-more"></p>
                                            </div> 
                                                                                 
                                            <div class="block-inner">
                                                <div class="price">
                                                    @if(!empty($products->discount_price))
                                                        {{$web_setting[19]->value}}{{$products->discount_price+0}}
                                                        <span> {{$web_setting[19]->value}}{{$products->products_price+0}}</span>
                                                    @else
                                                        {{$web_setting[19]->value}}{{$products->products_price+0}}
                                                    @endif
                                                </div>
                                                
                                                <div class="buttons">
                                                    @if($products->products_type==0)
                                                        @if(!in_array($products->products_id,$result['cartArray']))
                                                            <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                        @elseif($products->products_min_order>1)
                                                            <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
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
                                        </div>
                                        
                                        <div class="product-hover">
                                            <div class="icons">
                                                <div class="icon-liked">
                                                    <i class="fa fa-times wishlist_liked" aria-hidden="true" products_id = '{{$products->products_id}}' ></i>
                                                </div>  
                                                @if($products->products_type!=2)
                                                	<a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="fa fa-eye"></a>
                                                @endif                                          
                                            </div>
                                            
                                            
                                            <div class="buttons">                                      	
                                                
                                                @if($products->products_type==0)
                                                    @if(!in_array($products->products_id,$result['cartArray']))
                                                        @if($products->defaultStock==0)
                                                            <button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
                                                        @elseif($products->products_min_order>1)
                                                            <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                        @else
                                                            <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                        @endif
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
                                @endforeach
                            </div>
                            <div class="toolbar mt-3 loaded_content">
                            	<div class="form-inline">
                                    <div class="form-group  justify-content-start col-6">
                                    	
                                        <input id="record_limit" type="hidden" value="{{$result['limit']}}"> 
                                        <input id="total_record" type="hidden" value="{{$result['products']['total_record']}}">
                                       <label for="staticEmail" class="col-form-label">@lang('website.Showing')<span class="showing_record">{{$result['limit']}} </span> &nbsp; @lang('website.of')  &nbsp;<span class="showing_total_record">{{$result['products']['total_record']}}</span> &nbsp;@lang('website.results')</label>
                                        
                                    </div>
                                    <div class="form-group justify-content-end col-6">
                                        <input type="hidden" value="1" name="page_number" id="page_number">
                                        <?php
                                            if(!empty(app('request')->input('limit'))){
                                                $record = app('request')->input('limit');
                                            }else{
                                                $record = '15';
                                            }
                                        ?>
                                        <button class="btn btn-dark " type="button" id="load_wishlist" @if(count($result['products']['product_data']) < $record ) style="display:none" @endif >@lang('website.Load More')</button>

                                    </div>
                                </div>
                            </div>
                           
                           @endif
                           <div id="loaded_content_empty" @if($result['products']['success']==1) style="display: none;" @endif>
                           		@lang('website.product is not added to your wish list')
                           </div>
                        </div>
                    </div>  
                </div>
        	</div>
            </form>
		</div>
    </div>
</section>
@endsection 