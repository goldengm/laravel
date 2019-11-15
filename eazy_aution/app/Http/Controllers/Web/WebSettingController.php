<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
namespace App\Http\Controllers\Web;
//use Mail;
//validator is builtin class in laravel
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

//for Carbon a value 
use Carbon;
use Lang;
//email
use Illuminate\Support\Facades\Mail;
use Session;

class WebSettingController extends DataController
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
	 
	 //contactUs
	public function changeLanguage(Request $request){
		if($request->ajax()){
		   $request->session()->put('locale', $request->locale);
		   		   
		   //set language
		   $languages = DB::table('languages')->where('code','=',$request->locale)->get();	  
		   $request->session()->put('direction', $languages[0]->direction);		   
		   $request->session()->put('language_id', $languages[0]->languages_id);
		}
		
	}
	
	//subscribe
	public function subscribe(Request $request){
		
		$msg = Lang::get("website.Some problem occurred, please try again.");
		$result['commonContent'] = $this->commonContent();
		
		if(!empty($result['commonContent']['setting'][89]->value) and !empty($result['commonContent']['setting'][88]->value)){

			$fname = '';
			$lname = '';
			$apikey = $result['commonContent']['setting'][87]->value;
			$list_id= $result['commonContent']['setting'][88]->value;

			$auth = base64_encode( 'user:'.$apikey );

			$data = array(
				'apikey'        => $apikey,
				'email_address' => $request->email,
				'status'        => 'subscribed',
				'merge_fields'  => array(
					'FNAME' => $fname,
					'LNAME' => $lname
				)
			);
			
			$json_data = json_encode($data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://' . substr($apikey,strpos($apikey,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
														'Authorization: Basic '.$auth));
			/*curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');*/
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                                                                                                  

			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close ($ch);
			$msg = '';
			$status = 0;
			if ($httpCode == 200) {
				$msg = Lang::get("website.You have successfully subscribed.");
				$status = 1;
			} else {
				switch ($httpCode) {
					case 400:
						$msg = Lang::get("website.You have already subscribed.");
						$status = 0;
						break;
					default:						
						
						$status = 0;
						break;
				}
				$status = 0;
			}

			
		}else{
			$status = 0;
		}
		$result = array('success'=>$status, 'message'=>$msg);
		print_r(json_encode($result));
		
		
	}
	
	
}
