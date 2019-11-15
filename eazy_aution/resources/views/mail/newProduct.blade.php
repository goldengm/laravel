<div style="width: 100%; display:block;">
<h2>{{ trans('labels.NewProductEmailTitle') }}</h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $customers_data->customers_firstname }} {{ $customers_data->customers_lastname }}!</strong><br>
	
    {{ trans('labels.NewProductEmailPart1') }} <strong>{{$customers_data->product[0]->products_name}}</strong> {{ trans('labels.NewProductEmailPart2') }}
    <br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ trans('labels.regardsForThanks') }}
</p>
</div>