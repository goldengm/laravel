@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  {{ trans('labels.TaxClasses') }} <small>{{ trans('labels.ListingTaxClasses') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active"> {{ trans('labels.TaxClasses') }}</li>
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
            <h3 class="box-title">{{ trans('labels.ListingTaxClasses') }} </h3>
            <div class="box-tools pull-right">
            	<a href="addtaxclass" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddTaxClass') }}</a>
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
                      <th>{{ trans('labels.Title') }}</th>
                      <th>{{ trans('labels.Description') }}</th>
                      <th>{{ trans('labels.Date') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($result['tax_class'] as $key=>$taxClass)
                        <tr>
                            <td>{{ $taxClass->tax_class_id }}</td>
                            <td>{{ $taxClass->tax_class_title }}</td>
                            <td width="30%">{{ $taxClass->tax_class_description }}</td>
                            <td>
                            	<strong>{{ trans('labels.AddedDate') }}: </strong>{{ $taxClass->date_added }}<br>
                                <strong>{{ trans('labels.LastModified') }}: </strong>{{ $taxClass->last_modified }}
							</td>
                            <td><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="edittaxclass/{{ $taxClass->tax_class_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                            <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteTaxClassId" tax_class_id ="{{ $taxClass->tax_class_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                           </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$result['tax_class']->links('vendor.pagination.default')}}
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
        <!-- deleteTaxClassModal -->
	<div class="modal fade" id="deleteTaxClassModal" tabindex="-1" role="dialog" aria-labelledby="deleteTaxClassModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteTaxClassModalLabel">{{ trans('labels.DeleteTaxClass') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deletetaxclass', 'name'=>'deleteTaxClass', 'id'=>'deleteTaxClass', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'tax_class_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.DeleteTaxClassText') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary" id="deleteTaxClass">{{ trans('labels.Delete') }}</button>
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