<?php
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


class AdminShippingByWeightController extends Controller
{	
	//shippingMethods
	public function shppingbyweight(Request $request){
		if(session('shipping_methods_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.shppingbyweight"));		
				
		$products_shipping = DB::table('products_shipping_rates')->where('products_shipping_status','1')->get();
		
		$result['products_shipping'] = $products_shipping;
					
		return view("admin.shppingbyweight", $title)->with('result', $result);
		}
	}
	
	//shippingMethods
	public function updateShppingWeightPrice(Request $request){
		if(session('shipping_methods_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		
		for($i=0; $i<=4; $i++){
			$weight_from  = 'weight_from_'.$i;
			$weight_to	  = 'weight_to_'.$i;
			$weight_price = 'weight_price_'.$i;
			
			print $request->$weight_from;
			$products_shipping_rates_id = $i+1;
			
			DB::table('products_shipping_rates')->where('products_shipping_rates_id', $products_shipping_rates_id)->update([
					'weight_from'	 =>   $request->$weight_from,
					'weight_to'		 =>   $request->$weight_to,
					'weight_price'	 =>   $request->$weight_price,
					]);
		}
							
		$message = Lang::get("labels.WeightPriceUpdatedSuccessMessage");
		return redirect()->back()->withErrors([$message]);
		
		}
	}
		
}
