<div style="width: 100%; display:block;">
<h2>{{ trans('labels.WelcomeEamailTitle') }}</h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $userData[0]->customers_firstname }} {{ $userData[0]->customers_lastname }}!</strong><br>
	{{ trans('labels.accountCreatedText') }}<br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ trans('labels.regardsForThanks') }}
</p>
</div>