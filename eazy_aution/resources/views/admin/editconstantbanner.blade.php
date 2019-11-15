@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.editconstantbanner') }} <small>{{ trans('labels.editconstantbanner') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/constantbanners')}}"><i class="fa fa-bars"></i> {{ trans('labels.ListingConstantBanners') }}</a></li>
      <li class="active">{{ trans('labels.editconstantbanner') }}</li>
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
            <h3 class="box-title">{{ trans('labels.editconstantbanner') }} </h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
              		<div class="box box-info">
                    <br>                       
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              {{ session()->get('error') }}
                            </div>
                          @endif
                          
                          @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              {{ session()->get('success') }}
                            </div>
                          @endif
                        <!-- /.box-header -->
                        <!-- form start -->                        
                         <div class="box-body">
                         
                            {!! Form::open(array('url' =>'admin/updateconstantBanner', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                              
                                {!! Form::hidden('id',  $result['banners'][0]->banners_id , array('class'=>'form-control', 'id'=>'id')) !!}
                                {!! Form::hidden('oldImage',  $result['banners'][0]->banners_image, array('id'=>'oldImage')) !!}
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Language') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="languages_id">
                                          @foreach($result['languages'] as $language)
                                              <option value="{{$language->languages_id}}" @if($language->languages_id==$result['banners'][0]->languages_id) selected @endif>{{ $language->name }}</option>
                                          @endforeach
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ChooseLanguageText') }}</span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Side Banner') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="type">
                                          <option value="1" @if($result['banners'][0]->type==1) selected @endif>{{ trans('labels.Side Banner 1') }}</option>
                                          <option value="2" @if($result['banners'][0]->type==2) selected @endif>{{ trans('labels.Side Banner 2') }}</option>
                                          <option value="3" @if($result['banners'][0]->type==3) selected @endif>{{ trans('labels.Side Banner 3') }}</option>
                                          <option value="4" @if($result['banners'][0]->type==4) selected @endif>{{ trans('labels.Side Banner 4') }}</option>
                                          <option value="5" @if($result['banners'][0]->type==5) selected @endif>{{ trans('labels.Side Banner 5') }}</option>                                          <option value="6" @if($result['banners'][0]->type==6) selected @endif>{{ trans('labels.Side Banner 6') }}</option>
                                          <option value="7" @if($result['banners'][0]->type==7) selected @endif>{{ trans('labels.Side Banner 7') }}</option>
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.AddBannerText') }}</span>
                                  </div>
                                </div>
                                                                                                           
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::file('newImage', array('id'=>'banners')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ConstantBannerSizes') }}</span>
                                    <br>
                			
                                    <img src="{{asset('').$result['banners'][0]->banners_image}}" alt="" width=" 100px">
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.URL') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::text('banners_url', $result['banners'][0]->banners_url, array('class'=>'form-control','id'=>'banners_url')) !!}
                                    
                                  </div>
                                </div>               
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="status">
                                          <option value="1" @if($result['banners'][0]->status==1) selected @endif>{{ trans('labels.Active') }}</option>
                                          <option value="0" @if($result['banners'][0]->status==0) selected @endif>{{ trans('labels.Inactive') }}</option>
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.StatusBannerText') }}</span>
                                  </div>
                                </div>
                                
                                
                              <!-- /.box-body -->
                              <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary">{{ trans('labels.Update') }}</button>
                                <a href="{{ URL::to('admin/constantbanners')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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