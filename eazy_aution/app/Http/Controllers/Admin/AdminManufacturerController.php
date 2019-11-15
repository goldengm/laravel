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


class AdminManufacturerController extends Controller
{
	public function manufacturers(){
		if(session('manufacturer_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.Manufacturers"));
		$manufacturers = DB::table('manufacturers')
		->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
		->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturers_image as image',  'manufacturers.manufacturers_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date')
		->where('manufacturers_info.languages_id', '1')->paginate(10);
		
		return view("admin.manufacturers",$title)->with('manufacturers', $manufacturers);
		}
	}
	
	public function addmanufacturer(Request $request){	
		if(session('manufacturer_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		$title = array('pageTitle' => Lang::get("labels.AddManufacturer"));
		return view("admin.addmanufacturer",$title);
		}
	}
	
	
	public function addnewmanufacturer(Request $request){
		if(session('manufacturer_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$languages_id 	=  '1';		
		$title = array('pageTitle' => Lang::get("labels.AddManufacturer"));
		$date_added	= date('y-m-d h:i:s');
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$extensions = $myVar->imageType();
		
		$validator = Validator::make(
			array(
					'name'    => $request->name,
					//'image'	  => $request->newImage
				), 
			array(
					'name'    => 'required',
					//'image'   => 'required',
				)
		);
		
		if($validator->fails()){
			return redirect('admin/addManufacturer')->withErrors($validator)->withInput();
		}else{
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$c_extension = $request->newImage->extension();
			echo '<pre>'.print_r($c_extension, true).'</pre>';
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/manufacturers_images/', $fileName);
			$uploadImage = 'resources/assets/images/manufacturers_images/'.$fileName; 
		}	else{
			$uploadImage = '';
		}	
		
		
		$slug = $request->name;
		$slug_count = 0;
		do{
			if($slug_count==0){
				$currentSlug = $myVar->slugify($request->name);
			}else{
				$currentSlug = $myVar->slugify($request->name.'-'.$slug_count);
			}
			$slug = $currentSlug;
			$checkSlug = DB::table('manufacturers')->where('manufacturers_slug',$currentSlug)->get();
			$slug_count++;
		}
		
		while(count($checkSlug)>0);
		
		$manufacturers_id = DB::table('manufacturers')->insertGetId([
					'manufacturers_image'   =>   $uploadImage,
					'date_added'			=>   $date_added,
					'manufacturers_name' 	=>   $request->name,
					'manufacturers_slug'	=>	 $slug
					]);
					
		DB::table('manufacturers_info')->insert([
					'manufacturers_id'  	=>     $manufacturers_id,
					'manufacturers_url'     =>     $request->manufacturers_url,
					'languages_id'			=>	   $languages_id,
					//'url_clicked'			=>	   $request->url_clicked
				]);
		
		
				
		return redirect()->back()->withErrors([Lang::get("labels.manuFacturerAddeddMessage")]);
		}
		}
	}
		
	public function editmanufacturer(Request $request){
		if(session('manufacturer_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.EditManufacturers"));
		
		$manufacturers_id = $request->id;
		
		$editManufacturer = DB::table('manufacturers')
		->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
		->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturers_image as image',  'manufacturers.manufacturers_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date', 'manufacturers.manufacturers_slug as slug')
		->where( 'manufacturers.manufacturers_id', $manufacturers_id )
		->get();
		
		return view("admin.editmanufacturer",$title)->with('editManufacturer', $editManufacturer);
		}
	}
	
	public function updateManufacturer(Request $request){
		if(session('manufacturer_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$last_modified 	=   date('y-m-d h:i:s');		
		$title = array('pageTitle' => Lang::get("labels.EditManufacturers"));
		$languages_id = '1';
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		//check slug
		if($request->old_slug!=$request->slug ){
			$slug = $request->slug;
			$slug_count = 0;
			do{
				if($slug_count==0){
					$currentSlug = $myVar->slugify($request->slug);
				}else{
					$currentSlug = $myVar->slugify($request->slug.'-'.$slug_count);
				}
				$slug = $currentSlug;
				$checkSlug = DB::table('news_categories')->where('news_categories_slug',$currentSlug)->where('categories_id','!=',$request->id)->get();
				$slug_count++;
			}
			
			while(count($checkSlug)>0);		
			
		}else{
			$slug = $request->slug;
		}
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/manufacturers_images/', $fileName);
			$uploadImage = 'resources/assets/images/manufacturers_images/'.$fileName; 
		}else{
			$uploadImage = $request->oldImage;
		}
		
		
		DB::table('manufacturers')->where('manufacturers_id', $request->id)->update([
					'manufacturers_image'   =>   $uploadImage,
					'last_modified'			=>   $last_modified,
					'manufacturers_name' 	=>   $request->name,
					'manufacturers_slug'	=>	 $slug
					]);
		DB::table('manufacturers_info')->where('manufacturers_id', $request->id)->update([
					'manufacturers_url'     =>     $request->manufacturers_url,
					'languages_id'			=>	   $languages_id,
					//'url_clicked'			=>	   $request->url_clicked
				]);
				
		$editCategory = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name')
		->where('categories.categories_id', $request->id)->get();
		return redirect()->back()->withErrors([Lang::get("labels.ManufacturerUpdatedMessage")]);
		}
	}
	
	
	public function deleteManufacturer(Request $request){
		if(session('manufacturer_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		
		DB::table('manufacturers')->where('manufacturers_id', $request->manufacturers_id)->delete();
		DB::table('manufacturers_info')->where('manufacturers_id', $request->manufacturers_id)->delete();
		
		return redirect()->back()->withErrors([Lang::get("labels.manufacturersDeletedMessage")]);
		}
	}
	
	//getManufacturer
	public function getManufacturer($language_id){
		
		$getManufacturers = DB::table('manufacturers')
		->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
		->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturers_image as image',  'manufacturers.manufacturers_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date')
		->where('manufacturers_info.languages_id', $language_id)->get();
		return($getManufacturers);
	}
	
}
