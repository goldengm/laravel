@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.SendNotification') }} <small>{{ trans('labels.SendNotification') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/devices')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.ListingDevices') }}</a></li>
      <li class="active">{{ trans('labels.SendNotification') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">

      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
            @if(!empty($result['devices'][0]->customers_picture))
              <img class="profile-user-img img-responsive img-circle" src="{{asset('').$result['devices'][0]->customers_picture}}" alt="{{ $result['devices'][0]->customers_firstname }} profile picture">
              <h3 class="profile-username text-center">{{ $result['devices'][0]->customers_firstname }} {{ $result['devices'][0]->customers_lastname }}</h3>
            @endif

              <ul class="list-group list-group-unbDeviceed">
              	<li class="list-group-item">
                  <b>{{ trans('labels.DeviceType') }}</b> <a class="pull-right">
                    @if($result['devices'][0]->device_type == '1')
                       {{ trans('labels.IOS') }} 
                    @elseif($result['devices'][0]->device_type == '2')
                       {{ trans('labels.Android') }} 
                    @elseif($result['devices'][0]->device_type == '3')
                        {{ trans('labels.Other') }}
                    @endif
                    </a>
                </li>
                <li class="list-group-item">
                  <b>{{ trans('labels.DeviceOS') }} </b> <a class="pull-right">{{ $result['devices'][0]->device_os }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ trans('labels.Manufacturer') }}</b> <a class="pull-right">{{$result['devices'][0]->manufacturer }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ trans('labels.DeviceModel') }}</b> <a class="pull-right">{{ $result['devices'][0]->device_model }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ trans('labels.RegisterDate') }}</b> <a class="pull-right">{{ date('d/m/Y', $result['devices'][0]->register_date) }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ trans('labels.Status') }}</b> <a class="pull-right">
                  @if($result['devices'][0]->status=='0')
                  	<span class="badge bg-red"> {{ trans('labels.Inactive') }}</span>
                  @elseif($result['devices'][0]->status=='1')
                  	<span class="badge bg-light-blue"> {{ trans('labels.Active') }}</span>
                  @endif
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#push-notification" data-toggle="tab">{{ trans('labels.SendNotification') }}</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="push-notification">
               {!! Form::open(array('url' =>'admin/viewDevices', 'id'=>'sendNotificaionForm', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            	
                   {!! Form::hidden('device_type', $result['devices'][0]->device_type, array('class'=>'form-control', 'id'=>'device_type')) !!}
                   {!! Form::hidden('device_id', $result['devices'][0]->device_id, array('class'=>'form-control', 'id'=>'device_id')) !!}
                   
                   <div class="alert alert-success alert-dismissible callout hide sent-push">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ trans('labels.NotifcationSentMessage') }}
                   </div>
                  <div class="alert alert-danger alert-dismissible callout not-sent hide">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ trans('labels.NotifcationSentErrorMessage') }}
                  </div>
                  
                   <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">{{ trans('labels.Title') }}</label>

                    <div class="col-sm-10">
                       {!! Form::text('title', '', array('class'=>'form-control field-validate', 'required', 'id'=>'title')) !!}
                       <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                       {{ trans('labels.EnterNotificationTitle') }}</span>
                       <span class="help-block hidden title-error">{{ trans('labels.textRequiredFieldMessage') }}</span>
                    </div>
                   </div>
                   
                   <div class="form-group">
                       <label for="inputName" class="col-sm-2 control-label">{{ trans('labels.Image') }}</label>
                      <div class="col-sm-10 col-md-4">
                        {!! Form::file('image', array('id'=>'image')) !!}
                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                        {{ trans('labels.notificationImageText') }}</span>
                      </div>
                    </div>
                  
                  
                  <div class="form-group ">
                    <label for="inputExperience" class="col-sm-2 control-label">{{ trans('labels.Message') }}</label>
					<div class="col-sm-10">
                   	 {!! Form::textarea('message', '', array('class'=>'form-control', 'required', 'rows'=>'5', 'id'=>'message')) !!}
                       <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.MessageText') }}</span>
                     <span class="help-block hidden message-error">{{ trans('labels.textRequiredFieldMessage') }}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="send-notificaion">{{ trans('labels.SendNotification') }}</button>
                    </div>
                  </div>
                {!! Form::close() !!}
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
  <!-- /.content --> 
</div>
@endsection 