<header class="main-header">

    <!-- Logo -->
    <a href="{{ URL::to('admin/dashboard/this_month')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" style="font-size:12px"><b>{{ trans('labels.admin') }}</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>{{ trans('labels.admin') }}</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
      </a>
		<div id="countdown" style="
    width: 350px;
    margin-top: 13px !important;
    position: absolute;
    font-size: 16px;
    color: #ffffff;
    display: inline-block;
    margin-left: -175px;
    left: 50%;
"></div>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-list-ul"></i>
              <span class="label label-success">{{ count($unseenOrders) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{ trans('labels.you_have') }} {{ count($unseenOrders) }} {{ trans('labels.new_orders') }}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($unseenOrders as $unseenOrder)
                  <li><!-- start message -->
                    <a href="{{ URL::to("admin/vieworder")}}/{{ $unseenOrder->orders_id}}">
                      <div class="pull-left">
                        
                         @if(!empty($unseenOrder->customers_picture))
                            <img src="{{asset('').'/'.$unseenOrder->customers_picture}}" class="img-circle" alt="{{ $unseenOrder->customers_name }} Image">
                            @else
                            <img src="{{asset('').'/resources/assets/images/default_images/user.png' }}" class="img-circle" alt="{{ $unseenOrder->customers_name }} Image">
                         @endif
                                                  
                      </div>
                      <h4>
                        {{ $unseenOrder->customers_name }}
                        <small><i class="fa fa-clock-o"></i> {{ date('d/m/Y', strtotime($unseenOrder->date_purchased)) }}</small>
                      </h4>
                      <p>Ordered Products ({{ $unseenOrder->total_products}})</p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                </ul>
              </li>
              <!--<li class="footer"><a href="#">See All Messages</a></li>-->
            </ul>
          </li>
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-users"></i>
              <span class="label label-warning">{{ count($newCustomers) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{ count($newCustomers) }} {{ trans('labels.new_users') }}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($newCustomers as $newCustomer)
                  <li><!-- start message -->
                    <a href="{{ URL::to("admin/editcustomers")}}/{{ $newCustomer->customers_id}}">
                      <div class="pull-left">
                         @if(!empty($newCustomer->customers_picture))
                            <img src="{{asset('').'/'.$newCustomer->customers_picture}}" class="img-circle">
                            @else
                            <img src="{{asset('').'/resources/assets/images/default_images/user.png' }}" class="img-circle" alt="{{ $newCustomer->customers_firstname }} Image">
                         @endif
                      </div>
                      <h4>
                        {{ $newCustomer->customers_firstname }} {{ $newCustomer->customers_lastname }}
                        <small><i class="fa fa-clock-o"></i> {{ date('d/m/Y', $newCustomer->created_at) }}</small>
                      </h4>
                      <p></p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                </ul>
              </li>
              <!--<li class="footer"><a href="#">See All Messages</a></li>-->
            </ul>
          </li>
          
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-th"></i>
              <span class="label label-warning">{{ count($lowInQunatity) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{ count($lowInQunatity) }} {{ trans('labels.products_are_in_low_quantity') }}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($lowInQunatity as $lowInQunatity)
                  <li><!-- start message -->
                    <a href="{{ URL::to("admin/editproduct")}}/{{ $lowInQunatity->products_id}}">
                      <div class="pull-left">                         
                         <img src="{{asset('').'/'.$lowInQunatity->products_image}}" class="img-circle" >
                      </div>
                      <h4 style="white-space: normal;">
                        {{ $lowInQunatity->products_name }} 
                      </h4>
                      <p></p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                </ul>
              </li>
              <!--<li class="footer"><a href="#">See All Messages</a></li>-->
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('').auth()->guard('admin')->user()->image}}" class="user-image" alt="{{ auth()->guard('admin')->user()->first_name }} {{ auth()->guard('admin')->user()->last_name }} Image">
              <span class="hidden-xs">{{ auth()->guard('admin')->user()->first_name }} {{ auth()->guard('admin')->user()->last_name }} </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{asset('').auth()->guard('admin')->user()->image}}" class="img-circle" alt="{{ auth()->guard('admin')->user()->first_name }} {{ auth()->guard('admin')->user()->last_name }} Image">

                <p>
                  {{ auth()->guard('admin')->user()->first_name }} {{ auth()->guard('admin')->user()->last_name }} 
                  <small>{{ trans('labels.administrator')}}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
              @if(session('profile_view')==1 or auth()->guard('admin')->user()->adminType=='1')
                <div class="pull-left">
                  <a href="{{ URL::to('admin/profile')}}" class="btn btn-default btn-flat">{{ trans('labels.profile_link')}}</a>
                </div>                
              @endif
                <div class="pull-right">
                  <a href="{{ URL::to('admin/logout')}}" class="btn btn-default btn-flat">{{ trans('labels.sign_out') }}</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>

    </nav>
  </header>