<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 1.0
*/
namespace App\Http\Controllers\Web;
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
//for Carbon a value 
use Carbon;
use Session;
use Lang;
//email
use Illuminate\Support\Facades\Mail;

class ProductsController extends DataController
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
			
	//shop 
	public function shop(Request $request){
		
		$title = array('pageTitle' => Lang::get('website.Shop'));
		$result = array();
		
		$result['commonContent'] = $this->commonContent();
		if(!empty($request->page)){
			$page_number = $request->page;
		}else{
			$page_number = 0;
		}
		
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}
		
		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
		
		//category		
		if(!empty($request->category) and $request->category!='all'){
			$category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories_slug',$request->category)->where('language_id',Session::get('language_id'))->get();
			
			$categories_id = $category[0]->categories_id;
			//for main
			if($category[0]->parent_id==0){
				$category_name = $category[0]->categories_name;
				$sub_category_name = '';
				$category_slug = '';
			}else{
			//for sub
				$main_category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories.categories_id',$category[0]->parent_id)->where('language_id',Session::get('language_id'))->get();
				
				$category_slug = $main_category[0]->categories_slug;
				$category_name = $main_category[0]->categories_name;
				$sub_category_name = $category[0]->categories_name;
			}
			
		}else{
			$categories_id = '';
			$category_name = '';
			$sub_category_name = '';
			$category_slug = '';
		}
		
		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		 
		//search value
		if(!empty($request->search)){
			$search = $request->search;
		}else{
			$search = '';
		}	
		
		
		$filters = array();
		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$index = 0;
			$options = array();
			$option_values = array();
			
			$option = DB::table('products_options')
						->leftJoin('products_options_descriptions', 'products_options_descriptions.products_options_id', '=', 'products_options.products_options_id')->select('products_options.products_options_id', 'products_options_descriptions.options_name as products_options_name', 'products_options_descriptions.language_id')->where('language_id','=', Session::get('language_id'))->get();
						
			
										
			foreach($option as $key=>$options_data){				
				$option_name = str_replace(' ','_',$options_data->products_options_name);
				
				if(!empty($request->$option_name)){
					$index2 = 0;
					$values = array();
					foreach($request->$option_name as $value)
					{
						$value = DB::table('products_options_values')
									->leftJoin('products_options_values_descriptions','products_options_values_descriptions.products_options_values_id','=','products_options_values.products_options_values_id')
									->select('products_options_values.products_options_values_id', 'products_options_values_descriptions.options_values_name as products_options_values_name', 'products_options_values_descriptions.language_id')
									->where('products_options_values_descriptions.options_values_name', $value)->where('language_id',Session::get('language_id'))->get();
						$option_values[]=$value[0]->products_options_values_id;
					}
					$options[] = $options_data->products_options_id;
				}					
			}
			
			
			$filters['options_count'] = count($options);
			
			$filters['options'] = implode($options,',');
			$filters['option_value'] = implode($option_values, ',');
			
                        $filters['filter_attribute']['options'] = $options;
			$filters['filter_attribute']['option_values'] = $option_values;

                        $result['filter_attribute']['options'] = $options;
			$result['filter_attribute']['option_values'] = $option_values;
		}
		
		$myVar = new DataController();	
		$data = array('page_number'=>$page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );	
		
		$products = $myVar->products($data);
		$result['products'] = $products;
		
		$data = array('limit'=>$limit, 'categories_id'=>$categories_id );
		$filters = $this->filters($data);
		$result['filters'] = $filters;
		
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);		
		
		if($limit > $result['products']['total_record']){		
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}
		
		//liked products
		$result['liked_products'] = $this->likedProducts();	
		return view("shop", $title)->with('result', $result); 
		
	}
	
	//access object for custom pagination
	function accessObjectArray($var){
	  return $var;
	}

	//productDetail 
	public function productDetail(Request $request){
		
		$title 			= 	array('pageTitle' => Lang::get('website.Product Detail'));
		$result 		= 	array();
		$result['commonContent'] = $this->commonContent();
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
				
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}
		
		$products = DB::table('products')->where('products_slug',$request->slug)->get();
		
		//category		
		$category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->leftJoin('products_to_categories','products_to_categories.categories_id','=','categories.categories_id')->where('products_to_categories.products_id',$products[0]->products_id)->where('categories.parent_id',0)->where('language_id',Session::get('language_id'))->get();
		
		if(!empty($category) and count($category)>0){
			$category_slug = $category[0]->categories_slug;
			$category_name = $category[0]->categories_name;
		}else{
			$category_slug = '';
			$category_name = '';
		}
		$sub_category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->leftJoin('products_to_categories','products_to_categories.categories_id','=','categories.categories_id')->where('products_to_categories.products_id',$products[0]->products_id)->where('categories.parent_id','>',0)->where('language_id',Session::get('language_id'))->get();
		
		if(!empty($sub_category) and count($sub_category)>0){
			$sub_category_name = $sub_category[0]->categories_name;
			$sub_category_slug = $sub_category[0]->categories_slug;		
		}else{
			$sub_category_name = '';
			$sub_category_slug = '';	
		}
		
		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		$result['sub_category_slug'] = $sub_category_slug;
		
		$isFlash = DB::table('flash_sale')->where('products_id',$products[0]->products_id)
					->where('flash_expires_date','>=',  time())->where('flash_status','=',  1)
					->get();
		
		if(!empty($isFlash) and count($isFlash)>0){
			$type = "flashsale";
		}else{
			$type = "";
		}		


		$isAuction = DB::table('auctions')->where('products_id',$products[0]->products_id)
					->where('auction_expires_date','>=',  time())->where('auction_status','=',  1)
					->get();
		
		if(!empty($isAuction) and count($isAuction)>0){
			$type = "auction";
		}else{
			$type = "";
		}		
				
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>$type, 'products_id'=>$products[0]->products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$detail = $myVar->products($data);
		$result['detail'] = $detail;
		$postCategoryId = array();
		$i = 0;
		foreach($result['detail']['product_data'][0]->categories as $postCategory){
			if($i==0){
				$postCategoryId = $postCategory->categories_id;
				$i++;
			}
		}
				
		$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$postCategoryId, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$simliar_products = $myVar->products($data);
		$result['simliar_products'] = $simliar_products;
		
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);
		
		//liked products
		$result['liked_products'] = $this->likedProducts();	
		
		return view("product-detail", $title)->with('result', $result); 
	}
	
	
	public function filterProducts(Request $request){
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
				
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}
		
		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}
		
		//if(!empty($request->category_id)){
		if(!empty($request->category) and $request->category!='all'){
			$category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories_slug',$request->category)->where('language_id',Session::get('language_id'))->get();
			
			$categories_id = $category[0]->categories_id;
		}else{
			$categories_id = '';
		}
		
		//search value
		if(!empty($request->search)){
			$search = $request->search;
		}else{
			$search = '';
		}
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
		
		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$filters['options_count'] = count($request->options_value);
			$filters['options'] = $request->options;
			$filters['option_value'] = $request->options_value;
		}else{
			$filters = array();
		}	
						
		$myVar = new DataController();
		$data = array('page_number'=>$request->page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$products = $myVar->products($data);
		$result['products'] = $products;	
			
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);
		$result['limit'] = $limit;
		return view("filterproducts")->with('result', $result);			
		
	}
	
	//filters
	public function filters($data){
		
		$categories_id      =   $data['categories_id'];				
		$currentDate		=	time();		
				
		$price = DB::table('products_to_categories')
						->join('products', 'products.products_id', '=', 'products_to_categories.products_id');
						if(isset($categories_id) and !empty($categories_id)){
							$price->where('products_to_categories.categories_id','=', $categories_id);
						}
						
		$priceContent 	=	$price->max('products_price');			
		if(!empty($priceContent)){
			$maxPrice = round($priceContent+1);	
		}else{
			$maxPrice = '';
		}
		
		$product = DB::table('products')
			//DB::table('products_to_categories')
			//->join('products', 'products.products_id', '=', 'products_to_categories.products_id')
			->leftJoin('products_description','products_description.products_id','=','products.products_id')
			->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
			->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
			->LeftJoin('specials', function ($join) use ($currentDate) {  
				$join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
			});
			
			if(isset($categories_id) and !empty($categories_id)){
			$product->LeftJoin('products_to_categories', 'products.products_id', '=', 'products_to_categories.products_id')->select('products_to_categories.*', 'products.*','products_description.*','manufacturers.*','manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price');
			}else{
				$product->select('products.*','products_description.*','manufacturers.*','manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price');
			}
				$product->where('products_description.language_id','=', Session::get('language_id'));
			
			if(isset($categories_id) and !empty($categories_id)){
				$product->where('products_to_categories.categories_id','=', $categories_id);
			}
			
		$products = $product->get();
		
		$index = 0;
		$optionsIdArrays = array();
		$valueIdArray = array();
		foreach($products as $products_data){
			$option_name = DB::table('products_attributes')->where('products_id', '=', $products_data->products_id)->get();
			foreach($option_name as $option_data){
				
				if(!in_array($option_data->options_id, $optionsIdArrays)){
					$optionsIdArrays[] = $option_data->options_id;
				}
				
				if(!in_array($option_data->options_values_id, $valueIdArray)){
					$valueIdArray[] = $option_data->options_values_id;
				}
			}
		}
				
		if(!empty($optionsIdArrays)){
			
			$index3 = 0;
			$result = array();
			foreach($optionsIdArrays as $optionsIdArray){
				$option_name = DB::table('products_options')
										->leftJoin('products_options_descriptions', 'products_options_descriptions.products_options_id', '=', 'products_options.products_options_id')->select('products_options.products_options_id', 'products_options_descriptions.options_name as products_options_name', 'products_options_descriptions.language_id')->where('language_id','=', Session::get('language_id'))->where('products_options.products_options_id','=', $optionsIdArray)->get();
				
				if(count($option_name)>0){
					$attribute_opt_val = DB::table('products_options_values')->where('products_options_id', $optionsIdArray)->get();			
					if(count($attribute_opt_val)>0){
						$temp = array();
						$temp_name['name'] = $option_name[0]->products_options_name;
						$attr[$index3]['option'] = $temp_name;
						
						foreach($attribute_opt_val as $attribute_opt_val_data){
						
							$attribute_value = DB::table('products_options_values')
									->leftJoin('products_options_values_descriptions','products_options_values_descriptions.products_options_values_id','=','products_options_values.products_options_values_id')
									->select('products_options_values.products_options_values_id', 'products_options_values_descriptions.options_values_name as products_options_values_name', 'products_options_values_descriptions.language_id')
									->where('products_options_values.products_options_values_id', $attribute_opt_val_data->products_options_values_id )->where('language_id',Session::get('language_id'))->get();
							
							foreach($attribute_value as $attribute_value_data){
								
								if(in_array($attribute_value_data->products_options_values_id,$valueIdArray)){
									$temp_value['value'] = $attribute_value_data->products_options_values_name;
									$temp_value['value_id'] = $attribute_value_data->products_options_values_id;
									
									array_push($temp, $temp_value);
								}
							}
								$attr[$index3]['values'] = $temp;
						}
						$index3++;
					}
					$response = array('success'=>'1', 'attr_data'=>$attr, 'message'=> Lang::get('website.Returned all filters successfully'), 'maxPrice'=>$maxPrice);
				}else{
					$response = array('success'=>'0', 'attr_data'=>array(), 'message'=> Lang::get('website.Filter is empty for this category'), 'maxPrice'=>$maxPrice);
				}
			
			}
			
		}else{
			$response = array('success'=>'0', 'attr_data'=>array(), 'message'=>Lang::get('website.Filter is empty for this category'), 'maxPrice'=>$maxPrice);
		}
		
		return($response);
		}
	
	//getquantity
	public function getquantity(Request $request){
		$data = array();
		$data['products_id'] = $request->products_id;
		$data['attributes'] = $request->attributeid;
		
		$result = $this->productQuantity($data);
		print_r(json_encode($result));
	}
		
	//currentstock
	function productQuantity($data){
		
		if(!empty($data['attributes'])){
			
		$inventory_ref_id = '';
		$products_id = $data['products_id'];
		$attributes = array_filter($data['attributes']);
		$attributeid = implode(',',$attributes);
		$postAttributes = count($attributes);
				
		$inventories = DB::table('inventory')->where('products_id',$products_id)->get();
		
		$reference_ids = array();
		$stockIn = 0;
		$stockOut = 0;
		$inventory_ref_id = array();
		foreach($inventories as $inventory){
			
			$totalAttribute = DB::table('inventory_detail')->where('inventory_detail.inventory_ref_id','=',$inventory->inventory_ref_id)->get();
			$totalAttributes = count($totalAttribute);
			
			if($postAttributes>$totalAttributes){
				$count = $postAttributes;
			}elseif($postAttributes<$totalAttributes or $postAttributes==$totalAttributes){
				$count = $totalAttributes;
			}			
			
			$individualStock = DB::table('inventory')->leftjoin('inventory_detail','inventory_detail.inventory_ref_id','=','inventory.inventory_ref_id')
				->selectRaw('inventory.*')
				->whereIn('inventory_detail.attribute_id', [$attributeid])
				->where(DB::raw('(select count(*) from `inventory_detail` where `inventory_detail`.`attribute_id` in ('.$attributeid.') and `inventory_ref_id`= "'.$inventory->inventory_ref_id.'")'),'=',$count)
				->where('inventory.inventory_ref_id','=',$inventory->inventory_ref_id)				
				->groupBy('inventory_detail.inventory_ref_id')
				->get();		
					
			if(count($individualStock)>0){	
			
				if($individualStock[0]->stock_type=='in'){
					$stockIn += $individualStock[0]->stock;
				}
				
				if($individualStock[0]->stock_type=='out'){
					$stockOut += $individualStock[0]->stock;
				}
							
				$inventory_ref_id[] = $individualStock[0]->inventory_ref_id;
			}
			
		}
			
		//get option name and value
		$options_names  = array();
		$options_values = array(); 
		foreach($attributes as $attribute){
			$productsAttributes = DB::table('products_attributes')
					->leftJoin('products_options','products_options.products_options_id','=','products_attributes.options_id')
					->leftJoin('products_options_values','products_options_values.products_options_values_id','=','products_attributes.options_values_id')
					->select('products_attributes.*', 'products_options.products_options_name as options_name', 'products_options_values.products_options_values_name as options_values')
					->where('products_attributes_id',$attribute)->get();
			
			
			$options_names[] = $productsAttributes[0]->options_name;
			$options_values[] = $productsAttributes[0]->options_values;
		}
		
		$options_names_count = count($options_names);
		$options_names = implode ( "','", $options_names);
		$options_names = "'" . $options_names . "'";
		$options_values = "'" . implode ( "','", $options_values ) . "'";
				
		//orders products
		$orders_products = DB::table('orders_products')->where('products_id',$products_id)->get();
		//$stockOut = 0;
		/*foreach($orders_products as $orders_product){
			$totalAttribute = DB::table('orders_products_attributes')->where('orders_products_id','=',$orders_product->orders_products_id)->get();
			$totalAttributes = count($totalAttribute);
			
			if($postAttributes>$totalAttributes){
				$count = $postAttributes;
			}elseif($postAttributes<$totalAttributes or $postAttributes==$totalAttributes){
				$count = $totalAttributes;
			}	
						
			$products = DB::select("select orders_products.* from `orders_products` left join `orders_products_attributes` on `orders_products_attributes`.`orders_products_id` = `orders_products`.`orders_products_id` where `orders_products`.`products_id`='".$products_id."' and `orders_products_attributes`.`products_options` in (".$options_names.") and `orders_products_attributes`.`products_options_values` in (".$options_values.") and (select count(*) from `orders_products_attributes` where `orders_products_attributes`.`products_id` = '".$products_id."' and `orders_products_attributes`.`products_options` in (".$options_names.") and `orders_products_attributes`.`products_options_values` in (".$options_values.") and `orders_products_attributes`.`orders_products_id`= '".$orders_product->orders_products_id."') = ".$count." and `orders_products`.`orders_products_id` = '".$orders_product->orders_products_id."' group by `orders_products_attributes`.`orders_products_id`");
			
			if(count($products)>0){
				$stockOut += $products[0]->products_quantity;
			}			
		}*/
		
		$result = array();
		$result['remainingStock'] = $stockIn - $stockOut;
		
		if(!empty($inventory_ref_id)){
		$inventory_ref_id = implode(',',$inventory_ref_id);
		 $minMax = DB::table('manage_min_max')->where([['inventory_ref_id', $inventory_ref_id],['products_id',$products_id]])->get();		
		}else{
			$minMax = '';
		}
		
		$result['inventory_ref_id'] = $inventory_ref_id;		
		$result['minMax'] = $minMax;
		
		}else{
			$result['inventory_ref_id'] = 0;		
			$result['minMax'] = 0;
			$result['remainingStock'] = 0;
		}
		
		return $result;
	}
	
}
