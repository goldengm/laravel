<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use Validator;
use App;
use Lang;
use DB;
use App\Administrator;

//for authenitcate login data
use Auth;


//for requesting a value 
use Illuminate\Http\Request;


class AdminShippingController extends Controller
{
	public function upsData(){
		$ups_description = DB::table('shipping_description')->where([
				['language_id', '=', '1'],
				['table_name', '=', 'ups_shipping']
			])->get();
						
		$ups_shipping = DB::table('ups_shipping')
						->where('ups_id', '=', '1')->get();
						
		$result['ups_shipping'] = $ups_shipping;
		$result['ups_description'] = $ups_description;
		return ($result);
		
	}
	
	public function flateRateData(){
		$flate_rate = DB::table('flate_rate')->get();
		$flatrate_description = DB::table('shipping_description')->where([
				['language_id', '=', '1'],
				['table_name', '=', 'flate_rate']
			])->get();
		
						
		$result['flate_rate'] = $flate_rate;
		$result['flatrate_description'] = $flatrate_description;
		return ($result);
		
	}
	
	//shippingMethods
	public function shippingmethods(Request $request){
		if(session('shipping_methods_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.ShippingMethods"));		
		
		if(!empty($request->id)){
			if($request->active=='no'){
				$status = '0';
			}elseif($request->active=='yes'){
				$status = '1';
			}
			DB::table('shipping_methods')->where('shipping_methods_id', '=', $request->id)->update([
				'status'		 =>	  $status
				]);	
		}
		
		$shipping_methods = DB::table('shipping_methods')
								->leftJoin('shipping_description','shipping_description.table_name','=','shipping_methods.table_name')
								->where('shipping_description.language_id','1')
								->paginate(10);
		
		$result['shipping_methods'] = $shipping_methods;
		
		//ups data
		$ups_shipping = $this->upsData();
		$result['ups_shipping'] = $ups_shipping;
		
		//flatrate
		$flate_rate = DB::table('flate_rate')->get();
		$result['flate_rate'] = $flate_rate;
		
		return view("admin.shippingmethods", $title)->with('result', $result);
		}
	}
	
	//upsShipping
	public function upsShipping(Request $request){
		if(session('shipping_methods_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.UPSShipping"));
		$pickupType = '01';
			
		//ups data
		$ups_shipping = $this->upsData();
		$result['ups_shipping'] = $ups_shipping;
		
		$countries = DB::table('countries')->get();
		$result['countries'] = $countries;
		
		$shipping_methods = DB::table('shipping_methods')->where('shipping_methods_id', '=', '1')->get();
		$result['shipping_methods'] = $shipping_methods;
		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
				
		$description_data = array();
		$description_labels = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('shipping_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['table_name', '=', $shipping_methods[0]->table_name],
				])->get();
				
			if(count($description)>0){	
				$sub_labels = json_decode($description[0]->sub_labels);							
				$description_data[$languages_data->languages_id]['name'] = $description[0]->name;
				
				$description_data[$languages_data->languages_id]['nextDayAir'] = $sub_labels->nextDayAir;
				$description_data[$languages_data->languages_id]['secondDayAir'] = $sub_labels->secondDayAir;
				$description_data[$languages_data->languages_id]['ground'] = $sub_labels->ground;
				$description_data[$languages_data->languages_id]['threeDaySelect'] = $sub_labels->threeDaySelect;
				$description_data[$languages_data->languages_id]['nextDayAirSaver'] = $sub_labels->nextDayAirSaver;
				$description_data[$languages_data->languages_id]['nextDayAirEarlyAM'] = $sub_labels->nextDayAirEarlyAM;
				$description_data[$languages_data->languages_id]['secondndDayAirAM'] = $sub_labels->secondndDayAirAM;
				
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['name'] = '';
				
				$description_data[$languages_data->languages_id]['nextDayAir'] = '';
				$description_data[$languages_data->languages_id]['secondDayAir'] = '';
				$description_data[$languages_data->languages_id]['ground'] = '';
				$description_data[$languages_data->languages_id]['threeDaySelect'] = '';
				$description_data[$languages_data->languages_id]['nextDayAirSaver'] = '';
				$description_data[$languages_data->languages_id]['nextDayAirEarlyAM'] = '';
				$description_data[$languages_data->languages_id]['secondndDayAirAM'] = '';
				
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		
		$result['description'] = $description_data;	
		
		return view("admin.upsShipping", $title)->with('result', $result);
		}
	}

	//upsShipping
	public function uspsShipping(Request $request){
		if(session('shipping_methods_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.USPSShipping"));
		$pickupType = '01';
			
		//ups data
		$usps_shipping = $this->upsData();
		$result['usps_shipping'] = $usps_shipping;
		
		$countries = DB::table('countries')->get();
		$result['countries'] = $countries;
		
		$shipping_methods = DB::table('shipping_methods')->where('shipping_methods_id', '=', '6')->get();
		$result['shipping_methods'] = $shipping_methods;
		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
				
		$description_data = array();
		$description_labels = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('shipping_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['table_name', '=', $shipping_methods[0]->table_name],
				])->get();
				
			
		}
		
		$result['description'] = $description_data;	
		
		return view("admin.uspsShipping", $title)->with('result', $result);
		}
	}
	
	
	//flateRate
	public function flaterate(Request $request){
		if(session('shipping_methods_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.FlateRate"));
		
		$shipping_methods = DB::table('flate_rate')->where('id', '=', '1')->get();
		$result['flate_rate'] = $shipping_methods;
		
		$shipping_methods = DB::table('shipping_methods')->where('shipping_methods_id', '=', '4')->get();
		$result['shipping_methods'] = $shipping_methods;		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
				
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('shipping_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['table_name', '=', $shipping_methods[0]->table_name],
				])->get();
				
			if(count($description)>0){								
				$description_data[$languages_data->languages_id]['name'] = $description[0]->name;
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['name'] = '';
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		
		$result['description'] = $description_data;			
		return view("admin.flateRate", $title)->with('result', $result);
		}
	}
	
	//updateFlateRate	
	public function updateflaterate(Request $request){
		if(session('shipping_methods_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('flate_rate')->where('id', '=', '1')->update([
				'flate_rate'  		 =>   $request->flate_rate,
				'currency'			 =>	  $request->currency
				]);
				
		DB::table('shipping_methods')->where('shipping_methods_id', '=', '4')->update([
				'status'  		 =>   $request->status,
				]);
				
		$table_name = $request->table_name;
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			$content = array();
			
			$checkExist = DB::table('shipping_description')->where('table_name','=',$table_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('shipping_description')->where('table_name','=',$table_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$name,
					]);
			}else{
				DB::table('shipping_description')->insert([
					'name'  	     		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'table_name'			 =>   $table_name,
					]);
			}
		}
										
		$message = Lang::get("labels.InformationUpdatedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	//addNewTaxRate	
	public function updateUpsshipping(Request $request){
		if(session('shipping_methods_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('ups_shipping')->where('ups_id', '=', '1')->update([
				'pickup_method'  		 =>   $request->pickup_method,
				'serviceType'			 =>   implode(',', $request->serviceType),
				'shippingEnvironment'	 =>   $request->shippingEnvironment,
				'user_name'	 			 =>   $request->user_name,
				'access_key'	 		 =>   $request->access_key,
				'password'	 			 =>   $request->password,
				'address_line_1'	 	 =>   $request->address_line_1,
				'country'	 			 =>   $request->country,
				'state'	 			 	 =>   $request->state,
				'post_code'	 			 =>   $request->post_code,
				'city'	 				 =>   $request->city,
				'title'	 				 =>   $request->title
				]);
				
		DB::table('shipping_methods')->where('shipping_methods_id', '=', '1')->update([
				'status'  		 =>   $request->status,
				]);
		
		$table_name = $request->table_name;
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			
			$nextDayAir 		= 'nextDayAir_'.$languages_data->languages_id;
			$secondDayAir 		= 'secondDayAir_'.$languages_data->languages_id;
			$ground 			= 'ground_'.$languages_data->languages_id;
			$threeDaySelect 	= 'threeDaySelect_'.$languages_data->languages_id;
			$nextDayAirSaver 	= 'nextDayAirSaver_'.$languages_data->languages_id;
			$nextDayAirEarlyAM 	= 'nextDayAirEarlyAM_'.$languages_data->languages_id;
			$secondndDayAirAM 	= 'secondndDayAirAM_'.$languages_data->languages_id;
			
			$sub_labels = array(
						'nextDayAir'=>$request->$nextDayAir,
						'secondDayAir'=>$request->$secondDayAir,
						'ground'=>$request->$ground,
						'threeDaySelect'=>$request->$threeDaySelect,
						'nextDayAirSaver'=>$request->$nextDayAirSaver,
						'nextDayAirEarlyAM'=>$request->$nextDayAirEarlyAM,
						'secondndDayAirAM'=>$request->$secondndDayAirAM,
						);
			
			
			$checkExist = DB::table('shipping_description')->where('table_name','=',$table_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('shipping_description')->where('table_name','=',$table_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		=>   $request->$name,
					'sub_labels'  	    	=>   json_encode($sub_labels),
					]);
			}else{
				DB::table('shipping_description')->insert([
					'name'  	     		 =>   $request->$name,
					'sub_labels'  	    	 =>   json_encode($sub_labels),
					'language_id'			 =>   $languages_data->languages_id,
					'table_name'			 =>   $table_name,
					]);
			}
		}
									
		$message = Lang::get("labels.InformationAddedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	//addNewTaxRate	
	public function defaultShippingMethod(Request $request){
		if(session('shipping_methods_update')==0){
			return Lang::get("labels.You do not have to access this route");
		}else{
			
		if(session('shipping_methods_update')==0){
			return Lang::get("labels.You do not have to access this route");
		}else{
		
			DB::table('shipping_methods')->update([
					'isDefault'  		 =>   0,
					]);
					
			DB::table('shipping_methods')->where('shipping_methods_id', '=', $request->shipping_id)->update([
					'isDefault'  		 =>   1,
					]);
							
			$message = 'changed';
			return $message;
		}
		}
	}
	
	//shipping_detail
	public function shippingDetail(Request $request){
		if(session('shipping_methods_view')==0){
			return Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.FlateRate"));
		$result = array();		
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$shppingMethods = DB::table('shipping_methods')
							->where('table_name', $request->table_name)->get();
		
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('shipping_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['table_name', '=', $request->table_name],
				])->get();
				
			if(count($description)>0){								
				$description_data[$languages_data->languages_id]['name'] = $description[0]->name;
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['name'] = '';
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		
		$result['description'] = $description_data;	
		$result['shppingMethods'] = $shppingMethods;
		
		return view("admin.shippingDetail", $title)->with('result', $result);
		}
	}
	
	//updateShipping
	public function updateShipping(Request $request){
		if(session('shipping_methods_update')==0){
			return Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditMainCategories"));
		$last_modified 	=   date('y-m-d h:i:s');
		$table_name = $request->table_name;
		$result = array();		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			
			$checkExist = DB::table('shipping_description')->where('table_name','=',$table_name)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('shipping_description')->where('table_name','=',$table_name)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$name,
					]);
			}else{
				DB::table('shipping_description')->insert([
					'name'  	     		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'table_name'			 =>   $table_name,
					]);
			}
		}
		
		$message = Lang::get("labels.shippingUpdateMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	
}
