<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
namespace App\Http\Controllers\App;

//validator is builtin class in laravel
use Validator;

use Mail;
use DB;
use DateTime;

//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

//for Carbon a value 
use Carbon;
use Log;
use Lang;
class OrderController extends Controller
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	 
	
	//hyperpaytoken 
	public function hyperpaytoken(Request $request){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			$payments_setting = DB::table('payments_setting')->get();
			
			//check envinment
			if($payments_setting[0]->hyperpay_enviroment == '0'){
				$env_url = "https://test.oppwa.com/v1/checkouts";
			}else{
				$env_url = "https://oppwa.com/v1/checkouts";	
			}
						
			$url = $env_url;
			$data = "authentication.userId=" .$payments_setting[0]->hyperpay_userid.
				"&authentication.password=" .$payments_setting[0]->hyperpay_password.
				"&authentication.entityId=" .$payments_setting[0]->hyperpay_entityid.
				"&amount=" . $request->amount.
				"&currency=SAR" .
				"&paymentType=DB".
				"&customer.email=".$request->email.
				"&testMode=INTERNAL".
				"&merchantTransactionId=". uniqid();
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$responseData = curl_exec($ch);
			if(curl_errno($ch)) {
				return curl_error($ch);
			}
			curl_close($ch);
			
			$data = json_decode($responseData);
			
			if($data->result->code=='000.200.100'){
				$responseData = array('success'=>'1', 'token'=>$data->id, 'message'=>"Token generated.");
			}else{
				$responseData = array('success'=>'2', 'token'=>array(), 'message'=>$data->result->description);
			}
			
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	
	
	//hyperpaypaymentstatus 
	public function hyperpaypaymentstatus(Request $request){
		
		$payments_setting = DB::table('payments_setting')->get();
		
		//check envinment
		if($payments_setting[0]->hyperpay_enviroment == '0'){
			$env_url = "https://test.oppwa.com";
		}else{
			$env_url = "https://oppwa.com";
		}		
						
		$url = $env_url.$request->resourcePath;
		$url .= "?authentication.userId=".$payments_setting[0]->hyperpay_userid;
		$url .= "&authentication.password=".$payments_setting[0]->hyperpay_password;
		$url .= "&authentication.entityId=".$payments_setting[0]->hyperpay_entityid;
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$responseData = curl_exec($ch);
		if(curl_errno($ch)) {
			return curl_error($ch);
		}
		curl_close($ch);
		//print_r($responseData);
		$data = json_decode($responseData);
		
		if(preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $data->result->code)){
			$transaction_id = $data->ndc;
			$orders_id = DB::table('orders')->insertGetId(
					[	 'transaction_id' => $transaction_id,
						 'order_information'  => $responseData
					]);
			return redirect('app/paymentsuccess?data='.$responseData);
		}else{
			return redirect('app/paymenterror');
		}			
		
	}
	
	//paymentsuccess
	public function paymentsuccess(){}
	
	//paymenterror
	public function paymenterror(){}
		 
	//generate token 
	public function generatebraintreetoken(){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			$payments_setting = DB::table('payments_setting')->get();
			
			//braintree transaction get nonce
			$is_transaction  = '0'; 			# For payment through braintree
			
				if($payments_setting[0]->braintree_enviroment == '0'){
					$braintree_environment = 'sandbox';	
				}else{
					$braintree_environment = 'production';	
				}
				
				$braintree_merchant_id = $payments_setting[0]->braintree_merchant_id;
				$braintree_public_key  = $payments_setting[0]->braintree_public_key;
				$braintree_private_key = $payments_setting[0]->braintree_private_key;
			
			//for token please check braintree.php file
			require_once app_path('braintree/Braintree.php');
			
			$responseData = array('success'=>'1', 'token'=>$clientToken, 'message'=>"Token generated.");
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	
	//instamojoToken
	public function instamojoToken(){
		$payments_setting = DB::table('payments_setting')->get();
		$instamojo_client_id 	  = $payments_setting[0]->instamojo_client_id;
		$instamojo_client_secret  = $payments_setting[0]->instamojo_client_secret;
		$instamojo = new InstamojoController($instamojo_client_id, $instamojo_client_secret);	
		$clientToken = $instamojo->getToken();
		print $clientToken;
	}
	
	//get default payment method
	public function getpaymentmethods(Request $request){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			
			$result = array();
			$payments_setting = DB::table('payments_setting')->get();
			
			if($payments_setting[0]->braintree_enviroment=='0'){
				$braintree_enviroment = 'Test';
			}else{
				$braintree_enviroment = 'Live';
			}
			$braintree_description = DB::table('payment_description')->where([['payment_name','Braintree'],['language_id',$request->language_id]])->get();
			
			$braintree_card = array(
				'environment' => $braintree_enviroment, 
				'name' => $braintree_description[0]->sub_name_1,
				'method' => 'braintree_card',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'public_key' => $payments_setting[0]->braintree_public_key,
				'active' => $payments_setting[0]->brantree_active,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
			$braintree_paypal = array(
				'environment' => $braintree_enviroment, 
				'name' => $braintree_description[0]->sub_name_2,
				'method' => 'braintree_paypal',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'public_key' => $payments_setting[0]->braintree_public_key,
				'active' => $payments_setting[0]->brantree_active,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
			if($payments_setting[0]->stripe_enviroment=='0'){
				$stripe_enviroment = 'Test';
			}else{
				$stripe_enviroment = 'Live';
			}
			
			$stripe_description = DB::table('payment_description')->where([['payment_name','Stripe'],['language_id',$request->language_id]])->get();
			$stripe = array(
				'environment' => $stripe_enviroment,
				'name' => $stripe_description[0]->name, 
				'method' => 'stripe',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'public_key' => $payments_setting[0]->publishable_key,
				'active' => $payments_setting[0]->stripe_active,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
			$cod_description = DB::table('payment_description')->where([['payment_name','Cash On Delivery'],['language_id',$request->language_id]])->get();
			$cod = array(
				'environment' => '', 
				'name' => $cod_description[0]->name, 
				'method' => 'cod',
				'public_key' => '',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'active' => $payments_setting[0]->cash_on_delivery,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
			if($payments_setting[0]->paypal_enviroment=='0'){
				$paypal_enviroment = 'Test';
			}else{
				$paypal_enviroment = 'Live';
			}		
			
			$paypal_description = DB::table('payment_description')->where([['payment_name','Paypal'],['language_id',$request->language_id]])->get();
			$paypal = array(
				'environment' => $paypal_enviroment, 
				'name' => $paypal_description[0]->name, 
				'method' => 'paypal',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'public_key' => $payments_setting[0]->paypal_id,
				'active' => $payments_setting[0]->paypal_status,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
			if($payments_setting[0]->cybersource_enviroment=='0'){
				$cybersource_enviroment = 'Test';
			}else{
				$cybersource_enviroment = 'Live';
			}	
			
			$cybersource_description = DB::table('payment_description')->where([['payment_name','cybersource'],['language_id',$request->language_id]])->get();
			$cybersource = array(
				'environment' => $cybersource_enviroment, 
				'name' => $cybersource_description[0]->name, 
				'method' => 'cybersource',
				'public_key' => '',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'active' => $payments_setting[0]->cybersource_status,
				'payment_currency' => ''
			);
			
			if($payments_setting[0]->instamojo_enviroment=='0'){
				$instamojo_enviroment = 'Test';
			}else{
				$instamojo_enviroment = 'Live';
			}	
			
			$instamojo_description = DB::table('payment_description')->where([['payment_name','Instamojo'],['language_id',$request->language_id]])->get();
			$instamojo = array(
				'environment' => $instamojo_enviroment, 
				'name' => $instamojo_description[0]->name, 
				'method' => 'instamojo',
				'public_key' => $payments_setting[0]->instamojo_api_key,
				'auth_token' => $payments_setting[0]->instamojo_auth_token,
				'client_id' => $payments_setting[0]->instamojo_client_id,
				'client_secret' => $payments_setting[0]->instamojo_client_secret,
				'active' => $payments_setting[0]->instamojo_active,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
			if($payments_setting[0]->hyperpay_enviroment=='0'){
				$hyperpay_enviroment = 'Test';
			}else{
				$hyperpay_enviroment = 'Live';
			}
			
			$hyperpay_description = DB::table('payment_description')->where([['payment_name','hyperpay'],['language_id',$request->language_id]])->get();
			$hyperpay = array(
				'environment' => $hyperpay_enviroment, 
				'name' => $hyperpay_description[0]->name, 
				'method' => 'hyperpay',
				'public_key' => '',
				'auth_token' => '',
				'client_id' => '',
				'client_secret' => '',
				'active' => $payments_setting[0]->hyperpay_active,
				'payment_currency' => $payments_setting[0]->payment_currency
			);
			
						
			$result[0] = $braintree_card;
			$result[1] = $braintree_paypal;
			$result[2] = $stripe;
			$result[3] = $cod;
			$result[4] = $paypal;
			$result[5] = $instamojo;
			$result[6] = $hyperpay;
			
			$responseData = array('success'=>'1', 'data'=>$result, 'message'=>"Payment methods are returned.");
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	
	//get shipping / tax Rate
	public function getrate(Request $request){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			
			//tax rate
			$tax_zone_id   			=   $request->tax_zone_id;
			
			$index = '0';
			$total_tax = '0';
			$is_number = true;
			foreach($request->products as $products_data){
				$final_price = $request->products[$index]['final_price'];
				$products = DB::table('products')
					->LeftJoin('tax_rates', 'tax_rates.tax_class_id','=','products.products_tax_class_id')
					->where('tax_rates.tax_zone_id', $tax_zone_id)
					->where('products_id', $products_data['products_id'])->get();
				if(count($products)>0){
					$tax_value = $products[0]->tax_rate/100*$final_price;
					$total_tax = $total_tax+$tax_value;
					$index++;	
				}
			}
			
			if($total_tax>0){
				$data['tax'] = $total_tax;		
			}else{
				$data['tax'] = '0';
			}
			
			
			$countries = DB::table('countries')->where('countries_id','=',$request->country_id)->get();
			
			//website path
			$websiteURL =  "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$replaceURL = str_replace('getRate','', $websiteURL);
			$requiredURL = $replaceURL.'app/ups/ups.php';
			
			
			//default shipping method
			$shippings = DB::table('shipping_methods')->get();
			
			$result = array();
			
			foreach($shippings as $shipping_methods){
				//ups shipping rate
				if($shipping_methods->methods_type_link == 'upsShipping' and $shipping_methods->status == '1'){
					$result2= array();
					$is_transaction = '0';
					
					$ups_shipping = DB::table('ups_shipping')->where('ups_id', '=', '1')->get();
					
					//shipp from and all credentials
					$accessKey  = $ups_shipping[0]->access_key; 	
					$userId 	= $ups_shipping[0]->user_name;			
					$password 	= $ups_shipping[0]->password;
					
					//ship from address
					$fromAddress  = $ups_shipping[0]->address_line_1;
					$fromPostalCode  = $ups_shipping[0]->post_code;
					$fromCity  = $ups_shipping[0]->city;
					$fromState  = $ups_shipping[0]->state;
					$fromCountry  = $ups_shipping[0]->country; 
					
					//ship to address
					$toPostalCode = $request->postcode;
					$toCity = $request->city;	
					$toState = $request->state;	
					$toCountry = $countries[0]->countries_iso_code_2;	
					$toAddress = $request->street_address;	
					
					//product detail
					$products_weight = $request->products_weight;
					$products_weight_unit = $request->products_weight_unit;
					
					//change G or KG to LBS
					if($products_weight_unit=='g'){
						$productsWeight = $products_weight/453.59237;
					}else if($products_weight_unit=='kg'){
						$productsWeight = $products_weight/0.45359237;
					}
							
					//production or test mode
					if($ups_shipping[0]->shippingEnvironment == 1){ 			#production mode
						$useIntegration = true;				
					}else{
						$useIntegration = false;								#test mode
					}
					
					$serviceData = explode(',',$ups_shipping[0]->serviceType);
					
					
					$index = 0;
					$description = DB::table('shipping_description')->where([
										['language_id', '=', $request->language_id],
										['table_name', '=',  'ups_shipping'],
									])->get();
					
					$sub_labels = json_decode($description[0]->sub_labels);	
					
					foreach($serviceData as $value){
						if($value == "US_01")
						{
							$name = $sub_labels->nextDayAir;
							$serviceTtype = "1DA";
						}
						else if ($value == "US_02")
						{
							$name = $sub_labels->secondDayAir;
							$serviceTtype = "2DA";
						}
							else if ($value == "US_03")
						{
							$name = $sub_labels->ground;
							$serviceTtype = "GND";
						}
						else if ($value == "US_12")
						{
							$name = $sub_labels->threeDaySelect;
							$serviceTtype = "3DS";
						}
						else if ($value == "US_13")
						{
							$name = $sub_labels->nextDayAirSaver;
							$serviceTtype = "1DP";
						}
						else if ($value == "US_14")
						{
							$name = $sub_labels->nextDayAirEarlyAM;
							$serviceTtype = "1DM";
						}
						else if ($value == "US_59")
						{
							$name = $sub_labels->secondndDayAirAM;
							$serviceTtype = "2DM";
						}
						else if($value == "IN_07")
						{
							$name = Lang::get("labels.Worldwide Express");
							$serviceTtype = "UPSWWE";
						}
						else if ($value == "IN_08")
						{
							$name = Lang::get("labels.Worldwide Expedited");
							$serviceTtype = "UPSWWX";
						}
						else if ($value == "IN_11")
						{
							$name = Lang::get("labels.Standard");
							$serviceTtype = "UPSSTD";
						}
						else if ($value == "IN_54")
						{
							$name = Lang::get("labels.Worldwide Express Plus");
							$serviceTtype = "UPSWWEXPP";
						}
						
					$some_data = array(
						'access_key' => $accessKey,  						# UPS License Number
						'user_name' => $userId,								# UPS Username
						'password' => $password, 							# UPS Password
						'pickUpType' => '03',								# Drop Off Location
						'shipToPostalCode' => $toPostalCode, 				# Destination  Postal Code
						'shipToCountryCode' => $toCountry,					# Destination  Country
						'shipFromPostalCode' => $fromPostalCode, 			# Origin Postal Code
						'shipFromCountryCode' => $fromCountry,				# Origin Country
						'residentialIndicator' => 'IN', 					# Residence Shipping and for commercial shipping "COM"
						'cServiceCodes' => $serviceTtype, 					# Sipping rate for UPS Ground 
						'packagingType' => '02',
						'packageWeight' => $productsWeight
					  );  
					 
					  $curl = curl_init();
					  // You can also set the URL you want to communicate with by doing this:
					  // $curl = curl_init('http://localhost/echoservice');
					   
					  // We POST the data
					  curl_setopt($curl, CURLOPT_POST, 1);
					  // Set the url path we want to call
					  curl_setopt($curl, CURLOPT_URL, $requiredURL);  
					  // Make it so the data coming back is put into a string
					  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					  // Insert the data
					  curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
					   
					  // You can also bunch the above commands into an array if you choose using: curl_setopt_array
					   
					  // Send the request
					  $rate = curl_exec($curl);
					  // Free up the resources $curl is using
					  curl_close($curl);
					 $ups_description = DB::table('shipping_description')->where('table_name','ups_shipping')->where('language_id',$request->language_id)->get();
					 if(!empty($ups_description[0]->name)){
						$methodName = $ups_description[0]->name;
					 }else{
						$methodName = 'UPS Shipping';
					 }
					 if (is_numeric($rate)){
						$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$methodName);
						$result2[$index] = array('name'=>$name,'rate'=>$rate,'currencyCode'=>'USD','shipping_method'=>'upsShipping');
						$index++;
					 }
					 else{
						$success = array('success'=>'0','message'=>"Selected regions are not supported for UPS shipping", 'name'=>$ups_description[0]->name);
					 }
					 
					  $success['services'] = $result2;
					  $result['upsShipping'] = $success;
					  
					}
					
					
				}else if($shipping_methods->methods_type_link == 'flateRate' and $shipping_methods->status == '1'){
					$description = DB::table('shipping_description')->where('table_name','flate_rate')->where('language_id',$request->language_id)->get();
					
					if(!empty($description[0]->name)){
						$methodName = $description[0]->name;
					}else{
						$methodName = 'Flate Rate';
					}
					 
					$ups_shipping = DB::table('flate_rate')->where('id', '=', '1')->get();
					$data2 =  array('name'=>$methodName,'rate'=>$ups_shipping[0]->flate_rate,'currencyCode'=>$ups_shipping[0]->currency,'shipping_method'=>'flateRate');
					if(count($ups_shipping)>0){
						$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$methodName);
						$success['services'][0] = $data2;
						$result['flateRate'] = $success;
					}
					
					
				}else if($shipping_methods->methods_type_link == 'localPickup' and $shipping_methods->status == '1') {
					$description = DB::table('shipping_description')->where('table_name','local_pickup')->where('language_id',$request->language_id)->get();
					
					if(!empty($description[0]->name)){
						$methodName = $description[0]->name;
					}else{
						$methodName = 'Local Pickup';
					}
					
					$data2 =  array('name'=>$methodName, 'rate'=>'0', 'currencyCode'=>'USD', 'shipping_method'=>'localPickup');
					$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$methodName);
					$success['services'][0] = $data2;
					$result['localPickup'] = $success;
						
				}else if($shipping_methods->methods_type_link == 'freeShipping'  and $shipping_methods->status == '1'){
					$description = DB::table('shipping_description')->where('table_name','free_shipping')->where('language_id',$request->language_id)->get();
					
					if(!empty($description[0]->name)){
						$methodName = $description[0]->name;
					}else{
						$methodName = 'Free Shipping';
					}
					
					$data2 =  array('name'=>$methodName,'rate'=>'0','currencyCode'=>'USD','shipping_method'=>'freeShipping');
					$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$methodName);
					$success['services'][0] = $data2;
					$result['freeShipping'] = $success;
				
				}else if($shipping_methods->methods_type_link == 'shippingByWeight'  and $shipping_methods->status == '1'){
					$description = DB::table('shipping_description')->where('table_name','shipping_by_weight')->where('language_id',$request->language_id)->get();				
					if(!empty($description[0]->name)){
						$methodName = $description[0]->name;
					}else{
						$methodName = 'Shipping Price';
					}
									
					$weight = $request->products_weight;
					
					//check price by weight
					$priceByWeight = DB::table('products_shipping_rates')->where('weight_from','<=',$weight)->where('weight_to','>=',$weight)->get();				
					
					if(!empty($priceByWeight) and count($priceByWeight)>0 ){
						$price = $priceByWeight[0]->weight_price;
					}else{
						$price = 0;					
					}
					
					$data2 =  array('name'=>$methodName,'rate'=>$price,'currencyCode'=>'USD','shipping_method'=>'Shipping Price');
					$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$methodName);
					$success['services'][0] = $data2;
					$result['freeShipping'] = $success;
				}
			}
			$data['shippingMethods'] = $result;		
			
			$responseData = array('success'=>'1', 'data'=>$data, 'message'=>"Data is returned.");
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}		
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	 
	//get coupons
	public function getcoupon(Request $request){
		
		$result = array();
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			
			$coupons = DB::table('coupons')->where('code', '=', $request->code)->get();
					
			if(count($coupons)>0){
				
				if(!empty($coupons[0]->product_ids)){
					$product_ids = explode(',', $coupons[0]->product_ids);	
					$coupons[0]->product_ids =  $product_ids;
				}
				else{
					$coupons[0]->product_ids = array();
				}
				
				if(!empty($coupons[0]->exclude_product_ids)){
					$exclude_product_ids = explode(',', $coupons[0]->exclude_product_ids);	
					$coupons[0]->exclude_product_ids =  $exclude_product_ids;
				}else{
					$coupons[0]->exclude_product_ids =  array();
				}
				
				if(!empty($coupons[0]->product_categories)){
					$product_categories = explode(',', $coupons[0]->product_categories);	
					$coupons[0]->product_categories =  $product_categories;
				}else{
					$coupons[0]->product_categories =  array();
				}
				
				if(!empty($coupons[0]->excluded_product_categories)){
					$excluded_product_categories = explode(',', $coupons[0]->excluded_product_categories);	
					$coupons[0]->excluded_product_categories =  $excluded_product_categories;
				}else{
					$coupons[0]->excluded_product_categories = array();	
				}
				
				if(!empty($coupons[0]->email_restrictions)){
					$email_restrictions = explode(',', $coupons[0]->email_restrictions);	
					$coupons[0]->email_restrictions =  $email_restrictions;
				}else{
					$coupons[0]->email_restrictions =  array();
				}
				
				if(!empty($coupons[0]->used_by)){
					$used_by = explode(',', $coupons[0]->used_by);	
					$coupons[0]->used_by =  $used_by;
				}else{
					$coupons[0]->used_by =  array();
				}
				
				$responseData = array('success'=>'1', 'data'=>$coupons, 'message'=>"Coupon info is returned.");
			}else{
				$responseData = array('success'=>'0', 'data'=>$coupons, 'message'=>"Coupon doesn't exist.");
			}
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}
		
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	
	//addtoorder
	public function addtoorder(Request $request){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		$ipAddress = $this->get_client_ip_env();
		
		if($authenticate==1){
			
			$date_added								=	date('Y-m-d h:i:s');
			
			$customers_id            				=   $request->customers_id;
			$customers_telephone            		=   $request->customers_telephone;
			$email            						=   $request->email;					
			$delivery_firstname  	          		=   $request->delivery_firstname;
			$delivery_lastname            			=   $request->delivery_lastname;
			$delivery_street_address            	=   $request->delivery_street_address;
			$delivery_suburb            			=   $request->delivery_suburb;
			$delivery_city            				=   $request->delivery_city;
			$delivery_postcode            			=   $request->delivery_postcode;
			
			
			$delivery = DB::table('zones')->where('zone_name', '=', $request->delivery_zone)->get();
			
			if(count($delivery)){
				$delivery_state            				=   $delivery[0]->zone_code;
			}else{
				$delivery_state            				=   'other';
			}
			   
			$delivery_country            			=   $request->delivery_country;			
			$billing_firstname            			=   $request->billing_firstname;
			$billing_lastname            			=   $request->billing_lastname;
			$billing_street_address            		=   $request->billing_street_address;
			$billing_suburb	            			=   $request->billing_suburb;
			$billing_city            				=   $request->billing_city;
			$billing_postcode            			=   $request->billing_postcode;
			
			$billing = DB::table('zones')->where('zone_name', '=', $request->billing_zone)->get();
			
			if(count($billing)){
				$billing_state            				=   $billing[0]->zone_code;
			}else{
				$billing_state            				=   'other';
			}
			
			$billing_country            			=   $request->billing_country;			
			$payment_method            				=   $request->payment_method;
			$order_information 						=	array();
			
			$cc_type            				=   $request->cc_type;
			$cc_owner            				=   $request->cc_owner;
			$cc_number            				=   $request->cc_number;
			$cc_expires            				=   $request->cc_expires;
			$last_modified            			=   date('Y-m-d H:i:s');
			$date_purchased            			=   date('Y-m-d H:i:s');
			$order_price						=   $request->totalPrice;
			$shipping_cost            			=   $request->shipping_cost;
			$shipping_method            		=   $request->shipping_method;
			$orders_status            			=   '1';
			$orders_date_finished            	=   $request->orders_date_finished;
			$comments            				=   $request->comments;
			
			//additional fields
			$delivery_phone						=	$request->delivery_phone;
			$billing_phone						=	$request->billing_phone;
			
			$settings = DB::table('settings')->get();		
			$currency            				=   $settings[19]->value;
			$currency_value            			=   $request->currency_value;
			//$products_tax						=	$request->products_tax;
			
			//tax info
			$total_tax							=	$request->total_tax;
			
			$products_tax 						= 	1;
			//coupon info
			$is_coupon_applied            		=   $request->is_coupon_applied;
			
			if($is_coupon_applied==1){
				
				$code = array();	
				$coupon_amount = 0;	
				$exclude_product_ids = array();
				$product_categories = array();
				$excluded_product_categories = array();
				$exclude_product_ids = array();
				
				$coupon_amount    =		$request->coupon_amount;
				
				foreach($request->coupons as $coupons_data){
					
					//update coupans		
					$coupon_id = DB::statement("UPDATE `coupons` SET `used_by`= CONCAT(used_by,',$customers_id') WHERE `code` = '".$coupons_data['code']."'");
								
				}
				$code = json_encode($request->coupons);
				
			}else{
				$code            					=   '';
				$coupon_amount            			=   '';
			}	
			
			//payment methods 
			$payments_setting = DB::table('payments_setting')->get();
			
			if($payment_method == 'braintree_card' or $payment_method == 'braintree_paypal'){
				if($payment_method == 'braintree_card'){
					$fieldName = 'sub_name_1';
				}else{
					$fieldName = 'sub_name_2';
				}
				
				$paymentName = DB::table('payment_description')->where([['language_id',$request->language_id],['payment_name',$payments_setting[0]->braintree_name]])->get();
				$paymentMethodName = $paymentName[0]->$fieldName;
				
				//braintree transaction with nonce
				$is_transaction  = '1'; 			# For payment through braintree
				$nonce    		 =   $request->nonce;
				
				if($payments_setting[0]->braintree_enviroment == '0'){
					$braintree_environment = 'sandbox';	
				}else{
					$braintree_environment = 'production';	
				}
				
				$braintree_merchant_id = $payments_setting[0]->braintree_merchant_id;
				$braintree_public_key  = $payments_setting[0]->braintree_public_key;
				$braintree_private_key = $payments_setting[0]->braintree_private_key;
				
				//brain tree credential
				require_once app_path('braintree/Braintree.php');
				
				if ($result->success) 
				{
					
				if($result->transaction->id)
					{
						$order_information = array(
							'braintree_id'=>$result->transaction->id,
							'status'=>$result->transaction->status,
							'type'=>$result->transaction->type,
							'currencyIsoCode'=>$result->transaction->currencyIsoCode,
							'amount'=>$result->transaction->amount,
							'merchantAccountId'=>$result->transaction->merchantAccountId,
							'subMerchantAccountId'=>$result->transaction->subMerchantAccountId,
							'masterMerchantAccountId'=>$result->transaction->masterMerchantAccountId,
							//'orderId'=>$result->transaction->orderId,
							'createdAt'=>time(),
	//						'updatedAt'=>$result->transaction->updatedAt->date,
							'token'=>$result->transaction->creditCard['token'],
							'bin'=>$result->transaction->creditCard['bin'],
							'last4'=>$result->transaction->creditCard['last4'],
							'cardType'=>$result->transaction->creditCard['cardType'],
							'expirationMonth'=>$result->transaction->creditCard['expirationMonth'],
							'expirationYear'=>$result->transaction->creditCard['expirationYear'],
							'customerLocation'=>$result->transaction->creditCard['customerLocation'],
							'cardholderName'=>$result->transaction->creditCard['cardholderName']
						);
						
						$payment_status = "success";
						
					}
				} 
				else
					{
						$payment_status = "failed";
					}
					
			}else if($payment_method == 'stripe'){				#### stipe payment
				
				$paymentName = DB::table('payment_description')->where([['language_id',$request->language_id],['payment_name',$payments_setting[0]->stripe_name]])->get();
				$paymentMethodName = $paymentName[0]->name;
				
				//require file
				require_once app_path('stripe/config.php');
				
				//get token from app
				$token  = $request->nonce;
				
				$customer = \Stripe\Customer::create(array(
				  'email' => $email,
				  'source'  => $token
				));
				
				$charge = \Stripe\Charge::create(array(
				  'customer' => $customer->id,
				  'amount'   => 100*$order_price,
				  'currency' => 'usd'
				));
				
				 if($charge->paid == true){
					 $order_information = array(
							'paid'=>'true',
							'transaction_id'=>$charge->id,
							'type'=>$charge->outcome->type,
							'balance_transaction'=>$charge->balance_transaction,
							'status'=>$charge->status,
							'currency'=>$charge->currency,
							'amount'=>$charge->amount,
							'created'=>date('d M,Y', $charge->created),
							'dispute'=>$charge->dispute,
							'customer'=>$charge->customer,
							'address_zip'=>$charge->source->address_zip,
							'seller_message'=>$charge->outcome->seller_message,
							'network_status'=>$charge->outcome->network_status,
							'expirationMonth'=>$charge->outcome->type
						);
						
						$payment_status = "success";
						
				 }else{
						$payment_status = "failed";	 
				 }
				
			}else if($payment_method == 'cod'){
				
				$paymentName = DB::table('payment_description')->where([['language_id',$request->language_id],['payment_name',$payments_setting[0]->cod_name]])->get();
				$paymentMethodName = $paymentName[0]->name;
				$payment_method = 'Cash on Delivery';
				$payment_status='success';
				
			} else if($payment_method == 'paypal'){
				
				$paymentName = DB::table('payment_description')->where([['language_id',$request->language_id],['payment_name',$payments_setting[0]->paypal_name]])->get();
				$paymentMethodName = $paymentName[0]->name;
				$payment_method = 'PayPal Express Checkout';
				$payment_status='success';
				$order_information = $request->nonce;
					
			} else if($payment_method == 'cybersource'){
				
				$paymentMethodName = 'Cybersource';
				$order_information = '';
				$payment_status = 'success';
										
			}else if($payment_method == 'instamojo'){
				
				$paymentName = DB::table('payment_description')->where([['language_id',$request->language_id],['payment_name',$payments_setting[0]->instamojo_name]])->get();
				$paymentMethodName = $paymentName[0]->name;
				$payment_method = 'Instamojo';
				$payment_status='success';				
				$order_information = array('payment_id'=>$request->nonce, 'transaction_id'=>$request->transaction_id);	
								
			}else if($payment_method == 'hyperpay'){
				
				$paymentName = DB::table('payment_description')->where([['language_id',$request->language_id],['payment_name',$payments_setting[0]->hyperpay_name]])->get();
						
				$paymentMethodName = $paymentName[0]->name;
				$payment_method = 'Hyperpay';
				$payment_status='success';
			
			}		 
				
			
			//check if order is verified
			if($payment_status=='success'){
				if($payment_method == 'cybersource' or $payment_method == 'hyperpay'){
				$cyb_orders = DB::table('orders')->where('transaction_id','=',$request->transaction_id)->get();
				//dd($cyb_orders);
				$orders_id = $cyb_orders[0]->orders_id;
				
				//update database
				DB::table('orders')->where('transaction_id','=',$request->transaction_id)->update(
					[	 'customers_id' => $customers_id,
						 'customers_name'  => $delivery_firstname.' '.$delivery_lastname,
						 'customers_street_address' => $delivery_street_address,
						 'customers_suburb'  =>  $delivery_suburb,
						 'customers_city' => $delivery_city,
						 'customers_postcode'  => $delivery_postcode,
						 'customers_state' => $delivery_state,
						 'customers_country'  =>  $delivery_country,
						 'customers_telephone' => $customers_telephone,
						 'email'  => $email,
						 
						 'delivery_name'  =>  $delivery_firstname.' '.$delivery_lastname,
						 'delivery_street_address' => $delivery_street_address,
						 'delivery_suburb'  => $delivery_suburb,
						 'delivery_city' => $delivery_city,
						 'delivery_postcode'  =>  $delivery_postcode,
						 'delivery_state' => $delivery_state,
						 'delivery_country'  => $delivery_country,						 
						 'billing_name'  => $billing_firstname.' '.$billing_lastname,
						 'billing_street_address' => $billing_street_address,
						 'billing_suburb'  =>  $billing_suburb,
						 'billing_city' => $billing_city,
						 'billing_postcode'  => $billing_postcode,
						 'billing_state' => $billing_state,
						 'billing_country'  =>  $billing_country,
						 
						 'payment_method'  =>  $paymentMethodName,
						 'cc_type' => $cc_type,
						 'cc_owner'  => $cc_owner,
						 'cc_number' =>$cc_number,
						 'cc_expires'  =>  $cc_expires,
						 'last_modified' => $last_modified,
						 'date_purchased'  => $date_purchased,
						 'order_price'  => $order_price,
						 'shipping_cost' =>$shipping_cost,
						 'shipping_method'  =>  $shipping_method,
						 'currency'  =>  $currency,
						 'currency_value' => $last_modified,
						 'coupon_code'		 =>		$code,
						 'coupon_amount' 	 =>		$coupon_amount,
						 'total_tax'		 =>		$total_tax,
						 'ordered_source' 	 => 	'2',
						 'delivery_phone'	 =>		$delivery_phone,
						 'billing_phone'	 =>		$billing_phone
					]);
					
				}else{
					
				//insert order
				$orders_id = DB::table('orders')->insertGetId(
					[	 'customers_id' => $customers_id,
						 'customers_name'  => $delivery_firstname.' '.$delivery_lastname,
						 'customers_street_address' => $delivery_street_address,
						 'customers_suburb'  =>  $delivery_suburb,
						 'customers_city' => $delivery_city,
						 'customers_postcode'  => $delivery_postcode,
						 'customers_state' => $delivery_state,
						 'customers_country'  =>  $delivery_country,
						 'customers_telephone' => $customers_telephone,
						 'email'  => $email,
						 
						 'delivery_name'  =>  $delivery_firstname.' '.$delivery_lastname,
						 'delivery_street_address' => $delivery_street_address,
						 'delivery_suburb'  => $delivery_suburb,
						 'delivery_city' => $delivery_city,
						 'delivery_postcode'  =>  $delivery_postcode,
						 'delivery_state' => $delivery_state,
						 'delivery_country'  => $delivery_country,
						 
						 'billing_name'  => $billing_firstname.' '.$billing_lastname,
						 'billing_street_address' => $billing_street_address,
						 'billing_suburb'  =>  $billing_suburb,
						 'billing_city' => $billing_city,
						 'billing_postcode'  => $billing_postcode,
						 'billing_state' => $billing_state,
						 'billing_country'  =>  $billing_country,
						 
						 'payment_method'  =>  $paymentMethodName,
						 'cc_type' => $cc_type,
						 'cc_owner'  => $cc_owner,
						 'cc_number' =>$cc_number,
						 'cc_expires'  =>  $cc_expires,
						 'last_modified' => $last_modified,
						 'date_purchased'  => $date_purchased,
						 'order_price'  => $order_price,
						 'shipping_cost' =>$shipping_cost,
						 'shipping_method'  =>  $shipping_method,
						 'currency'  =>  $currency,
						 'currency_value' => $last_modified,
						 'order_information' => json_encode($order_information),
						 'coupon_code'		 =>		$code,
						 'coupon_amount' 	 =>		$coupon_amount,
						 'total_tax'		 =>		$total_tax,
						 'ordered_source' 	 => 	'2',
						 'delivery_phone'	 =>		$delivery_phone,
						 'billing_phone'	 =>		$billing_phone,
					]);
				
				}
				 //orders status history
				 $orders_history_id = DB::table('orders_status_history')->insertGetId(
					[	 'orders_id'  => $orders_id,
						 'orders_status_id' => $orders_status,
						 'date_added'  => $date_added,
						 'customer_notified' =>'1',
						 'comments'  =>  $comments
					]);
					
				 foreach($request->products as $products){	
					
					$orders_products_id = DB::table('orders_products')->insertGetId(
					[		 		
						 'orders_id' 		 => 	$orders_id,
						 'products_id' 	 	 =>		$products['products_id'],
						 'products_name'	 => 	$products['products_name'],
						 'products_price'	 =>  	$products['price'],
						 'final_price' 		 =>  	$products['final_price']*$products['customers_basket_quantity'],
						 'products_tax' 	 =>  	$products_tax,
						 'products_quantity' =>  	$products['customers_basket_quantity'],
					]);
					 
					
					if(!empty($products['attributes'])){
						foreach($products['attributes'] as $attribute){
							DB::table('orders_products_attributes')->insert(
							[
								 'orders_id' => $orders_id,
								 'products_id'  => $products['products_id'],
								 'orders_products_id'  => $orders_products_id,
								 'products_options' =>$attribute['products_options'],
								 'products_options_values'  =>  $attribute['products_options_values'],
								 'options_values_price'  =>  $attribute['options_values_price'],
								 'price_prefix'  =>  $attribute['price_prefix']
							]);
							
							
						}
					}
								
				 }
				
				$responseData = array('success'=>'1', 'data'=>array(), 'message'=>"Order has been placed successfully.");
				
				//send order email to user			
				$order = DB::table('orders')
					->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
					->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
					->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
				
			//foreach
			foreach($order as $data){
				$orders_id	 = $data->orders_id;
				
				$orders_products = DB::table('orders_products')
					->join('products', 'products.products_id','=', 'orders_products.products_id')
					->select('orders_products.*', 'products.products_image as image')
					->where('orders_products.orders_id', '=', $orders_id)->get();
					$i = 0;
					$total_price  = 0;
					$product = array();
					$subtotal = 0;
					foreach($orders_products as $orders_products_data){
						$product_attribute = DB::table('orders_products_attributes')
							->where([
								['orders_products_id', '=', $orders_products_data->orders_products_id],
								['orders_id', '=', $orders_products_data->orders_id],
							])
							->get();
							
						$orders_products_data->attribute = $product_attribute;
						$product[$i] = $orders_products_data;
						//$total_tax	 = $total_tax+$orders_products_data->products_tax;
						$total_price = $total_price+$orders_products[$i]->final_price;
						
						$subtotal += $orders_products[$i]->final_price;
						
						$i++;
					}
					
				$data->data = $product;
				$orders_data[] = $data;
			}
			
				$orders_status_history = DB::table('orders_status_history')
					->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
					->orderBy('orders_status_history.date_added', 'desc')
					->where('orders_id', '=', $orders_id)->get();
						
				$orders_status = DB::table('orders_status')->get();
						
				$ordersData['orders_data']		 	 	=	$orders_data;
				$ordersData['total_price']  			=	$total_price;
				$ordersData['orders_status']			=	$orders_status;
				$ordersData['orders_status_history']    =	$orders_status_history;
				$ordersData['subtotal']    				=	$subtotal;
				
				
				//notification/email
				$myVar = new AlertController();
				$alertSetting = $myVar->orderAlert($ordersData);
				
			}else if($payment_status == "failed"){
				if(!empty($error_cybersource)){
					$return_error = $error_cybersource;
				}else{
					$return_error = 'Error while placing order.';
				}
				$responseData = array('success'=>'0', 'data'=>array(), 'message'=>$error_cybersource);	
			}
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}		
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	
	
	//getorders
	public function getorders(Request $request){
		$consumer_data 		 				  =  array();
		$customers_id						  =  $request->customers_id;
		$language_id						  =  $request->language_id;
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			
			$order = DB::table('orders')->orderBy('customers_id', 'desc')
					->where([
						['customers_id', '=', $customers_id],
					])->get();
			if(count($order) > 0){		
				//foreach
				$index = '0';
				foreach($order as $data){
					
					if(!empty($data->coupon_code)){
						$coupon_code =  $data->coupon_code;
						$order[$index]->coupons = json_decode($coupon_code);
					}
					else{
						$coupon_code =  array();
						$order[$index]->coupons = $coupon_code;
					}
					
					unset($data->coupon_code);
					
					$orders_id	 = $data->orders_id;
					
					$orders_status_history = DB::table('orders_status_history')
							->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
							->select('orders_status.orders_status_name', 'orders_status.orders_status_id', 'orders_status_history.comments')
							->where('orders_id', '=', $orders_id)->orderby('orders_status_history.orders_status_history_id', 'ASC')->get();
							
					$order[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
					$order[$index]->orders_status = $orders_status_history[0]->orders_status_name;
					$order[$index]->customer_comments = $orders_status_history[0]->comments;
					
					$total_comments = count($orders_status_history);
					$i = 1;
					
					foreach($orders_status_history as $orders_status_history_data){
						
						if($total_comments == $i && $i != 1){
							$order[$index]->orders_status_id = $orders_status_history_data->orders_status_id;
							$order[$index]->orders_status = $orders_status_history_data->orders_status_name;
							$order[$index]->admin_comments = $orders_status_history_data->comments;
						}else{
							$order[$index]->admin_comments = '';
						}
						
						$i++;
					}
									
					$orders_products = DB::table('orders_products')
					->join('products', 'products.products_id','=', 'orders_products.products_id')
					->select('orders_products.*', 'products.products_image as image')
					->where('orders_products.orders_id', '=', $orders_id)->get();
					$k = 0;
					$product = array();
					foreach($orders_products as $orders_products_data){
						//categories
						$categories = DB::table('products_to_categories')
										->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
										->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
										->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
										->where('products_id','=', $orders_products_data->products_id)
										->where('categories_description.language_id','=',$language_id)->get();		
						
						$orders_products_data->categories =  $categories;
						
						$product_attribute = DB::table('orders_products_attributes')
							->where([
								['orders_products_id', '=', $orders_products_data->orders_products_id],
								['orders_id', '=', $orders_products_data->orders_id],
							])
							->get();
							
						$orders_products_data->attributes = $product_attribute;
						$product[$k] = $orders_products_data;
						$k++;
					}
					$data->data = $product;
					$orders_data[] = $data;
				$index++;
				}
					$responseData = array('success'=>'1', 'data'=>$orders_data, 'message'=>"Returned all orders.");
			}else{
					$orders_data = array();
					$responseData = array('success'=>'0', 'data'=>$orders_data, 'message'=>"Order is not placed yet.");
			}
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}		
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
	
	public function get_client_ip_env(){
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
	 
		return $ipaddress;
	}
	
	//updatestatus
	public function updatestatus(Request $request){
		
		$consumer_data 		 				  =  array();
		$orders_id							  =  $request->orders_id;
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			
			$date_added			=    date('Y-m-d h:i:s');
			$comments			=	 '';
			$orders_history_id = DB::table('orders_status_history')->insertGetId(
				[	 'orders_id'  => $orders_id,
					 'orders_status_id' => '3',
					 'date_added'  => $date_added,
					 'customer_notified' =>'1',
					 'comments'  =>  $comments
				]);
				
			$responseData = array('success'=>'1', 'data'=>array(), 'message'=>"Status has been changed succefully.");
			
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}		
		$orderResponse = json_encode($responseData);
		print $orderResponse;
	}
			
}