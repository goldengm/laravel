@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.website_settings') }} <small>{{ trans('labels.website_settings') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.website_settings') }}</li>
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
            <h3 class="box-title">{{ trans('labels.website_settings') }} </h3>
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
                                      <span class="sr-only">{{ trans('labels.Setting') }}Error:</span>
                                      {{ $error }}
                                </div>
                             @endforeach
                          @endif
                        
                            {!! Form::open(array('url' =>'admin/updateSetting', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                            <br>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.homeStyle') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][80]->name}}" class="form-control">
                              	<option @if($result['settings'][80]->value == 'one')
                                        selected
                                    @endif
                                 value="one"> {{ trans('labels.Style1') }}</option>
                                <option @if($result['settings'][80]->value == 'two')
                                        selected
                                    @endif
                                 value="two"> {{ trans('labels.Style2') }}</option>
                              	<option @if($result['settings'][80]->value == 'three')
                                        selected
                                    @endif
                                 value="three"> {{ trans('labels.Style3') }}</option>
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.homeStyleText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Home Colors') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][81]->name}}" class="form-control">
                              	<option @if($result['settings'][81]->value == 'app')
                                        selected
                                    @endif
                                 value="app"> {{ trans('labels.Default') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.1')
                                        selected
                                    @endif
                                 value="app.theme.1"> {{ trans('labels.Black/Red') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.2')
                                        selected
                                    @endif
                                 value="app.theme.2"> {{ trans('labels.White/Blue') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.3')
                                        selected
                                    @endif
                                 value="app.theme.3"> {{ trans('labels.White/Parrot') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.4')
                                        selected
                                    @endif
                                 value="app.theme.4"> {{ trans('labels.Cyan/Blue') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.5')
                                        selected
                                    @endif
                                 value="app.theme.5"> {{ trans('labels.Brown/Skin') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.6')
                                        selected
                                    @endif
                                 value="app.theme.6"> {{ trans('labels.White/Yellow') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.7')
                                        selected
                                    @endif
                                 value="app.theme.7"> {{ trans('labels.White/Red') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.8')
                                        selected
                                    @endif
                                 value="app.theme.8"> {{ trans('labels.Baby Pink/Purple') }}</option>
                                <option @if($result['settings'][81]->value == 'app.theme.9')
                                        selected
                                    @endif
                                 value="app.theme.9"> {{ trans('labels.Black/White') }}</option>
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.homecolorText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.sitename logo') }}</label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][78]->name}}" class="form-control">
                              	<option @if($result['settings'][78]->value == 'name')
                                        selected
                                    @endif
                                 value="name"> {{ trans('labels.Name') }}</option>
                              	<option @if($result['settings'][78]->value == 'logo')
                                        selected
                                    @endif
                                 value="logo"> {{ trans('labels.Logo') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.sitename logo Text') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.website name') }}</label>
                              <div class="col-sm-10 col-md-4">
                                <input type="text" id="{{$result['settings'][79]->name}}" name="{{$result['settings'][79]->name}}" class="form-control" value="<?=stripslashes($result['settings'][79]->value)?>">
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.website name text') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label"> {{ trans('labels.WebLogo') }} </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::file($result['settings'][15]->name, array('id'=>$result['settings'][15]->name)) !!}<span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.WebLogoText') }}</span>
                                <br>
                                {!! Form::hidden('oldImage',  $result['settings'][15]->value , array('id'=>$result['settings'][15]->name)) !!}
                                <img src="{{asset('').$result['settings'][15]->value}}" alt="" width=" 175px">
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.facebookLink') }}</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][50]->name,  $result['settings'][50]->value, array('class'=>'form-control', 'id'=>$result['settings'][50]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.facebookLinkText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.googleLink') }}</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][51]->name,  $result['settings'][51]->value, array('class'=>'form-control', 'id'=>$result['settings'][51]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.googleLinkText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.twitterLink') }}</label>
                              <div class="col-sm-10 col-md-4">
                              		{!! Form::text($result['settings'][52]->name,  $result['settings'][52]->value, array('class'=>'form-control', 'id'=>$result['settings'][52]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.twitterLinkText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.linkedLink') }}</label>
                              <div class="col-sm-10 col-md-4">
                              		{!! Form::text($result['settings'][53]->name,  $result['settings'][53]->value, array('class'=>'form-control', 'id'=>$result['settings'][53]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.linkedLinkText') }}</span>
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