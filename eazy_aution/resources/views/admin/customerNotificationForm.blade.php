<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="editManufacturerLabel">{{ trans('labels.SendNotifications') }}</h4>
</div>
  {!! Form::open(array('url' =>'admin/singleUserNotification', 'name'=>'send_user_notification', 'id'=>'sendNotificaionForm', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
		  
<div class="modal-body">     

      	@foreach($devices as $devices_data)
		 	<input type="hidden" id="device_id" name="device_id" value="{{$devices_data->device_id}}"> 
    		<input id="device_type" name="device_type" value="{{$devices_data->device_type}}" type="hidden">
        @endforeach

       
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
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
	<button type="button" class="btn btn-primary" id="single-notificaion">{{ trans('labels.Send') }}</button>
</div>
  {!! Form::close() !!}