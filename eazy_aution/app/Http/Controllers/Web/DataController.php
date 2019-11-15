<?php
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
//for Carbon a value 
use Carbon;

//email
use Illuminate\Support\Facades\Mail;
use Session;
use Lang;

class DataController extends Controller
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
	
	public function commonContent(){
		
		$languages = DB::table('languages')->where('is_default','1')->get();
		
		if(empty(Session::get('language_id'))){
			session(['language_id' => $languages[0]->languages_id]);
		}
		
		$setting = DB::table('settings')->get();
		//
		if(!empty($setting[80]->value)){
			session(['homeStyle' => $setting[80]->value]);
		}
		
		if(!empty($setting[81]->value)){
			session(['theme' => $setting[81]->value]);	
		}
		
		
		$result = array();		
		
		$data = array('type'=>'header');
		$myVar = new CartController();
		
		$cart = $myVar->cart($data);		
		$result['cart'] = $cart;
				
		if(count($result['cart'])==0){
			session(['step' => '0']);
			session(['coupon' => array()]);	
			session(['coupon_discount' => array()]);
			session(['billing_address' => array()]);
			session(['shipping_detail' => array()]);
			session(['payment_method' => array()]);
			session(['braintree_token' => array()]);
			session(['order_comments' => '']);
		}
		
		//produt categories
		$result['categories'] = $this->categories();
		
		//news categories
		$newsCategories = new NewsController();
		$result['newsCategories'] = $newsCategories->getNewsCategories();
		
		 
		$popularCategories = DB::table('orders_products')
			->leftJoin('products_to_categories', function($join){
				$join->on('products_to_categories.products_id','=','orders_products.products_id');
			})
			->leftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
			->select('categories_description.categories_name as name','orders_products.products_id','products_to_categories.categories_id as id', DB::raw('COUNT(orders_products.products_id) as count'))->where('categories_description.language_id',session('language_id'))->groupby('products_to_categories.categories_id')->orderby('count', 'DESC')->get();
		
		$popularCategories = $popularCategories->toArray();
				
		if(count($popularCategories)>0){
			$counter = 0;
			$categoriesContent = array();
			foreach($popularCategories as $categories_data){
				if($counter<=9)	{
					$categoriesContent[$counter]['id']   = $categories_data->id;
					$categoriesContent[$counter]['name'] = $categories_data->name;
				}
				$counter++;
			}
			
		}else{
			$counter = 0;
			$categoriesContent = array();
			foreach($result['categories'] as $categories_data){
				if(count($categories_data->sub_categories)>0){
					foreach($categories_data->sub_categories as $key=>$sub_categories_data){
						if($counter<=9)	{
							$categoriesContent[$counter]['id']   = $sub_categories_data->sub_id;
							$categoriesContent[$counter]['name'] = $sub_categories_data->sub_name;
						}
						$counter++;
					}
				}	
			}
		}
		
		$result['popularCategories'] = $categoriesContent;		
		$result['setting'] = DB::table('settings')->get();
		
		
		$result['pages'] = DB::table('pages')
							->leftJoin('pages_description', 'pages_description.page_id', '=', 'pages.page_id')
							->where([['type','2'],['status','1'],['pages_description.language_id',session('language_id')]])->orderBy('pages_description.name', 'ASC')->get();
		
		if(!empty(session('customers_id'))){						
			$wishlist = DB::table('liked_products')->where([
					'liked_customers_id' => session('customers_id')
				])->get();
			$result['totalWishList'] = count($wishlist); 	
		}else{
			$result['totalWishList']=0;
		}
		
		//recent product
		$data = array('page_number'=>0, 'type'=>'', 'limit'=>5, 'categories_id'=>'', 'search'=>'', 'min_price'=>'', 'max_price'=>'' );			
		$products = $this->products($data);
		$result['recentProducts'] = $products;
		
		$myVar 		  = new NewsController();
		$data 		  = array('page_number'=>0, 'type'=>'', 'is_feature'=>'1', 'limit'=>5, 'categories_id'=>'', 'load_news'=>0);		
		$featuredNews = $myVar->getAllNews($data);		
		$result['featuredNews'] = $featuredNews;
		
		$todayDate = time();
		
		//home banners 
		
		$homeBanners = DB::table('constant_banners')->where('languages_id', session('language_id'))->orderby('type','ASC')->get();
		
		$result['homeBanners'] = $homeBanners;
		
		return ($result);
	}
	
	//categories 
	public function categories(){
				
		$result 	= 	array();
		
		$categories = DB::table('categories')
			->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
			->select('categories.categories_id as id',
				 'categories.categories_image as image',
				 'categories.categories_icon as icon',
				 'categories.sort_order as order',				 
				 'categories.categories_slug as slug',
				 'categories.parent_id',
				 'categories_description.categories_name as name'
				 )
			->where('categories_description.language_id','=', Session::get('language_id'))
			->where('parent_id','0')
			->get();
		
		$index = 0;
		foreach($categories as $categories_data){
			$categories_id = $categories_data->id;
			
			$products = DB::table('categories')
					->LeftJoin('categories as sub_categories', 'sub_categories.parent_id', '=', 'categories.categories_id')
					->LeftJoin('products_to_categories', 'products_to_categories.categories_id', '=', 'sub_categories.categories_id')
					->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
					->select('categories.categories_id', DB::raw('COUNT(DISTINCT products.products_id) as total_products'))
					->where('categories.categories_id','=', $categories_id)
					->get();
			
			$categories_data->total_products = $products[0]->total_products;
			array_push($result,$categories_data);						
			
			$sub_categories = DB::table('categories')
				->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
				->select('categories.categories_id as sub_id',
					 'categories.categories_image as sub_image',
					 'categories.categories_icon as sub_icon',
					 'categories.sort_order as sub_order',				 
				 	'categories.categories_slug as sub_slug',
					 'categories.parent_id',
					 'categories_description.categories_name as sub_name'
					 )
				->where('categories_description.language_id','=', Session::get('language_id'))
				->where('parent_id',$categories_id)
				->get();
			
			$data = array();
			$index2 = 0; 
			foreach($sub_categories as $sub_categories_data){
				$sub_categories_id = $sub_categories_data->sub_id;
								
				$individual_products = DB::table('products_to_categories')
					->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
					->select('products_to_categories.categories_id', DB::raw('COUNT(DISTINCT products.products_id) as total_products'))
					->where('products_to_categories.categories_id','=', $sub_categories_id)
					->get();
			
				$sub_categories_data->total_products = $individual_products[0]->total_products;
				$data[$index2++] = $sub_categories_data;				
			
			}
			
			$result[$index++]->sub_categories = $data;
			
		}		
		return($result);		
		
	}
	
	
	public function getSession(){
		return Session::getId();
	}
	
	//products 
	public function products($data){
		
		if(empty($data['page_number']) or $data['page_number'] == 0 ){
			$skip								=   $data['page_number'].'0';
		}else{
			$skip								=   $data['limit']*$data['page_number'];
		}		
		
		$min_price	 							=   $data['min_price'];	
		$max_price	 							=   $data['max_price'];	
		$take									=   $data['limit'];
		$currentDate 							=   time();	
		$type									=	$data['type'];
		
		if($type=="atoz"){
			$sortby								=	"products_name";
			$order								=	"ASC";
		}elseif($type=="ztoa"){
			$sortby								=	"products_name";
			$order								=	"DESC";
		}elseif($type=="hightolow"){
			$sortby								=	"products_price";
			$order								=	"DESC";
		}elseif($type=="lowtohigh"){
			$sortby								=	"products_price";
			$order								=	"ASC";
		}elseif($type=="topseller"){
			$sortby								=	"products_ordered";
			$order								=	"DESC";
		}elseif($type=="mostliked"){
			$sortby								=	"products_liked";
			$order								=	"DESC";
			
		}elseif($type == "special"){ 
			$sortby = "specials.products_id";
			$order = "desc";
		}elseif($type == "flashsale"){ //flashsale products
			$sortby = "flash_sale.flash_start_date";
			$order = "asc";
		}elseif($type == "auctions"){ //auctions products
			$sortby = "auctions.auction_date";
			$order = "asc";
		}else{
			$sortby = "products.products_id";
			$order = "desc";
		}	
				
		$filterProducts = array();
		$eliminateRecord = array();
			
			$categories = DB::table('products')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id');
				
			if(!empty($data['categories_id'])){
				$categories->LeftJoin('products_to_categories', 'products.products_id', '=', 'products_to_categories.products_id')
						->leftJoin('categories','categories.categories_id','=','products_to_categories.categories_id')
						->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id');
			}
			
			
			if(!empty($data['filters']) and empty($data['search'])){			
				$categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id');
			}
			
			if(!empty($data['search'])){
				$categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id')
					->leftJoin('products_options','products_options.products_options_id','=','products_attributes.options_id')
					->leftJoin('products_options_values','products_options_values.products_options_values_id','=','products_attributes.options_values_id');
			}
			//wishlist customer id
			if($type == "wishlist"){
				$categories->LeftJoin('liked_products', 'liked_products.liked_products_id', '=', 'products.products_id');
			}			
			//parameter special
			elseif($type == "special"){
				$categories->LeftJoin('specials', 'specials.products_id', '=', 'products.products_id')
					->select('products.*', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price');
			}
			elseif($type == "flashsale"){
				//flash sale				
				$categories->LeftJoin('flash_sale', 'flash_sale.products_id', '=', 'products.products_id')
				->select(DB::raw(time().' as server_time'),'products.*', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url','flash_sale.flash_start_date', 'flash_sale.flash_expires_date', 'flash_sale.flash_sale_products_price as flash_price');
				
			}
			elseif($type == "auctions"){
				//auctions				
				$categories->LeftJoin('auctions', 'auctions.products_id', '=', 'products.products_id')
				->select(DB::raw(time().' as server_time'),'products.*', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url','auctions.auction_start_date', 'auctions.auction_expires_date', 'auctions.auction_products_price as auction_price');
				
			}
			else{
				$categories->LeftJoin('specials', function ($join) use ($currentDate) {  
					$join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
				})->select('products.*','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price');
			}
			
			if($type == "special"){ //deals special products
				$categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);
			}
			
			if($type == "flashsale"){ //flashsale
				$categories->where('flash_sale.flash_status','=', '1')->where('flash_expires_date','>',  $currentDate);
			}else{
				$categories->whereNotIn('products.products_id',function($query) use ($currentDate) {
							$query->select('flash_sale.products_id')->from('flash_sale')->where('flash_sale.flash_status','=', '1');
						});
			}

			if($type == "auctions"){ //auctions
				$categories->where('auctions.auction_status','=', '1')->where('auction_expires_date','>',  $currentDate);
			}else{
				$categories->whereNotIn('products.products_id',function($query) use ($currentDate) {
							$query->select('auctions.products_id')->from('auctions')->where('auctions.auction_status','=', '1');
						});
			}
			
			//get single products
			if(!empty($data['products_id']) && $data['products_id']!=""){
				$categories->where('products.products_id','=', $data['products_id']);
			}
			
			
			//for min and maximum price
			if(!empty($max_price)){
				$categories->whereBetween('products.products_price', [$min_price, $max_price]);
			}
			
			if(!empty($data['search'])){
				
				$searchValue = $data['search'];
				$categories->where('products_options.products_options_name', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);
								
				if(!empty($data['categories_id'])){
					$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
				}
				
				if(!empty($data['filters'])){
				 $temp_key = 0;
				 foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

					  if($temp_key == 0){

						$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						if(count($data['filters']['filter_attribute']['options'])>1){
						
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
						}

					  }else{
						$categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						
						if(count($data['filters']['filter_attribute']['options'])>1){
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
							}
												
						}
							$temp_key++;
					 }
									
				}	
				
				if(!empty($max_price)){
					$categories->whereBetween('products.products_price', [$min_price, $max_price]);
				}			
					
				$categories->orWhere('products_options_values.products_options_values_name', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);				
				if(!empty($data['categories_id'])){
					$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
				}
				
				if(!empty($data['filters'])){
				 $temp_key = 0;
				 foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

					  if($temp_key == 0){

						$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						if(count($data['filters']['filter_attribute']['options'])>1){
						
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
						}

					  }else{
						$categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						
						if(count($data['filters']['filter_attribute']['options'])>1){
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
							}
												
						}
							$temp_key++;
					 }
									
				}	
				
				if(!empty($max_price)){
					$categories->whereBetween('products.products_price', [$min_price, $max_price]);
				}
				
				$categories->orWhere('products_name', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);	
							
				if(!empty($data['categories_id'])){
					$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
				}
				
				if(!empty($data['filters'])){
				 $temp_key = 0;
				 foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

					  if($temp_key == 0){

						$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						if(count($data['filters']['filter_attribute']['options'])>1){
						
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
						}

					  }else{
						$categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						
						if(count($data['filters']['filter_attribute']['options'])>1){
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
							}
												
						}
							$temp_key++;
					 }
									
				}	
				
				
				if(!empty($max_price)){
					$categories->whereBetween('products.products_price', [$min_price, $max_price]);
				}
				
				$categories->orWhere('products_model', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);
				
				if(!empty($data['categories_id'])){
					$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
				}
				
				if(!empty($data['filters'])){
				 $temp_key = 0;
				 foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

					  if($temp_key == 0){

						$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						if(count($data['filters']['filter_attribute']['options'])>1){
						
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
						}

					  }else{
						$categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						
						if(count($data['filters']['filter_attribute']['options'])>1){
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
							}
												
						}
							$temp_key++;
					 }
									
				}					
			}
						
			if(!empty($data['filters'])){
				 $temp_key = 0;
				 foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

					  if($temp_key == 0){

						$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						if(count($data['filters']['filter_attribute']['options'])>1){
						
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
						}

					  }else{
						$categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
						->where('products_attributes.options_values_id', $option_id_temp);
						
						if(count($data['filters']['filter_attribute']['options'])>1){
							$categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
							}
												
						}
							$temp_key++;
					 }
									
			}	
			
			//wishlist customer id
			if($type == "wishlist"){
				$categories->where('liked_customers_id', '=', session('customers_id'));
			}
			
			//wishlist customer id
			if($type == "is_feature"){
				$categories->where('products.is_feature', '=', 1);
			}
					
			
			$categories->where('products_description.language_id','=',Session::get('language_id'))->where('products_status','=',1);
			
			//get single category products			
			if(!empty($data['categories_id'])){				
				$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
				$categories->where('categories_description.language_id','=',Session::get('language_id'));
			}
			
			$categories->orderBy($sortby, $order)->groupBy('products.products_id');
				
			//count
			$total_record = $categories->get();
			$products  = $categories->skip($skip)->take($take)->get();
			
			$result = array();
			$result2 = array();
			
			//check if record exist
			if(count($products)>0){
				
				$index = 0;	
				foreach ($products as $products_data){
				$products_id = $products_data->products_id;
				
				//multiple images
				$products_images = DB::table('products_images')->select('image')->where('products_id','=', $products_id)->orderBy('sort_order', 'ASC')->get();		
				$products_data->images =  $products_images;
				
				//categories
				$categories = DB::table('products_to_categories')
								->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
								->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
								->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
								->where('products_id','=', $products_id)
								->where('categories_description.language_id','=', Session::get('language_id'))->get();		
				
				$products_data->categories =  $categories;				
				array_push($result,$products_data);
				
				$options = array();
				$attr = array();
				
				$stocks = 0;
				$stockOut = 0;				
				if($products_data->products_type == '0'){									
					$stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
					$stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');							
				}
								
				$result[$index]->defaultStock = $stocks - $stockOut;	
				
				//like product
				if(!empty(session('customers_id'))){
					$liked_customers_id						=	session('customers_id');	
					$categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();
					
					if(count($categories)>0){
						$result[$index]->isLiked = '1';
					}else{
						$result[$index]->isLiked = '0';
					}
				}else{
					$result[$index]->isLiked = '0';						
				}
				
				// fetch all options add join from products_options table for option name
				$products_attribute = DB::table('products_attributes')->where('products_id','=', $products_id)->groupBy('options_id')->orderby('is_default',"DESC")->get();
				if(count($products_attribute)){
				$index2 = 0;
					foreach($products_attribute as $attribute_data){
												
						$option_name = DB::table('products_options')
										->leftJoin('products_options_descriptions', 'products_options_descriptions.products_options_id', '=', 'products_options.products_options_id')->select('products_options.products_options_id', 'products_options_descriptions.options_name as products_options_name', 'products_options_descriptions.language_id')->where('language_id','=', Session::get('language_id'))->where('products_options.products_options_id','=', $attribute_data->options_id)->get();
						
						if(count($option_name)>0){
							
							$temp = array();
							$temp_option['id'] = $attribute_data->options_id;
							$temp_option['name'] = $option_name[0]->products_options_name;
							$temp_option['is_default'] = $attribute_data->is_default;
							$attr[$index2]['option'] = $temp_option;

							// fetch all attributes add join from products_options_values table for option value name
							$attributes_value_query =  DB::table('products_attributes')->where('products_id','=', $products_id)->where('options_id','=', $attribute_data->options_id)->orderby('is_default',"DESC")->get();
							$k = 0;
							foreach($attributes_value_query as $products_option_value){
								
								$option_value = DB::table('products_options_values')->leftJoin('products_options_values_descriptions','products_options_values_descriptions.products_options_values_id','=','products_options_values.products_options_values_id')->select('products_options_values.products_options_values_id', 'products_options_values_descriptions.options_values_name as products_options_values_name' )->where('products_options_values_descriptions.language_id','=', Session::get('language_id'))->where('products_options_values.products_options_values_id','=', $products_option_value->options_values_id)->get();
								
								
								$attributes = DB::table('products_attributes')->where([['products_id','=', $products_id],['options_id','=', $attribute_data->options_id],['options_values_id','=', $products_option_value->options_values_id]])->orderby('is_default',"DESC")->get();
								
								$temp_i['products_attributes_id'] = $attributes[0]->products_attributes_id;
								$temp_i['id'] = $products_option_value->options_values_id;
								$temp_i['value'] = $option_value[0]->products_options_values_name;
								$temp_i['price'] = $products_option_value->options_values_price;
								$temp_i['price_prefix'] = $products_option_value->price_prefix;
								$temp_i['is_default'] = $products_option_value->is_default;
								array_push($temp,$temp_i);

							}
							$attr[$index2]['values'] = $temp;
							$result[$index]->attributes = 	$attr;	
							$index2++;
						}
					}
				}else{
					$result[$index]->attributes = 	array();	
				}
					$index++;
				}
					$responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));
					
				}else{
					$responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
				}	
				
		return($responseData);
	
	}	
	
	//getCart
	public function cart($request){
		
		$cart = DB::table('customers_basket')
			->join('products', 'products.products_id','=', 'customers_basket.products_id')
			->join('products_description', 'products_description.products_id','=', 'products.products_id')
			->select('customers_basket.*', 'products.products_model as model', 'products.products_image as image', 'products_description.products_name as products_name', 'products.products_quantity as quantity', 'products.products_price as price', 'products.products_weight as weight', 'products.products_weight as weight', 'products.products_weight as weight' )->where('customers_basket.is_order', '=', '0')->where('products_description.language_id','=', '1');
			
			if(empty(session('customers_id'))){
				$cart->where('customers_basket.session_id', '=', Session::getId());
			}else{
				$cart->where('customers_basket.customers_id', '=', session('customers_id'));
			}
		
		$baskit = $cart->get();
		return($baskit); 
		
	}
	
	//get liked products
	public function likedProducts(){	

		$products = DB::table('liked_products')->where('liked_customers_id','=', session('customers_id'))->get();	
		$result = array();
		$index = 0;
		foreach($products as $products_data){
			$result[$index++] = $products_data->liked_products_id;
		}	
		return($result); 		

	}	

}