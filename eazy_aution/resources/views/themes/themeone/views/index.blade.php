@extends('layout')
@section('content')

<section class="site-content">
  <div class="container">
     <div class="group-banners">
    	<div class="row">
        	@if(count($result['commonContent']['homeBanners'])>0)
                @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                    @if($homeBanners->type==3 or $homeBanners->type==4 or $homeBanners->type==5)
                    <div class="col-12 col-sm-4">
                        <div class="banner-image">
                            <a title="Banner Image" href="{{ $homeBanners->banners_url}}"><img class="img-fluid" src="{{asset('').$homeBanners->banners_image}}" alt="Banner Image"></a>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

   @if($result['flash_sale']['success']==1)
    <!-- dynamic content -->
   <div class="products-area">
      <div class="row">
        <div class="col-md-12">
          <div class="nav nav-pills" role="tablist">
            <a class="nav-link nav-item nav-index active" href="#special" id="flashsale-tab" data-toggle="pill" role="tab" aria-controls="flashsale" aria-selected="false">@lang('website.Flash Sale')</a>
          </div>
          <!-- Tab panes -->
          <div class="tab-content">
          	<div class="overlay" style="display:none;"><img src="{{asset('').'public/images/loader.gif'}}"></div>

            <div role="tabpanel" class="tab-pane fade show active" id="flashsale" role="tabpanel" aria-labelledby="flashsale-tab">
              <div id="owl_flashsale" class="owl-carousel">

                @foreach($result['flash_sale']['product_data'] as $key=>$products)

                @if( $products->server_time >= $products->flash_start_date)
                <div class="product" id="product_div_{{$products->products_id}}">
                  <article>
                  	<div class="thumb"><img class="img-fluid" src="{{asset('').$products->products_image}}" alt="{{$products->products_name}}"></div>
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

						if(!empty($products->flash_price)){
							$discount_price = $products->flash_price;
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
                     <div class="sale-counter">
                     <span  id="counter_{{$products->products_id}}"></span>
                     </div>

                     <span class="tag text-center">
                        @foreach($products->categories as $key=>$category)
                            {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                        @endforeach
                    </span>

                    <h2 class="title text-center wrap-dot-1">{{$products->products_name}}</h2>

                    <div class="price text-center">
                    	{{$web_setting[19]->value}}{{$products->flash_price+0}}
                    	<span>{{$web_setting[19]->value}}{{$products->products_price+0}} </span>
                    </div>

                    <div class="product-hover">


                        <div class="buttons">
                        	 @if($products->products_type==0)
                                @if(!in_array($products->products_id,$result['cartArray']))
                                    @if($products->defaultStock==0)
                                        <button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
                                    @else
                                        <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
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




                @foreach($result['flash_sale']['product_data'] as $key=>$products)

                @if( $products->server_time < $products->flash_start_date)

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
							print '<span class="new-tag">';
							print __('website.New');
							print '</span>';
						}

						if(!empty($products->flash_price)){
							$discount_price = $products->flash_price;
							$orignal_price = $products->products_price;

							if(($orignal_price+0)>0){
								$discounted_price = $orignal_price-$discount_price;
								$discount_percentage = $discounted_price/$orignal_price*100;
							}else{
								$discount_percentage = 0;
							}
							echo "<span class='discount-tag' >".(int)$discount_percentage."%</span>";
						}

			  		 ?>
                     <span class="discount-tag upcomming-tag" style='top :38px'>@lang('website.UP COMMING')</span>

                     <span class="tag text-center">
                        @foreach($products->categories as $key=>$category)
                            {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                        @endforeach
                    </span>

                    <h2 class="title text-center wrap-dot-1">{{$products->products_name}}</h2>

                    <div class="price text-center">
                    	{{$web_setting[19]->value}}{{$products->flash_price+0}}
                    	<span>{{$web_setting[19]->value}}{{$products->products_price+0}} </span>
                    </div>

                    <div class="product-hover">


                        <div class="buttons">
                        	 @if($products->products_type==0)
                                @if(!in_array($products->products_id,$result['cartArray']))
                                    @if($products->defaultStock==0)
                                        <button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
                                    @else
                                        <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
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



                </div>
            </div>


          </div>
        </div>
      </div>
    </div>
   @endif
    <div class="products-area">
      <div class="row">
        <div class="col-md-12">
          <div class="nav nav-pills" role="tablist">
          	@if($result['top_seller']['success']==1)
             <a class="nav-link nav-item nav-index active" href="#featured" id="featured-tab" data-toggle="pill" role="tab" aria-controls="featured" aria-selected="true">@lang('website.TopSales')</a>
            @endif
            @if($result['special']['success']==1)
            <a class="nav-link nav-item nav-index" href="#special" id="special-tab" data-toggle="pill" role="tab" aria-controls="special" aria-selected="false">@lang('website.Special')</a>
            @endif
            @if($result['most_liked']['success']==1)
             <a class="nav-link nav-item nav-index" href="#liked" id="liked-tab" data-toggle="pill" role="tab" aria-controls="liked" aria-selected="false">@lang('website.MostLiked')</a>
             @endif
          </div>

          <!-- Tab panes -->
          <div class="tab-content">
          	<div class="overlay" style="display:none;"><img src="{{asset('').'public/images/loader.gif'}}"></div>
            @if($result['top_seller']['success']==1)
            <div role="tabpanel" class="tab-pane fade show active" id="featured" role="tabpanel" aria-labelledby="featured-tab">
              <div id="owl_featured" class="owl-carousel owl_featured">

              	@foreach($result['top_seller']['product_data'] as $key=>$top_seller)

                <div class="product">
                  <article>

                       <div class="thumb"> <img class="img-fluid" src="{{asset('').$top_seller->products_image}}" alt="{{$top_seller->products_name}}"></div>
						<?php
                                $current_date = date("Y-m-d", strtotime("now"));

                                $string = substr($top_seller->products_date_added, 0, strpos($top_seller->products_date_added, ' '));
                                $date=date_create($string);
                                date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));

                                //echo $top_seller->products_date_added . "<br>";
                                $after_date = date_format($date,"Y-m-d");

                                if($after_date>=$current_date){
                                    print '<span class="new-tag">';
                                    print __('website.New');
                                    print '</span>';
                                }

                                if(!empty($top_seller->discount_price)){
                                    $discount_price = $top_seller->discount_price;
                                    $orignal_price = $top_seller->products_price;

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
                            @foreach($top_seller->categories as $key=>$category)
                            	{{$category->categories_name}}@if(++$key === count($top_seller->categories)) @else, @endif
                        	@endforeach
                        </span>
                        <h2 class="title text-center wrap-dot-1">{{$top_seller->products_name}}</h2>
                        <div class="price text-center">
                        	@if(!empty($top_seller->discount_price))
                          	{{$web_setting[19]->value}}{{$top_seller->discount_price+0}}
                          	<span> {{$web_setting[19]->value}}{{$top_seller->products_price+0}}</span>
                          	@else
                          		{{$web_setting[19]->value}}{{$top_seller->products_price+0}}

                          	@endif
						</div>


                     <div class="product-hover">
                     	<div class="icons">
                        	<div class="icon-liked">

                            	<span products_id = '{{$top_seller->products_id}}' class="fa @if ($top_seller->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$top_seller->products_liked}}</span></span>
                            </div>

                            @if($top_seller->products_type!=2)
                                <a href="{{ URL::to('/product-detail/'.$top_seller->products_slug)}}" class="fa fa-eye"></a>
                            @endif
                        </div>
                        <div class="buttons">
                        	 @if($top_seller->products_type==0)
                                @if(!in_array($top_seller->products_id,$result['cartArray']))
                                   @if($top_seller->defaultStock==0)
                                        <button type="button" class="btn btn-block btn-danger" products_id="{{$top_seller->products_id}}">@lang('website.Out of Stock')</button>
                                    @elseif($top_seller->products_min_order>1)
                                   		 <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$top_seller->products_slug)}}">@lang('website.View Detail')</a>
                                    @else
                                       <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$top_seller->products_id}}">@lang('website.Add to Cart')</button>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                @endif
                            @elseif($top_seller->products_type==1)
                                <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$top_seller->products_slug)}}">@lang('website.View Detail')</a>
                            @elseif($top_seller->products_type==2)
                                <a href="{{$top_seller->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
                            @endif
                        </div>
                     </div>

                  </article>
                </div>
                @endforeach
                    <div class="product last-product">
                      <article>
                      	<a href="{{ URL::to('/shop?type=topseller')}}" class="buttons">
                        	<span class="fa fa-angle-right"></span>
                        	<span class="btn btn-secondary">@lang('website.View All')</span>
                        </a>

                      </article>
                    </div>

                </div>
              <!-- 1st tab -->
            </div>
            @endif
            @if($result['special']['success']==1)
            <div role="tabpanel" class="tab-pane fade" id="special" role="tabpanel" aria-labelledby="special-tab">
              <div id="owl_special" class="owl-carousel">

                @foreach($result['special']['product_data'] as $key=>$special)
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
                    	{{$web_setting[19]->value}}{{$special->discount_price+0}}
                    	<span>{{$web_setting[19]->value}}{{$special->products_price+0}} </span>
                    </div>

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
                                    @elseif($special->products_min_order>1)
                                   		 <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$special->products_slug)}}">@lang('website.View Detail')</a>
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
                @endforeach
                    <div class="product last-product">
                      <article>
                      	<a href="{{ URL::to('/shop?type=special')}}" class="buttons">
                        	<span class="fa fa-angle-right"></span>
                        	<span class="btn btn-secondary">@lang('website.View All')</span>
                        </a>
                      </article>
                    </div>

                </div>
            </div>
            @endif
            @if($result['most_liked']['success']==1)
            <div role="tabpanel" class="tab-pane fade" id="liked" role="tabpanel" aria-labelledby="liked-tab">
              <div id="owl_liked" class="owl-carousel">

                @foreach($result['most_liked']['product_data'] as $key=>$most_liked)
                <div class="product">
                  <article>
                  	<div class="thumb"><img class="img-fluid" src="{{asset('').$most_liked->products_image}}" alt="{{$most_liked->products_name}}"></div>
                    <?php
						$current_date = date("Y-m-d", strtotime("now"));

						$string = substr($most_liked->products_date_added, 0, strpos($most_liked->products_date_added, ' '));
						$date=date_create($string);
						date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));

						//echo $top_seller->products_date_added . "<br>";
						$after_date = date_format($date,"Y-m-d");

						if($after_date>=$current_date){
							print '<span class="new-tag">';
							print __('website.New');
							print '</span>';
						}

						if(!empty($most_liked->discount_price)){
							$discount_price = $most_liked->discount_price;
							$orignal_price = $most_liked->products_price;

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
                        @foreach($most_liked->categories as $key=>$category)
                            {{$category->categories_name}}@if(++$key === count($most_liked->categories)) @else, @endif
                        @endforeach
                    </span>

                    <h2 class="title text-center wrap-dot-1">{{$most_liked->products_name}}</h2>

                    <div class="price text-center">
                      @if(!empty($most_liked->discount_price))
                      	{{$web_setting[19]->value}}{{$most_liked->discount_price+0}} <span>{{$web_setting[19]->value}}{{$most_liked->products_price+0}}</span> @else
                      	{{$web_setting[19]->value}}{{$most_liked->products_price+0}}
                      @endif
                    </div>

                    <div class="product-hover">
                     	<div class="icons">
                        	<div class="icon-liked">

                            	<span products_id = '{{$most_liked->products_id}}' class="fa @if($most_liked->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$most_liked->products_liked}}</span></span>
                            </div>
                            @if($most_liked->products_type!=2)
                                <a href="{{ URL::to('/product-detail/'.$most_liked->products_slug)}}" class="fa fa-eye"></a>
                            @endif
                        </div>

                        <div class="buttons">
                        	@if($most_liked->products_type==0)
                                @if(!in_array($most_liked->products_id,$result['cartArray']))
                                    @if($most_liked->defaultStock==0)
                                        <button type="button" class="btn btn-block btn-danger" products_id="{{$most_liked->products_id}}">@lang('website.Out of Stock')</button>
                                   @elseif($most_liked->products_min_order>1)
                                   		 <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$most_liked->products_slug)}}">@lang('website.View Detail')</a>
                                    @else
                                        <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$most_liked->products_id}}">@lang('website.Add to Cart')</button>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                @endif
                            @elseif($most_liked->products_type==1)
                                <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$most_liked->products_slug)}}">@lang('website.View Detail')</a>
                            @elseif($most_liked->products_type==2)
                                <a href="{{$most_liked->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
                            @endif

                        </div>
                     </div>

					</article>
                </div>
                @endforeach
                    <div class="product last-product">
                      <article>
                      	<a href="{{ URL::to('/shop?type=mostliked')}}" class="buttons">
                        	<span class="fa fa-angle-right"></span>
                        	<span class="btn btn-secondary">@lang('website.View All')</span>
                        </a>
                      </article>
                    </div>

                </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>


    <!-- ./end of dynamic content -->
  </div>
</section>

<section class="products-content"> @include('common.products') </section>

<section class="blog-content">
<div class="container">
    <div class="blog-area">
        <!-- heading -->
        <div class="heading">
            <h2>@lang('website.From our News') <small class="pull-right"><a href="{{ URL::to('/news')}}">@lang('website.View All')</a></small></h2>
            <hr>
        </div>

		<div class="row">
            <div class="blogs blogs-3x">
            	<!-- Blog Post -->
				@if($result['news']['success']==1)
					@foreach($result['news']['news_data'] as $key=>$news_data)
                        <div class="blog-post">
                            <article>
                                <div class="blog-thumb">
                                	<h4 class="blog-title">
                                        <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">{{$news_data->news_name}} </a>
                                        @if($news_data->is_feature==1)
                                            <span class="badge badge-success">@lang('website.Featured')</span>
                                        @endif
                                    </h4>
                                    <span class="blog-date">
                                        <strong>
                                            <?php
                                                $timestamp = strtotime($news_data->news_date_added);
                                                echo date('d',$timestamp);
                                            ?>
                                        </strong>
                                        <?php
                                            echo date('M',$timestamp);
                                        ?>
                                    </span>

                                    <div class="blog-overlay">
                                        <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}" class="fa fa-search-plus"></a>
                                    </div>

                                    <img class="img-fluid" src="{{asset('').$news_data->news_image}}" alt="">
                                </div>
                            </article>
                        </div>
                	@endforeach
            	@endif
			</div>
		</div>
    </div>
</div>
</section>
@endsection
