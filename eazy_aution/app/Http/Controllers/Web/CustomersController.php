<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
namespace App\Http\Controllers\Web;
use App\User;
use Socialite;
//use Mail;
//validator is builtin class in laravel
use Validator;
use Services;
use File; 

use Illuminate\Contracts\Auth\Authenticatable;
use Hash;
use DB;


//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;


//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//for Carbon a value 
use Carbon;
use Illuminate\Support\Facades\Redirect;
use Session;
use Lang;

//email
use Illuminate\Support\Facades\Mail;

class CustomersController extends DataController
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
	
	//signup 
	public function signup(Request $request){	
		if(auth()->guard('customer')->check()){
			return redirect('/');
		}
		else{
			$title = array('pageTitle' => Lang::get("website.Sign Up"));
			$result = array();						
			$result['commonContent'] = $this->commonContent();		
			return view("signup", $title)->with('result', $result);   
		} 			
	}
	
	//login 
	public function login(Request $request){	
		if(auth()->guard('customer')->check()){
			return redirect('/');
		}
		else{
			
			$title = array('pageTitle' => Lang::get("website.Login"));
			$result = array();		
			
			$previous_url = Session::get('_previous.url');
				
			
			$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$ref = rtrim($ref, '/');
						
			if ($previous_url == url('checkout') or $previous_url == url('shop') or $previous_url == url('')) {
				session(['previous'=> $previous_url]);
			}					
					
			$result['commonContent'] = $this->commonContent();		
			return view("login", $title)->with('result', $result);   
		} 		
				
	}
	
	//login
	public function processLogin(Request $request){		
		$old_session = Session::getId();	
		$previous_url = session('previous');
		$result = array();		
		
			//check authentication of email and password
			$customerInfo = array("email" => $request->email, "password" => $request->password);
			
			if(auth()->guard('customer')->attempt($customerInfo)) {
				if(auth()->guard('customer')->attempt($customerInfo)) {
					$customer = auth()->guard('customer')->user();
					
					//set session				
					session(['customers_id' => $customer->customers_id]);
					
					//cart 				
					$cart = DB::table('customers_basket')->where([
						['session_id', '=', $old_session],
					])->get();
					
					if(count($cart)>0){					
						foreach($cart as $cart_data){						
							$exist = DB::table('customers_basket')->where([
								['customers_id', '=', $customer->customers_id],
								['products_id', '=', $cart_data->products_id],
								['is_order', '=', '0'],
							])->delete();
						}									
					}
					
					DB::table('customers_basket')->where('session_id','=', $old_session)->update([
						'customers_id'	=>	$customer->customers_id
						]);
	
					DB::table('customers_basket_attributes')->where('session_id','=', $old_session)->update([
						'customers_id'	=>	$customer->customers_id
						]);
					
					
					//insert device id
					if(!empty(session('device_id'))){					
						DB::table('devices')->where('device_id', session('device_id'))->update(['customers_id'	=>	$customer->customers_id]);		
					}
							
					$result['customers'] = DB::table('customers')->where('customers_id', $customer->customers_id)->get();	
					if(!empty($previous_url)){
						return Redirect::to($previous_url);
					}else{
						return redirect()->intended('/')->with('result', $result);
					}
				}else{
			        Auth::logout();
            		Auth::guard('customer')->logout();
            		session()->flush();
            		$request->session()->forget('customers_id');
			        return redirect('login')->with('loginError',Lang::get("website.Your account has been deactivated")); 
			    }
			}else{
				return redirect('login')->with('loginError',Lang::get("website.Email or password is incorrect"));
			}
	}
		
	
	
	public function profile(Request $request){
		$title = array('pageTitle' => Lang::get("website.Profile"));
		$result = array();	
		$result['commonContent'] = $this->commonContent();
		
		return view("profile", $title)->with('result', $result); 
	}
	
	public function updateMyProfile(Request $request){
		
		$customers_id								=	auth()->guard('customer')->user()->customers_id; 
		$customers_firstname            			=   $request->customers_firstname;
		$customers_lastname           				=   $request->customers_lastname;			
		//$customers_email_address    		   		=   $request->customers_email_address;	
		$customers_fax          		   			=   $request->customers_fax;	
		$customers_newsletter          		   					=   $request->customers_newsletter;	
		$customers_telephone          		   		=   $request->customers_telephone;	
		$customers_gender          		   			=   $request->customers_gender;	
		$customers_dob          		   			=   $request->customers_dob;
		$customers_info_date_account_last_modified 	=   date('y-m-d h:i:s');
		
		$extensions = array('gif','jpg','jpeg','png');
		if($request->hasFile('picture') and in_array($request->picture->extension(), $extensions)){
			$image = $request->picture;
			
			$verifyimg = getimagesize($image);

			/* Make sure the MIME type is an image */
			$pattern = "#^(image/)[^\s\n<]+$#i";

			if(!preg_match($pattern, $verifyimg['mime']))
			{
				$customers_picture = "";
			} else {
			
				$fileName = time().'.'.$image->getClientOriginalName();
				$image->move('resources/assets/images/user_profile/', $fileName);
				$customers_picture = 'resources/assets/images/user_profile/'.$fileName; 
			}
		}	else{
			$customers_picture = "";
		}	
		
		if ($customers_picture == "")
		{	
		$customer_data = array(
			'customers_firstname'			 =>  $customers_firstname,
			'customers_lastname'			 =>  $customers_lastname,
			'customers_fax'					 =>  $customers_fax,
			'customers_newsletter'			 =>  $customers_newsletter,
			'customers_telephone'			 =>  $customers_telephone,
			'customers_gender'				 =>  $customers_gender,
			'customers_dob'					 =>  $customers_dob
			);
		} else {
			$customer_data = array(
			'customers_firstname'			 =>  $customers_firstname,
			'customers_lastname'			 =>  $customers_lastname,
			'customers_fax'					 =>  $customers_fax,
			'customers_newsletter'			 =>  $customers_newsletter,
			'customers_telephone'			 =>  $customers_telephone,
			'customers_gender'				 =>  $customers_gender,
			'customers_dob'					 =>  $customers_dob,
			'customers_picture'				 =>  $customers_picture
				);
		}
		
					
		//update into customer
		DB::table('customers')->where('customers_id', $customers_id)->update($customer_data);
				
		DB::table('customers_info')->where('customers_info_id', $customers_id)->update(['customers_info_date_account_last_modified'   => $customers_info_date_account_last_modified]);	
		$message = Lang::get("website.Prfile has been updated successfully");
		
		return redirect()->back()->with('success', $message);
			
	}
	
	public function updateMyPassword(Request $request){
		$old_session = Session::getId();
		$customers_id            					=   auth()->guard('customer')->user()->customers_id;	
		$new_password								=   $request->new_password;
		$old_password								=   $request->old_password;
		//$customers_email_address    		   		=   $request->customers_email_address;
		$updated_at 								=   date('y-m-d h:i:s');	
		$customers_info_date_account_last_modified 	=   date('y-m-d h:i:s');	
		
		
		$customer_data = array(
			'password'			=>  bcrypt($new_password),
			'updated_at'		=>  date('y-m-d h:i:s'),
		);
		
		$userData = DB::table('customers')->where('customers_id', $customers_id)->update($customer_data);
		$user = DB::table('customers')->where('customers_id', $customers_id)->get();
		
		//check authentication of email and password
		/*$customerInfo = array("email" => $user[0]->email, "password" => $request->new_password);
		
		if(Auth::attempt($customerInfo)) {

			$customer = Auth::User();
			//set session

			session(['customers_id' => $customer->customers_id]);

			//cart 
			$cart = DB::table('customers_basket')->where([
				['session_id', '=', $old_session],
			])->get();

			if(count($cart)>0){

				foreach($cart as $cart_data){

					$exist = DB::table('customers_basket')->where([
						['customers_id', '=', $customer->customers_id],
						['products_id', '=', $cart_data->products_id],
						['is_order', '=', '0'],
					])->delete();

				}

			}

			DB::table('customers_basket')->where('session_id','=', $old_session)->update([
				'customers_id'	=>	$customer->customers_id
				]);

			DB::table('customers_basket_attributes')->where('session_id','=', $old_session)->update([
				'customers_id'	=>	$customer->customers_id
				]);


			$result['customers'] = DB::table('customers')->where('customers_id', $customer->customers_id)->get();	
			
			
			$message = Lang::get("website.Password has been updated successfully");
			return redirect()->back()->with('success', $message);
		}
				
		$userData = DB::table('customers')->where('customers_id', $customers_id)->update($customer_data);*/
				
		DB::table('customers_info')->where('customers_info_id', $customers_id)->update(['customers_info_date_account_last_modified'   =>   $customers_info_date_account_last_modified]);

		$message = Lang::get("website.Password has been updated successfully");
			return redirect()->back()->with('success', $message);
			
		
		
	}
	//logout
	public function logout(REQUEST $request){
		Auth::logout();
		Auth::guard('customer')->logout();
		session()->flush();
		$request->session()->forget('customers_id');
		$request->session()->regenerate();		
		return redirect()->intended('/');
	}
	
	 /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialLogin($social){
		//print_r($social);
        return Socialite::driver($social)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleSocialLoginCallback($social){
		$old_session = Session::getId();
		
		$user =Socialite::driver($social)->stateless()->user();
		$password = $this->createRandomPassword();	
		
		// OAuth Two Providers
		$token = $user->token;
		if(!empty($user['gender'])){
			if($user['gender']=='male'){
				$customers_gender = '0';
			}else{
				$customers_gender = '1';
			}
		}else{
			$customers_gender = '0';
		}

		// All Providers
		$social_id = $user->getId();	
		
		$customers_firstname = substr($user->getName(), 0, strpos($user->getName(), ' '));
		$customers_lastname = str_replace($customers_firstname.' ', '', $user->getName());
		
		$email = $user->getEmail();		
		if(empty($email)){
			$email = '';	
		}			
		
			$img = file_get_contents($user->getAvatar());
			$dir="resources/assets/images/user_profile/";
			if (!file_exists($dir) and !is_dir($dir)) {
				mkdir($dir);
			} 

			$uploadfile = $dir."/pic_".time().".jpg";
			$temp_upload_path = base_path().'/'.$uploadfile;
			file_put_contents($temp_upload_path, $img);
			$profile_photo=$uploadfile;		
			
		if($social == 'facebook'){
			
			$customer_data = array(
				'customers_firstname' => $customers_firstname,
				'fb_id' => $social_id,
				'customers_lastname' => $customers_lastname,
				'email' => $email,
				'password' => Hash::make($password),
				'isActive' => '1',
				'customers_picture' => $profile_photo,
				'created_at' =>	 time()
			);
			
			$update_customer_data = array(
				'customers_firstname' => $customers_firstname,
				'fb_id' => $social_id,
				'customers_lastname' => $customers_lastname,
				'email' => $email,
				'isActive' => '1',
				'customers_picture' => $profile_photo,
				'created_at' =>	 time()
			);


			$existUser = DB::table('customers')->where('fb_id', '=', $social_id)->orWhere('email', '=', $email)->get();
		}
		
		if($social == 'google'){
		//user information
			$customer_data = array(
				'customers_firstname' => $customers_firstname,
				'google_id' => $social_id,
				'customers_lastname' => $customers_lastname,
				'email' => $email,
				'password' => Hash::make($password),
				'isActive' => '1',
				'customers_picture' => $profile_photo,
				'created_at' =>	 time()
			);
			
			$update_customer_data = array(
				'customers_firstname' => $customers_firstname,
				'google_id' => $social_id,
				'customers_lastname' => $customers_lastname,
				'email' => $email,
				'isActive' => '1',
				'customers_picture' => $profile_photo,
				'created_at' =>	 time()
			);

			$existUser = DB::table('customers')->where('google_id', '=', $social_id)->orWhere('email', '=', $email)->get();
		}
		
		if(count($existUser)>0){
			
			$customers_id = $existUser[0]->customers_id;
			
			//update data of customer
			DB::table('customers')->where('customers_id','=',$customers_id)->update($customer_data);
		}else{
			//insert data of customer
			$customers_id = DB::table('customers')->insertGetId($customer_data);
		}
		
		$userData = DB::table('customers')->where('customers_id', '=', $customers_id)->get();
		
		$existUserInfo = DB::table('customers_info')->where('customers_info_id', $customers_id)->get();
		$customers_info_id 							= $customers_id;
		$customers_info_date_of_last_logon  		= date('Y-m-d H:i:s');
		$customers_info_number_of_logons     		= '1';
		$customers_info_date_account_created 		= date('Y-m-d H:i:s');
		$global_product_notifications 				= '1';
		
		if(count($existUserInfo)>0){
			//update customers_info table
			DB::table('customers_info')->where('customers_info_id', $customers_info_id)->update([
				'customers_info_date_of_last_logon' => $customers_info_date_of_last_logon,
				'global_product_notifications' => $global_product_notifications,
				'customers_info_number_of_logons'=> DB::raw('customers_info_number_of_logons + 1')
			]);
			
		}else{
			
			//insert customers_info table
			$customers_default_address_id = DB::table('customers_info')->insertGetId([
					'customers_info_id' => $customers_info_id,
					'customers_info_date_of_last_logon' => $customers_info_date_of_last_logon,
					'customers_info_number_of_logons' =>  $customers_info_number_of_logons,
					'customers_info_date_account_created' => $customers_info_date_account_created,
					'global_product_notifications' => $global_product_notifications
			]);	
			
		}		
		
		//check if already login or not
		$already_login = DB::table('whos_online')->where('customer_id', '=', $customers_id)->get();	
		if(count($already_login)>0){
			DB::table('whos_online')
				->where('customer_id', $customers_id)
				->update([
						'full_name'  => $userData[0]->customers_firstname.' '.$userData[0]->customers_lastname,
						'time_entry'   => date('Y-m-d H:i:s'),							
				]);
		}else{
			DB::table('whos_online')
				->insert([
						'full_name'  => $userData[0]->customers_firstname.' '.$userData[0]->customers_lastname,
						'time_entry' => date('Y-m-d H:i:s'),
						'customer_id'    => $customers_id							
				]);
		}
		
		$customerInfo = array("email" => $email, "password" => $password);
		$old_session = Session::getId();
		$previous_url = session('previous');
		
		if(auth()->guard('customer')->attempt($customerInfo)) {	
				$customer = auth()->guard('customer')->user();
								
				//set session				
				session(['customers_id' => $customer->customers_id]);
				
				//cart 				
				$cart = DB::table('customers_basket')->where([
					['session_id', '=', $old_session],
				])->get();
				
				if(count($cart)>0){					
					foreach($cart as $cart_data){						
						$exist = DB::table('customers_basket')->where([
							['customers_id', '=', $customer->customers_id],
							['products_id', '=', $cart_data->products_id],
							['is_order', '=', '0'],
						])->delete();
					}									
				}
				
				DB::table('customers_basket')->where('session_id','=', $old_session)->update([
					'customers_id'	=>	$customer->customers_id
					]);

				DB::table('customers_basket_attributes')->where('session_id','=', $old_session)->update([
					'customers_id'	=>	$customer->customers_id
					]);
				
				
				//insert device id
				if(!empty(session('device_id'))){					
					DB::table('devices')->where('device_id', session('device_id'))->update(['customers_id'	=>	$customer->customers_id]);		
				}
						
				$result['customers'] = DB::table('customers')->where('customers_id', $customer->customers_id)->get();	
				if(!empty($previous_url)){
					return Redirect::to($previous_url);
				}else{
					return redirect()->intended('/')->with('result', $result);
				}
				
			}
//		
//		auth()->login($userData);
//		
//		return redirect()->intended('/');
		/*Mail::send('/mail/createAccount', ['userData' => $userData], function($m) use ($userData){
				$m->to($userData[0]->email)->subject('Welcome to Ecommerce App"')->getSwiftMessage()
				->getHeaders()
				->addTextHeader('x-mailgun-native-send', 'true');	
			});*/

		
		
    }
	
	//create random password for social links
	function createRandomPassword() { 
		$pass = substr(md5(uniqid(mt_rand(), true)) , 0, 8);	
		return $pass; 
	}
	
	// likeProduct 
	public function likeMyProduct(Request $request){		
		
		if(!empty(session('customers_id'))){
		
			$liked_products_id  = $request->products_id;
			
			$liked_customers_id = session('customers_id');
			$date_liked			= date('Y-m-d H:i:s');
			
			//to avoide duplicate record
			$record = DB::table('liked_products')->where([
					'liked_products_id'  => $liked_products_id,
					'liked_customers_id' => $liked_customers_id
				])->get();
			
				
			if(count($record)>0){
				
				DB::table('liked_products')->where([
					'liked_products_id'  => $liked_products_id,
					'liked_customers_id' => $liked_customers_id
				])->delete();				
				
				
				
				DB::table('products')->where('products_id','=',$liked_products_id)->decrement('products_liked');
				$products = DB::table('products')->where('products_id','=',$liked_products_id)->get();
				
				$responseData = array('success'=>'1', 'message'=>Lang::get("website.Product is disliked"), 'total_likes' => $products[0]->products_liked);
			}else{
				
				DB::table('liked_products')->insert([
					'liked_products_id'  => $liked_products_id,
					'liked_customers_id' => $liked_customers_id,
					'date_liked' 		 => $date_liked
				]);				
				DB::table('products')->where('products_id','=',$liked_products_id)->increment('products_liked');
				$products = DB::table('products')->where('products_id','=',$liked_products_id)->get();
				
				$responseData = array('success'=>'2', 'message'=>Lang::get("website.Product is liked"), 'total_likes' => $products[0]->products_liked);
			}
			
		}else{
			$responseData = array('success'=>'0', 'message'=>Lang::get("website.Please login first to like this product"));
		}
		
		$cartResponse = json_encode($responseData);
		print $cartResponse;
	}
	
	// likeProduct 
	public function unlikeMyProduct(Request $request){
		
		if(!empty(session('customers_id'))){
		
			$liked_products_id  = $request->product_id;
			
			$liked_customers_id = session('customers_id');
			
			DB::table('liked_products')->where([
				'liked_products_id'  => $liked_products_id,
				'liked_customers_id' => $liked_customers_id
			])->delete();
			
			DB::table('products')->where('products_id','=',$liked_products_id)->decrement('products_liked');					
			$message = Lang::get("website.Product is unliked");
			return redirect()->back()->with('success', $message);
		}else{
			return redirect('login')->with('loginError','Please login to like product!');
		}
		
	} 
	
	
	//wishlist
	public function wishlist(Request $request){
		$title = array('pageTitle' => Lang::get("website.Wishlist"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();
			
		
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}	
		
		$myVar = new DataController();
		$data = array('page_number'=>0, 'type'=>'wishlist', 'limit'=>$limit, 'categories_id'=>'', 'search'=>'', 'min_price'=>'', 'max_price'=>'' );			
		$products = $myVar->products($data);
		$result['products'] = $products;
								
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);
		
		//liked products
		$result['liked_products'] = $this->likedProducts();
		if($limit > $result['products']['total_record']){		
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}
		
		//echo '<pre>'.print_r($result['products'], true).'</pre>';
		return view("wishlist", $title)->with('result', $result); 
	}
	
	
	public function loadMoreWishlist(Request $request){
		
		$limit = $request->limit;
						
		$myVar = new DataController();
		$data = array('page_number'=>$request->page_number, 'type'=>'wishlist', 'limit'=>$limit, 'categories_id'=>'', 'search'=>'', 'min_price'=>'', 'max_price'=>'' );	
		$products = $myVar->products($data);
		$result['products'] = $products;	
				
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);
		$result['limit'] = $limit;
		return view("wishlistproducts")->with('result', $result);	
		
	}
	
	//forgotPassword
	public function forgotPassword(){
		if(auth()->guard('customer')->check()){
			return redirect('/');
		}
		else{
			
			$title = array('pageTitle' => Lang::get("website.Forgot Password"));
			$result = array();			
			$result['commonContent'] = $this->commonContent();
			return view("forgotpassword", $title)->with('result', $result);   
		} 
	}
	
	//forgotPassword
	public function processPassword(Request $request){
		$title = array('pageTitle' => Lang::get("website.Forgot Password"));
		
		$password = $this->createRandomPassword();
		
		$email    		  =   $request->email;
		$postData = array();
				
		//check email exist
		$existUser = DB::table('customers')->where('email', $email)->get();				
		if(count($existUser)>0){
			DB::table('customers')->where('email', $email)->update([
					'password'	=>	Hash::make($password)
					]);
			$existUser[0]->password = $password;
			
			$myVar = new AlertController();
			$alertSetting = $myVar->forgotPasswordAlert($existUser);
					
			return redirect('login')->with('success', Lang::get("website.Password has been sent to your email address"));
		}else{	
			return redirect('forgotPassword')->with('error', Lang::get("website.Email address does not exist"));
		}
		
	}
	
	//forgotPassword
	public function recoverPassword(){
		$title = array('pageTitle' => Lang::get("website.Forgot Password"));
		$user = DB::table('')->where('','')->get();
		return view("recoverPassword", $title)->with('result', $result); 
	}
	
	//generate random password
	function subscribeNotification(Request $request) {
			
		$setting = $this->commonContent();
		 
		/* Desktop */
		$type = 3;
		
		session(['device_id' => $request->device_id]);
		
		if(!empty(auth()->guard('customer')->user()->customers_id)){
		
			$device_data = array(
				'device_id' => $request->device_id,
				'device_type' =>  $type,
				'register_date' => time(),
				'update_date' => time(),
				'ram' =>  '',
				'status' => '1',
				'processor' => '',
				'device_os' => '',
				'location' => '',
				'device_model'=>'',
				'customers_id'=>auth()->guard('customer')->user()->customers_id,
				'manufacturer'=>'',
				$setting['setting'][54]->value=>'1'
			);
			
		
		}else{
			
			$device_data = array(
				'device_id' => $request->device_id,
				'device_type' =>  $type,
				'register_date' => time(),
				'update_date' => time(),
				'ram' =>  '',
				'status' => '1',
				'processor' => '',
				'device_os' => '',
				'location' => '',
				'device_model'=>'',
				'manufacturer'=>'',
				$setting['setting'][54]->value=>'1'
			);
						
		}
		
		//check device exist
		$device_id = DB::table('devices')->where('device_id','=', $request->device_id)->get();
	
		if(count($device_id)>0){			
			$dataexist = DB::table('devices')->where('device_id','=', $request->device_id)->where('customers_id','==', '0')->get();
			DB::table('devices')
				->where('device_id', $request->device_id)
				->update($device_data);			
		}
		else{
			$device_id = DB::table('devices')->insertGetId($device_data);	
		}

		print 'success';	
	}
	
	
	public function signupProcess(Request $request){
		$old_session = Session::getId();
		$previous_url = session('previous');
		
		$firstName = $request->firstName;
		$lastName = $request->lastName;
		$gender = $request->gender;
		$email = $request->email;
		$password = $request->password;
		//$token = $request->token;
		$date = date('y-md h:i:s');
		
		$extensions = array('gif','jpg','jpeg','png');
		if($request->hasFile('picture') and in_array($request->picture->extension(), $extensions))
		{
			$image = $request->picture;
			
			$verifyimg = getimagesize($image);

			/* Make sure the MIME type is an image */
			$pattern = "#^(image/)[^\s\n<]+$#i";

			if(!preg_match($pattern, $verifyimg['mime']))
			{
				$profile_photo = 'resources/assets/images/user_profile/default_user.png';
			} else {
			
				$fileName = time().'.'.$image->getClientOriginalName();
				$image->move('resources/assets/images/user_profile/', $fileName);
				$profile_photo = 'resources/assets/images/user_profile/'.$fileName; 
			}
		}else{
			$profile_photo = 'resources/assets/images/user_profile/default_user.png';
		}	
		
//		//validation start
		$validator = Validator::make(
			array(
				'firstName' => $request->firstName,
				'lastName' => $request->lastName,
				'customers_gender' => $request->gender,
				'email' => $request->email,
				'password' => $request->password,
				//'re_password' => $request->re_password,
				
			),array(
				'firstName' => 'required ',
				'lastName'  => 'required',
				'customers_gender' 	=> 'required',
				'email' 	=> 'required | email',
				'password'  => 'required',
				//'re_password' => 'required | same:password',
			)
		);
		if($validator->fails()){
			return redirect('signup')->withErrors($validator)->withInput();
		}else{
			
			//echo "Value is completed";
			$data = array(
				'customers_firstname' => $request->firstName,
				'customers_lastname'  => $request->lastName,
				'customers_gender' => $request->gender,
				'email' => $request->email,
				'password' => Hash::make($password),
				'customers_picture'				 =>  $profile_photo,
				'created_at' => $date,
				'updated_at' => $date,
			);	
			
			
			//eheck email already exit
			$user_email = DB::table('customers')->select('email')->where('email', $email)->get();	
			if(count($user_email)>0){
				return redirect('/signup')->withInput($request->input())->with('error', Lang::get("website.Email already exist"));
			}else{
				if(DB::table('customers')->insert($data)){					
					
					//check authentication of email and password
					$customerInfo = array("email" => $request->email, "password" => $request->password);
										
					if(auth()->guard('customer')->attempt($customerInfo)) {
						$customer = auth()->guard('customer')->user();
						
						//set session
						session(['customers_id' => $customer->customers_id]);

						//cart 
						$cart = DB::table('customers_basket')->where([
							['session_id', '=', $old_session],
						])->get();

						if(count($cart)>0){
							foreach($cart as $cart_data){
								$exist = DB::table('customers_basket')->where([
									['customers_id', '=', $customer->customers_id],
									['products_id', '=', $cart_data->products_id],
									['is_order', '=', '0'],
								])->delete();
							}
						}

						DB::table('customers_basket')->where('session_id','=', $old_session)->update([
							'customers_id'	=>	$customer->customers_id
							]);

						DB::table('customers_basket_attributes')->where('session_id','=', $old_session)->update([
							'customers_id'	=>	$customer->customers_id
							]);

						//insert device id
						if(!empty(session('device_id'))){					
							DB::table('devices')->where('device_id', session('device_id'))->update(['customers_id'	=>	$customer->customers_id]);		
						}
						
						$customers = DB::table('customers')->where('customers_id', $customer->customers_id)->get();
						$result['customers'] = $customers;
						//email and notification			
						$myVar = new AlertController();
						$alertSetting = $myVar->createUserAlert($customers);
						
						
						if(!empty($previous_url)){
							return Redirect::to($previous_url);
						}else{
							return redirect()->intended('/')->with('result', $result);
						}						
						
					}else{
						return redirect('login')->with('loginError', Lang::get("website.Email or password is incorrect"));
					}

					
				}else{
					return redirect('/signup')->with('error', Lang::get("website.something is wrong"));
				}
			}		
			
		}
	}
	
}
