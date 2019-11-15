@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>{{ trans('labels.CustomerOrdersTotal') }} <small>{{ trans('labels.CustomerOrdersTotal') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.CustomerOrdersTotal') }}</li>
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
            <h3 class="box-title">{{ trans('labels.CustomerOrdersTotal') }} </h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
           
            <div class="row">
              <div class="col-xs-12">
              		
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.No') }}.</th>
                      <th>{{ trans('labels.CustomerName') }}</th>
                      <th>{{ trans('labels.TotalPurchased') }}</th>
                      <th>{{ trans('labels.View') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(count($result['data'])>0)
                    @foreach ($result['data'] as $key=>$orderData)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $orderData->firstname }} {{ $orderData->lastname }}</td>
                            <td>{{ $result['currency'][19]->value }}{{ $orderData->price }}</td>
                            <td><a href="{{ URL::to('admin/editcustomers')}}/{{$orderData->customers_id}}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                        </tr>
                    @endforeach
                  @else
                  	<tr>
                    	<td colspan="6"><strong>{{ trans('labels.NoRecordFound') }}</strong></td>
                    </tr>
                  @endif
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$result['data']->links('vendor.pagination.default')}}
                </div>
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
    
    <!-- Main row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 