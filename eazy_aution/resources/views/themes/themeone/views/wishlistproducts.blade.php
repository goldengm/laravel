
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
                @if(count($result['products']['product_data'])> 0 and $result['limit'] > $result['products']['total_record'])
                 <style>
                    #load_wishlist{
                        display: none;
                    }
                    #loaded_content{
                        display: block !important;
                    }
                    #loaded_content_empty{
                        display: none !important;
                    }
                 </style>
                @endif
            @elseif(count($result['products']['product_data'])==0 or $result['products']['success']==0)
            	<style>
             	#load_wishlist{
					display: none;
				}
				#loaded_content{
					display: none !important;
				}
				#loaded_content_empty{
					display: block !important;
				}
             </style>
            @endif
