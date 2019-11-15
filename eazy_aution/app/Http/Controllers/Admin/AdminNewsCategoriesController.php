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


class AdminNewsCategoriesController extends Controller
{
	public function getNewsCategories($language_id){
		
		$getCategories = DB::table('news_categories')
		->leftJoin('news_categories_description','news_categories_description.categories_id', '=', 'news_categories.categories_id')
		->select('news_categories.categories_id as id', 'news_categories.categories_image as image',  'news_categories.categories_icon as icon',  'news_categories.date_added as date_added', 'news_categories.last_modified as last_modified', 'news_categories_description.categories_name as name', 'news_categories_description.language_id')
		->where('parent_id', '0')->where('news_categories_description.language_id', $language_id)->get();
		return($getCategories) ;
	}
	
	public function newscategories(){
		if(session('news_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.NewsCategories"));
		
		$listingCategories = DB::table('news_categories')
		->leftJoin('news_categories_description','news_categories_description.categories_id', '=', 'news_categories.categories_id')
		->select('news_categories.categories_id as id', 'news_categories.categories_image as image',  'news_categories.categories_icon as icon',  'news_categories.date_added as date_added', 'news_categories.last_modified as last_modified', 'news_categories_description.categories_name as name', 'news_categories_description.language_id')
		->where('parent_id', '0')->where('news_categories_description.language_id', '1')->paginate(10);
		
		return view("admin.newscategories",$title)->with('listingCategories', $listingCategories);
		}
	}
	
	//add category
	public function addnewscategory(Request $request){
		if(session('news_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddNewsCategories"));
		
		$result = array();
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		return view("admin.addnewscategory",$title)->with('result', $result);
		}
	}
	
	//addNewCategory	
	public function addnewsnewcategory(Request $request){
		if(session('news_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddNewsCategories"));
		
		$result = array();
		$date_added	= date('y-m-d h:i:s');
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
				
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/news_categories_images/', $fileName);
			$uploadImage = 'resources/assets/images/news_categories_images/'.$fileName; 
		}	else{
			$uploadImage = '';
		}	
		
		if($request->hasFile('newIcon') and in_array($request->newIcon->extension(), $extensions)){
			$icon = $request->newIcon;
			$iconName = time().'.'.$icon->getClientOriginalName();
			$icon->move('resources/assets/images/news_icons/', $iconName);
			$uploadIcon = 'resources/assets/images/news_icons/'.$iconName; 
		}	else{
			$uploadIcon = '';
		}	
		
		$categories_id = DB::table('news_categories')->insertGetId([
					'categories_image'   =>   $uploadImage,
					'date_added'		 =>   $date_added,
					'parent_id'		 	 =>   '0',
					'categories_icon'	 =>	  $uploadIcon
					]);
					
		$slug_flag = false;	
		//multiple lanugauge with record 
		foreach($languages as $languages_data){
			$categoryName= 'categoryName_'.$languages_data->languages_id;
			
			//slug
			if($slug_flag==false){
				$slug_flag=true;
				
				$slug = $request->$categoryName;
				$old_slug = $request->$categoryName;
				
				$slug_count = 0;
				do{
					if($slug_count==0){
						$currentSlug = $myVar->slugify($slug);
					}else{
						$currentSlug = $myVar->slugify($old_slug.'-'.$slug_count);
					}
					$slug = $currentSlug;
					//$checkSlug = DB::table('news_categories')->where('news_categories_slug',$currentSlug)->where('categories_id','!=',$request->id)->get();
					$checkSlug = DB::table('news_categories')->where('news_categories_slug',$currentSlug)->get();
					$slug_count++;
				}
				while(count($checkSlug)>0);
				DB::table('news_categories')->where('categories_id',$categories_id)->update([
					'news_categories_slug'	 =>   $slug
					]);
			}
			
				
			DB::table('news_categories_description')->insert([
					'categories_name'   =>   $request->$categoryName,
					'categories_id'     =>   $categories_id,
					'language_id'       =>   $languages_data->languages_id
				]);
		}		
				
		$message = Lang::get("labels.NewsCategoriesAddedMessage");
				
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//editCategory
	public function editnewscategory(Request $request){	
		if(session('news_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditNewsCategories"));
		$result = array();		
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$editCategory = DB::table('news_categories')
		->leftJoin('news_categories_description','news_categories_description.categories_id', '=', 'news_categories.categories_id')
		->select('news_categories.categories_id as id', 'news_categories.categories_image as image', 'news_categories.categories_icon as icon',  'news_categories.date_added as date_added', 'news_categories.last_modified as last_modified', 'news_categories.news_categories_slug as slug')
		->where('news_categories.categories_id', $request->id)->get();
		
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('news_categories_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['categories_id', '=', $request->id],
				])->get();
				
			if(count($description)>0){								
				$description_data[$languages_data->languages_id]['name'] = $description[0]->categories_name;
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['name'] = '';
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		$result['description'] = $description_data;	
		$result['editCategory'] = $editCategory;		
		
		return view("admin.editnewscategory", $title)->with('result', $result);
		}
	}
	
	//updateCategory
	public function updatenewscategory(Request $request){
		if(session('news_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditNewsCategories"));
		$last_modified 	   =   date('y-m-d h:i:s');
		$categories_id     =   $request->id;
		$result = array();		
		
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
			$image->move('resources/assets/images/news_categories_images/', $fileName);
			$uploadImage = 'resources/assets/images/news_categories_images/'.$fileName; 
		}else{
			$uploadImage = $request->oldImage;
		}
		
		if($request->hasFile('newIcon') and in_array($request->newIcon->extension(), $extensions)){
			$icon = $request->newIcon;
			$iconName = time().'.'.$icon->getClientOriginalName();
			$icon->move('resources/assets/images/news_icons/', $iconName);
			$uploadIcon = 'resources/assets/images/news_icons/'.$iconName; 
		}	else{
			$uploadIcon = $request->oldIcon;
		}		
		
		DB::table('news_categories')->where('categories_id', $request->id)->update([
			'categories_image'   =>   $uploadImage,
			'last_modified'   	 =>   $last_modified,
			'categories_icon'    =>   $uploadIcon,
			'news_categories_slug'=>  $slug
			]);
				
		foreach($languages as $languages_data){
			$categories_name = 'category_name_'.$languages_data->languages_id;
			
			$checkExist = DB::table('news_categories_description')->where('categories_id','=',$categories_id)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('news_categories_description')->where('categories_id','=',$categories_id)->where('language_id','=',$languages_data->languages_id)->update([
					'categories_name'  	    		 =>   $request->$categories_name,
					]);
			}else{
				DB::table('news_categories_description')->insert([
					'categories_name'  	     =>   $request->$categories_name,
					'language_id'			 =>   $languages_data->languages_id,
					'categories_id'			 =>   $categories_id,
					]);
			}
		}
		
		$message = Lang::get("labels.NewsCategoriesUpdatedMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	//deleteNewsCategory
	public function deletenewscategory(Request $request){
		if(session('news_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('news_categories')->where('categories_id', $request->id)->delete();
		DB::table('news_categories_description')->where('categories_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.NewsCategoriesDeletedMessage")]);
		}
	}
	
}
