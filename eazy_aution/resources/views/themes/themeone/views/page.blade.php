@extends('layout')
@section('content')
<section class="site-content">
	<div class="container">
  		<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3><?=$result['pages'][0]->name?></h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
            		<li class="breadcrumb-item active"><?=$result['pages'][0]->name?></li>
                </ol>
            </div>
        </div>
        <div class="info-pages-area">
        	
            <div class="row">
            	
            	<div class="col-12 col-lg-9">
                	
                	<div class="col-12 spaceleft-0">
                    	<div class="heading">
                            <h2><?=$result['pages'][0]->name?></h2>
                            <hr>
                        </div>
                    	<?=stripslashes($result['pages'][0]->description)?>     
                    </div>
                </div>
                
                <div class="col-12 col-lg-3 spaceleft-0">
                    @include('common.sidebar_info')
                 </div>
            </div>
        </div>
	</div>
</section>
@endsection 