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
//for password encryption or hash protected
use Hash;
use App\Administrator;

//for authenitcate login data
use Auth;

//for requesting a value 
use Illuminate\Http\Request;

class AdminSiteSettingController extends Controller
{		
	public function slugify($slug){
		
	  // replace non letter or digits by -
	  $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);
	
	  // transliterate
	  if (function_exists('iconv')){
		$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
	  }
	
	  // remove unwanted characters
	  $slug = preg_replace('~[^-\w]+~', '', $slug);
	
	  // trim
	  $slug = trim($slug, '-');
	
	  // remove duplicate -
	  $slug = preg_replace('~-+~', '-', $slug);
	
	  // lowercase
	  $slug = strtolower($slug);
	
	  if (empty($slug)) {
		return 'n-a';
	  }
	
	  return $slug;
	}
	
	public function imageType(){	
		$extensions = array('gif','jpg','jpeg','png');	
		return $extensions;
	}
	
	//orderstatus
	public function orderstatus(Request $request){
		if(session('general_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		
		$title = array('pageTitle' => Lang::get("labels.ListingOrderStatus"));		
		
		$result = array();
		
		$orders_status = DB::table('orders_status')
			->LeftJoin('languages', 'languages.languages_id','=', 'orders_status.language_id')
			->paginate(60);
		
		$result['orders_status'] = $orders_status;
		
		return view("admin.orderstatus",$title)->with('result', $result);
		
		}
	}
	
	//addorderstatus
	public function addorderstatus(Request $request){
		if(session('general_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddOrderStatus"));
		$result = array();
		
		$languages = DB::table('languages')->get();		
		$result['languages'] = $languages;
		
		return view("admin.addorderstatus",$title)->with('result', $result);
		}
	}
		
	//addNewOrderStatus	
	public function addNewOrderStatus(Request $request){
		if(session('general_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		
		//total records
		$orders_status = DB::table('orders_status')->get();
		$orders_status_id = count($orders_status)+1;
		
		if($request->public_flag==1){
			$languages = DB::table('orders_status')->where('public_flag',1)->where('language_id',$request->language_id)->update([
				'public_flag'			=>	0,
				]);	
		}
		
		DB::table('orders_status')->insertGetId([
				'orders_status_id'		=>	$orders_status_id,
				'language_id'			=>	$request->language_id,
				'orders_status_name'	=>	$request->orders_status_name,
				'public_flag'			=>	$request->public_flag
				]);
								
		$message = Lang::get("labels.OrderStatusAddedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//editorderstatus
	public function editorderstatus(Request $request){		
		if(session('general_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditOrderStatus"));
		$result = array();		
		
		$orders_status = DB::table('orders_status')
			->LeftJoin('languages', 'languages.languages_id','=', 'orders_status.language_id')
			->where('orders_status_id','=', $request->id)
			->paginate(60);
			
		$result['orders_status'] = $orders_status;	
			
		$languages = DB::table('languages')->get();		
		$result['languages'] = $languages;
		
		return view("admin.editorderstatus",$title)->with('result', $result);
		}
	}
	
	//updateOrderStatus	
	public function updateOrderStatus(Request $request){
		if(session('general_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		if($request->public_flag==1){
			$languages = DB::table('orders_status')->where('public_flag','=',1)->where('language_id','=',$request->language_id)->update([
				'public_flag'			=>	0,
				]);	
		}
		
		$orders_status = DB::table('orders_status')->where('orders_status_id','=', $request->id)->update([
				'language_id'			=>	$request->language_id,
				'orders_status_name'	=>	$request->orders_status_name,
				'public_flag'			=>	$request->public_flag
				]);
		
		$message = Lang::get("labels.OrderStatusUpdatedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//deleteOrderStatus
	public function deleteOrderStatus(Request $request){
		if(session('general_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('orders_status')->where('orders_status_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.OrderStatusDeletedMessage")]);
		}
	}
		
	//getlanguages
	public function getlanguages(){
		$languages = DB::table('languages')->get();
		return $languages;
	}
	
	//getsinglelanguages
	public function getSingleLanguages($language_id){
		$languages = DB::table('languages')->get();
		return $languages;
	}
	
	//languages
	public function languages(Request $request){
		if(session('language_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.ListingLanguages"));		
		
		$result = array();
		
		$languages = DB::table('languages')
			->paginate(60);
		
		$result['languages'] = $languages;
		
		return view("admin.languages",$title)->with('result', $result);
		}
	}
	
	//addLanguages
	public function addlanguages(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddLanguage"));		
		return view("admin.addlanguages",$title);
	}
		
	//addNewLanguages	
	public function addnewlanguages(Request $request){
		if(session('language_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/language_flags/', $fileName);
			$uploadImage = 'resources/assets/images/language_flags/'.$fileName; 
		}	else{
			$uploadImage = '';
		}	
		
		if($request->is_default=='1'){
			$orders_status = DB::table('languages')->where('is_default','=', 1)->update([				
				'is_default'	=>	0
				]);	
		}
		
		DB::table('languages')->insertGetId([
				'name'			=>	$request->name,
				'code'			=>	$request->code,
				'image'			=>	$uploadImage,
				'directory'		=>	$request->directory,
				'direction'		=>	$request->direction,
				'is_default'	=>	$request->is_default
				]);
								
		$message = Lang::get("labels.languageAddedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//editOrderStatus
	public function editlanguages(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditLanguage"));
		
		$languages = DB::table('languages')->where('languages_id','=', $request->id)->get();		
		$result['languages'] = $languages;
		
		return view("admin.editlanguages",$title)->with('result', $result);
	}
	
	//updateOrderStatus	
	public function updatelanguages(Request $request){
		if(session('language_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/language_flags/', $fileName);
			$uploadImage = 'resources/assets/images/language_flags/'.$fileName; 
		}	else{
			$uploadImage = $request->oldImage;
		}	
		
		if($request->is_default=='1'){
			$orders_status = DB::table('languages')->where('is_default','=', 1)->update([				
				'is_default'	=>	0
				]);	
		}
		
		$orders_status = DB::table('languages')->where('languages_id','=', $request->id)->update([
				'name'			=>	$request->name,
				'code'			=>	$request->code,
				'image'			=>	$uploadImage,
				'directory'		=>	$request->directory,
				'direction'		=>	$request->direction,
				'is_default'	=>	$request->is_default
				]);
		
		$message = Lang::get("labels.languageEditMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	//defaultLanguage	
	public function defaultlanguage(Request $request){	
		if(session('language_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$orders_status = DB::table('languages')->where('is_default','=', 1)->update([				
			'is_default'	=>	0
			]);		
		
		$orders_status = DB::table('languages')->where('languages_id','=', $request->languages_id)->update([
				'is_default'	=>	1
				]);		
		}
	}
	
	//deletelanguage
	public function deletelanguage(Request $request){
		if(session('language_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		DB::table('languages')->where('languages_id', $request->id)->delete();
		
		DB::table('categories_description')->where('language_id', $request->id)->delete();
		DB::table('label_value')->where('language_id', $request->id)->delete();
		DB::table('manufacturers_info')->where('languages_id', $request->id)->delete();
		DB::table('products_description')->where('language_id', $request->id)->delete();
		DB::table('pages_description')->where('language_id', $request->id)->delete();
		DB::table('products_options_descriptions')->where('language_id', $request->id)->delete();
		DB::table('products_options_values_descriptions')->where('language_id', $request->id)->delete();
		DB::table('shipping_description')->where('language_id', $request->id)->delete();
		DB::table('payment_description')->where('language_id', $request->id)->delete();
				
		return redirect()->back()->withErrors([Lang::get("labels.languageDeleteMessage")]);
		}
	}
	
	
	//setting page
	public function setting(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
			$title = array('pageTitle' => Lang::get("labels.setting"));		
			
			$result = array();
			
			$settings = DB::table('settings')->get();
			
			$result['settings'] = $settings;
			
			return view("admin.setting",$title)->with('result', $result);
		}
	}
	
	//applicationApi
	public function applicationApi(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.applicationApi"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.applicationApi",$title)->with('result', $result);
		}
	}
	//setting page
	public function getSetting(){
		$setting = DB::table('settings')->get();
		return $setting;
	}
	
	//units page
	public function getUnits(){
		$units = DB::table('units')->where('is_active','1')->get();
		return $units;
	}
	
	//webSettings
	public function webSettings(Request $request){
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.setting"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.webSettings",$title)->with('result', $result);
		}
	}
	
	//update setting	
	public function updateSetting(Request $request){
		
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		foreach($request->all() as $key => $value){	
			
			//website logo
			if($key=='website_logo'){				
				if($request->hasFile('website_logo') and in_array($request->website_logo->extension(), $extensions)){
					$dir="resources/assets/images/site_images/";
					if (!file_exists($dir) and !is_dir($dir)) {
						mkdir($dir);
					} 			
					$image = $request->website_logo;
					$fileName = time().'.'.$image->getClientOriginalName();
					$image->move('resources/assets/images/site_images/', $fileName);
					$value = 'resources/assets/images/site_images/'.$fileName; 
						
				}else{
					$value = $request->oldImage;
				}				
			}
			
			DB::table('settings')->where('name','=', $key)->update([
				'value'			=>	addslashes($value),
				'updated_at'	=>	date('Y-m-d h:i:s')
				]);
		}
		
		$message = Lang::get("labels.SettingUpdateMessage");
		return redirect()->back()->withErrors([$message]);
	}
	
	//customstyle
	public function customstyle(Request $request){
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.custom_style/js"));			
		$result = array();		
		$settings = DB::table('settings')->get();		
		$result['settings'] = $settings;		
		return view("admin.customstyle",$title)->with('result', $result);
		
		}
	}
	
	//appSettings
	public function appSettings(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.application_settings"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.appSettings",$title)->with('result', $result);
		}
	}
	
	//seo
	public function seo(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.SEO Content"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.seo",$title)->with('result', $result);
		}
	}
	
	//admobSettings
	public function admobSettings(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		
		$title = array('pageTitle' => Lang::get("labels.admobSettings"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.admobSettings",$title)->with('result', $result);
		}
	}
	
	//facebookSettings
	public function facebookSettings(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.facebook_settings"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.facebookSettings",$title)->with('result', $result);
		}
	}
	
	//googleSettings
	public function googleSettings(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.google_settings"));		
		
		$result = array();
		
		$settings = DB::table('settings')->get();
		
		$result['settings'] = $settings;
		
		return view("admin.googleSettings",$title)->with('result', $result);
		}
	}
	
	//alert Setting
	public function getAlertSetting(){
		$setting = DB::table('alert_settings')->get();
		return $setting;
	}
	
	//setting page
	public function alertSetting(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.alertSetting"));		
		
		$result = array();
		
		$setting = DB::table('alert_settings')->get();
		
		$result['setting'] = $setting;
		
		return view("admin.alertSetting",$title)->with('result', $result);
		}
	}
	
	//alertSetting
	public function updateAlertSetting(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$orders_status = DB::table('alert_settings')->where('alert_id','=', $request->alert_id)->update([
				'create_customer_email'				=>	$request->create_customer_email,
				'create_customer_notification'		=>	$request->create_customer_notification,
				'order_status_email'				=>	$request->order_status_email,
				'order_status_notification'			=>	$request->order_status_notification,
				'new_product_email'					=>	$request->new_product_email,
				'new_product_notification'			=>	$request->new_product_notification,
				'forgot_email'						=>	$request->forgot_email,
				'forgot_notification'				=>  $request->forgot_notification,
				'contact_us_email'					=>	$request->contact_us_email,
				'news_email'						=>	$request->news_email,
				'news_notification'					=>	$request->news_notification,
				'order_email'						=>	$request->order_email,
				'order_notification'				=>	$request->order_notification,
				]);
		
		$message = Lang::get("labels.alertSettingUpdateMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
		
	//generateKey
	public function generateKey(Request $request){
		$result = array();
		$result['consumerKey'] = $this->getKey();
		$result['consumerSecret'] = $this->getKey();	
		
		DB::table('settings')->where('name','=', 'consumer_key')->update([
				'value'			=>	$result['consumerKey'],
				'updated_at'	=>	date('Y-m-d h:i:s')
				]);		
				
		DB::table('settings')->where('name','=', 'consumer_secret')->update([
				'value'			=>	$result['consumerSecret'],
				'updated_at'	=>	date('Y-m-d h:i:s')
				]);	
					
		return $result; 
	}
	
	public function getKey(){
		$start = substr(md5(uniqid(mt_rand(), true)) , 0, 8);	
		$middle = time();		
		$end = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
		return $start.$middle.$end;
	}
	
	//websiteThemes
	public function webthemes(Request $request){	
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		
		$title = array('pageTitle' => Lang::get("labels.themes setting"));			
		$result = array();		
		$setting = DB::table('settings')->get();		
		$result['settings'] = $setting;	
		return view("admin.webthemes",$title)->with('result', $result);	
		}
	}
	
	//update Website Theme	
	public function updateWebTheme(Request $request){	
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		$chkAlreadyApplied = DB::table('settings')->where([['name','website_themes'],['value',$request->theme_id]])->get();
		
		if(count($chkAlreadyApplied)==0){
			$setting = DB::table('settings')->where('name','website_themes')->update(['value'=>$request->theme_id]);
			print 'success';
		}else{
			print 'already';
		}
		}
	}
	
	//pushNotification
	public function pushNotification(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{		
		$title = array('pageTitle' => Lang::get("labels.pushNotification"));			
		$result = array();		
		$settings = DB::table('settings')->get();		
		$result['settings'] = $settings;	
		return view("admin.pushNotification",$title)->with('result', $result);	
		}
	}
	
	
	//orderstatus
	public function units(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.ListingUnits"));		
		
		$result = array();
		
		$units = DB::table('units')->paginate(60);
		
		$result['units'] = $units;
		
		return view("admin.units",$title)->with('result', $result);
		}
	}
	
	//addunit
	public function addunit(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddUnit"));
		$result = array();		
		
		return view("admin.addunit",$title)->with('result', $result);
		}
	}
		
	//addnewunit	
	public function addnewunit(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{		
		DB::table('units')->insertGetId([
				'unit_name'		=>	$request->unit_name,
				'is_active'		=>	$request->is_active
				]);
								
		$message = Lang::get("labels.UnitAddedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//editunit
	public function editunit(Request $request){	
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		$title = array('pageTitle' => Lang::get("labels.EditUnit"));
		$result = array();		
		
		$units = DB::table('units')->where('unit_id','=', $request->id)->get();
			
		$result['units'] = $units;			
		return view("admin.editunit",$title)->with('result', $result);
		}
	}
	
	//updateunit	
	public function updateunit(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$orders_status = DB::table('units')->where('unit_id','=', $request->id)->update([
				'unit_name'		=>	$request->unit_name,
				'is_active'		=>	$request->is_active
				]);
		
		$message = Lang::get("labels.UnitUpdatedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//deleteunit
	public function deleteunit(Request $request){
		if(session('general_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('units')->where('unit_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.UnitDeletedMessage")]);
		}
	}	
}
