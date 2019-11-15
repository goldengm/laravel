@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View Device <small>View Device...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="../listingDevices"><i class="fa fa-dashboard"></i> List All Device</a></li>
      <li class="active">View Device</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">

      <div class="row">
        <div class="col-md-3">             		
              @if (count($errors) > 0)
                  @if($errors->any())
                    <div class="row">
                      <div class="col-xs-12"> 
                            <div class="alert alert-danger alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              {{$errors->first()}}
                            </div>
                      </div>
                    </div>
                  @endif
              @endif
            
            @if(count($result['message'])>0)
            <div class="row">
              <div class="col-xs-12"> 
                    <div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      {{ $result['message'] }}
                    </div>
              </div>
            </div>					
            @endif 

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{asset('').$result['devices'][0]->customers_picture}}" alt="{{ $result['devices'][0]->customers_firstname }} profile picture">
              <h3 class="profile-username text-center">{{ $result['devices'][0]->customers_firstname }} {{ $result['devices'][0]->customers_lastname }}</h3>

              <!--<p class="text-muted text-center">Software Engineer</p>-->

              <ul class="list-group list-group-unbDeviceed">
              	<li class="list-group-item">
                  <b>Device Type</b> <a class="pull-right">
                    @if($result['devices'][0]->device_type == '1')
                        IOS
                    @elseif($result['devices'][0]->device_type == '2')
                        Android
                    @elseif($result['devices'][0]->device_type == '3')
                        Desktop
                    @endif
                    </a>
                </li>
                <li class="list-group-item">
                  <b>Register Date</b> <a class="pull-right">{{ date('d/m/Y', $result['devices'][0]->register_date) }}</a>
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
              <li class="active"><a href="#push-notification" data-toggle="tab">Send Push Notification</a></li>
            </ul>
            <div class="tab-content">

              <div class="tab-pane active" id="push-notification">
               {!! Form::open(array('url' =>'admin/viewDevices', 'id'=>'sendNotificaionForm', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                            	
                   {!! Form::hidden('device_type', $result['devices'][0]->device_type, array('class'=>'form-control', 'id'=>'device_type')) !!}
                   {!! Form::hidden('device_id', $result['devices'][0]->device_id, array('class'=>'form-control', 'id'=>'device_id')) !!}
                   
                   <div class="alert alert-success alert-dismissible callout hide sent-push">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Notification has been sent!
                   </div>
                  <div class="alert alert-danger alert-dismissible callout not-sent hide">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Error while sending notification!
                  </div>
                  
                   <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                       {!! Form::text('title', '', array('class'=>'form-control', 'required', 'id'=>'title')) !!}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
					<div class="col-sm-10">
                   	 {!! Form::textarea('message', '', array('class'=>'form-control', 'required', 'rows'=>'5', 'id'=>'message')) !!}
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="button" class="btn btn-danger" id="send-notificaion">Send Notification</button>
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