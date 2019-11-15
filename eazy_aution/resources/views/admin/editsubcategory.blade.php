@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.EditSubCategories') }} <small>{{ trans('labels.EditSubCategories') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/subcategories')}}"><i class="fa fa-bars"></i> {{ trans('labels.SubCategories') }}</a></li>
      <li class="active">{{ trans('labels.EditSubCategories') }}</li>
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
            <h3 class="box-title">{{ trans('labels.EditSubCategories') }} </h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
              		<div class="box box-info">
                        <br>                       
                        @if (count($errors) > 0)
                              @if($errors->any())
                                <div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  {{$errors->first()}}
                                </div>
                              @endif
                          @endif
                        <!--<div class="box-header with-border">
                          <h3 class="box-title">Edit category</h3>
                        </div>-->
                        <!-- /.box-header -->
                        <!-- form start -->                        
                         <div class="box-body">
                         
                            {!! Form::open(array('url' =>'admin/updatesubcategory', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
							
                                {!! Form::hidden('id',  $result['editSubCategory'][0]->id , array('class'=>'form-control', 'id'=>'id')) !!}
                                {!! Form::hidden('oldImage', $result['editSubCategory'][0]->image , array('id'=>'oldImage')) !!}
                                {!! Form::hidden('oldIcon',  $result['editSubCategory'][0]->icon , array('id'=>'oldIcon')) !!}
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Category') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="parent_id">
                                         @foreach ($result['categories'] as $categories)
                                          <option 
                                          @if($categories->mainId == $result['editSubCategory'][0]->parent_id)
                                          	selected
                                          @endif 
                                           value="{{$categories->mainId}}">{{$categories->mainName}}</option>
                                         @endforeach
                                      </select>
                                  	  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseMainCategory') }}</span>
                                  
                                  </div>
                                </div>
                                
                                
                                 @foreach($result['description'] as $description_data)
                                    <div class="form-group">
                                      <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Name') }} ({{ $description_data['language_name'] }})</label>
                                      <div class="col-sm-10 col-md-4">
                                        <input type="text" name="category_name_<?=$description_data['languages_id']?>" class="form-control field-validate" value="{{$description_data['name']}}">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CategoryName') }} ({{ $description_data['language_name'] }}).</span>          
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                      </div>
                                    </div>
                                 
                                @endforeach
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.slug') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    <input type="hidden" name="old_slug" value="{{$result['editSubCategory'][0]->slug}}">
                                    <input type="text" name="slug" class="form-control field-validate" value="{{$result['editSubCategory'][0]->slug}}">
                                  	<span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.slugText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::file('newImage', array('id'=>'newImage')) !!}
                                     <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.UploadSubCategoryImage') }}</span>
                                    <br>
                
                                    <img src="{{asset('').$result['editSubCategory'][0]->image}}" alt="" width=" 100px">
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Icon') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::file('newIcon', array('id'=>'newIcon')) !!}
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.UploadSubCategoryIcon') }}</span>
                                  <br>
                
                                    <img src="{{asset('').$result['editSubCategory'][0]->icon}}" alt="" width=" 100px">
                                  </div>
                                </div>
                                
                              <!-- /.box-body -->
                              
                              <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary">{{ trans('labels.Update') }}</button>
                                <a href="{{ URL::to('admin/subcategories')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                              </div>
                              <!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>
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