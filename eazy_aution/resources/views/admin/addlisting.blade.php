
@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Add Listing <small>Add Listing...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">Add Listing</li>
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
            <h3 class="box-title">Add Listing </h3>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                    <div class="box box-info">
                        <!-- form start -->
                         <div class="box-body">
                          @if( count($errors) > 0)
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <span class="sr-only">{{ trans('labels.Error') }}:</span>
                                    {{ $error }}
                                </div>
                             @endforeach
                          @endif

                            {!! Form::open(array('url' =>'admin/addnewlisting', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'id'=>'inputForm', 'enctype'=>'multipart/form-data')) !!}
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Category') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                  @if(!empty(session('categories_id')))
										                    @php
                                        $cat_array = explode(',', session('categories_id'));
                                        @endphp
                                        <ul class="list-group list-group-root well">
                                          @foreach ($result['categories'] as $categories)
                                          @if(in_array($categories->id,$cat_array))
                                          <li href="#" class="list-group-item"><label style="width:100%"><input id="categories_<?=$categories->id?>" type="checkbox" class=" required_one categories" name="categories[]" value="{{ $categories->id }}" > {{ $categories->name }}</label></li>
                                          @endif
                                              @if(!empty($categories->sub_categories))
                                              <ul class="list-group">
                                                    	<li class="list-group-item" >
                                                    @foreach ($categories->sub_categories as $sub_category)
                                                    @if(in_array($sub_category->sub_id,$cat_array))
                                                    <label><input type="checkbox" name="categories[]" class="required_one sub_categories sub_categories_<?=$categories->id?>" parents_id = '<?=$categories->id?>' value="{{ $sub_category->sub_id }}"> {{ $sub_category->sub_name }}</label> @endif @endforeach</li>

                                              </ul>
                                              @endif
                                          @endforeach
                                        </ul>
                                  @else
                                   <ul class="list-group list-group-root well">
                                      @foreach ($result['categories'] as $categories)
                                      <li href="#" class="list-group-item"><label style="width:100%"><input id="categories_<?=$categories->id?>" type="checkbox" class=" required_one categories" name="categories[]" value="{{ $categories->id }}" > {{ $categories->name }}</label></li>
                                          @if(!empty($categories->sub_categories))
                                          <ul class="list-group">
                                                    <li class="list-group-item" >
                                                @foreach ($categories->sub_categories as $sub_category)<label><input type="checkbox" name="categories[]" class="required_one sub_categories sub_categories_<?=$categories->id?>" parents_id = '<?=$categories->id?>' value="{{ $sub_category->sub_id }}"> {{ $sub_category->sub_name }}</label>@endforeach</li>

                                          </ul>
                                          @endif
                                      @endforeach
                                    </ul>
                                  @endif

                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ChooseCatgoryText') }}.</span>
                                      <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                      <label for="name" class="col-sm-2 col-md-3 control-label"> Title </label>
                                      <div class="col-sm-10 col-md-4">
                                            <input type="text" name="title" class="form-control field-validate">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                           {{ trans('labels.EnterProductNameIn') }}  </span>
                                      <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                      </div>
                                </div>


                                <div class="form-group">
                                	<label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Description') }}  </label>
                                    <div class="col-sm-10 col-md-8">
                                    	<textarea id="editor" name="desc" class="form-control" rows="5"></textarea>
                                    	<span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                       {{ trans('labels.EnterProductDetailIn') }} </span>
                                      </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Condition</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="condition">
                                          <option value="0">New</option>
                                          <option value="0">Used</option>
                                      </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      Please select listing condition.</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label"> Price </label>
                                  <div class="col-sm-10 col-md-4">
                                        <input type="text" name="price" class="form-control field-validate">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                       Item price  </span>
                                  <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Shipping</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="shipping">
                                        <option value="upsShipping">UPS Shippng</option>
                                        <option value="freeShiping">Free Shipping</option>
                                        <option value="localPickup">Local Pickup</option>
                                        <option value="flateRate">Flate Rate</option>
                                        <option value="shippingByWeight">Shipping By Weight</option>

                                      </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      Please select shipping method</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Condition</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="condition">
                                          <option value="0">New</option>
                                          <option value="0">Used</option>
                                      </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      Please select listing condition.</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Accept Offers?</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="acceptoffer">
                                          <option value="1">Yes</option>
                                          <option value="0">No</option>
                                      </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Auto Relisting?</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="autolisting">
                                          <option value="1">Yes</option>
                                          <option value="0">No</option>
                                      </select>
                                  </div>
                                </div>
                                <div class="form-group">                                    
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Listing date</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input  class="form-control datepicker" type="text" name="listdate" id="listdate" value="">     
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    Please select item listing date</span>                               
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Auction Style</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="auction_style">
                                        <option value="upsShipping">Fixed Listing</option>
                                        <option value="freeShiping">Raffle</option>
                                      </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      Please select auction style</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                	<label for="name" class="col-sm-2 col-md-3 control-label">Additional Description</label>
                                    <div class="col-sm-10 col-md-8">
                                    	<textarea id="editor1" name="additional_desc" class="form-control" rows="5"></textarea>
                                    	<span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                       {{ trans('labels.EnterProductDetailIn') }} </span>
                                      </div>
                                </div>

                              <!-- /.box-body -->
                              <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary pull-right"  id="normal-btn"> Save <i class="fa fa-angle-right 2x"></i></button>
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
@section('script')
<script type="text/javascript">
    function validate(formData, jqForm, options) {
        var form = jqForm[0];
        if (!form.title.value) {
            alert('Please insert title');
            return false;
        }
        if (!form.desc.value) {
            alert('Please insert item description.');
            return false;
        }
    }

		$(function () {


				CKEDITOR.replace('editor');
        CKEDITOR.replace('editor1');
			//bootstrap WYSIHTML5 - text editor
			$(".textarea").wysihtml5();

      $("#inputForm").ajaxForm({
        beforeSubmit: validate,
        complete: function(xhr) {
          console.log(xhr)
        }
      })
    });
</script>
@endsection