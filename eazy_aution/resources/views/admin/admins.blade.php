@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.admins') }} <small>{{ trans('labels.admins') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.admins') }}</li>
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
            <h3 class="box-title">{{ trans('labels.admins') }} </h3>
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addadmins')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.addadmins') }}</a>
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
                      <th>{{ trans('labels.AdminType') }}</th>
                      <th>{{ trans('labels.Status') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @if (count($result['admins']) > 0)
						@foreach ($result['admins']  as $key=>$admin)
							<tr>
								<td>{{ $admin->myid }}</td>
								<td>
                                @if(!empty($admin->image))
                                <img src="../{{ $admin->image }}" style="width: 100px; float: left; margin-right: 10px">
                                @else
                                <img src="../resources/assets/images/default_images/user.png" style="width: 100px; float: left; margin-right: 10px">
                                @endif
									
								</td>								
								<td>
                                	<!--<strong>UserName: </strong> {{ $admin->user_name }}<br>-->
                                    <strong>{{ trans('labels.Name') }}: </strong> {{ $admin->first_name }} {{ $admin->last_name }} <br>
									<strong>{{ trans('labels.Email') }}: </strong> {{ $admin->email }} <br>
									<strong>{{ trans('labels.phone') }}: </strong> {{ $admin->phone }} <br>
                                    </td>
								<td>
                                    <strong>{{ trans('labels.Address') }}: </strong> 
                                    @if(!empty($admin->address)) 
                                    	{{ $admin->address }},
                                    @endif
                                     @if(!empty($admin->city)) 
                                    	{{ $admin->city }},
                                    @endif
                                     @if(!empty($admin->state)) 
                                    	{{ $admin->zone_name }},
                                    @endif
                                     @if(!empty($admin->zip)) 
                                    	{{ $admin->zip }}
                                    @endif
                                     @if(!empty($admin->country)) 
                                    	{{ $admin->countries_name }}
                                    @endif 
                                    
                                </td>
                                <td>
                                @if($admin->admin_type_id==1)
                                	<strong class="badge bg-green">
                                @else
                                	<strong class="badge bg-light-blue">
                                @endif
                                	{{$admin->admin_type_name}}</strong>
                                </td>
                                <td>
                                  @if($admin->isActive==1)
                                    <strong class="badge bg-green">{{trans('labels.Active')}} </strong>
                               	  @else
                                	<strong class="badge bg-light-grey">{{trans('labels.InActive')}} </strong>
                                  @endif
                                  
                                </td>
								<td>
                                <ul class="nav table-nav">
                              <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                  {{ trans('labels.Action') }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="editadmin/{{ $admin->myid }}">{{ trans('labels.editadmin') }}</a></li>
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCustomerFrom" customers_id="{{ $admin->myid }}">{{ trans('labels.Delete') }}</a></li>
                                </ul>
                              </li>
                            </ul>
								</td>
							</tr>
						@endforeach
                    @else
                    	<tr>
							<td colspan="5">{{ trans('labels.NoRecordFound') }}</td>							
						</tr>
                    @endif
                  </tbody>
                </table>
                @if (count($result['admins']) > 0)
					<div class="col-xs-12 text-right">
						{{$result['admins']->links('vendor.pagination.default')}}
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
			<h4 class="modal-title" id="deleteCustomerModalLabel">{{ trans('labels.deleteAdmin') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deleteadmin', 'name'=>'deleteAdmin', 'id'=>'deleteAdmin', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('myid',  '', array('class'=>'form-control', 'id'=>'customers_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.Are you sure you want to delete this admin') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary">{{ trans('labels.Delete') }}</button>
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