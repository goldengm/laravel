<?php
require_once app_path('stripe/vendor/autoload.php');

$stripe = array(
 // "secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
 // "publishable_key" => "pk_test_6pRNASCoBOKtIshFeQd4XMUh"
  "secret_key"      => $payments_setting[0]->secret_key,
  "publishable_key" => $payments_setting[0]->publishable_key
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>