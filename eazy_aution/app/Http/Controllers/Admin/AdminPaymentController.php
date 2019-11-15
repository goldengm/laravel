<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 2.9.2
*/
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

//validator is builtin class in laravel
use Validator;

use DB;
use App\Administrator;

use App;
use Lang;

//for authenitcate login data
use Auth;


//for requesting a value 
use Illuminate\Http\Request;
//use Illuminate\Routing\Controller;


class AdminPaymentController extends Controller
{
	//braintreeSetting
	public function paymentsetting(Request $request){
		$title = array('pageTitle' => Lang::get("labels.PaymentSetting"));		
		
		$shipping_methods = DB::table('payments_setting')->where('payments_id', '=', '1')->get();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
				
		$braintree_description = array();
		$stripe_description = array();	
		$paypal_description = array();	
		$cod_description = array();		
		$cybersource_description = array();
		$instamojo_description = array();	
		$hyperpay_description = array();
		foreach($result['languages'] as $languages_data){
			
			$braintree = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->braintree_name],
				])->get();
				
			if(count($braintree)>0){								
				$braintree_description[$languages_data->languages_id]['name'] = $braintree[0]->name;
				$braintree_description[$languages_data->languages_id]['sub_name_1'] = $braintree[0]->sub_name_1;
				$braintree_description[$languages_data->languages_id]['sub_name_2'] = $braintree[0]->sub_name_2;
				$braintree_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$braintree_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$braintree_description[$languages_data->languages_id]['name'] = '';
				$braintree_description[$languages_data->languages_id]['sub_name_1'] = '';
				$braintree_description[$languages_data->languages_id]['sub_name_2'] = '';
				$braintree_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$braintree_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
			
			$stripe = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->stripe_name],
				])->get();
				
			if(count($stripe)>0){								
				$stripe_description[$languages_data->languages_id]['name'] = $stripe[0]->name;
				$stripe_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$stripe_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$stripe_description[$languages_data->languages_id]['name'] = '';
				$stripe_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$stripe_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
			
			
			$paypal = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->paypal_name],
				])->get();
				
			if(count($paypal)>0){								
				$paypal_description[$languages_data->languages_id]['name'] = $paypal[0]->name;
				$paypal_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$paypal_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$paypal_description[$languages_data->languages_id]['name'] = '';
				$paypal_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$paypal_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
			
			
			$cod = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->cod_name],
				])->get();
				
			if(count($cod)>0){								
				$cod_description[$languages_data->languages_id]['name'] = $cod[0]->name;
				$cod_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$cod_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$cod_description[$languages_data->languages_id]['name'] = '';
				$cod_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$cod_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
			
			$cybersource = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->cybersource_name],
				])->get();
				
			if(count($cybersource)>0){								
				$cybersource_description[$languages_data->languages_id]['name'] = $cybersource[0]->name;
				$cybersource_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$cybersource_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$cybersource_description[$languages_data->languages_id]['name'] = '';
				$cybersource_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$cybersource_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
			
			
			$instamojo = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->instamojo_name],
				])->get();
				
			if(count($instamojo)>0){								
				$instamojo_description[$languages_data->languages_id]['name'] = $instamojo[0]->name;
				$instamojo_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$instamojo_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$instamojo_description[$languages_data->languages_id]['name'] = '';
				$instamojo_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$instamojo_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
						
			$hyperpay = DB::table('payment_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['payment_name', '=', $shipping_methods[0]->hyperpay_name],
				])->get();
				
			if(count($hyperpay)>0){								
				$hyperpay_description[$languages_data->languages_id]['name'] = $hyperpay[0]->name;
				$hyperpay_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$hyperpay_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$hyperpay_description[$languages_data->languages_id]['name'] = '';
				$hyperpay_description[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$hyperpay_description[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
			
		}
		
		$result['braintree_description'] 		 = $braintree_description;
		$result['stripe_description']    		 = $stripe_description;	
		$result['paypal_description']    		 = $paypal_description;	
		$result['cod_description'] 		 		 = $cod_description;
		$result['cybersource_description'] 		 = $cybersource_description;	
		$result['instamojo_description'] 		 = $instamojo_description;	
		$result['hyperpay_description'] 		 = $hyperpay_description;	
		
		$result['shipping_methods']	= $shipping_methods;
		return view("admin.paymentsetting", $title)->with('result', $result);
	}
	
	//updatePaymentSetting	
	public function updatePaymentSetting(Request $request){
		
		DB::table('payments_setting')->where('payments_id', '=', '1')->update([
				'braintree_enviroment'   	 =>   $request->braintree_enviroment,
				'braintree_merchant_id'	 	 =>   $request->braintree_merchant_id,
				'braintree_public_key'	 	 =>   $request->braintree_public_key,
				'braintree_private_key'	 	 =>   $request->braintree_private_key,
				'brantree_active'	 	 	 =>   $request->brantree_active,
				'stripe_enviroment'	 	 	 =>   $request->stripe_enviroment,
				'secret_key'	 	 	 	 =>   $request->secret_key,
				'publishable_key'	 	  	 =>   $request->publishable_key,
				'stripe_active'	 		  	 =>   $request->stripe_active,
				'cash_on_delivery'		 	 =>	  $request->cash_on_delivery,
				'paypal_status'	 		 	 =>   $request->paypal_status,
				'paypal_enviroment'		 	 =>	  $request->paypal_enviroment,
				'paypal_id'	 	 		 	 =>   $request->paypal_id,
				'payment_currency'		 	 =>	  $request->payment_currency,
				'cybersource_enviroment'	 =>	  $request->cybersource_enviroment,
				'cybersource_status'		 =>	  $request->cybersource_status,
								
				'instamojo_active'	 	 	=>   $request->instamojo_active,
				'instamojo_enviroment' 	 	=>   $request->instamojo_enviroment,
				'instamojo_api_key'	  	 	=>   $request->instamojo_api_key,
				'instamojo_auth_token' 	 	=>   $request->instamojo_auth_token,	
				'instamojo_client_id'	 	=>   $request->instamojo_client_id,
				'instamojo_client_secret' 	=>   $request->instamojo_client_secret,	
				
				
				'hyperpay_enviroment' 		=>   $request->hyperpay_enviroment,	
				'hyperpay_active' 			=>   $request->hyperpay_active,	
				'hyperpay_userid' 			=>   $request->hyperpay_userid,	
				'hyperpay_password' 		=>   $request->hyperpay_password,	
				'hyperpay_entityid' 		=>   $request->hyperpay_entityid,	
				]);
			
		
		$braintree_name = $request->braintree_name;
		$stripe_name = $request->stripe_name;
		$paypal_name = $request->paypal_name;
		$cod_name = $request->cod_name;
		$cybersource_name = $request->cybersource_name;
		$instamojo_name = $request->instamojo_name;
		$hyperpay_name = $request->hyperpay_name;
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		foreach($languages as $languages_data){
			$braintreeName = 'briantree_name_'.$languages_data->languages_id;
			$sub_name_1 = 'sub_name_1_'.$languages_data->languages_id;
			$sub_name_2 = 'sub_name_2_'.$languages_data->languages_id;
			$checkExist = DB::table('payment_description')->where('payment_name','=',$braintree_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$braintree_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$braintreeName,
					'sub_name_1'  	     	 =>   $request->$sub_name_1,
					'sub_name_2'  	     	 =>   $request->$sub_name_2,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$braintreeName,
					'sub_name_1'  	     	 =>   $request->$sub_name_1,
					'sub_name_2'  	     	 =>   $request->$sub_name_2,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $braintree_name,
					]);
			}
			
			
			$stripeName = 'stripe_name_'.$languages_data->languages_id;
			$checkExist = DB::table('payment_description')->where('payment_name','=',$stripe_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$stripe_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$stripeName,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$stripeName,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $stripe_name,
					]);
			}
			
			$paypalName = 'paypal_name_'.$languages_data->languages_id;			
			$checkExist = DB::table('payment_description')->where('payment_name','=',$paypal_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$paypal_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$paypalName,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$paypalName,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $paypal_name,
					]);
			}
			
			$cybersourceName = 'cybersource_name_'.$languages_data->languages_id;			
			$checkExist = DB::table('payment_description')->where('payment_name','=',$cybersource_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$cybersource_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$cybersourceName,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$cybersourceName,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $cybersource_name,
					]);
			}
			
			
			$codName = 'cod_name_'.$languages_data->languages_id;			
			$checkExist = DB::table('payment_description')->where('payment_name','=',$cod_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$cod_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$codName,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$codName,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $cod_name,
					]);
			}
			
			
			$instamojoName = 'instamojo_name_'.$languages_data->languages_id;			
			$checkExist = DB::table('payment_description')->where('payment_name','=',$instamojo_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$instamojo_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$instamojoName,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$instamojoName,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $instamojo_name,
					]);
			}
			
			
			$hyperpayName = 'hyperpay_name_'.$languages_data->languages_id;			
			$checkExist = DB::table('payment_description')->where('payment_name','=',$hyperpay_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('payment_description')->where('payment_name','=',$hyperpay_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$hyperpayName,
					]);
			}else{
				DB::table('payment_description')->insert([
					'name'  	     		 =>   $request->$hyperpayName,
					'language_id'			 =>   $languages_data->languages_id,
					'payment_name'			 =>   $hyperpay_name,
					]);
			}
			
		}
									
		$message = Lang::get("labels.InformationUpdatedMessage");
		return redirect()->back()->withErrors([$message]);
	}
	
}
