@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.TaxRates') }} <small>{{ trans('labels.ListingAllTaxRates') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active"> {{ trans('labels.TaxRates') }}</li>
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
            <h3 class="box-title">{{ trans('labels.ListingAllTaxRates') }} </h3>
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addtaxrate')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddTaxRate') }}</a>
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
                      <th>{{ trans('labels.Zone') }}</th>
                      <th>{{ trans('labels.TaxRates') }}</th>
                      <th>{{ trans('labels.TaxClass') }}</th>
                      <th>{{ trans('labels.Date') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($result['tax_rates'] as $key=>$taxRate)
                        <tr>
                            <td>{{ $taxRate->tax_rates_id }}</td>
                            <td>{{ $taxRate->zone_name }}</td>
                            <td>{{ $taxRate->tax_rate }}</td>
                            <td>{{ $taxRate->tax_class_title }}</td>
                            <td>
                            	<strong>{{ trans('labels.AddedDate') }}: </strong>{{ $taxRate->date_added }}<br>
                                <strong>{{ trans('labels.LastModified') }}: </strong>{{ $taxRate->last_modified }}
							</td>
                            <td><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="edittaxrate/{{ $taxRate->tax_rates_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                            <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteTaxRateId" tax_rates_id ="{{ $taxRate->tax_rates_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                           </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$result['tax_rates']->links('vendor.pagination.default')}}
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
        <!-- deletetaxRateModal -->
	<div class="modal fade" id="deleteTaxRateModal" tabindex="-1" role="dialog" aria-labelledby="deletetaxRateModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteTaxRateModalLabel">{{ trans('labels.DeleteTaxRate') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deletetaxrate', 'name'=>'deletetaxRate', 'id'=>'deletetaxRate', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'tax_rates_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.DeleteTaxRateText') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary" id="deletetaxRate">{{ trans('labels.Delete') }}</button>
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