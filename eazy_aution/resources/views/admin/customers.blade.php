@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Customers') }} <small>{{ trans('labels.ListingAllCustomers') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Customers') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- Info boxes --> 
    
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ trans('labels.ListingAllCustomers') }} </h3>
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addcustomers')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNewCustomers') }}</a>
            </div>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
              		
				  @if (count($errors) > 0)
					  @if($errors->any())
						<div class="alert alert-success alert-dismissible" role="alert">
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						  {{$errors->first()}}
						</div>
					  @endif
				  @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.ID') }}</th>
                      <th>{{ trans('labels.Picture') }}</th>
                      <th>{{ trans('labels.PersonalInfo') }}</th>
                      <th>{{ trans('labels.Address') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @if (count($customers['result']) > 0)
						@foreach ($customers['result']  as $key=>$listingCustomers)
							<tr>
								<td>{{ $listingCustomers->customers_id }}</td>
								<td>
                                @if(!empty($listingCustomers->customers_picture))
                                <img src="../{{ $listingCustomers->customers_picture }}" style="width: 100px; float: left; margin-right: 10px">
                                @else
                                <img src="../resources/assets/images/default_images/user.png" style="width: 100px; float: left; margin-right: 10px">
                                @endif
									
								</td>								
								<td>
                                	<!--<strong>UserName: </strong> {{ $listingCustomers->user_name }}<br>-->
                                    <strong>{{ trans('labels.Name') }}: </strong> {{ $listingCustomers->customers_firstname }} {{ $listingCustomers->customers_lastname }} <br>
									<strong>{{ trans('labels.DOB') }}: </strong> {{ $listingCustomers->customers_dob }}  <br>
									<strong>{{ trans('labels.Email') }}: </strong> {{ $listingCustomers->email }} <br>
									<strong>{{ trans('labels.Telephone') }}: </strong> {{ $listingCustomers->customers_telephone }} <br>
									<strong>{{ trans('labels.Fax') }}: </strong> {{ $listingCustomers->customers_fax }} <br>
                                    <strong>{{ trans('labels.Devices') }}: </strong> 
                                    @if(count($listingCustomers->devices)>0)
                                      <a href="javaScript:avoid(0)" id="notification-popup" customers_id = "{{ $listingCustomers->customers_id }}"> 
                                    	@foreach($listingCustomers->devices as $devices_data)
                                        	<span>
                                            	@if($devices_data->device_type == 1)
                                            		{{ trans('labels.IOS') }}
                                                @elseif($devices_data->device_type == 2)
                                                	{{ trans('labels.Android') }}
                                                @elseif($devices_data->device_type == 3)
                                                	{{ trans('labels.Website') }} 
                                                @endif
                                            </span> 
                                    	@endforeach
                                      </a>
                                    @endif
                                    </td>
								<td>
                                	<strong>{{ trans('labels.Company') }}: </strong> {{ $listingCustomers->entry_company }} <br>
                                    <strong>{{ trans('labels.Address') }}: </strong> 
                                    @if(!empty($listingCustomers->entry_street_address)) 
                                    	{{ $listingCustomers->entry_street_address }},
                                    @endif
                                     @if(!empty($listingCustomers->entry_city)) 
                                    	{{ $listingCustomers->entry_city }},
                                    @endif
                                     @if(!empty($listingCustomers->entry_state)) 
                                    	{{ $listingCustomers->entry_state }},
                                    @endif
                                     @if(!empty($listingCustomers->entry_postcode)) 
                                    	{{ $listingCustomers->entry_postcode }}
                                    @endif
                                     @if(!empty($listingCustomers->countries_name)) 
                                    	{{ $listingCustomers->countries_name }}
                                    @endif 
                                    
                                </td>
								<td>
                                <ul class="nav table-nav">
                              <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                  {{ trans('labels.Action') }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="editcustomers/{{ $listingCustomers->customers_id }}">{{ trans('labels.EditCustomers') }}</a></li>
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="addaddress/{{ $listingCustomers->customers_id }}">{{ trans('labels.EditAddress') }}</a></li>
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCustomerFrom" customers_id="{{ $listingCustomers->customers_id }}">{{ trans('labels.Delete') }}</a></li>
                                </ul>
                              </li>
                            </ul>
								</td>
							</tr>
						@endforeach
                    @else
                    	<tr>
							<td colspan="4">{{ trans('labels.NoRecordFound') }}</td>							
						</tr>
                    @endif
                  </tbody>
                </table>
                @if (count($customers['result']) > 0)
					<div class="col-xs-12 text-right">
						{{$customers['result']->links('vendor.pagination.default')}}
					</div>
                 @endif
              </div>
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
    
    <!-- /.row --> 
   
    <!-- deleteCustomerModal -->
	<div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteCustomerModalLabel">{{ trans('labels.DeleteCustomer') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deletecustomers', 'name'=>'deleteCustomer', 'id'=>'deleteCustomer', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('customers_id',  '', array('class'=>'form-control', 'id'=>'customers_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.DeleteCustomerText') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary">{{ trans('labels.DeleteCustomer') }}</button>
		  </div>
		  {!! Form::close() !!}
		</div>
	  </div>
	</div>
    
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content notificationContent">

		</div>
	  </div>
	</div>

    <!-- Main row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 