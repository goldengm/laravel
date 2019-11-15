@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ trans('labels.title_dashboard') }}  
        <small>{{ trans('labels.title_dashboard') }} 1.1</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">   
    <div class="row" style="display: none">
        <div class="col-md-12">
          <div class="box box-default">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Version Info!</h4>
                You are using the latest <strong>version </strong> of our <strong>Ecommerce Solution</strong>.<br>
                This latest version is came up with both <strong>Ecommerce Desktop</strong> and <strong>Application</strong><br>
				Our old version is not compatible with <strong>Desktop Version</strong>.<br>
				If you want to choose our <strong>Ecommerce Desktop System</strong> as well. Please <strong>upgrade</strong> your all CMS to our latest <strong>version 3.0</strong>.
                If you have purchased CMS with <strong>Desktop System package</strong> and want to buy <strong>Application Package</strong>. Please purchase our <strong>CMS and Application services</strong> and enable these feture from <strong>Admin Panel</strong>.<br>
				If you have purchased CMS with Application System package and want to buy Desktop Package. Please purchase our <strong>CMS and Desktop services</strong> and enable these feture from <strong>Admin Panel</strong>.<br>
				Just put your files into your existing system and enjoy with our <strong>Ecommerce Solution.</strong>.<br>
				<strong>Now feel free to use!</strong>
				</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $result['total_orders'] }}</h3>
			  <p>{{ trans('labels.NewOrders') }}</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{ URL::to('admin/orders')}}" class="small-box-footer" data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.viewAllOrders') }}">{{ trans('labels.viewAllOrders') }} <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-light-blue">
            <div class="inner">
              <h3>{{ $result['currency'][19]->value }}{{ $result['total_money'] }}</h3>
			  <p>{{ trans('labels.Total Money') }}</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{ URL::to('admin/products')}}" class="small-box-footer" data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.viewAllProducts') }}">{{ trans('labels.viewAllProducts') }} <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-teal">
            <div class="inner">
              <h3>{{ $result['currency'][19]->value }}{{ $result['total_earned_money'] }}</h3>
			  <p>{{ trans('labels.Total Money Earned') }}</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{ URL::to('admin/orders')}}" class="small-box-footer" data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.viewAllOrders') }}">{{ trans('labels.viewAllOrders') }} <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
        
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $result['outOfStock'] }} </h3>
              <p>{{ trans('labels.outOfStock') }}</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ URL::to('admin/outofstock')}}" class="small-box-footer" data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.outOfStock') }}">{{ trans('labels.outOfStock') }} <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $result['totalCustomers'] }}</h3>

              <p>{{ trans('labels.customerRegistrations') }}</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ URL::to('admin/customers')}}" class="small-box-footer" data-toggle="tooltip" data-placement="bottom" title="{ trans('labels.viewAllCustomers') }}">{{ trans('labels.viewAllCustomers') }}  <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $result['totalProducts'] }}</h3>

              <p>{{ trans('labels.totalProducts') }}</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ URL::to('admin/products')}}" class="small-box-footer" data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.viewAllProducts') }}">{{ trans('labels.viewAllProducts') }} <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
      </div>

      <div class="row">
      <div class="col-sm-12">
          <div class="nav-tabs-custom">
          <div class="box-header with-border">
            <h3 class="box-title"> {{ trans('labels.addedSaleReport') }}</h3>
            <div class="box-tools pull-right">
               <p class="notify-colors"><span class="sold-content" data-toggle="tooltip" data-placement="bottom" title="Sold Products"></span> {{ trans('labels.soldProducts') }}  <span class="purchased-content" data-toggle="tooltip" data-placement="bottom" title="Added Products"></span>{{ trans('labels.addedProducts') }} </p>
               </div>
            </div>
          {!! Form::hidden('reportBase',  $result['reportBase'] , array('id'=>'reportBase')) !!}
            <ul class="nav nav-tabs">
              <li class="{{ Request::is('admin/dashboard/last_year') ? 'active' : '' }}"><a href="{{ URL::to('admin/dashboard/last_year')}}">{{ trans('labels.lastYear') }}</a></li>
              <li class="{{ Request::is('admin/dashboard/last_month') ? 'active' : '' }}"><a href="{{ URL::to('admin/dashboard/last_month')}}">{{ trans('labels.LastMonth') }}</a></li>
              <li class="{{ Request::is('admin/dashboard/this_month') ? 'active' : '' }}"><a href="{{ URL::to('admin/dashboard/this_month')}}">{{ trans('labels.thisMonth') }}</a></li>
              <li style="width: 33%"><a href="#" data-toggle="tab">
                  <div class="input-group ">
                  	 <div class="input-group-btn">
                        <button type="button" class="btn btn-default" aria-label="Help">{{ trans('labels.custom') }}</button> 
                     </div>
                     
                     <input class="form-control reservation dateRange" readonly value="" name="dateRange" aria-label="Text input with multiple buttons ">
                     <div class="input-group-btn"><button type="button" class="btn btn-primary getRange" >{{ trans('labels.go') }}</button> </div> 
                   </div>
                </a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <!-- Post -->
                <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 400px;"></canvas>
                  </div>
                <!-- /.post -->
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <div class="col-md-12" style="display: none">
          <div class="box">
            <div class="box-header with-border">
              <!--<h3 class="box-title pull-left">Monthly Report</h3>-->
            
              <div class="col-xs-12 col-lg-4"> 
              	<div class="input-group">
                   	<div class="input-group-btn">
                    	<button type="button" class="btn btn-default" aria-label="Help">{{ trans('labels.customDate') }}</button> 
                    </div>
                	<input class="form-control" aria-label="Text input with multiple buttons">
                    <div class="input-group-btn">                    	
                        <button type="button" class="btn btn-primary">{{ trans('labels.go') }}</button> 
                    </div> 
                </div>
              </div>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!--<div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>-->
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center">
                    <strong>{{ trans('labels.sales') }}: 1 Jan, 2014 - 30 Jul, 2014</strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 400px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer" style="display: none">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">{{ trans('labels.total_revenue') }}</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">{{ trans('labels.total_cost') }}</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">{{ trans('labels.total_profit') }}</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block">
                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                    <h5 class="description-header">1200</h5>
                    <span class="description-text">{{ trans('labels.goal_completions') }}</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->          
          <!-- /.box -->
          <div class="row">
            <!-- /.col -->

            <div class="col-md-12">
              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('labels.latest_customers') }}</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">{{ count($result['recentCustomers']) }} {{ trans('labels.new_members') }}</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  @if(count($result['recentCustomers'])>0)
                  <ul class="users-list clearfix">
                     <?php $i = 1; ?>
                      @foreach ($result['recentCustomers']  as $recent)
                      	
                       	@foreach ($recent  as $recentCustomers)
                       	@if($i<=21)
                        <li>
                            @if(!empty($recentCustomers->customers_picture))
                               <img src="{{asset('').'/'.$recentCustomers->customers_picture}}">
                            @else
                               <img src="{{asset('').'/resources/assets/images/default_images/user.png' }}">
                            @endif
                          <a class="users-list-name" href="{{ URL::to('admin/editcustomers') }}/{{ $recentCustomers->customers_id }}">{{ $recentCustomers->customers_firstname }} {{ $recentCustomers->customers_lastname }}</a>
                          <span class="users-list-date">{{ date('d-M', strtotime($recentCustomers->customers_info_date_account_created)) }}</span>
                        </li>
                        @endif
                        <?php $i++; ?>
                        @endforeach
                      @endforeach
                  </ul>
                  @else
                  	<p style="padding: 8px 0 0 10px;">{{ trans('labels.no_customer_exist') }}</p>
                  @endif

                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="{{ URL::to('admin/customers')}}" class="uppercase" data-toggle="tooltip" data-placement="bottom" title="View All Customers">{{ trans('labels.viewAllCustomers') }}</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('labels.NewOrders') }}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>{{ trans('labels.OrderID') }}</th>
                    <th>{{ trans('labels.CustomerName') }}</th>
                    <th>{{ trans('labels.TotalPrice') }}</th>
                    <th>{{ trans('labels.Status') }} </th>
                  </tr>
                  </thead>
                  <tbody>
                  @if(count($result['orders'])>0)
                  	@foreach($result['orders'] as $total_orders)
                     	@foreach($total_orders as $key=>$orders)
                     	@if($key<=10)
                      <tr>
                        <td><a href="{{ URL::to('admin/vieworder') }}/{{ $orders->orders_id }}" data-toggle="tooltip" data-placement="bottom" title="Go to detail">{{ $orders->orders_id }}</a></td>
                        <td>{{ $orders->customers_name }}</td>
                        <td>{{ $result['currency'][19]->value }}{{ floatval($orders->total_price) }} </td>
                        <td>
                            @if($orders->orders_status_id==1)
                           	    <span class="label label-warning">
                            @elseif($orders->orders_status_id==2)
                                <span class="label label-success">
                            @elseif($orders->orders_status_id==3)
                                 <span class="label label-danger">
                            @else
                                 <span class="label label-primary">
                            @endif
                            {{ $orders->orders_status }}
                                 </span>
                                 
                           
                         </td>
                      </tr>
                      @endif
                    @endforeach
                    @endforeach
                    
                    @else
                    	<tr>
							<td colspan="4">{{ trans('labels.noOrderPlaced') }}</td>
							
						</tr>
                    @endif
                  
                  
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <!--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>-->
              <a href="{{ URL::to('admin/orders') }}" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="tooltip" data-placement="bottom" title="View All Orders">{{ trans('labels.viewAllOrders') }}</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">
         
          <!-- PRODUCT LIST -->
          
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('labels.GoalCompletion') }}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
                  <div class="progress-group">
                    <span class="progress-text">{{ trans('labels.AddProductstoCart') }}</span>
                    <span class="progress-number"><b>{{ $result['cart'] }}</b>/500</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: {{ $result['cart']*100/500 }}%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                    @if($result['total_orders']>0)
                  <div class="progress-group">
                    <span class="progress-text">{{ trans('labels.CompleteOrders') }}</span>
                    <span class="progress-number"><b>{{ $result['compeleted_orders'] }}</b>/{{ $result['total_orders'] }}</span>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: {{ $result['compeleted_orders']*100/$result['total_orders'] }}%"></div>
                    </div>
                  </div>
					@endif
                    @if($result['total_orders']>0)
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">{{ trans('labels.PendingOrders') }}</span>
                    <span class="progress-number"><b>{{ $result['pending_orders'] }}</b>/{{ $result['total_orders'] }}</span>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-yellow" style="width: {{ $result['pending_orders']*100/$result['total_orders'] }}%"></div>
                    </div>
                  </div>
					@endif
                  <!-- /.progress-group -->
                    @if($result['total_orders']>0)
                  <div class="progress-group">
                    <span class="progress-text">{{ trans('labels.InprocessOrders') }}</span>
                    <span class="progress-number"><b>{{ $result['inprocess'] }}</b>/{{ $result['total_orders'] }}</span>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: {{ $result['inprocess']*100/$result['total_orders'] }}%"></div>
                    </div>
                  </div>
					@endif
            </div>
            <!-- /.box-body -->
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('labels.RecentlyAddedProducts') }}</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
              @foreach($result['recentProducts'] as $recentProducts)
                <li class="item">
                  <div class="product-img">
                  	<img src="{{asset('').'/'.$recentProducts->products_image}}" alt="" width=" 100px" height="100px">
                  </div>
                  <div class="product-info">
                    <a href="{{ URL::to('admin/editproduct') }}/{{ $recentProducts->products_id }}" class="product-title">{{ $recentProducts->products_name }}
                      <span class="label label-warning label-succes pull-right">{{ $result['currency'][19]->value }}{{ floatval($recentProducts->products_price) }}</span></a>
                        <!--<span class="product-description">
                          {{ stripslashes($recentProducts->products_description) }}
                        </span>-->
                  </div>
                </li>
              @endforeach
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="{{ URL::to('admin/products') }}" class="uppercase" data-toggle="tooltip" data-placement="bottom" title="View All Products">{{ trans('labels.viewAllProducts') }}</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
<script src="{!! asset('resources/views/admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>

<script src="{!! asset('resources/views/admin/dist/js/pages/dashboard2.js') !!}"></script>
  @endsection