@extends('layout')
@section('content')

<section class="site-content">
	<div class="container">
    	<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Shop')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    
                    @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                    	<li class="breadcrumb-item"><a href="{{ URL::to('/shop')}}">@lang('website.Shop')</a></li>
                    	<li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
						<li class="breadcrumb-item active">{{$result['sub_category_name']}}</li>
                    @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                    	<li class="breadcrumb-item"><a href="{{ URL::to('/shop')}}">@lang('website.Shop')</a></li>
                    	<li class="breadcrumb-item active">{{$result['category_name']}}</li>
                    @else                    
                    	<li class="breadcrumb-item active">@lang('website.Shop')</li>
                    @endif
                </ol>
            </div>
        </div>
		<div class="shop-area">
			<form method="get" enctype="multipart/form-data" id="load_products_form">
               @if(!empty(app('request')->input('search')))
                <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
               @endif
               @if(!empty(app('request')->input('category')))
                <input type="hidden"  name="category" value="@if(app('request')->input('category')!='all'){{ app('request')->input('category') }} @endif">
               @endif
                <input type="hidden"  name="load_products" value="1">                
                <div class="row">                
                    <div class="col-12 col-lg-3 spaceright-0">
                        @include('common.sidebar_shop')
                    </div>
                    
       
                    <div class="col-12 col-lg-9">
                        <div class="col-12 spaceright-0">
                        	<div class="row">
                            
                            @if(!empty(app('request')->input('search')))
                                <div class="search-result">
                                    <h4>@lang('website.Search result for') '{{app('request')->input('search')}}' @if($result['products']['total_record']>0) {{$result['products']['total_record']}} @else 0 @endif @lang('website.item found') <h4>
                                </div>
                            @endif
                            
                             @if($result['products']['total_record']>0)
                                
                                <div class="toolbar mb-3">
                                    <div class="form-inline">
                                        <div class="form-group col-12 col-md-4">
                                            <label class="col-12 col-lg-5 col-form-label">@lang('website.Display')</label>
                                            <div class="col-12 col-lg-7 btn-group">
                                                <a href="javascript:void(0);" id="grid" class="btn btn-default active"> <i class="fa fa-th-large" aria-hidden="true"></i> </a>
                                                <a href="javascript:void(0);" id="list" class="btn btn-default"> <i class="fa fa-list" aria-hidden="true"></i> </a>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-4 center">
                                            <label class="col-12 col-lg-4 col-form-label">@lang('website.Sort')</label>
                                            <select class="col-12 col-lg-6 form-control sortby" name="type">
                                                <option value="desc" @if(app('request')->input('type')=='desc') selected @endif>@lang('website.Newest')</option>
                                                <option value="atoz" @if(app('request')->input('type')=='atoz') selected @endif>@lang('website.A - Z')</option>
                                                <option value="ztoa" @if(app('request')->input('type')=='ztoa') selected @endif>@lang('website.Z - A')</option>
                                                <option value="hightolow" @if(app('request')->input('type')=='hightolow') selected @endif>@lang('website.Price: High To Low')</option>
                                                <option value="lowtohigh" @if(app('request')->input('type')=='lowtohigh') selected @endif>@lang('website.Price: Low To High')</option>
                                                <option value="topseller" @if(app('request')->input('type')=='topseller') selected @endif>@lang('website.Top Seller')</option>
                                                <option value="special" @if(app('request')->input('type')=='special') selected @endif>@lang('website.Special Products')</option>
                                                <option value="mostliked" @if(app('request')->input('type')=='mostliked') selected @endif>@lang('website.Most Liked')</option>
                                            </select>
                                        </div>                                
                                        <div class="form-group col-12 col-md-4">
                                            <label class="col-12 col-lg-4 col-form-label">@lang('website.Limit')</label>
                                            <select class="col-12 col-lg-3 form-control sortby" name="limit">
                                                <option value="15" @if(app('request')->input('limit')=='15') selected @endif>15</option>
                                                <option value="30" @if(app('request')->input('limit')=='30') selected @endif>30</option>
                                                <option value="60" @if(app('request')->input('limit')=='60') selected @endif>60</option>
                                            </select>
                                            <label class="col-12 col-lg-5 col-form-label">@lang('website.per page')</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- products-3x for gird -->
                                <!--products-list for list-->
                                <div class="products products-3x" id="listing-products">
                                    @if($result['products']['success']==1)
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
                                                        @if(!in_array($products->products_id,$result['cartArray']))
                                                            <button type="button" class="btn btn-secondary btn-round cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                        @elseif($products->products_min_order>1)
                                                             <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                        @else
                                                            <button type="button"  class="btn btn-secondary btn-round acitve">@lang('website.Added')</button>
                                                        @endif
                                                    </div>
                                                </div>
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
                                    @endif
                                </div>
                        		
                                <div class="toolbar mt-3">
                                    <div class="form-inline">
                                        <div class="form-group  justify-content-start col-6">
                                        	<input id="record_limit" type="hidden" value="{{$result['limit']}}"> 
                                        	<input id="total_record" type="hidden" value="{{$result['products']['total_record']}}"> 
                                            <label for="staticEmail" class="col-form-label"> @lang('website.Showing')<span class="showing_record">{{$result['limit']}} </span> &nbsp; @lang('website.of')  &nbsp;<span class="showing_total_record">{{$result['products']['total_record']}}</span> &nbsp;@lang('website.results')</label>                                            
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
                                            <button class="btn btn-dark" type="button" id="load_products" 
                                            @if(count($result['products']['product_data']) < $record ) 
                                                style="display:none"
                                            @endif 
                                            >@lang('website.Load More')</button>        
                                        </div>
                                    </div>
                                </div>  
                                @elseif(empty(app('request')->input('search')))
                                    <p>@lang('website.Record not found')</p>
                                @endif                             
                            </div>
                        </div>
                        
                    </div>
                    
                                        
				</div>
			</form>
		</div>
	</div>
</section>
@endsection 