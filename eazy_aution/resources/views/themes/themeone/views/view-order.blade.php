@extends('layout')
@section('content')
<section class="site-content">
    <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.View Order')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::to('/orders')}}">@lang('website.My Orders')</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">@lang('website.View Order')</a></li>
                </ol>
            </div>
        </div>
        <div class="orders-detail-area">
            <div class="row">
            	<div class="col-12 col-lg-3 spaceright-0">
                	@include('common.sidebar_account')
                </div>
                <div class="col-12 col-lg-9">
                    <div class="col-12 spaceright-0">
                        <div class="heading">
                            <h2>@lang('website.Order information')</h2>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6 card-box">
                                <div class="card">
                                    <div class="card-header">
                                        @lang('website.orderID')&nbsp;{{$result['orders'][0]->orders_id}}
                                    </div>
                                    <div class="card-body">
                                        <div class="card-text">
                                            <p>
                                                <strong>@lang('website.orderStatus')</strong>
                                                @if($result['orders'][0]->orders_status_id == '1')
                                                    <span class="badge badge-primary">{{$result['orders'][0]->orders_status}}</span>
                                                
                                                @elseif($result['orders'][0]->orders_status_id == '2')
                                                    <span class="badge badge-success">{{$result['orders'][0]->orders_status}}</span>
                                                @elseif($result['orders'][0]->orders_status_id == '3')
                                                    <span class="badge badge-danger">{{$result['orders'][0]->orders_status}}</span>
                                                @else
                                                	<span class="badge badge-warning">{{$result['orders'][0]->orders_status}}</span>   
                                                @endif
                                            </p>
                                            <p><strong>Ordered Date</strong>{{ date('d/m/Y', strtotime($result['orders'][0]->date_purchased))}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 card-box">
                                <div class="card">
                                  <div class="card-header">                
                                    @lang('website.Shipping Detail')
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                        <p><strong>{{$result['orders'][0]->delivery_name}}</strong></p>
                                        <p>{{$result['orders'][0]->delivery_street_address}}, {{$result['orders'][0]->delivery_city}}, {{$result['orders'][0]->delivery_state}},
                                        {{$result['orders'][0]->delivery_postcode}},  {{$result['orders'][0]->delivery_country}}</p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 card-box">
                                <div class="card">
                                  <div class="card-header">                
                                    @lang('website.Billing Detail')
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                        <p>
                                            <strong>{{$result['orders'][0]->billing_name}}</strong></p>
                                            <p>{{$result['orders'][0]->billing_street_address}}, {{$result['orders'][0]->billing_city}}, {{$result['orders'][0]->billing_state}},
                                            {{$result['orders'][0]->billing_postcode}},  {{$result['orders'][0]->billing_country}}
                                        </p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 card-box">
                                <div class="card">
                                  <div class="card-header">
                                    @lang('website.Payment/Shipping Method')
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                    <p><strong>@lang('website.Shipping Method')</strong>{{$result['orders'][0]->shipping_method}}</p>
                                    <p><strong>@lang('website.Payment Method')</strong>{{$result['orders'][0]->payment_method}}</p>
                    
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-start">
                            <div class="col-12 col-md-6 col-lg-8">
                                <div class="table-responsive">
                                    <table class="table" style="margin-bottom:0;">
                                        <thead>
                                            <tr>
                                                <th align="left">@lang('website.items')</th>
                                                <th align="right">@lang('website.Price')</th>
                                                <th align="right">@lang('website.Qty')</th>
                                                <th align="right">@lang('website.SubTotal')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $price = 0;
                                            ?>
                                            @if(count($result['orders']) > 0)
                                                @foreach( $result['orders'][0]->products as $products)
                                                <?php 
                                                    $price+= $products->final_price;					
                                                ?>
                                                <tr>
                                                    <td align="left" class="item">
                                                        <div class="cart-thumb">
                                                            <img class="img-fluid" src="{{asset('').$products->image}}" alt="{{$products->products_name}}" alt="">
                                                        </div>
                                                        <div class="cart-product-detail">
                                                            <div class="title">{{$products->products_name}} {{$products->model}}</div>
                                                            @if(count($products->attributes) >0)
                                                                <ul>
                                                                    @foreach($products->attributes as $attributes)
                                                                        <li>{{$attributes->products_options}}<span>{{$attributes->products_options_values}}</span></li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    
                                                    <td align="right" class="price"><span>{{$result['orders'][0]->currency}}{{$products->final_price/$products->products_quantity}}</span></td>
                                                    <td align="right" class="Qty"><span>{{$products->products_quantity}}</span></td>
                                                
                                                    <td align="right" class="subtotal"><span>{{$result['orders'][0]->currency}}{{$products->final_price+0}}</span>
                                                    </td>
                                                </tr>    
                                                @endforeach
                                            @endif				
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="order-summary-outer">
                                    <div class="order-summary">
                                        <div class="table-responsive">
                                            <table class="table order-table">
                                                <thead>
                                                    <tr><th colspan="2">@lang('website.Order Summary')</th></tr>
                                                </thead>
                                                  <tbody>
                                                    <tr>
                                                        <th><span>@lang('website.Subtotal')</span></th>
                                                        <td valign="middle" align="right" id="subtotal">{{$result['orders'][0]->currency}}{{$price+0}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th><span>@lang('website.Tax')</span></th>
                                                        <td valign="middle" align="right">{{$result['orders'][0]->currency}}{{$result['orders'][0]->total_tax}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th><span>@lang('website.Shipping Cost')</br><small>{{$result['orders'][0]->shipping_method}}</small></span></th>
                                                        <td valign="middle" align="right">{{$result['orders'][0]->currency}}{{$result['orders'][0]->shipping_cost}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th><span>@lang('website.Discount(Coupon)')</span></th>
                                                        <td valign="middle" align="right" id="discount">{{$result['orders'][0]->currency}}{{number_format((float)$result['orders'][0]->coupon_amount, 2, '.', '')+0}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="last"><span>@lang('website.Total')</span></th>
                                                        <td class="last" valign="middle" align="right" id="total_price">{{$result['orders'][0]->currency}}{{number_format((float)$result['orders'][0]->order_price+0, 2, '.', '')+0}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                @if(count($result['orders'][0]->statusess)>0)
                                    <div class="card">
                                        <div class="card-header">
                                        	@lang('website.Comments')
                                        </div>
                                        <div class="card-body">
                                        @foreach($result['orders'][0]->statusess as $key=>$statusess)
                                            @if(!empty($statusess->comments))
                                                @if(++$key==1)
                                                	<h6>@lang('website.Order Comments'): {{ date('d/m/Y', strtotime($statusess->date_added))}}</h6>
                                                   
                                                @else
                                                	<h6>@lang('website.Admin Comments'): {{ date('d/m/Y', strtotime($statusess->date_added))}}</h6>
                                                @endif
                                                <p class="card-text">{{$statusess->comments}}</p>  
                                            @endif
                                        @endforeach
                                        </div>
                                    </div>
                                @endif 
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
 </section>		
@endsection 	


