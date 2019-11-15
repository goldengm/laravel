@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  {{ trans('labels.Coupons') }} <small>{{ trans('labels.ListingAllCoupons') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active"> {{ trans('labels.Coupons') }}</li>
    </ol>
  </section>
  
  <!--  content -->
  <section class="content"> 
    <!-- Info boxes --> 
    
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ trans('labels.ListingAllCoupons') }} </h3>
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addcoupons')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNewCoupon') }}</a>
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
                      <th>{{ trans('labels.Code') }}</th>
                      <th>{{ trans('labels.CouponType') }}</th>
                      <th>{{ trans('labels.CouponAmount') }}</th>
                      <th>{{ trans('labels.Descrition') }}</th>
                      <th>{{ trans('labels.ExpiryDate') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                     @if(count($result['coupons'])>0)
                        @foreach ($result['coupons'] as $key=>$coupan)
                            <tr>
                                <td>{{ $coupan->code }}</td>
                                <td>{{ str_replace('_', ' ', $coupan->discount_type) }} </td>
                                <td>
                                @if($coupan->discount_type=='fixed_product' or $coupan->discount_type=='fixed_cart')
                                	{{ $result['currency'][19]->value }}{{ $coupan->amount }}
                                @else
                                	{{ $coupan->amount }}%
                                @endif
                                 </td>
                                <td>{{ $coupan->description }} </td>
                                <td>{{ date('d/m/Y',strtotime($coupan->expiry_date)) }} </td>
                                
                                <td><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="editcoupons/{{ $coupan->coupans_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                                <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCoupans_id" coupans_id ="{{ $coupan->coupans_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    	<tr>
                       		<td colspan="8"><strong>{{ trans('labels.NoRecordFound') }}</strong></td>
                        </tr>
                    @endif
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{ $result['coupons']->links('vendor.pagination.default') }}
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
        <!-- deleteCoupanModal -->
	<div class="modal fade" id="deleteCoupanModal" tabindex="-1" role="dialog" aria-labelledby="deleteCoupanModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteCoupanModalLabel">{{ trans('labels.DeleteCoupon') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deletecoupon', 'name'=>'deleteCoupan', 'id'=>'deleteCoupan', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'coupans_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.DeleteCouponText') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary" id="deleteCoupanBtn">{{ trans('labels.Delete') }} </button>
		  </div>
		  {!! Form::close() !!}
		</div>
	  </div>
	</div>
    
    <!--  row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 