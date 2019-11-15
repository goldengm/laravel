<?php
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

class AdminConstantController extends Controller
{
	
	//banners
	public function constantBanners(Request $request){
		if(session('application_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.ListingConstantBanners"));		
		
		$result = array();
		$message = array();
			
		$banners = DB::table('constant_banners')->join('languages','languages.languages_id','=','constant_banners.languages_id')->orderBy('constant_banners.banners_id','ASC')->paginate(20);
		
		$result['message'] = $message;
		$result['banners'] = $banners;
		
		return view("admin.constantbanners", $title)->with('result', $result);
		}
	}
	
	//addTaxClass
	public function addconstantbanner(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddConstantBanner"));
		
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
		
		return view("admin.addconstantbanner", $title)->with('result', $result);
	}
	
	//addNewZone	
	public function addNewConstantBanner(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$exist = DB::table('constant_banners')->where([
				'type'					 =>	  $request->type,
				'languages_id'			 =>	  $request->languages_id
				])->get();
				
		if(count($exist)>0){			
			return redirect()->back()->with('error', Lang::get("labels.constantBannerErrorMessage"));
		}else{
				
			$type = $request->type;
			
			//get function from other controller
			$myVar = new AdminSiteSettingController();
			$extensions = $myVar->imageType();	
			$setting = $myVar->getSetting();	
			
			if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
				$image = $request->newImage;
				$fileName = time().'.'.$image->getClientOriginalName();
				$image->move('resources/assets/images/constant_banners/', $fileName);
				$uploadImage = 'resources/assets/images/constant_banners/'.$fileName; 
			}else{
				$uploadImage = '';
			}
			
			
			DB::table('constant_banners')->insert([
					'banners_title'  		 =>   $request->type,
					'date_added'	 		 =>   date('Y-m-d H:i:s'),
					'banners_image'			 =>	  $uploadImage,
					'banners_url'	 		 =>   $request->banners_url,
					'status'	 			 =>   $request->status,
					'type'					 =>	  $request->type,
					'languages_id'			 =>	  $request->languages_id
					]);
			return redirect()->back()->with('success', Lang::get("labels.BannerAddedMessage"));
			}
		}
	}
	
	//editTaxClass
	public function editconstantbanner(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditBanner"));
		$result = array();		
		$result['message'] = array();
		
		$banners = DB::table('constant_banners')->where('banners_id', $request->id)->get();
		$result['banners'] = $banners;	
		
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
		
		return view("admin.editconstantbanner",$title)->with('result', $result);
	}
	
	//updateTaxClass
	public function updateconstantBanner(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
			$exist = DB::table('constant_banners')->where([
				'type'					 =>	  $request->type,
				'languages_id'			 =>	  $request->languages_id
				])->where('banners_id','!=',$request->id)->get();
				
			if(count($exist)>0){			
				return redirect()->back()->with('error', Lang::get("labels.constantBannerErrorMessage"));
			}else{
			
				
			$title = array('pageTitle' => Lang::get("labels.EditBanner"));
			
			
			$type = $request->type;
			
			//get function from other controller
			$myVar = new AdminSiteSettingController();
			$extensions = $myVar->imageType();	
			$setting = $myVar->getSetting();	
			
			if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
				$image = $request->newImage;
				$fileName = time().'.'.$image->getClientOriginalName();
				$image->move('resources/assets/images/constant_banners/', $fileName);
				$uploadImage = 'resources/assets/images/constant_banners/'.$fileName; 
			}else{
				$uploadImage = $request->oldImage;
			}
			
			if($type=='category'){
				$banners_url = $request->categories_id;
			}else if($type=='product'){
				$banners_url = $request->products_id;
			}else{
				$banners_url = '';
			}
			
			$countryData = array();		
					
			$countryUpdate = DB::table('constant_banners')->where('banners_id', $request->id)->update([
						'banners_title'  		 =>   $request->type,
						'date_added'	 		 =>   date('Y-m-d H:i:s'),
						'banners_image'			 =>	  $uploadImage,
						'banners_url'	 		 =>   $request->banners_url,
						'status'	 			 =>   $request->status,
						'type'					 =>	  $request->type,
						'languages_id'			 =>	  $request->languages_id
						]);
					
			return redirect()->back()->with('success', Lang::get("labels.BannerUpdatedMessage"));
			}
		}
	}
	
	//deleteCountry
	public function deleteconstantBanner(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('constant_banners')->where('banners_id', $request->banners_id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.BannerDeletedMessage")]);
		}
	}
}
