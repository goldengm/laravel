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
use App\Admin;

//for authenitcate login data
use Auth;



//for requesting a value 
use Illuminate\Http\Request;


class AdminSlidersController extends Controller
{
	
	//listingTaxClass
	public function sliders(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingSliders"));		
		
		$result = array();
		$message = array();		
			
		$banners = DB::table('sliders_images')->leftJoin('languages','languages.languages_id','=','sliders_images.languages_id')->orderBy('sliders_images.sliders_id','ASC')->paginate(20);
		
		$result['message'] = $message;
		$result['sliders'] = $banners;
		
		return view("admin.sliders", $title)->with('result', $result);
	}
	
	//addTaxClass
	public function addsliderimage(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddSliderImage"));
		
		$result = array();
		$message = array();
		
		//get function from other controller
		$myVar = new AdminCategoriesController();
		$categories = $myVar->getSubCategories(1);
		
		//get function from other controller
		$myVar = new AdminProductsController();
		$products = $myVar->getProducts(null);		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$result['message'] = $message;
		$result['categories'] = $categories;
		$result['products'] = $products;
		
		return view("admin.addsliderimage", $title)->with('result', $result);
	}
	
	//addNewZone	
	public function addNewSlide(Request $request){
		
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		$expiryDate = str_replace('/', '-', $request->expires_date);
		$expiryDateFormate = date('Y-m-d H:i:s', strtotime($expiryDate));
		$type = $request->type;
		
		if (!file_exists('resources/assets/images/slider_images/')) {
			mkdir('resources/assets/images/slider_images/', 0777, true);
		}
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/slider_images/', $fileName);
			$uploadImage = 'resources/assets/images/slider_images/'.$fileName; 
		}else{
			$uploadImage = '';
		}
		
		if($type=='category'){
			$sliders_url = $request->categories_id;
		}else if($type=='product'){
			$sliders_url = $request->products_id;
		}else{
			$sliders_url = '';
		}
		
		DB::table('sliders_images')->insert([
				'sliders_title'  		 =>   $request->sliders_title,
				'date_added'	 		 =>   date('Y-m-d H:i:s'),
				'sliders_image'			 =>	  $uploadImage,
				'sliders_url'	 		 =>   $sliders_url,
				'status'	 			 =>   $request->status,
				'expires_date'			 =>	  $expiryDateFormate,
				'type'					 =>	  $request->type,
				'languages_id'			 =>	  $request->languages_id
				]);
										
		$message = Lang::get("labels.SliderAddedMessage");
		return redirect()->back()->withErrors([$message]);
	}
	
	//editTaxClass
	public function editslide(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditSliderImage"));
		$result = array();		
		$result['message'] = array();
		
		$banners = DB::table('sliders_images')->where('sliders_id', $request->id)->get();
		$result['sliders'] = $banners;	
		
		//get function from other controller
		$myVar = new AdminCategoriesController();
		$categories = $myVar->getSubCategories(1);
		
		//get function from other controller
		$myVar = new AdminProductsController();
		$products = $myVar->getProducts(null);		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$result['categories'] = $categories;
		$result['products'] = $products;		
		
		return view("admin.editslide",$title)->with('result', $result);
	}
	
	//updateTaxClass
	public function updateSlide(Request $request){
		
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		$expiryDate = str_replace('/', '-', $request->expires_date);
		$expiryDateFormate = date('Y-m-d H:i:s', strtotime($expiryDate));
		$type = $request->type;
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/banner_images/', $fileName);
			$uploadImage = 'resources/assets/images/banner_images/'.$fileName; 
		}else{
			$uploadImage = $request->oldImage;
		}
		
		if($type=='category'){
			$sliders_url = $request->categories_id;
		}else if($type=='product'){
			$sliders_url = $request->products_id;
		}else{
			$sliders_url = '';
		}
		
		$countryData = array();		
		$message = Lang::get("labels.SliderUpdatedMessage");
				
		$countryUpdate = DB::table('sliders_images')->where('sliders_id', $request->id)->update([
					'date_status_change'	 =>   date('Y-m-d H:i:s'),
					'sliders_title'  		 =>   $request->sliders_title,
					'date_added'	 		 =>   date('Y-m-d H:i:s'),
					'sliders_image'			 =>	  $uploadImage,
					'sliders_url'	 		 =>   $sliders_url,
					'status'	 			 =>   $request->status,
					'expires_date'			 =>	  $expiryDateFormate,
					'type'					 =>	  $request->type,
					'languages_id'			 =>	  $request->languages_id
					]);
				
		return redirect()->back()->withErrors([$message ]);
	}
	
	//deleteCountry
	public function deleteSlider(Request $request){
		DB::table('sliders_images')->where('sliders_id', $request->sliders_id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.SliderDeletedMessage")]);
	}
}
