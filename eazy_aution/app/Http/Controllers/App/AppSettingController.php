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
class AppSettingController extends Controller
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
	public function apiAuthenticate($consumer_data){
		
		$settings = $this->getSetting();	
		
		$callExist = DB::table('api_calls_list')->where([['device_id','=', $consumer_data['consumer_device_id']],['nonce','=',$consumer_data['consumer_nonce']],['url','=',$consumer_data['consumer_url']]])->get();
		
		if(md5($settings['consumer_key']) == $consumer_data['consumer_key'] && md5($settings['consumer_secret']) == $consumer_data['consumer_secret'] && count($callExist)==0){
			DB::table('api_calls_list')->insert(['device_id'=>$consumer_data['consumer_device_id'],'nonce'=>$consumer_data['consumer_nonce'],'url'=>$consumer_data['consumer_url'], 'created_at'=>date('Y-m-d h:i:s')]);
			return '1';						
		}else{
			return '0';
		}		
	}
	

	public function getlanguages(){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;

		$authenticate = $this->apiAuthenticate($consumer_data);	
		
		if($authenticate==1){
			$languages = DB::table('languages')->get();
			$responseData = array('success'=>'1', 'languages'=>$languages,  'message'=>"Returned all languages.");
		}else{			
			$responseData = array('success'=>'0', 'languages'=>array(),  'message'=>"Unauthenticated call.");
		}
		
		$categoryResponse = json_encode($responseData);
		print $categoryResponse;
	}

	//get Setting
	public function getSetting(){
		$setting = DB::table('settings')->get();
		$result = array();
		foreach($setting as $settings){
			$name  = $settings->name;
			$value = $settings->value;			 
			$result[$name] = $value;
		}	
		return $result;
	}	

	//get Settings
	public function sitesetting(){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;

		$authenticate = $this->apiAuthenticate($consumer_data);	
		
		if($authenticate==1){
			$settings = $this->getSetting();				
			$responseData = array('success'=>'1', 'data'=>$settings,  'message'=>"Returned all site data.");
		}else{			
			$responseData = array('success'=>'0', 'languages'=>array(),  'message'=>"Unauthenticated call.");
		}
		$categoryResponse = json_encode($responseData);
		print $categoryResponse;
	}	

	//get Settings
	public function contactus(Request $request){	

		$name 		=  $request->name;
		$email 		=  $request->email;
		$message 	=  $request->message;		
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;

		$authenticate = $this->apiAuthenticate($consumer_data);		
		
		if($authenticate==1){			
			$setting = $this->getSetting();
			$data = array('name'=>$name, 'email'=>$email, 'message'=>$message, 'adminEmail'=>$setting['contact_us_email']);
			$responseData = array('success'=>'1', 'data'=>'',  'message'=>"Message has been sent successfully!");
			$categoryResponse = json_encode($responseData);
			print $categoryResponse;
		}else{			
			$responseData = array('success'=>'0', 'languages'=>array(),  'message'=>"Unauthenticated call.");
			$categoryResponse = json_encode($responseData);
			print $categoryResponse;
			
			Mail::send('/mail/contactUs', ['data' => $data], function($m) use ($data){
				$m->to($data['adminEmail'])->subject(Lang::get("labels.contactUsTitle"))->getSwiftMessage()
				->getHeaders()
				->addTextHeader('x-mailgun-native-send', 'true');	
			});
		}
	}

	//applabels
	public function applabels(Request $request){
		$language_id 		 =  $request->lang;		
		$labels = DB::table('labels')
			->leftJoin('label_value','label_value.label_id','=','labels.label_id')
			->where('language_id','=', $language_id)
			->get();			

		$result = array();
		foreach($labels as $labels_data){
			$result[$labels_data->label_name] = $labels_data->label_value;
		}
		
		$responseData = array('success'=>'1', 'labels'=>$result,  'message'=>"Returned all site labels.");
		$categoryResponse = json_encode($responseData);
		print $categoryResponse;

	}

	

	//applabels3
	public function applabels3(Request $request){	

		$language_id 		=  $request->lang;			
		
		$labels = DB::table('labels')
			->leftJoin('label_value','label_value.label_id','=','labels.label_id')
			->where('language_id','=', $language_id)
			->get();
			

		$result = array();
		foreach($labels as $labels_data){
			$result[$labels_data->label_name] = $labels_data->label_value;
		}		
		
		$categoryResponse = json_encode($result);
		print $categoryResponse;
	}

	

}

