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

use DB;

//for password encryption or hash protected
use Hash;
use App\Administrator;
use Lang;

//for authenitcate login data
use Auth;



//for requesting a value 
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
	
	public function news(Request $request){
		if(session('news_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.News"));
		$language_id            				=   '1';			
		
		$news = DB::table('news_to_news_categories')
			->leftJoin('news_categories', 'news_categories.categories_id', '=', 'news_to_news_categories.categories_id')
			->leftJoin('news', 'news.news_id', '=', 'news_to_news_categories.news_id')
			->leftJoin('news_description','news_description.news_id','=','news.news_id')
			->leftJoin('news_categories_description','news_categories_description.categories_id','=','news_to_news_categories.categories_id')
			
			->select('news_to_news_categories.*', 'news_categories_description.categories_name','news_categories.*', 'news.*','news_description.*')
			->where('news_description.language_id','=', $language_id)
			->where('news_categories_description.language_id','=', $language_id)
			->orderBy('news.news_id', 'ASC')
			->paginate(20);
		
		$currentTime =  array('currentTime'=>time());
		return view("admin.news",$title)->with('news', $news);
		}
	}
	
	public function addnews(Request $request){
		if(session('news_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddNews"));
		$language_id      =   '1';
		
		$result = array();
		
		//get function from other controller
		$myVar = new AdminNewsCategoriesController();
		$result['newsCategories'] = $myVar->getNewsCategories($language_id);
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
		return view("admin.addnews", $title)->with('result', $result);
		}
	}
	
	//addNewNews
	public function addnewnews(Request $request){
		if(session('news_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddNews"));
		$date_added	= date('Y-m-d h:i:s');
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();		
		$extensions = $myVar->imageType();
				
		if($request->hasFile('news_image') and in_array($request->news_image->extension(), $extensions)){
			$image = $request->news_image;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/news_images/', $fileName);
			$uploadImage = 'resources/assets/images/news_images/'.$fileName; 
		}else{
			$uploadImage = '';
		}	
		
		$news_id = DB::table('news')->insertGetId([
					'news_image'  			 =>   $uploadImage,
					'news_date_added'	 	 =>   $date_added,
					'news_status'		 	 =>   $request->news_status,
					'is_feature'		 	 =>   $request->is_feature
					]);
		
		$slug_flag = false;	
		foreach($languages as $languages_data){
			$news_name = 'news_name_'.$languages_data->languages_id;
			$news_description = 'news_description_'.$languages_data->languages_id;
			
			//slug
			if($slug_flag==false){
				$slug_flag=true;
				
				$slug = $request->$news_name;
				$old_slug = $request->$news_name;
				
				$slug_count = 0;
				do{
					if($slug_count==0){
						$currentSlug = $myVar->slugify($slug);
					}else{
						$currentSlug = $myVar->slugify($old_slug.'-'.$slug_count);
					}
					$slug = $currentSlug;
					$checkSlug = DB::table('news')->where('news_slug',$currentSlug)->get();
					$slug_count++;
				}
				while(count($checkSlug)>0);
				DB::table('news')->where('news_id',$news_id)->update([
					'news_slug'	 =>   $slug
					]);
			}
			
			
			DB::table('news_description')->insert([
					'news_name'  	    	 =>   $request->$news_name,
					'language_id'			 =>   $languages_data->languages_id,
					'news_id'				 =>   $news_id,
					/*'news_url'			 =>   $request->news_url,*/
					'news_description'		 =>   addslashes($request->$news_description)
					]);
		}	
		
		DB::table('news_to_news_categories')->insert([
					'news_id'   		=>     $news_id,
					'categories_id'     =>     $request->category_id
				]);
		
		
		//notify users	
		$myVar = new AdminAlertController();
		$alertSetting = $myVar->newsNotification($news_id);
		
		$message = Lang::get("labels.Newshasbeenaddedsuccessfully");				
		return redirect()->back()->withErrors([$message]);
		}
	}
		
	//editnew
	public function editnews(Request $request){
		if(session('news_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditNews"));
		$language_id      =   '1';	
		$news_id     	  =   $request->id;	
		$category_id	  =	  '0';
		
		$result = array();
		
		//get categories from other controller
		$myVar = new AdminNewsCategoriesController();
		$result['categories'] = $myVar->getNewsCategories($language_id);
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
						
		$news = DB::table('news')
			->where('news.news_id','=', $news_id)
			->get();
			
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('news_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['news_id', '=', $news_id],
				])->get();
				
			if(count($description)>0){								
				$description_data[$languages_data->languages_id]['news_name'] = $description[0]->news_name;
				$description_data[$languages_data->languages_id]['news_description'] = $description[0]->news_description;
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['news_name'] = '';
				$description_data[$languages_data->languages_id]['news_description'] = '';
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		$result['description'] = $description_data;	
		$result['news'] = $news;
		
		
		//get new sub category id
		$newsCategory = DB::table('news_to_news_categories')->where('news_id','=', $news_id)->get();
		$result['categoryId'] = $newsCategory;
		
		
		$categories = DB::table('news_categories')
		->leftJoin('news_categories_description','news_categories_description.categories_id', '=', 'news_categories.categories_id')
		->select('news_categories.categories_id as id', 'news_categories_description.categories_name as name', 'news_categories.categories_id', 'news_categories_description.categories_description_id' )
		->where('news_categories.categories_id','=', $result['categoryId'][0]->categories_id)->get();
		
		$result['editCategory'] = $categories;
		
		return view("admin.editnews", $title)->with('result', $result);	
		}
	}
	
	
	//updatenew
	public function updatenews(Request $request){
		if(session('news_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$language_id      =   '1';	
		$news_id      =   $request->id;	
		$news_last_modified	= date('Y-m-d h:i:s');
			
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
				$checkSlug = DB::table('news')->where('news_slug',$currentSlug)->where('news_id','!=',$news_id)->get();
				$slug_count++;
			}			
			while(count($checkSlug)>0);		
			
		}else{
			$slug = $request->slug;
		}
		
		if($request->hasFile('news_image') and in_array($request->news_image->extension(), $extensions)){
			$image = $request->news_image;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/news_images/', $fileName);
			$uploadImage = 'resources/assets/images/news_images/'.$fileName; 
		}else{
			$uploadImage = $request->oldImage;
		}	
		
		DB::table('news')->where('news_id','=',$news_id)->update([
					'news_image'  			 =>   $uploadImage,
					'news_last_modified'	 =>   $news_last_modified,
					'news_status'		 	 =>   $request->news_status,
					'is_feature'		 	 =>   $request->is_feature,
					'news_slug'				 =>	  $slug
					]);
		
		
		foreach($languages as $languages_data){
			$news_name = 'news_name_'.$languages_data->languages_id;
			$news_description = 'news_description_'.$languages_data->languages_id;
			//if(!empty($request->$news_name)){
			
			$checkExist = DB::table('news_description')->where('news_id','=',$news_id)->where('language_id','=',$languages_data->languages_id)->get();
			
			if(count($checkExist)>0){
				DB::table('news_description')->where('news_id','=',$news_id)->where('language_id','=',$languages_data->languages_id)->update([
					'news_name'  	     =>   $request->$news_name,
					/*'news_url'		 =>   $request->news_url,*/
					'news_description'	 =>   addslashes($request->$news_description)
					]);
			}else{
				DB::table('news_description')->insert([
						'news_name'  	     =>   $request->$news_name,
						'language_id'		 =>   $languages_data->languages_id,
						'news_id'			 =>   $news_id,
						/*'news_url'		 =>   $request->news_url,*/
						'news_description'	 =>   addslashes($request->$news_description)
						]);	
			}
		}
		
		DB::table('news_to_news_categories')->where('news_id','=',$news_id)->update([
					'categories_id'     =>     $request->category_id
				]);
		
		$message = Lang::get("labels.Newshasbeenupdatedsuccessfully");				
		return redirect()->back()->withErrors([$message]);
		}		
	}
	
	//deleteNews
	public function deletenews(Request $request){
		if(session('news_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		DB::table('news')->where('news_id', $request->id)->delete();
		DB::table('news_description')->where('news_id', $request->id)->delete();
		DB::table('news_to_news_categories')->where('news_id', $request->id)->delete();
		return redirect()->back()->withErrors(['News has been deleted successfully!']);
		}
	}
	
}
