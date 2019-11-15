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


class AdminCouponsController extends Controller
{	
	//listing coupons
	public function coupons(Request $request){
		if(session('coupons_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.ListingCoupons"));		
		
		$result = array();
		$message = array();
			
		$coupons = DB::table('coupons')
					->orderBy('date_created', 'DESC')
					->paginate(40);
					
		$result['coupons'] = $coupons;
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['currency'] = $myVar->getSetting();
		
		return view("admin.coupons", $title)->with('result', $result);
		}
	}
	
	//add coupons
	public function addcoupons(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.AddCoupon"));
		
		$result = array();
		$message = array();
		$result['message'] = $message;
		
		$emails = DB::table('customers')->select('email')->get();
		$result['emails'] = $emails;
		
		$products = DB::table('products')
			->LeftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
			->select('products_name', 'products.products_id', 'products.products_model')->get();
		$result['products'] = $products;
		
		$categories = DB::table('categories')
			->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
			->select('categories_name', 'categories.categories_id')
			->where('parent_id', '>', '0')
			->get();
		$result['categories'] = $categories;
		
		return view("admin.addcoupons", $title)->with('result', $result);
	}
	
	//addNewcoupons	
	public function addnewcoupons(Request $request){
		
		if(session('coupons_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
				
		if(!empty($request->free_shipping)){
			$free_shipping = $request->free_shipping;
		}else{
			$free_shipping = '0';
		}
		
		$code = $request->code;
		$description = $request->description;
		$discount_type = $request->discount_type;
		$amount = $request->amount;
				
		$date = str_replace('/', '-', $request->expiry_date);
		$expiry_date = date('Y-m-d', strtotime($date));
		 
		if(!empty($request->individual_use)){
			$individual_use = $request->individual_use;
		}else{
			$individual_use = 0;
		}
			
		//include products
		if(!empty($request->product_ids)){
			$product_ids = implode(',',$request->product_ids);
		}else{
			$product_ids = '';
		}
		
		if(!empty($request->exclude_product_ids)){
			$exclude_product_ids = implode(',',$request->exclude_product_ids);
		}else{
			$exclude_product_ids = '';
		}
		
		
		//limit
		$usage_limit = $request->usage_limit;
		$usage_limit_per_user = $request->usage_limit_per_user;
		
		//$limit_usage_to_x_items = $request->limit_usage_to_x_items;
		
		if(!empty($request->product_categories)){
			$product_categories = implode(',',$request->product_categories);
		}else{
			$product_categories = '';
		}
		
		if(!empty($request->excluded_product_categories)){
			$excluded_product_categories = implode(',',$request->excluded_product_categories);
		}else{
			$excluded_product_categories = '';
		}
		
		if(!empty($request->exclude_sale_items)){
			$exclude_sale_items = $request->exclude_sale_items;
		}else{
			$exclude_sale_items = 0;
		}
		
		if(!empty($request->email_restrictions)){
			$email_restrictions = implode(',',$request->email_restrictions);
		}else{
			$email_restrictions = '';
		}
		
		$minimum_amount = $request->minimum_amount;
		$maximum_amount = $request->maximum_amount;		
		
		
		$validator = Validator::make(
			array(
					'code'    => $request->code,
				), 
			array(
					'code'    => 'required',
				)
		);
		//check validation
		if($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput();
		}else{
			
			//check coupon already exist
			$couponInfo = DB::table('coupons')->where('code','=', $code)->get();
			if(count($couponInfo)>0) {	
				return redirect()->back()->withErrors(Lang::get("labels.CouponAlreadyError"))->withInput();
			}else if(empty($code)){
				return redirect()->back()->withErrors(Lang::get("labels.EnterCoupon"))->withInput();
			}else{
				
				//insert record
				$coupon_id = DB::table('coupons')->insertGetId([
					'code'  	 				 =>   $code,
					'date_created'				 =>   date('Y-m-d H:i:s'),
					'description'				 =>   $description,
					'discount_type'	 			 =>   $discount_type,
					'amount'	 	 			 =>   $amount,
					'individual_use'	 		 =>   $individual_use,
					'product_ids'	 			 =>   $product_ids,
					'exclude_product_ids'		 =>   $exclude_product_ids,
					'usage_limit'	 			 =>   $usage_limit,
					'usage_limit_per_user'	 	 =>   $usage_limit_per_user,
					//'limit_usage_to_x_items'	 =>   $limit_usage_to_x_items,
					'product_categories'	 	 =>   $product_categories,
					'excluded_product_categories'=>   $excluded_product_categories,
					'exclude_sale_items'		 =>   $exclude_sale_items,
					'email_restrictions'	 	 =>   $email_restrictions,
					'minimum_amount'	 		 =>   $minimum_amount,
					'maximum_amount'	 		 =>   $maximum_amount,
					'expiry_date'				 =>	  $expiry_date,
					'free_shipping'				 =>   $free_shipping
					]);
				
				return redirect('admin/addcoupons')->with('success', Lang::get("labels.CouponAddedMessage"));
			}
		}
		}
	}
	
	//editcoupons
	public function editcoupons(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.EditCoupon"));
		
		$result = array();
		$message = array();
		$result['message'] = $message;
		
		//coupon
		$coupon = DB::table('coupons')->where('coupans_id', '=', $request->id)->get();
		$result['coupon'] = $coupon;
						
		$emails = DB::table('customers')->select('email')->get();
		$result['emails'] = $emails;
		
		$products = DB::table('products')
			->LeftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
			->select('products_name', 'products.products_id', 'products.products_model')->get();
		$result['products'] = $products;
		
		$categories = DB::table('categories')
			->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
			->select('categories_name', 'categories.categories_id')
			->where('parent_id', '>', '0')
			->get();
		$result['categories'] = $categories;
		
		return view("admin.editcoupons", $title)->with('result', $result);
	}
	
	//updateCoupons	
	public function updatecoupons(Request $request){
		if(session('coupons_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$coupans_id = $request->id;
		
		if(!empty($request->free_shipping)){
			$free_shipping = $request->free_shipping;
		}else{
			$free_shipping = '0';
		}
		
		$code = $request->code;
		$description = $request->description;
		$discount_type = $request->discount_type;
		$amount = $request->amount;
		
		$date = str_replace('/', '-', $request->expiry_date);
		$expiry_date = date('Y-m-d', strtotime($date));
		
		if(!empty($request->individual_use)){
			$individual_use = $request->individual_use;
		}else{
			$individual_use = '';
		}
			
		//include products
		if(!empty($request->product_ids)){
			$product_ids = implode(',',$request->product_ids);
		}else{
			$product_ids = '';
		}
		
		if(!empty($request->exclude_product_ids)){
			$exclude_product_ids = implode(',',$request->exclude_product_ids);
		}else{
			$exclude_product_ids = '';
		}
		
		
		//limit
		$usage_limit = $request->usage_limit;
		$usage_limit_per_user = $request->usage_limit_per_user;
		
		//$limit_usage_to_x_items = $request->limit_usage_to_x_items;
		
		if(!empty($request->product_categories)){
			$product_categories = implode(',',$request->product_categories);
		}else{
			$product_categories = '';
		}
		
		if(!empty($request->excluded_product_categories)){
			$excluded_product_categories = implode(',',$request->excluded_product_categories);
		}else{
			$excluded_product_categories = '';
		}
		
//		if(!empty($request->exclude_sale_items)){
//			$exclude_sale_items = $request->exclude_sale_items;
//		}else{
//			$exclude_sale_items = '';
//		}
		
		if(!empty($request->email_restrictions)){
			$email_restrictions = implode(',',$request->email_restrictions);
		}else{
			$email_restrictions = '';
		}
		
		$minimum_amount = $request->minimum_amount;
		$maximum_amount = $request->maximum_amount;		
		
		
		$validator = Validator::make(
			array(
					'code'    => $request->code,
				), 
			array(
					'code'    => 'required',
				)
		);
		//check validation
		if($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput();
		}else{
			
			//check coupon already exist
			$couponInfo = DB::table('coupons')->where('code','=', $code)->get();
			if(count($couponInfo)>1) {	
				return redirect()->back()->withErrors(Lang::get("labels.CouponAlreadyError"))->withInput();
			}else if(empty($code)){
				return redirect()->back()->withErrors(Lang::get("labels.EnterCoupon"))->withInput();
			}else{
				
				//insert record
				$coupon_id = DB::table('coupons')->where('coupans_id', '=', $coupans_id)->update([
					'code'  	 				 =>   $code,
					'date_modified'				 =>   date('Y-m-d H:i:s'),
					'description'				 =>   $description,
					'discount_type'	 			 =>   $discount_type,
					'amount'	 	 			 =>   $amount,
					'individual_use'	 		 =>   $individual_use,
					'product_ids'	 			 =>   $product_ids,
					'exclude_product_ids'		 =>   $exclude_product_ids,
					'usage_limit'	 			 =>   $usage_limit,
					'usage_limit_per_user'	 	 =>   $usage_limit_per_user,
					//'limit_usage_to_x_items'	 =>   $limit_usage_to_x_items,
					'product_categories'	 	 =>   $product_categories,
					'excluded_product_categories'=>   $excluded_product_categories,
					'exclude_sale_items'		 =>   $request->exclude_sale_items,
					'email_restrictions'	 	 =>   $email_restrictions,
					'minimum_amount'	 		 =>   $minimum_amount,
					'maximum_amount'	 		 =>   $maximum_amount,
					'expiry_date'				 =>	  $expiry_date,
					'free_shipping'				 =>   $free_shipping
					]);
				
			$message = Lang::get("labels.CouponUpdatedMessage");
			return redirect()->back()->withErrors([$message]);
			}
			
		}
			
		}
										
	}
	
	//deleteTaxRate
	public function deletecoupon(Request $request){
		if(session('coupons_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		DB::table('coupons')->where('coupans_id', '=', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.CouponDeletedMessage")]);
		}
	}
	
	//get couponProducts
	public function couponProducts(Request $request){	
		
		$coupons = DB::table('products')->get();
					
		return $coupons;
	}
}
