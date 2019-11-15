<header id="header-area" class="header-area bg-primary">
	<div class="header-mini">
    	<div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                
                	<nav id="navbar_0" class="navbar navbar-expand-md navbar-dark navbar-0 p-0">
                        <div class="navbar-brand">
                            <select name="change_language" id="change_language" class="change-language">
                            @foreach($languages as $languages_data)                               
                                <option value="{{$languages_data->code}}" data-class="{{$languages_data->code}}" data-style="background-image: url({{asset('').$languages_data->image}});" @if(session('locale')==$languages_data->code) selected @endif>{{$languages_data->name}}</option>
                            @endforeach 
                            </select>
                        </div>                    
                    
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_collapse_0" aria-controls="navbar_collapse_0" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbar_collapse_0">
                            <ul class="navbar-nav">
                                            
                                @if (Auth::guard('customer')->check())
                                    <li class="nav-item">
                                        <div class="nav-link">
                                            <span class="p-pic"><img src="{{asset('').auth()->guard('customer')->user()->customers_picture}}" alt="image"></span>@lang('website.Welcome')&nbsp;{{ auth()->guard('customer')->user()->customers_firstname }}&nbsp;{{ auth()->guard('customer')->user()->customers_lastname }}!
                                        </div>
                                    </li>
                                   <li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">@lang('website.Wishlist')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">@lang('website.Orders')</a> </li>
                                    
                                    <li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
                                @else
                                    <li class="nav-item"><div class="nav-link">@lang('website.Welcome Guest!')</div></li>
                                    <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;@lang('website.Login/Register')</a> </li>
                                @endif
                            </ul> 
                        </div>   
                	</div>
                 </nav>
            </div>
        </div>
    </div>
    <div class="header-maxi">
    	<div class="container">
        	<div class="row align-items-center">
            	<div class="col-12 col-sm-12 col-lg-3 spaceright-0">
                    <a href="{{ URL::to('/')}}" class="logo">
                    	@if($result['commonContent']['setting'][78]->value=='name')
                        	<?=stripslashes($result['commonContent']['setting'][79]->value)?>
                        @endif
                        
                        @if($result['commonContent']['setting'][78]->value=='logo')
                            <img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
                        @endif
                    </a>
                </div>
                
                 <div class="col-12 col-sm-7 col-md-8 col-lg-6 px-0">      
                    <form class="form-inline" action="{{ URL::to('/shop')}}" method="get">
                    <div class="search-categories">
                    <select id="category_id" name="category">
                    <option value="all">@lang('website.All Categories')</option>     
                        @foreach($result['commonContent']['categories'] as $categories_data)
                        	<option value="{{$categories_data->slug}}" @if($categories_data->slug==app('request')->input('category')) selected @endif>{{$categories_data->name}}</option>
                            @if(count($categories_data->sub_categories)>0)
                                @foreach($categories_data->sub_categories as $sub_categories_data)
                                <option value="{{$sub_categories_data->sub_slug}}" @if($sub_categories_data->sub_slug==app('request')->input('category')) selected @endif>--{{$sub_categories_data->sub_name}}</option>
                                @endforeach
                            @endif	
                        @endforeach						
                    </select>
                    <input type="search"  name="search" placeholder="@lang('website.Search entire store here')..." value="{{ app('request')->input('search') }}" aria-label="Search">
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                    </form>
				</div>
                <div class="col-12 col-sm-5 col-md-4 col-lg-3 spaceleft-0">
                <ul class="top-right-list">
                
                    
                    <li class="wishlist-header">
                        <a href="{{ URL::to('/wishlist')}}">
                            <span class="badge badge-secondary" id="wishlist-count">{{$result['commonContent']['totalWishList']}}</span>
                            <!--<img class="img-fluid" src="{{asset('').'public/images/wishlist_bag.png'}}" alt="icon">-->
                            
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-shopping-bag fa-stack-2x"></i>
                              <i class="fa fa-heart fa-stack-2x"></i>
                            </span>
                        </a>
                    </li>
                
                    <li class="cart-header dropdown head-cart-content"></li>
                </ul>
              </div>
            </div>
        </div>
    </div>
    <div class="header-navi">
    	<div class="container">
        	<div class="row align-items-center">
            
            	<div class="col-12">
                	<nav id="navbar_1" class="navbar navbar-expand-lg navbar-dark navbar-1 p-0 d-none d-lg-block">
                       
                        <div class="collapse navbar-collapse" id="navbar_collapse_1">
                        
                          <ul class="navbar-nav"> 
                            <li class="nav-item first"><a href="{{ URL::to('/')}}" class="nav-link"><i class="fa fa-home"></i></a></li>   
                            <!--<li class="nav-item dropdown open">
                                <a class="nav-link dropdown-toggle" href="">@lang('website.homePages')</a>
                                <ul class="dropdown-menu" >
                                    <li> <a class="dropdown-item" href="{{ URL::to('/setStyle?style=one')}}" >@lang('website.homePage1')</a> </li>
                                    <li> <a class="dropdown-item" href="{{ URL::to('/setStyle?style=two')}}">@lang('website.homePage2')</a> </li>
                                    <li> <a class="dropdown-item" href="{{ URL::to('/setStyle?style=three')}}">@lang('website.homePage3')</a> </li>
                                </ul>
                            </li>-->
                            <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/shop')}}">@lang('website.Shop')</a> </li>
                            
                            <li class="nav-item dropdown mega-dropdown open">
                              <a href="javascript:void(0);" class="nav-link dropdown-toggle">
                                @lang('website.collection')
                                <span class="badge badge-secondary">@lang('website.hot')</span>
                              </a>
                    
                              <ul class="dropdown-menu mega-dropdown-menu row" >
                                <li class="col-sm-3">
                                  <ul>
                                    <li class="dropdown-header underline">@lang('website.new in Stores')</li>
                                    
                                    @if($result['commonContent']['recentProducts']['success']==1)
                                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                                          <div class="carousel-inner">
                                          
                                        @foreach($result['commonContent']['recentProducts']['product_data'] as $key=>$products)
                                            <div class="carousel-item @if($key==0) active @endif">
                                                <span products_id = '{{$products->products_id}}' class="fa @if($products->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">2</span></span>
                                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><img src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"></a>
                                                <small>@foreach($products->categories as $key=>$category)
                                                	{{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif                                                	
                                                @endforeach</small>
                                                <h5>{{$products->products_name}}</h5>
                                                
                                                <div class="block">
                                                    <span class="price">
                                                        @if(!empty($products->discount_price))
                                                            <span class="line-through">{{$web_setting[19]->value}}{{$products->discount_price+0}}</span>
                                                            {{$web_setting[19]->value}}{{$products->products_price+0}}
                                                        @else
                                                            {{$web_setting[19]->value}}{{$products->products_price+0}}
                                                        @endif
                                                    </span>
                                                    
                                                    <div class="buttons">
                                                        <button class="btn btn-dark" >@lang('website.View Detail')</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Item -->
                                        @endforeach
                                            
                                          </div>
                                          <!-- End Carousel Inner -->
                                        </div>
                                    @endif
                                    
                                  </ul>
                                </li>
                                <li class="col-sm-9 pl-4 row">
                                @foreach($result['commonContent']['categories'] as $categories_data)
                                    
                                      <ul class="col-sm-4">
                                            
                                        <li class="dropdown-header"><a href="{{ URL::to('/shop')}}?category={{$categories_data->slug}}">{{$categories_data->name}}</a></li>
                                          @if(count($categories_data->sub_categories)>0)
                                             @foreach($categories_data->sub_categories as $sub_categories_data)
                                                <li><a href="{{ URL::to('/shop')}}?category={{$sub_categories_data->sub_slug}}">{{$sub_categories_data->sub_name}}</a></li>              		
                                             @endforeach  
                                          @endif 
                                      
                                      </ul>        
                                @endforeach 
                                </li> 
                              </ul>                    
                            </li>
                            <li class="nav-item dropdown open">
                                <a class="nav-link dropdown-toggle" href="{{ URL::to('/news/')}}">@lang('website.News')</a>
                    
                                <ul class="dropdown-menu" > 
                                @foreach($result['commonContent']['newsCategories'] as $categories)             	
                                    <li>                
                                        <a class="dropdown-item" href="{{ URL::to('/news?category='.$categories->slug)}}">{{$categories->name}}</a>                 
                                    </li>
                                @endforeach
                                </ul>    
                            </li>
                            <li class="nav-item dropdown open">
                                <a href="" class="nav-link dropdown-toggle">@lang('website.infoPages')</a>
                            
                                <ul class="dropdown-menu">
                                    @if(count($result['commonContent']['pages']))
                                    @foreach($result['commonContent']['pages'] as $page)
                                        <li> <a href="{{ URL::to('/page?name='.$page->slug)}}" class="dropdown-item">{{$page->name}}</a> </li>
                                    @endforeach
                                    @endif  
                                </ul>
                            </li>
                            
                            <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/contact-us')}}">@lang('website.Contact Us')</a> </li>
                            <li class="nav-item last"><a class="nav-link"><span>@lang('website.hotline')</span>({{$result['commonContent']['setting'][11]->value}})</a></li>
                          </ul>
                        </div>
                    </nav>
                    
                    
                    <nav id="navbar_2" class="navbar navbar-expand-lg navbar-dark navbar-2 p-0 d-block d-lg-none">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_collapse_2" aria-controls="navbar_collapse_2" aria-expanded="false" aria-label="Toggle navigation"> @lang('website.Menu') </button>
                        
                        <div class="collapse navbar-collapse" id="navbar_collapse_2">
                        
                          <ul class="navbar-nav"> 
                            <li class="nav-item first"><a href="{{ URL::to('/')}}" class="nav-link"><i class="fa fa-home"></i></a></li>   
<!--
                            <li class="nav-item dropdown open">
                                <div class="nav-link dropdown-toggle">@lang('website.homePages')</div>
                                <ul class="dropdown-menu" >
                                    <li> <a class="dropdown-item" href="{{ URL::to('/setStyle?style=one')}}" >@lang('website.homePage1')</a> </li>
                                    <li> <a class="dropdown-item" href="{{ URL::to('/setStyle?style=two')}}">@lang('website.homePage2')</a> </li>
                                    <li> <a class="dropdown-item" href="{{ URL::to('/setStyle?style=three')}}">@lang('website.homePage3')</a> </li>
                                </ul>
                            </li>
-->
                            <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/shop')}}">@lang('website.Shop')</a> </li>
                            
                            <li class="nav-item dropdown mega-dropdown open">
                              <div class="nav-link dropdown-toggle">
                                @lang('website.collection')
                                <span class="badge badge-secondary">@lang('website.hot')</span>
                              </div>
                    
                              <ul class="dropdown-menu mega-dropdown-menu row" >
                                <li class="col-sm-3">
                                  <ul>
                                    <li class="dropdown-header underline">@lang('website.new in Stores')</li>
                                    
                                    @if($result['commonContent']['recentProducts']['success']==1)
                                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                                          <div class="carousel-inner">                                          
                                        @foreach($result['commonContent']['recentProducts']['product_data'] as $key=>$products)
                                            <div class="carousel-item @if($key==0) active @endif">
                                                <span products_id = '{{$products->products_id}}' class="fa @if($products->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">2</span></span>
                                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><img src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"></a>
                                                <small>@foreach($products->categories as $key=>$category)
                                                	{{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif                                                	
                                                @endforeach</small>
                                                <h5>{{$products->products_name}}</h5>
                                                
                                                <div class="block">
                                                    <span class="price">
                                                        @if(!empty($products->discount_price))
                                                            <span class="line-through">{{$web_setting[19]->value}}{{$products->discount_price+0}}</span>
                                                            {{$web_setting[19]->value}}{{$products->products_price+0}}
                                                        @else
                                                            {{$web_setting[19]->value}}{{$products->products_price+0}}
                                                        @endif
                                                    </span>                                                    
                                                    <div class="buttons">
                                                        <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="btn btn-dark" >@lang('website.View Detail')</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Item -->
                                        @endforeach
                                            
                                          </div>
                                          <!-- End Carousel Inner -->
                                        </div>
                                    @endif
                                    
                                  </ul>
                                </li>
                                <li class="col-sm-9 pl-4 row">
                                @foreach($result['commonContent']['categories'] as $categories_data)
                                    
                                      <ul class="col-sm-4">
                                            
                                        <li class="dropdown-header"><a href="{{ URL::to('/shop')}}?category={{$categories_data->slug}}">{{$categories_data->name}}</a></li>
                                          @if(count($categories_data->sub_categories)>0)
                                             @foreach($categories_data->sub_categories as $sub_categories_data)
                                                <li><a href="{{ URL::to('/shop')}}?category={{$sub_categories_data->sub_slug}}">{{$sub_categories_data->sub_name}}</a></li>              		
                                             @endforeach  
                                          @endif 
                                      
                                      </ul>        
                                @endforeach 
                                </li>
                                
                                
                              </ul>
                    
                            </li>
                            <li class="nav-item dropdown open">
                                <div class="nav-link dropdown-toggle">@lang('website.News')</div>
                    
                                <ul class="dropdown-menu" > 
                                @foreach($result['commonContent']['newsCategories'] as $categories)             	
                                    <li>                
                                        <a class="dropdown-item" href="{{ URL::to('/news?category='.$categories->slug)}}">{{$categories->name}}</a>                 
                                    </li>
                                @endforeach
                                </ul>    
                            </li>
                            <li class="nav-item dropdown open">
                                <div class="nav-link dropdown-toggle">@lang('website.infoPages')</div>
                            
                                <ul class="dropdown-menu">
                                    @if(count($result['commonContent']['pages']))
                                    @foreach($result['commonContent']['pages'] as $page)
                                        <li> <a href="{{ URL::to('/page?name='.$page->slug)}}" class="dropdown-item">{{$page->name}}</a> </li>
                                    @endforeach
                                    @endif  
                                </ul>
                            </li>
                            
                            <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/contact-us')}}">@lang('website.Contact Us')</a> </li>
                            <li class="nav-item last"><a class="nav-link"><span>@lang('website.hotline')</span>({{$result['commonContent']['setting'][11]->value}})</a></li>
                          </ul>
                        </div>
                    </nav>
                </div>
            </div>	
        </div>
    </div>
      
</header>