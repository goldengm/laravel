@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  {{ trans('labels.languages') }} <small>{{ trans('labels.ListingAllLanguages') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active"> {{ trans('labels.languages') }}</li>
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
            <h3 class="box-title">{{ trans('labels.ListingAllLanguages') }} </h3>
            <div class="box-tools pull-right">
                <a href="{{ URL::to('admin/addlanguages')}}" type="button" style="display:inline-block; width: auto; margin-top: 0;" class="btn btn-block btn-primary">{{ trans('labels.AddLanguage') }}</a>
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
            
            <div class="row default-div hidden">
              <div class="col-xs-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                  {{ trans('labels.DefaultLanguageChangedMessage') }}
                </div>
              </div>
           </div>
           
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.Default') }}</th>
                      <th>{{ trans('labels.Language') }}</th>
                      <th>{{ trans('labels.Icon') }}</th>
                      <th>{{ trans('labels.Code') }}</th>
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(count($result['languages'])>0)
                    @foreach ($result['languages'] as $key=>$languages)
                        <tr>
                        	<td>
                           		<label>
                                  <input type="radio" name="languages_id" value="{{ $languages->languages_id}}"  class="default_language" @if($languages->is_default==1) checked @endif >
                                </label>
                            </td>
                            <!--<td>{{ $languages->languages_id }}</td>-->
                            <td>{{ $languages->name }}</td>
                            <td><img src="{{asset('').'/'.$languages->image}}" width="25px" alt=""></td>
                            <td>{{ $languages->code }}</td>
                            <td>
                            	<a data-toggle="tooltip" data-placement="bottom" title=" {{ $languages->name }}" href="{{ URL::to('admin/editlanguages/'.$languages->languages_id)}}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                                
                                @if($languages->is_default==0) 
                            	<a data-toggle="tooltip" data-placement="bottom" title=" {{ $languages->name }}" id="deleteLanguageId" languages_id ="{{ $languages->languages_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                @endif
                           </td>
                        </tr>
                    @endforeach
                   @else
                   <tr>
                   		<td colspan="5">{{ trans('labels.Nolanguageexist') }}</td>
                   </tr>
                   @endif
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$result['languages']->links('vendor.pagination.default')}}
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
        <!-- deletelanguagesModal -->
	<div class="modal fade" id="deleteLanguagesModal" tabindex="-1" role="dialog" aria-labelledby="deleteLanguagesModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="deleteLanguagesModalLabel">{{ trans('labels.DeleteLanguages') }}</h4>
		  </div>
		  {!! Form::open(array('url' =>'admin/deletelanguage', 'name'=>'deletelanguages', 'id'=>'deletelanguages', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
				  {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
				  {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'languages_id')) !!}
		  <div class="modal-body">						
			  <p>{{ trans('labels.confrimLanguageDelete') }}</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
			<button type="submit" class="btn btn-primary" id="deletelanguages">{{ trans('labels.Delete') }}</button>
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