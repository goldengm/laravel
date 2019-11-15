@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.ViewOrder') }} <small> {{ trans('labels.ViewOrder') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/orders')}}"><i class="fa fa-dashboard"></i>  {{ trans('labels.ListingAllOrders') }}</a></li>
      <li class="active"> {{ trans('labels.ViewOrder') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="invoice" style="margin: 15px;">
      <!-- title row -->
      @if(session()->has('message'))
       <div class="col-xs-12">
       <div class="row">
      	<div class="alert alert-success alert-dismissible">
           <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
           <h4><i class="icon fa fa-check"></i> {{ trans('labels.Successlabel') }}</h4>
            {{ session()->get('message') }}
        </div>
        </div>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="col-xs-12">
      	<div class="row">
        	<div class="alert alert-warning alert-dismissible">
               <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
               <h4><i class="icon fa fa-warning"></i> {{ trans('labels.WarningLabel') }}</h4>
                {{ session()->get('error') }}
            </div>
          </div>
         </div>
        @endif
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header" style="padding-bottom: 25px; margin-top:0;">
            <i class="fa fa-globe"></i> {{ trans('labels.OrderID') }}# {{ $data['orders_data'][0]->orders_id }} 
            <small style="display: inline-block">{{ trans('labels.OrderedDate') }}: {{ date('m/d/Y', strtotime($data['orders_data'][0]->date_purchased)) }}</small>
            <a href="{{ URL::to('admin/invoiceprint/'.$data['orders_data'][0]->orders_id)}}" target="_blank"  class="btn btn-default pull-right"><i class="fa fa-print"></i> {{ trans('labels.Print') }}</a> 
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          {{ trans('labels.CustomerInfo') }}:
          <address>
            <strong>{{ $data['orders_data'][0]->customers_name }}</strong><br>
            {{ $data['orders_data'][0]->customers_street_address }} <br>
            {{ $data['orders_data'][0]->customers_city }}, {{ $data['orders_data'][0]->customers_state }} {{ $data['orders_data'][0]->customers_postcode }}, {{ $data['orders_data'][0]->customers_country }}<br>
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->customers_telephone }}<br>
            {{ trans('labels.Email') }}: {{ $data['orders_data'][0]->email }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          {{ trans('labels.ShippingInfo') }}
          <address>
            <strong>{{ $data['orders_data'][0]->delivery_name }}</strong><br>
            {{ $data['orders_data'][0]->delivery_street_address }} <br>
            {{ $data['orders_data'][0]->delivery_city }}, {{ $data['orders_data'][0]->delivery_state }} {{ $data['orders_data'][0]->delivery_postcode }}, {{ $data['orders_data'][0]->delivery_country }}<br>
            
            <strong>{{ trans('labels.Phone') }}: </strong>{{ $data['orders_data'][0]->delivery_phone }}<br>
           <strong> {{ trans('labels.ShippingMethod') }}:</strong> {{ $data['orders_data'][0]->shipping_method }} <br>
           <strong> {{ trans('labels.ShippingCost') }}:</strong> @if(!empty($data['orders_data'][0]->shipping_cost)) {{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->shipping_cost }} @else --- @endif <br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
         {{ trans('labels.BillingInfo') }} 
          <address>
            <strong>{{ $data['orders_data'][0]->billing_name }}</strong><br>
            {{ $data['orders_data'][0]->billing_street_address }} <br>
            <strong>{{ trans('labels.Phone') }}: </strong>{{ $data['orders_data'][0]->billing_phone }}<br>
            {{ $data['orders_data'][0]->billing_city }}, {{ $data['orders_data'][0]->billing_state }} {{ $data['orders_data'][0]->billing_postcode }}, {{ $data['orders_data'][0]->billing_country }}<br>
          </address>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>{{ trans('labels.Qty') }}</th>
              <th>{{ trans('labels.Image') }}</th>
              <th>{{ trans('labels.ProductName') }}</th>
              <th>{{ trans('labels.ProductModal') }}</th>
              <th>{{ trans('labels.Options') }}</th>
              <th>{{ trans('labels.Price') }}</th>
            </tr>
            </thead>
            <tbody>
            
            @foreach($data['orders_data'][0]->data as $products)
            	
            <tr>
                <td>{{  $products->products_quantity }}</td>
                <td >
                   <img src="{{ asset('').$products->image }}" width="60px"> <br>
                </td>
                <td  width="30%">
                    {{  $products->products_name }}<br>
                </td>
                <td>
                    {{  $products->products_model }}
                </td>
                <td>
                @foreach($products->attribute as $attributes)
                	<b>{{ trans('labels.Name') }}:</b> {{ $attributes->products_options }}<br>
                    <b>{{ trans('labels.Value') }}:</b> {{ $attributes->products_options_values }}<br>
                    <b>{{ trans('labels.Price') }}:</b> {{ $data['currency'][19]->value }}{{ $attributes->options_values_price }}<br>

                @endforeach</td>
                
                <td>{{ $data['currency'][19]->value }}{{ $products->final_price }}</td>
             </tr>
            @endforeach
            
            </tbody>
          </table>
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-7">
          <p class="lead" style="margin-bottom:10px">{{ trans('labels.PaymentMethods') }}:</p>
          <p class="text-muted well well-sm no-shadow" style="text-transform:capitalize">
           	{{ str_replace('_',' ', $data['orders_data'][0]->payment_method) }}
          </p>
          @if(!empty($data['orders_data'][0]->coupon_code))
              <p class="lead" style="margin-bottom:10px">{{ trans('labels.Coupons') }}:</p>
                <table class="text-muted well well-sm no-shadow stripe-border table table-striped" style="text-align: center; ">
                	<tr>
                        <th style="text-align: center; ">{{ trans('labels.Code') }}</th>
                        <th style="text-align: center; ">{{ trans('labels.Amount') }}</th>
                    </tr>
                	@foreach( json_decode($data['orders_data'][0]->coupon_code) as $couponData)
                    	<tr>
                        	<td>{{ $couponData->code}}</td>
                            <td>{{ $couponData->amount}} 
                            	
                                @if($couponData->discount_type=='percent_product')
                                    ({{ trans('labels.Percent') }})
                                @elseif($couponData->discount_type=='percent')
                                    ({{ trans('labels.Percent') }})
                                @elseif($couponData->discount_type=='fixed_cart')
                                    ({{ trans('labels.Fixed') }})
                                @elseif($couponData->discount_type=='fixed_product')
                                    ({{ trans('labels.Fixed') }})
                                @endif
                            </td>
                        </tr>
                    @endforeach
				</table>
               <!-- {{ $data['orders_data'][0]->coupon_code }}-->
                
          @endif
          <!-- <img src="../../dist/img/credit/visa.png" alt="Visa">
          <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
          <img src="../../dist/img/credit/american-express.png" alt="American Express">
          <img src="../../dist/img/credit/paypal2.png" alt="Paypal">-->
          
		  <p class="lead" style="margin-bottom:10px">{{ trans('labels.Orderinformation') }}:</p>
          <p class="text-muted well well-sm no-shadow" style="text-transform:capitalize; word-break:break-all;">
           @if(trim($data['orders_data'][0]->order_information) != '[]' or !empty($data['orders_data'][0]->order_information))
           		{{ $data['orders_data'][0]->order_information }}
           @endif
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
          <!--<p class="lead"></p>-->

          <div class="table-responsive ">
            <table class="table order-table">
              <tr>
                <th style="width:50%">{{ trans('labels.Subtotal') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['subtotal'] }}</td>
              </tr>
              <tr>
                <th>{{ trans('labels.Tax') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->total_tax }}</td>
              </tr>
              <tr>
                <th>{{ trans('labels.ShippingCost') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->shipping_cost }}</td>
              </tr>
              @if(!empty($data['orders_data'][0]->coupon_code))
              <tr>
                <th>{{ trans('labels.DicountCoupon') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->coupon_amount }}</td>
              </tr>
              @endif
              <tr>
                <th>{{ trans('labels.Total') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->order_price }}</td>
              </tr>
            </table>
          </div>
              
        </div>
    {!! Form::open(array('url' =>'admin/updateOrder', 'method'=>'post', 'onSubmit'=>'return cancelOrder();', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
     {!! Form::hidden('orders_id', $data['orders_data'][0]->orders_id, array('class'=>'form-control', 'id'=>'orders_id'))!!}
     {!! Form::hidden('old_orders_status', $data['orders_data'][0]->orders_status_id, array('class'=>'form-control', 'id'=>'old_orders_status'))!!}
     {!! Form::hidden('customers_id', $data['orders_data'][0]->customers_id, array('class'=>'form-control', 'id'=>'device_id')) !!}
        <div class="col-xs-12">
        <hr>
          <p class="lead">{{ trans('labels.ChangeStatus') }}:</p>
          
            <div class="col-md-12">
              <div class="form-group">
                <label>{{ trans('labels.PaymentStatus') }}:</label>
                <select class="form-control select2" id="status_id" name="orders_status" style="width: 100%;">
               	 @foreach( $data['orders_status'] as $orders_status)
                  <option value="{{ $orders_status->orders_status_id }}" @if( $data['orders_data'][0]->orders_status_id == $orders_status->orders_status_id) selected="selected" @endif >{{ $orders_status->orders_status_name }}</option>
                 @endforeach
                </select>
                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseStatus') }}</span>
              </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                <label>{{ trans('labels.Comments') }}:</label>
                {!! Form::textarea('comments',  '', array('class'=>'form-control', 'id'=>'comments', 'rows'=>'4'))!!}
                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CommentsOrderText') }}</span>
              </div>
            </div>
        </div>
         <!-- this row will not appear when printing -->
            <div class="col-xs-12">
              <a href="{{ URL::to('admin/orders')}}" class="btn btn-default"><i class="fa fa-angle-left"></i> {{ trans('labels.back') }}</a>
              <button type="submit" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> {{ trans('labels.Submit') }} </button>
              <!--<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                <i class="fa fa-download"></i> Generate PDF
              </button>-->

         <br><br> <hr><br>

            </div>
          {!! Form::close() !!}
        
        <div class="col-xs-12">
          <p class="lead">{{ trans('labels.OrderHistory') }}</p>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>{{ trans('labels.Date') }}</th>
                  <th>{{ trans('labels.Status') }}</th>
                  <th>{{ trans('labels.Comments') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach( $data['orders_status_history'] as $orders_status_history)
                    <tr>
                        <td>
							<?php 
								$date = new DateTime($orders_status_history->date_added);
								$status_date = $date->format('d-m-Y');
								print $status_date;
							?>
                        </td>
                        <td>
                        	@if($orders_status_history->orders_status_id==1)
                            	<span class="label label-warning">
                            @elseif($orders_status_history->orders_status_id==2)
                                <span class="label label-success">
                            @elseif($orders_status_history->orders_status_id==3)
                                 <span class="label label-danger">
                            @else
                                 <span class="label label-primary">
                            @endif
                            {{ $orders_status_history->orders_status_name }}
                                 </span>
                        </td>
                        <td style="text-transform: initial;">{{ $orders_status_history->comments }}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

     
    </section>
  <!-- /.content --> 
</div>
@endsection 