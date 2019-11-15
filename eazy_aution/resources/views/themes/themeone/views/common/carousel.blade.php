<div id="myCarousel" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		@foreach($result['slides'] as $key=>$slides_data)
			<li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if($key==0) active @endif"></li>
		@endforeach
	</ol>
	<div class="carousel-inner">
    	
		@foreach($result['slides'] as $key=>$slides_data)
			<div class="carousel-item  @if($key==0) active @endif">
			@if($slides_data->type == 'category')
				<a href="{{ URL::to('/shop?category='.$slides_data->url)}}">
			@elseif($slides_data->type == 'product')
				<a href="{{ URL::to('/product-detail/'.$slides_data->url)}}">
			@elseif($slides_data->type == 'mostliked')
				<a href="{{ URL::to('shop?type=mostliked')}}">
			@elseif($slides_data->type == 'topseller')
				<a href="{{ URL::to('shop?type=topseller')}}">
			@elseif($slides_data->type == 'deals')
				<a href="{{ URL::to('shop?type=deals')}}">
			@endif
				<img width="100%" class="first-slide"  src="{{asset('').$slides_data->image}}" width="100%" alt="First slide">
				</a>
			</div>
					
		@endforeach


	</div>
	<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
		<span class="fa fa-angle-left" aria-hidden="true"></span>
		<span class="sr-only">@lang('website.Previous')</span>
	</a>
	<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
		<span class="fa fa-angle-right" aria-hidden="true"></span>
		<span class="sr-only">@lang('website.Next')</span>
	</a>
</div>