 <div class="container-fuild">
  <div class="container">
    <div class="products-area"> 
		<!-- heading -->
      
        <div class="heading">
        <h2>@lang('website.Top Selling of the Week') <small class="pull-right"><a href="{{ URL::to('/shop?type=topseller')}}" >@lang('website.View All')</a></small></h2>
        <hr>
        </div>
      	<div class="row"> 
        	<div class="col-xs-12 col-sm-12">
            	<!-- Items -->
                <div class="row">
                  	<div class="products products-5x">
                  	@if($result['featured']['success']==1)                
                    @foreach($result['featured']['product_data'] as $key=>$products)
                    @if($key==0)
                    
                    <div class="product product-2x">
                        <span class="product-featured-tag"><i class="fa fa-flag-o" aria-hidden="true"></i>&nbsp;@lang('website.Featured')</span>
                        <div class="buttons-liked">
                        	
                            <span products_id = '{{$products->products_id}}' class="fa @if($products->isLiked==1) fa-heart @else fa-heart-o @endif is_liked">
                            	<span class="badge badge-secondary">{{$products->products_liked}}</span>
                            </span>
                        </div>
                        
                        <article>
                            <div class="thumb"><img class="img-fluid" src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"></div>
                            <span class="tag" style="display: inline-block; min-height: inherit;">@foreach($products->categories as $key=>$category)
                                                	{{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif                                            	
                                                @endforeach</span>
                            <h2 class="title wrap-dot-1"><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">{{$products->products_name}}</a></h2>
                            <div class="price">
                                @if(!empty($products->discount_price))
                              
                                    {{$web_setting[19]->value}}{{$products->discount_price+0}} <span>{{$web_setting[19]->value}}{{$products->products_price+0}}</span>
                                @else
                                    
                                    {{$web_setting[19]->value}}{{$products->products_price+0}}
                                
                                @endif
                            </div>
                            <div class="block"> 
                                @if(count($products->attributes)>0) 
                                
                                    @foreach( $products->attributes as $key=>$attributes_data )
                                    
                                    @if($key==1)
                                    
                                    @endif 
                                    
                                    <span class="option-name">{{ $attributes_data['option']['name'] }}</span>
                                    
                                    @foreach( $attributes_data['values'] as $key=>$values_data )
                                    
                                    <span class="option-value">{{ $values_data['value'] }}</span>
                                    
                                    @if($key+1!=count($attributes_data['values']))
                                    
                                    <span class="option-value">|</span>
                                    
                                    @endif
                                    
                                    @endforeach
                                    
                                    @endforeach
                                
                                @endif
                            
                            </div>
                            
                            <div class="buttons">
                                @if($products->products_type==0)
                                    @if(!in_array($products->products_id,$result['cartArray']))
                                       @if($products->defaultStock==0)
                                            <button type="button" class="btn btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
                                        @elseif($products->products_min_order>1)
                                   		 <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                   		@else
                                            <button type="button" class="btn btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-secondary active">@lang('website.Added')</button>
                                    @endif
                                @elseif($products->products_type==1)
                                    <a class="btn btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                @elseif($products->products_type==2)
                                    <a href="{{$products->products_url}}" target="_blank" class="btn btn-secondary">@lang('website.External Link')</a>
                                @endif
                           </div>  
                        </article>
                    </div>
       
                    @endif
                    @endforeach
                    @endif 
                    
                    <!-- Product sold -->
                    @if($result['weeklySoldProducts']['success']==1)                
                    @foreach($result['weeklySoldProducts']['product_data'] as $key=>$products)                
                        @if($key<=7)
                            <div class="product">
                              <article>
                                <div class="thumb"> <img class="img-fluid" src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"> </div>
                                    <?php
            
                                        $current_date = date("Y-m-d", strtotime("now"));
            
                                        $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));            
                                        $date=date_create($string);            
                                        date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days")); 
            
                                        $after_date = date_format($date,"Y-m-d");            
                                        if($after_date>=$current_date){            
                                            print '<span class="new-tag">';            
                                            print __('website.New');            
                                            print '</span>';            
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
                                            {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                                        @endforeach
                                    </span>
                                    
                                    <h2 class="title text-center wrap-dot-1"> {{$products->products_name}}</a></h2>                                
                                    <div class="price text-center"> @if(!empty($products->discount_price))                                  
                                        {{$web_setting[19]->value}}{{$products->discount_price+0}} <span>{{$web_setting[19]->value}}{{$products->products_price+0}}</span> @else                                        
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
                        @endif 
                    
                    @endforeach
                    
                    @endif
                    </div>
                </div>
            </div>
      	</div>
    </div>
    
    <div class="group-banners">
    	<div class="row">
        	<div class="col-xs-12 col-md-12">
           		@if(count($result['commonContent']['homeBanners'])>0)
                    @foreach(($result['commonContent']['homeBanners']) as $homeBanners)                
                        @if($homeBanners->type==6)
                        <div class="banner-image en">
                            <a title="Banner Image" href="{{ $homeBanners->banners_url}}"><img class="img-fluid" src="{{asset('').$homeBanners->banners_image}}" alt="Banner Image"></a>
                        </div>
                        @endif                
                    @endforeach
                @endif 
            </div>
        </div>
    </div>
  </div>
</div>

<div class="container-fuild">
  <div class="container">
    <div class="products-area"> 
        <!-- heading -->
        <div class="heading">
        	<h2>@lang('website.Special products of the Week') <small class="pull-right"><a href="{{ URL::to('/shop?type=special')}}" >@lang('website.View All')</a></small></h2>
        	<hr>
        </div>
        <div class="row">         
            
            <div class="col-xs-12 col-sm-12">
                <div class="row">
                	<!-- Items -->
                    <div class="products products-5x">
                        <!-- Product --> 
                        
                        @if($result['special']['success']==1)
                        @foreach($result['special']['product_data'] as $key=>$special)
                        @if($key<=9)
                        <div class="product">
                          <article>
                            <div class="thumb"><img class="img-fluid" src="{{asset('').$special->products_image}}" alt="{{$special->products_name}}"></div>
                            <?php
                                    $current_date = date("Y-m-d", strtotime("now"));
                                    
                                    $string = substr($special->products_date_added, 0, strpos($special->products_date_added, ' '));
                                    $date=date_create($string);
                                    date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                                    
                                    //echo $top_seller->products_date_added . "<br>";
                                    $after_date = date_format($date,"Y-m-d");
                                    
                                    if($after_date>=$current_date){
                                        print '<span class="new-tag">';
                                        print __('website.New');
                                        print '</span>';
                                    }
                                    
                                    if(!empty($special->discount_price)){
                                        $discount_price = $special->discount_price;	
                                        $orignal_price = $special->products_price;	
                                        
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
                            @foreach($special->categories as $key=>$category)
                            	{{$category->categories_name}}@if(++$key === count($special->categories)) @else, @endif
                        	@endforeach
                            </span>
                            <h2 class="title text-center wrap-dot-1">{{$special->products_name}}</h2>                          
                            
                            <div class="price text-center">
                            {{$web_setting[19]->value}}{{$special->discount_price+0}}<span>{{$web_setting[19]->value}}{{$special->products_price+0}}</span></div>
                            <div class="product-hover">
                                <div class="icons">
                                    <div class="icon-liked">
                                        <span products_id = '{{$special->products_id}}' class="fa @if($special->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$special->products_liked}}</span></span>
                                    </div>
                                    @if($special->products_type!=2)
                                        <a href="{{ URL::to('/product-detail/'.$special->products_slug)}}" class="fa fa-eye"></a>
                                    @endif
                                </div>
                                
                                <div class="buttons">                                    
                                    @if($special->products_type==0)
                                        @if(!in_array($special->products_id,$result['cartArray']))
                                            @if($special->defaultStock==0)
                                                <button type="button" class="btn btn-block btn-danger" products_id="{{$special->products_id}}">@lang('website.Out of Stock')</button>
                                            @elseif($products->products_min_order>1)
                                             <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                            @else
                                                <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$special->products_id}}">@lang('website.Add to Cart')</button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                        @endif
                                    @elseif($special->products_type==1)
                                        <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$special->products_slug)}}">@lang('website.View Detail')</a>
                                    @elseif($special->products_type==2)
                                        <a href="{{$special->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
                                    @endif
                                </div>
                                
                             </div>
                          </article>
                        </div>
                        @endif
                        @endforeach
                        
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group-banners">
    	<div class="row">
        	<div class="col-xs-12 col-sm-12">
                @if(count($result['commonContent']['homeBanners'])>0)
                    @foreach(($result['commonContent']['homeBanners']) as $homeBanners)                
                        @if($homeBanners->type==7)
                        <div class="banner-image">
                            <a title="Banner Image" href="{{ $homeBanners->banners_url}}"><img class="img-fluid" src="{{asset('').$homeBanners->banners_image}}" alt="Banner Image"></a>
                        </div>
                        @endif                
                    @endforeach
                @endif                 
            </div>
        </div>
    </div>
    
  </div>
</div>

<div class="container-fuild">
  <div class="container">
    <div class="products-area"> 
      <!-- heading -->
      <div class="heading">
        <h2>@lang('website.Categories') <small class="pull-right"><!--<a href="shop" >@lang('website.View All')</a>--></small></h2>
        <hr>
      </div>
        <div class="row"> 
            <div class="col-xs-12 col-sm-12">
                <div class="row">
                    <!-- Items -->
                    <div class="products products-5x">
                        <!-- categories --> 
                        <?php $counter = 0;?>
                        @foreach($result['commonContent']['categories'] as $categories_data)
                                @if($counter<=9)
                                <div class="product">
                                    <div class="blog-post">
                                        <article>
                                            <div class="module">
                                            	<a href="{{ URL::to('/shop?category='.$categories_data->slug)}}" class="cat-thumb">
                                                   <img class="img-fluid" src="{{asset('').$categories_data->image}}" alt="{{$categories_data->name}}">             
                                                </a>
                                                <a href="{{ URL::to('/shop?category='.$categories_data->slug)}}" class="cat-title">
                                                	{{$categories_data->name}}
                                                </a>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                                @endif	
                                <?php $counter++;?>
                        @endforeach	
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="container-fuild">
  <div class="container">
    <div class="products-area"> 
      <!-- heading -->
      <div class="heading">
        <h2>@lang('website.Newest Products') <small class="pull-right"><a href="{{ URL::to('/shop')}}" >@lang('website.View All')</a></small></h2>
        <hr>
      </div>
        <div class="row"> 
            <div class="col-xs-12 col-sm-12">
                <div class="row">
                    <!-- Items -->
                    <div class="products products-5x">
                        <!-- Product --> 
                        @if($result['products']['success']==1)              
                        @foreach($result['products']['product_data'] as $key=>$products)
                        <div class="product">
                          <article>
                            <div class="thumb"> <img class="img-fluid" src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"> </div>
                            <?php
        
                                    $current_date = date("Y-m-d", strtotime("now"));
        
                                    
        
                                    $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
        
                                    $date=date_create($string);
        
                                    date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                                    $after_date = date_format($date,"Y-m-d");
        
                                    
        
                                    if($after_date>=$current_date){
        
                                        print '<span class="new-tag">';
        
                                        print __('website.New');
        
                                        print '</span>';
        
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
                                {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                            @endforeach
                            </span>
                            <h2 class="title text-center wrap-dot-1">{{$products->products_name}}</h2>
                            <div class="price text-center"> @if(!empty($products->discount_price))
                              
                              {{$web_setting[19]->value}}{{$products->discount_price+0}} <span> {{$web_setting[19]->value}}{{$products->products_price+0}}</span> @else
                              
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
                </div>
            </div>
        </div>
    </div>
    
    
  </div>
</div>


