@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Banners') }} <small>{{ trans('labels.ListingAllBanners') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Banners') }}</li>
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
            <h3 class="box-title">{{ trans('labels.ListingAllBanners') }} </h3>
            <div class="box-tools pull-right">
            	<a href="addbanner" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNewBanner') }}</a>
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
                      <th>{{ trans('labels.Image') }}</th>
                      <th>{{ trans('labels.AddedModifiedDate') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(count($result['banners'])>0)
                    @foreach ($result['banners'] as $key=>$banners)
                        <tr>
                            <td>{{ $banners->banners_id }}</td>
                            <td>{{ $banners->banners_title }}</td>
                            <td><img src="{{asset('').'/'.$banners->banners_image}}" alt="" width=" 100px"></td>
                            <td><strong>{{ trans('labels.AddedDate') }}: </strong> {{ date('d M, Y', strtotime($banners->date_added)) }}<br>
                            <strong>{{ trans('labels.ModifiedDate') }}: </strong>@if(!empty($banners->date_status_change)) {{ date('d M, Y', strtotime($banners->date_status_change)) }}  @endif<br>
                            <strong>{{ trans('labels.ExpiryDate') }}: </strong>@if(!empty($banners->expires_date)) {{ date('d M, Y', strtotime($banners->expires_date)) }}  @endif</td>
                            
                            <td><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="editbanner/{{ $banners->banners_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                            
                            <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteBannerId" banners_id ="{{ $banners->banners_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </tr>
                    @endforeach
                    @else
                       <tr>
                            <td colspan="5">{{ trans('labels.NoRecordFound') }}</td>
                       </tr>
                    @endif
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$result['banners']->links('vendor.pagination.default')}}
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
    
    <!-- deleteBannerModal -->
	<div class="modal fade" id="deleteBannerModal" tabindex="-1" role="dialog" aria-labelledby="deleteBannerModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteBannerModalLabel">{{ trans('labels.DeleteBanner') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deleteBanner', 'name'=>'deleteBanner', 'id'=>'deleteBanner', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('banners_id',  '', array('class'=>'form-control', 'id'=>'banners_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.DeleteBannerText') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary" id="deleteBanner">{{ trans('labels.Delete') }}</button>
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