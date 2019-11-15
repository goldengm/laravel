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

class BannersController extends Controller
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
	
	//getbanners
	public function getbanners(Request $request){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			//current time
			$currentDate = Carbon\Carbon::now();
			$currentDate = $currentDate->toDateTimeString();
			
			$banners = DB::table('banners')
					   ->select('banners_id as id', 'banners_title as title', 'banners_url as url', 'banners_image as image', 'type', 'banners_title as title')
					   ->where('status', '=', '1')
					   ->where('expires_date', '>', $currentDate)
					   ->get();
					   
			if(count($banners)>0){
				$responseData = array('success'=>'1', 'data'=>$banners, 'message'=>"Banners are returned successfull.");
			}else{
				$banners = array();
				$responseData = array('success'=>'0', 'data'=>$banners, 'message'=>"Banners are empty.");
			}		   
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}
		
		$response = json_encode($responseData);
		print $response;
		
	}
	
	//banners history
	public function bannerhistory(Request $request){
		$consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
		$authenticate = $authController->apiAuthenticate($consumer_data);
		
		if($authenticate==1){
			
			$banners_id = $request->banners_id;
			$banners_history_date = date('Y-m-d H:i:s');
			
			$bannerHistory = DB::table('banners_history')
					   ->where('banners_id', '=', $banners_id)
					   ->get();
					   
			//if already clicked by other user
			if(count($bannerHistory)){
				$addBanner = DB::table('banners_history')->insert([
										'banners_clicked' => '1',
										'banners_history_date' => '$banners_history_date',
										'banners_id' => '$banners_id'
									]);
			}else{
				$updateBanner = DB::table('banners_history')->update([
										'banners_clicked' => '1',
										'banners_history_date' => '$banners_history_date',
									])
									->where('banners_id', '=', '$banners_id');
			}
			$data = array();
			$responseData = array('success'=>'1', 'data'=>$data, 'message'=>"banner history has been added.");
			
		}else{
			$responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
		}
		
		$response = json_encode($responseData);
		print $response;
	}
			
}