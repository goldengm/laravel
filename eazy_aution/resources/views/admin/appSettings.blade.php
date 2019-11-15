@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.application_settings') }} <small>{{ trans('labels.application_settings') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.application_settings') }}</li>
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
            <h3 class="box-title">{{ trans('labels.application_settings') }} </h3>
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
                            <h4>{{ trans('labels.generalSetting') }} </h4>
                            <hr>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.homeStyle') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][28]->name}}" class="form-control">
                              	<option @if($result['settings'][28]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Style1') }}</option>
                                <option @if($result['settings'][28]->value == '2')
                                        selected
                                    @endif
                                 value="2"> {{ trans('labels.Style2') }}</option>
                              	<option @if($result['settings'][28]->value == '3')
                                        selected
                                    @endif
                                 value="3"> {{ trans('labels.Style3') }}</option>
                                <option @if($result['settings'][28]->value == '4')
                                        selected
                                    @endif
                                 value="4"> {{ trans('labels.Style4') }}</option>
                              	<option @if($result['settings'][28]->value == '5')
                                        selected
                                    @endif
                                 value="5"> {{ trans('labels.Style5') }}</option>                                   
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.homeStyleText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.categoryStyle') }}</label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][45]->name}}" class="form-control">
                              	<option @if($result['settings'][45]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.categories1') }}</option>
                                <option @if($result['settings'][45]->value == '2')
                                        selected
                                    @endif
                                 value="2"> {{ trans('labels.categories2') }}</option>
                              	<option @if($result['settings'][45]->value == '3')
                                        selected
                                    @endif
                                 value="3"> {{ trans('labels.categories3') }}</option>
                                <option @if($result['settings'][45]->value == '4')
                                        selected
                                    @endif
                                 value="4"> {{ trans('labels.categories4') }}</option>
                              	<option @if($result['settings'][45]->value == '5')
                                        selected
                                    @endif
                                 value="5"> {{ trans('labels.categories5') }}</option>  
                              	<option @if($result['settings'][45]->value == '6')
                                        selected
                                    @endif
                                 value="6"> {{ trans('labels.categories6') }}</option>                                   
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.categoryStyleText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group android-hide">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.DisplayFooterMenu') }}</label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][24]->name}}" class="form-control">
                              	<option @if($result['settings'][24]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][24]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>                                         
                               </select>                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.DisplayFooterMenuText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group android-hide">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.DisplayCartButton') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][25]->name}}" class="form-control">
                              	<option @if($result['settings'][25]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][25]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.DisplayCartButtonText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group android-hide">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.packageName') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][46]->name,  $result['settings'][46]->value, array('class'=>'form-control', 'id'=>$result['settings'][46]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.packageNameText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group android-hide">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Manage App Icons/Images') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][83]->name}}" class="form-control">
                              	<option @if($result['settings'][25]->value == 'image')
                                        selected
                                    @endif
                                 value="image"> {{ trans('labels.Ionic Image') }}</option>
                              	<option @if($result['settings'][83]->value == 'icon')
                                        selected
                                    @endif
                                 value="icon"> {{ trans('labels.Ionic Icon') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.Manage App Icons/Images Text') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group android-hide"  style="display: none">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.googleAnalyticId') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][47]->name,  $result['settings'][47]->value, array('class'=>'form-control', 'id'=>$result['settings'][47]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.googleAnalyticIdText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group android-hide" style="display: none">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.LazzyLoadingEffect') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                               
                                <select name="{{$result['settings'][23]->name}}" class="form-control">
                                    	<option 
                                        	@if($result['settings'][23]->value == 'android')
                                            	selected
                                            @endif
                                         value="android"> {{ trans('labels.Android') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'ios-small')
                                            	selected
                                            @endif
                                         value="ios-small"> {{ trans('labels.IOSSmall') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'bubbles')
                                            	selected
                                            @endif
                                         value="bubbles"> {{ trans('labels.Bubbles') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'circles')
                                            	selected
                                            @endif
                                         value="circles"> {{ trans('labels.Circles') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'crescent')
                                            	selected
                                            @endif
                                         value="crescent"> {{ trans('labels.Crescent') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'dots')
                                            	selected
                                            @endif
                                         value="dots"> {{ trans('labels.Dots') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'lines')
                                            	selected
                                            @endif
                                         value="lines"> {{ trans('labels.Lines') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'ripple')
                                            	selected
                                            @endif
                                         value="ripple"> {{ trans('labels.Ripple') }}</option>
                                         <option 
                                        	@if($result['settings'][23]->value == 'spiral')
                                            	selected
                                            @endif
                                         value="spiral"> {{ trans('labels.Spiral') }}</option>
                                         
                                 </select>
                                    
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.LazzyLoadingEffectText') }}</span>
                              </div>
                            </div>
                                                        
                            <hr>
                            <h4>{{ trans('labels.displayPages') }} </h4>
                            <hr>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.wishListPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][29]->name}}" class="form-control">
                              	<option @if($result['settings'][29]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][29]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.wishListPageText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.editProfilePage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][30]->name}}" class="form-control">
                              	<option @if($result['settings'][30]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][30]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.editProfilePageText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.shippingAddressPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][31]->name}}" class="form-control">
                              	<option @if($result['settings'][31]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][31]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.shippingAddressPageText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.myOrdersPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][32]->name}}" class="form-control">
                              	<option @if($result['settings'][32]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][32]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.myOrdersPageText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.contactUsPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][33]->name}}" class="form-control">
                              	<option @if($result['settings'][33]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][33]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.contactUsPageText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.aboutUsPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][34]->name}}" class="form-control">
                              	<option @if($result['settings'][34]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][34]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.aboutUsPageText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.newsPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][35]->name}}" class="form-control">
                              	<option @if($result['settings'][35]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][35]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.newsPageText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.introPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][36]->name}}" class="form-control">
                              	<option @if($result['settings'][36]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][36]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.introPageText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.shareapp') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][38]->name}}" class="form-control">
                              	<option @if($result['settings'][38]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][38]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.shareappText') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.rateapp') }}</label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][39]->name}}" class="form-control">
                              	<option @if($result['settings'][39]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][39]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.rateappText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.settingPage') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][38]->name}}" class="form-control">
                              	<option @if($result['settings'][38]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                              	<option @if($result['settings'][38]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.settingPageText') }}</span>
                              </div>
                            </div>

                             <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">Reviews
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][94]->name}}" class="form-control">
                                <option @if($result['settings'][94]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                                <option @if($result['settings'][94]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.reviewsPageText') }}</span>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">Messages
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                              <select name="{{$result['settings'][95]->name}}" class="form-control">
                                <option @if($result['settings'][95]->value == '1')
                                        selected
                                    @endif
                                 value="1"> {{ trans('labels.Show') }}</option>
                                <option @if($result['settings'][95]->value == '0')
                                        selected
                                    @endif
                                 value="0"> {{ trans('labels.Hide') }}</option>
                                         
                               </select>
                                
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.reviewsPageText') }}</span>
                              </div>
                            </div>
                            
                            <hr>
                            <h4>{{ trans('labels.LocalNotification') }} </h4>
                            <hr>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Title') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][21]->name, $result['settings'][21]->value, array('class'=>'form-control', 'id'=>$result['settings'][21]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.NotificationTitleText') }}</span>
                              </div>
                            </div>
                            
                            
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Detail') }}
                              
                              </label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text($result['settings'][23]->name, $result['settings'][23]->value, array('class'=>'form-control', 'id'=>$result['settings'][23]->name)) !!}
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;margin-top: 0;">{{ trans('labels.NotificationDetailtext') }}</span>
                              </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.NotificationDuration') }}</label>
                                <div class="col-sm-10 col-md-4">
                                                                    
                                    <select class="form-control" name="{{$result['settings'][27]->name}}">
                                          <option value="day" @if($result['settings'][27]->value=='day') selected @endif>{{ trans('labels.Day') }}</option>
                                          <option value="month" @if($result['settings'][27]->value=='month') selected @endif>{{ trans('labels.Month') }}</option>
                                          <option value="year" @if($result['settings'][27]->value=='year') selected @endif>{{ trans('labels.Year') }}</option>
                                    </select>
                                    
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.NotificationDurationText') }}</span>
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