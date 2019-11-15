@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.admobSettings') }} <small>{{ trans('labels.admobSettings') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.admobSettings') }}</li>
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
            <h3 class="box-title">{{ trans('labels.admobSettings') }} </h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
              		<div class="box box-info">
                        <!--<div class="box-header with-border">
                          <h3 class="box-title">Setting</h3>
                        </div>-->
                        <!-- /.box-header -->
                        <!-- form start -->                        
                         <div class="box-body">
                          @if( count($errors) > 0)
                            @foreach($errors->all() as $error)
                                <div class="alert alert-success" role="alert">
                                      <span class="icon fa fa-check" aria-hidden="true"></span>
                                      <span class="sr-only">{{ trans('labels.Setting') }}:</span>
                                      {{ $error }}
                                </div>
                             @endforeach
                          @endif
                        
                            {!! Form::open(array('url' =>'admin/updateSetting', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                                                                                   
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.admobID') }}</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][42]->name,  $result['settings'][42]->value, array('class'=>'form-control', 'id'=>$result['settings'][42]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.admobIDText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.unitIdBanner') }}</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][43]->name,  $result['settings'][43]->value, array('class'=>'form-control', 'id'=>$result['settings'][43]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.unitIdBannerText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.unitIdInterstitial') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][44]->name, $result['settings'][44]->value, array('class'=>'form-control', 'id'=>$result['settings'][44]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.unitIdInterstitialText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.admobStatus') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][41]->name}}" class="form-control">
                              	<option @if($result['settings'][41]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][41]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.admobStatusText') }}</span>
                              </div>
                            </div>
                                                     
                            <div class="android-hide">
                            <hr>
                            <h4>{{ trans('labels.admobSettingIOS') }} </h4>
                            <hr>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.admobID') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][58]->name, $result['settings'][58]->value, array('class'=>'form-control', 'id'=>$result['settings'][58]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.admobIDText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.unitIdBanner') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][59]->name, $result['settings'][59]->value, array('class'=>'form-control', 'id'=>$result['settings'][59]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.unitIdBannerText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.unitIdInterstitial') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][60]->name, $result['settings'][60]->value, array('class'=>'form-control', 'id'=>$result['settings'][60]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.unitIdInterstitialText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.admobStatus') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][57]->name}}" class="form-control">
                              	<option @if($result['settings'][57]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][57]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>                                         
                               </select>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.admobStatusText') }}</span>
                              </div>
                            </div>
                            
                           </div>
                            
                              <!-- /.box-body -->
                            <div class="box-footer text-center">
                            	<button type="submit" class="btn btn-primary">{{ trans('labels.Update') }} </button>
                            	<a href="{{ URL::to('admin/dashboard/this_month')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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