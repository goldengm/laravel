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


class AdminCategoriesController extends Controller
{
	public function allCategories($language_id){
		$categories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name', 'categories.categories_slug as slug')
		->where('categories_description.language_id','=', $language_id )->where('parent_id', '0')->get();
		
		$results = array();
		$index = 0;
		foreach($categories  as $category){
			array_push($results,$category);
			
			$subCategories = DB::table('categories')
			->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
			->select('categories.categories_id as sub_id', 'categories.categories_image as sub_image',  'categories.date_added as sub_date_added', 'categories.last_modified as sub_last_modified', 'categories_description.categories_name as sub_name', 'categories.categories_slug as sub_slug')
			->where('categories_description.language_id','=', $language_id )->where('parent_id', $category->id)->get();
			$results[$index]->sub_categories = $subCategories;
			$index++;
		}	
		return($results);		
	}
	
	public function getCategories($language_id){
		
		$listingCategories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name', 'categories.categories_slug as slug')
		->where('categories_description.language_id','=', $language_id )->where('parent_id', '0')->get();
		return($listingCategories) ;
	}
	
	public function getSubCategories($language_id){
		
		$language_id     =   $language_id;		
		$listingCategories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name', 'categories.categories_slug as slug')
		->where('categories_description.language_id','=', $language_id )->where('parent_id','>', '0')->get();
		return($listingCategories);
	}
	
	public function categories(){
		if(session('categories_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.MainCategories"));
		
		$categories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.categories_icon as icon',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name', 'categories_description.language_id')
		->where('parent_id', '0')->where('categories_description.language_id', '1')->paginate(10);
		
		return view("admin.categories",$title)->with('categories', $categories);
		}
	}
	
	//add category
	public function addcategory(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddCategories"));
		
		$result = array();
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		return view("admin.addcategory",$title)->with('result', $result);
	}
	
	//addNewCategory	
	public function addnewcategory(Request $request){
		if(session('categories_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.AddCategories"));
		
		$result = array();
		$date_added	= date('y-m-d h:i:s');
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();		
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/category_images/', $fileName);
			$uploadImage = 'resources/assets/images/category_images/'.$fileName; 
		}	else{
			$uploadImage = '';
		}	
		
		if($request->hasFile('newIcon') and in_array($request->newIcon->extension(), $extensions)){
			$icon = $request->newIcon;
			$iconName = time().'.'.$icon->getClientOriginalName();
			$icon->move('resources/assets/images/category_icons/', $iconName);
			$uploadIcon = 'resources/assets/images/category_icons/'.$iconName; 
		}	else{
			$uploadIcon = '';
		}	
		
		$categories_id = DB::table('categories')->insertGetId([
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
					//$checkSlug = DB::table('categories')->where('categories_slug',$currentSlug)->where('categories_id','!=',$request->id)->get();
					$checkSlug = DB::table('categories')->where('categories_slug',$currentSlug)->get();
					$slug_count++;
				}
				while(count($checkSlug)>0);
				DB::table('categories')->where('categories_id',$categories_id)->update([
					'categories_slug'	 =>   $slug
					]);
			}
				
			DB::table('categories_description')->insert([
					'categories_name'   =>   $request->$categoryName,
					'categories_id'     =>   $categories_id,
					'language_id'       =>   $languages_data->languages_id
				]);
		}		
				
		$message = Lang::get("labels.CategoriesAddedMessage");
				
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	//editCategory
	public function editcategory(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditMainCategories"));
		$result = array();		
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$editCategory = DB::table('categories')
		->select('categories.categories_id as id', 'categories.categories_image as image', 'categories.categories_icon as icon',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories.categories_slug as slug')
		
		->where('categories.categories_id', $request->id)->get();
		
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('categories_description')->where([
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
		
		return view("admin.editcategory", $title)->with('result', $result);
	}
	
	//updateCategory
	public function updatecategory(Request $request){		
		if(session('categories_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditMainCategories"));
		$last_modified 	=   date('y-m-d h:i:s');
		$categories_id = $request->id;
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
				$checkSlug = DB::table('categories')->where('categories_slug',$currentSlug)->where('categories_id','!=',$request->id)->get();
				$slug_count++;
			}
			
			while(count($checkSlug)>0);		
			
		}else{
			$slug = $request->slug;
		}
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/category_images/', $fileName);
			$uploadImage = 'resources/assets/images/category_images/'.$fileName; 
		}else{
			$uploadImage = $request->oldImage;
		}
		
		if($request->hasFile('newIcon') and in_array($request->newIcon->extension(), $extensions)){
			$icon = $request->newIcon;
			$iconName = time().'.'.$icon->getClientOriginalName();
			$icon->move('resources/assets/images/category_icons/', $iconName);
			$uploadIcon = 'resources/assets/images/category_icons/'.$iconName; 
		}	else{
			$uploadIcon = $request->oldIcon;
		}
		
		
		DB::table('categories')->where('categories_id', $request->id)->update([
			'categories_image'   =>   $uploadImage,
			'last_modified'   	 =>   $last_modified,
			'categories_icon'    =>   $uploadIcon,
			'categories_slug'	 =>   $slug
			]);
		
		foreach($languages as $languages_data){
			$categories_name = 'category_name_'.$languages_data->languages_id;
			
			$checkExist = DB::table('categories_description')->where('categories_id','=',$categories_id)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('categories_description')->where('categories_id','=',$categories_id)->where('language_id','=',$languages_data->languages_id)->update([
					'categories_name'  	    		 =>   $request->$categories_name,
					]);
			}else{
				DB::table('categories_description')->insert([
					'categories_name'  	     =>   $request->$categories_name,
					'language_id'			 =>   $languages_data->languages_id,
					'categories_id'			 =>   $categories_id,
					]);
			}
		}
		
		$message = Lang::get("labels.CategoriesUpdateMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	//delete category
	public function deletecategory(Request $request){
		if(session('categories_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		
		DB::table('categories')->where('categories_id', $request->id)->delete();
		DB::table('categories_description')->where('categories_id', $request->id)->delete();
		
		$listingCategories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name')
		->where('parent_id', '0')->get();
		
		$message = Lang::get("labels.CategoriesDeleteMessage");
				
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	
	//sub categories
	public function subcategories(){
		if(session('categories_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.SubCategories"));
		
		$listingSubCategories = DB::table('categories as subCategories')
		->leftJoin('categories_description as subCategoryDesc','subCategoryDesc.categories_id', '=', 'subCategories.categories_id')
		
		->leftJoin('categories as mainCategory','mainCategory.categories_id', '=', 'subCategories.categories_id')
		->leftJoin('categories_description as mainCategoryDesc','mainCategoryDesc.categories_id', '=', 'mainCategory.parent_id')
		
		->select(
			'subCategories.categories_id as subId',
			'subCategories.categories_image as image',
			'subCategories.categories_icon as icon',
			'subCategories.date_added as date_added',
			'subCategories.last_modified as last_modified',
			'subCategoryDesc.categories_name as subCategoryName',
			'mainCategoryDesc.categories_name as mainCategoryName',
			'subCategoryDesc.language_id'
			)
		->where('subCategories.parent_id', '>', '0')->where('subCategoryDesc.language_id', '1')->where('mainCategoryDesc.language_id', '1')->orderBy('subId','ASC')->paginate(20);
		
		return view("admin.subcategories",$title)->with('listingSubCategories', $listingSubCategories);
		}
	}
	
	//addsubcategory
	public function addsubcategory(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.AddSubCategories"));
		$result = array();
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$categories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as mainId', 'categories_description.categories_name as mainName')
		->where('parent_id', '0')->where('language_id','=', 1)->get();
		$result['categories'] = $categories;
		
		return view("admin.addsubcategory",$title)->with('result', $result);
	}
	
	
	//addNewsubcategory
	public function addnewsubcategory(Request $request){
		if(session('categories_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.AddSubCategories"));
		$date_added	= date('y-m-d h:i:s');
		$result = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
		
		$categoryName = $request->categoryName;
		$parent_id = $request->parent_id;
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/category_images/', $fileName);
			$uploadImage = 'resources/assets/images/category_images/'.$fileName; 
		}else{
			$uploadImage = '';
		}
		
		if($request->hasFile('newIcon') and in_array($request->newIcon->extension(), $extensions)){
			$icon = $request->newIcon;
			$iconName = time().'.'.$icon->getClientOriginalName();
			$icon->move('resources/assets/images/category_icons/', $iconName);
			$uploadIcon = 'resources/assets/images/category_icons/'.$iconName; 
		}	else{
			$uploadIcon = '';
		}		
		
		$categories_id = DB::table('categories')->insertGetId([
					'categories_image'   =>   $uploadImage,
					'date_added'		 =>   $date_added,
					'parent_id'		 	 =>   $parent_id,
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
						$currentSlug = $myVar->slugify($old_slug);
					}else{
						$currentSlug = $myVar->slugify($old_slug.'-'.$slug_count);
					}
					$slug = $currentSlug;
					$checkSlug = DB::table('categories')->where('categories_slug',$currentSlug)->get();
					$slug_count++;
				}
				while(count($checkSlug)>0);
				DB::table('categories')->where('categories_id',$categories_id)->update([
					'categories_slug'	 =>   $slug
					]);
			}			
				
			DB::table('categories_description')->insert([
					'categories_name'   =>   $request->$categoryName,
					'categories_id'     =>   $categories_id,
					'language_id'       =>   $languages_data->languages_id
				]);
		}	
		
				
		$categories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as mainId', 'categories_description.categories_name as mainName')
		->where('parent_id', '0')->get();
		
		$result['categories'] = $categories;
		
		$message = Lang::get("labels.AddSubCategoryMessage");
				
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	
	
	public function editsubcategory(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.EditSubCategories"));
		$result = array();
		$result['message'] = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		$editSubCategory = DB::table('categories')
		->select('categories.categories_id as id', 'categories.categories_image as image', 'categories.categories_icon as icon',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories.categories_slug as slug', 'categories.parent_id as parent_id')
		->where('categories.categories_id', $request->id)->get();
		
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('categories_description')->where([
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
		
		$categories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as mainId', 'categories_description.categories_name as mainName')
		->where('parent_id', '0')->where('language_id','=', 1)->get();		
		
		$result['editSubCategory'] = $editSubCategory;
		$result['categories'] = $categories;
		
		return view("admin.editsubcategory",$title)->with('result', $result);
	}
	
	
	//updatesubcategory
	public function updatesubcategory(Request $request){
		if(session('categories_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditSubCategories"));
		$result = array();
		$result['message'] = Lang::get("labels.Sub category has been updated successfully");
		$last_modified 	=   date('y-m-d h:i:s');
		$parent_id = $request->parent_id;
		$categories_id = $request->id;
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
				
		//check slug
		if($request->old_slug!=$request->slug){
			
			$slug = $request->slug;
			$slug_count = 0;
			do{
				if($slug_count==0){
					$currentSlug = $myVar->slugify($request->slug);
				}else{
					$currentSlug = $myVar->slugify($request->slug.'-'.$slug_count);
				}
				$slug = $currentSlug;
				$checkSlug = DB::table('categories')->where('categories_slug',$currentSlug)->where('categories_id','!=',$request->id)->get();
				$slug_count++;
			}
			
			while(count($checkSlug)>0);		
			
		}else{
			$slug = $request->slug;
		}
		
		
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/category_images/', $fileName);
			$uploadImage = 'resources/assets/images/category_images/'.$fileName; 
		}else{
			$uploadImage = $request->oldImage;
		}
		
		if($request->hasFile('newIcon') and in_array($request->newIcon->extension(), $extensions)){
			$icon = $request->newIcon;
			$iconName = time().'.'.$icon->getClientOriginalName();
			$icon->move('resources/assets/images/category_icons/', $iconName);
			$uploadIcon = 'resources/assets/images/category_icons/'.$iconName; 
		}	else{
			$uploadIcon = $request->oldIcon;
		}
		
		DB::table('categories')->where('categories_id', $request->id)->update(
		[
			'categories_image'   =>   $uploadImage,
			'categories_icon'    =>   $uploadIcon,
			'last_modified'  	 =>   $last_modified,
			'parent_id' 		 =>   $parent_id,
			'categories_slug'    =>   $slug,
		]);
		
		foreach($languages as $languages_data){
			$categories_name = 'category_name_'.$languages_data->languages_id;
			
			$checkExist = DB::table('categories_description')->where('categories_id','=',$categories_id)->where('language_id','=',$languages_data->languages_id)->get();			
			if(count($checkExist)>0){
				DB::table('categories_description')->where('categories_id','=',$categories_id)->where('language_id','=',$languages_data->languages_id)->update([
					'categories_name'  	    		 =>   $request->$categories_name,
					]);
			}else{
				DB::table('categories_description')->insert([
					'categories_name'  	     =>   $request->$categories_name,
					'language_id'			 =>   $languages_data->languages_id,
					'categories_id'			 =>   $categories_id,
					]);
			}
		}
		
		$message = Lang::get("labels.SubCategorieUpdateMessage");
		return redirect()->back()->withErrors([$message]);
		}
		
	}
	
	//delete sub category
	public function deletesubcategory(Request $request){
		if(session('categories_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		DB::table('categories')->where('categories_id', $request->id)->delete();
		DB::table('categories_description')->where('categories_id', $request->id)->delete();
		
		$message = Lang::get("labels.SubCategorieDeleteMessage");
		return redirect()->back()->withErrors([$message]);
		}
	}
	
	public function getajaxcategories(Request $request){
		$language_id 	 = '1';
		
		if(empty($request->category_id)){
			$category_id	= '0';
		}else{
			$category_id	=   $request->category_id;
		}
		
		$getCategories = DB::table('categories')
		->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
		->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.date_added as date_added', 'categories.last_modified as last_modified', 'categories_description.categories_name as name')
		->where('parent_id', $category_id)->where('categories_description.language_id', $language_id)->get();
		return($getCategories) ;
	}
}
