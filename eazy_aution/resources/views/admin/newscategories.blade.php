@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.NewsCategories') }} <small>{{ trans('labels.ListingNewsCategories') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.NewsCategories') }}</li>
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
            <h3 class="box-title">{{ trans('labels.ListingNewsCategories') }} </h3>
            <div class="box-tools pull-right">
            	<a href="addnewscategory" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNewsCategory') }}</a>
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
                      <th>{{ trans('labels.Name') }}</th>
                      <th>{{ trans('labels.Image') }}</th>
                      <!--<th>Icon</th>-->
                      <th>{{ trans('labels.AddedLastModifiedDate') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(count($listingCategories)>0)
                    @foreach ($listingCategories as $key=>$categories)
                        <tr>
                            <td>{{ $categories->id }}</td>
                            <td>{{ $categories->name }}</td>
                            <td><img src="{{asset('').'/'.$categories->image}}" alt="" width=" 100px"></td>
                           <!-- <td><img src="{{asset('').'/'.$categories->icon}}" alt="" width=" 100px"></td>-->
                            <td><strong>{{ trans('labels.AddedDate') }}: </strong> {{ $categories->date_added }}<br>
                            <strong>{{ trans('labels.ModifiedDate') }}: </strong>{{ $categories->last_modified }}  </td>
                            <td><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="editnewscategory/{{ $categories->id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                           <!-- <a data-toggle="tooltip" data-placement="bottom" title="Delete {{ $categories->name }} category" href="deleteNewsCategory/{{ $categories->id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>-->
                           <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteNewsCategroyId" category_id ="{{ $categories->id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                           
                           </td>
                        </tr>
                        
                    @endforeach
                 @else
                 <tr>
                 	<td colspan="5"> {{ trans('labels.NoRecordFound') }}</td>
                 </tr>
                 @endif
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$listingCategories->links('vendor.pagination.default')}}
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
    
    <div class="modal fade" id="deleteNewsCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteNewsCategoryModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteNewsCategoryModalLabel">{{ trans('labels.DeleteNewsCategory') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deletenewscategory', 'name'=>'deleteNewsCategory', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.DeleteNewsCategoryText') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary" id="deleteNewsCategory">{{ trans('labels.Delete') }}</button>
		  </div>
		  {!! Form::close() !!}
		</div>
	  </div>
	</div>
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 