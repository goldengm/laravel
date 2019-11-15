@extends('layout')
@section('content')
<section class="site-content">
    <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.My Orders')</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                     <li class="breadcrumb-item active">@lang('website.My Orders')</li>
                </ol>
            </div>
        </div>
        
        <div class="my-order-area">
        	
        	
        	<div class="row">
            	<div class="col-12 col-lg-3 spaceright-0">
                    @include('common.sidebar_account')
                </div>
            	<div class="col-12 col-lg-9 new-customers">
                	
                	<div class="col-12 spaceright-0">
                        <div class="heading">
                            <h2>@lang('website.My Orders')</h2>
                            <hr>
                        </div>
                        
                        <div class="my-orders">
        
                        @if(session()->has('message'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                 {{ session()->get('message') }}
                            </div>
                            
                        @endif
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>@lang('website.Order ID')</th>
                                        <th>@lang('website.Order Date')</th>
                                        <th>@lang('website.Price')</th>
                                        <th>@lang('website.Status')</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($result['orders']) > 0)
                                @foreach( $result['orders'] as $orders)
                                    <tr>
                                        <td>{{$orders->orders_id}}</td>
                                        <td>{{ date('d/m/Y', strtotime($orders->date_purchased))}}</td>
                                        <td>{{$orders->currency}}{{$orders->order_price}}</td>
                                        <td>                                        
                                            @if($orders->orders_status_id == '2')
                                                <span class="badge badge-success">{{$orders->orders_status}}</span>
                                                &nbsp;&nbsp;/&nbsp;&nbsp;
                                         
                                                <form action="{{ URL::to('/updatestatus')}}" method="post" onSubmit="return returnOrder();" style="display: inline-block">
                                                <input type="hidden" name="orders_id" value="{{$orders->orders_id}}">
                                                <input type="hidden" name="orders_status_id" value="4">                                                
                                                <button type="submit" class="badge badge-danger" style="text-transform:capitalize; cursor:pointer">{{$orders->orders_status}}) </button>
                                                </form>
                                            @else
                                           	 	@if($orders->orders_status_id == '3')
                                             		<span class="badge badge-danger">{{$orders->orders_status}} </span>
                                             	@elseif($orders->orders_status_id == '4')
                                             		<span class="badge badge-danger">{{$orders->orders_status}} </span>                                                @else
                                                <span class="badge badge-primary">{{$orders->orders_status}}</span> 
                                                &nbsp;&nbsp;/&nbsp;&nbsp;
                                         
                                                <form action="{{ URL::to('/updatestatus')}}" method="post" onSubmit="return cancelOrder();" style="display: inline-block">
                                                <input type="hidden" name="orders_id" value="{{$orders->orders_id}}">
                                                <input type="hidden" name="orders_status_id" value="3">
                                                <button type="submit" class="badge badge-danger" style="text-transform:capitalize; cursor:pointer">@lang('website.cancel order') </button>
                                                </form>
                                                
                                                @endif                                           
                                            @endif
                                        </td>
                                        <td align="right"><a class="btn btn-sm btn-dark" href="{{ URL::to('/view-order/'.$orders->orders_id)}}">@lang('website.View Order')</a></td>
                                    </tr>              
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">@lang('website.No order is placed yet')
                                        </td>
                                    </tr>
                                @endif				
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>	
            </div>    
        </div>
	</div>
</section>	
@endsection 	


