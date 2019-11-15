@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        SignUp
        <small>SignUp Here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
        <li class="active">SignUp</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Recap Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-xs-8">
                <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Horizontal Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(array('url' => 'admin/signme', 'id' => 'register-form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
            <!--<form class="form-horizontal">-->
              <div class="box-body">
              @if (count($errors) > 0)
              
             	@foreach ($errors->all() as $error) 
                 	<div class="alert alert-danger" role="alert">
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <span class="sr-only">Error:</span>
                      {{ $error }}
                  </div>
                
                @endforeach
              @endif
                <div class="form-group">
                  {!! Form::label('name', 'Name', array('class' => 'col-sm-2 control-label')) !!}
                  <div class="col-sm-10">
                  	 {!! Form::text('userName', '', array('id' => 'userName', 'class' => 'form-control')) !!}
                  </div>
                </div>
                
                <div class="form-group">
                  {!! Form::label('name', 'Email', array('class' => 'col-sm-2 control-label')) !!}
                  <div class="col-sm-10">
                    {!! Form::email('email','', array('id' => 'email', 'class' => 'form-control')) !!}
                  </div>
                </div>
                
                <div class="form-group">
                   {!! Form::label('password', 'Password', array('class' => 'col-sm-2 control-label')) !!}
                  <div class="col-sm-10">
                   {!! Form::password('password', array('id' => 'password', 'class' => 'form-control')) !!}
                  </div>
                </div>
                
                <div class="form-group">
                   {!! Form::label('Re-Password', 'Re-Password', array('class' => 'col-sm-2 control-label')) !!}
                  <div class="col-sm-10">
                   {!! Form::password('re_password', array('id' => 're-password3', 'class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                {!! Form::submit('Sign in', array('id' => 'registerForm', 'class' => 'btn btn-info pull-right')) !!}
              </div>
              <!-- /.box-footer -->
            {!! Form::close() !!}
            
            @if(Session::has('success'))
           		 {!! session('success') !!}
           	
            @endif
            
            @if(Session::has('error'))
            	{!! session('error') !!}
            
            @endif
          </div>
                </div>
               
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            
            <!-- /.box-footer -->
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