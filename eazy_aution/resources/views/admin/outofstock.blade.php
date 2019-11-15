@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Productsoutofstock') }} <small>{{ trans('labels.Productsoutofstock') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Productsoutofstock') }}</li>
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
            <h3 class="box-title">{{ trans('labels.Productsoutofstock') }} </h3>
          </div>          
          <!-- /.box-header -->
          <div class="box-body">
            
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.ID') }}</th>
                      <th>{{ trans('labels.Image') }}</th>
                      <th>{{ trans('labels.Products') }}</th>
                      <!--<th>{{ trans('labels.Quantity') }}</th>-->
                      <th>{{ trans('labels.ViewStock') }}</th>
                    </tr>
                  </thead>
                  <tbody>
					@if(count($result['products']) > 0)
                    	@foreach ($result['products'] as  $key=>$outOfStockData)
                                
                            <tr>
                                <td>{{ $outOfStockData->products_id }}</td>
                                <td><img src="{{asset('').'/'.$outOfStockData->products_image}}" alt="" width=" 100px" height="100px"></td>
                                <td width="45%">
                                    <strong>{{ $outOfStockData->products_name }} ( {{ $outOfStockData->products_model }} )</strong><br>
                                </td>
                                <!--<td>
                                    {{ $outOfStockData->products_quantity }}
                                </td>-->
                               
                                <td>
                                    <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.View') }}" href="stockin?products_id={{ $outOfStockData->products_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                                </td>
                            </tr>  
                        @endforeach               
                  @else 
                 <tr>
                 	<td colspan="4">
                 		{{ trans('labels.NoRecordFound') }}
                    </td>
                 </tr>
                 @endif
                 </tbody>
                </table>
                <div class="col-xs-12 text-right">
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

    <!-- Main row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 