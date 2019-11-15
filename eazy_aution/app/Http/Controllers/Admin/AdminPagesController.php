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
use App;
use Lang;
//for authenitcate login data
use Auth;

//for requesting a value 
use Illuminate\Http\Request;

class AdminPagesController extends Controller
{
	
	public function pages(Request $request){
		if(session('application_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title 			= array('pageTitle' => Lang::get("labels.Pages"));
		$language_id    =   '1';		
		
		$pages = DB::table('pages')
			->leftJoin('pages_description', 'pages_description.page_id', '=', 'pages.page_id')
			->where([
						['pages_description.language_id','=',$language_id],
						['pages.type','=','1']
					])
			->orderBy('pages.page_id', 'ASC')
			->paginate(20);
		
		$result["pages"] = $pages;
		return view("admin.pages",$title)->with('result', $result);
		}
	}
	
	public function addpage(Request $request){
		if(session('application_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddPage"));
		$language_id      =   '1';
		
		$result = array();
		
		//get function from other controller
		$myVar = new AdminNewsCategoriesController();
		$result['newsCategories'] = $myVar->getNewsCategories($language_id);
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();		
		
		return view("admin.addpage", $title)->with('result', $result);
		}
	}
	
	//addNewPage
	public function addnewpage(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.AddPage"));	
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		$slug = str_replace(' ','-' ,trim($request->slug));
		$slug = str_replace('_','-' ,$slug);
		
		$page_id = DB::table('pages')->insertGetId([
					'slug'		 			 =>   $slug,
					'type'		 			 =>   1,
					'status'		 		 =>   $request->status,	
					]);
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			$description = 'description_'.$languages_data->languages_id;
			
			DB::table('pages_description')->insert([
					'name'  	    		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'page_id'				 =>   $page_id,
					'description'			 =>   addslashes($request->$description)
					]);
		}	
		
				
		$message = Lang::get("labels.PageAddedMessage");				
		return redirect()->back()->withErrors([$message]);
		}
	}
		
	//editnew
	public function editpage(Request $request){
		if(session('application_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.EditPage"));
		$language_id      =   '1';	
		$page_id     	  =   $request->id;	
		
		$result = array();
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
				
		$pages = DB::table('pages')
			->leftJoin('pages_description', 'pages_description.page_id', '=', 'pages.page_id')
			->select('pages.*','pages_description.description','pages_description.language_id','pages_description.name' ,'pages_description.page_description_id')
			->where('pages.page_id','=', $page_id)
			->get();
			
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('pages_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['page_id', '=', $page_id],
				])->get();
				
			if(count($description)>0){								
				$description_data[$languages_data->languages_id]['name'] = $description[0]->name;
				$description_data[$languages_data->languages_id]['description'] = $description[0]->description;
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['name'] = '';
				$description_data[$languages_data->languages_id]['description'] = '';
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		
		$result['description'] = $description_data;			
		$result['editPage'] = $pages;
		
		return view("admin.editpage", $title)->with('result', $result);	
		}
	}
	
	
	//updatePage
	public function updatepage(Request $request){
		if(session('application_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$page_id      =   $request->id;	
			
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		$slug = str_replace(' ','-' ,trim($request->slug));
		$slug = str_replace('_','-' ,$slug);
		
		DB::table('pages')->where('page_id','=',$page_id)->update([
					'slug'		 			 =>   $slug,
					'type'		 			 =>   1,
					'status'		 		 =>   $request->status,					
					]);
		
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			$description = 'description_'.$languages_data->languages_id;
			
			$checkExist = DB::table('pages_description')->where('page_id','=',$page_id)->where('language_id','=',$languages_data->languages_id)->get();
			
			if(count($checkExist)>0){
				DB::table('pages_description')->where('page_id','=',$page_id)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'description'			 =>   addslashes($request->$description)
					]);
			}else{
				DB::table('pages_description')->insert([
					'name'  	    		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'page_id'				 =>   $page_id,
					'description'			 =>   addslashes($request->$description)
					]);
			}
		}
		
		
		$message = Lang::get("labels.PageUpdateMessage");				
		return redirect()->back()->withErrors([$message]);
		}
	}
	
		
	//pageStatus
	public function pageStatus(Request $request){
		if(session('application_setting_update')==0){
				print Lang::get("labels.You do not have to access this route");
			}else{
				
		if(!empty($request->id)){
			if($request->active=='no'){
				$status = '0';
			}elseif($request->active=='yes'){
				$status = '1';
			}
			DB::table('pages')->where('page_id', '=', $request->id)->update([
				'status'		 =>	  $status
				]);	
			}
		
		return redirect()->back()->withErrors([Lang::get("labels.PageStatusMessage")]);
			}
	}
	
	
	//listing web pages
	public function webpages(Request $request){
		if(session('website_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title 			= array('pageTitle' => Lang::get("labels.Pages"));
		$language_id    =   '1';		
		
		$pages = DB::table('pages')
			->leftJoin('pages_description', 'pages_description.page_id', '=', 'pages.page_id')
			->where([
						['pages_description.language_id','=',$language_id],
						['pages.type','=','2']
					])
			->orderBy('pages.page_id', 'ASC')
			->paginate(20);
		
		$result["pages"] = $pages;
		return view("admin.webpages",$title)->with('result', $result);
		}
	}
	
	public function addwebpage(Request $request){
		if(session('website_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddPage"));
		$language_id      =   '1';
		
		$result = array();
		
		//get function from other controller
		$myVar = new AdminNewsCategoriesController();
		$result['newsCategories'] = $myVar->getNewsCategories($language_id);
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
				
		return view("admin.addwebpage", $title)->with('result', $result);
		}
	}
	
	//addNewPage
	public function addnewwebpage(Request $request){
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddPage"));			
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		$slug = str_replace(' ','-' ,trim($request->slug));
		$slug = str_replace('_','-' ,$slug);
		
		$page_id = DB::table('pages')->insertGetId([
					'slug'		 			 =>   $slug,
					'type'		 			 =>   2,
					'status'		 		 =>   $request->status,	
					]);
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			$description = 'description_'.$languages_data->languages_id;
			
			DB::table('pages_description')->insert([
					'name'  	    		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'page_id'				 =>   $page_id,
					'description'			 =>   addslashes($request->$description)
					]);
		}	
		
				
		$message = Lang::get("labels.PageAddedMessage");				
		return redirect()->back()->withErrors([$message]);
		}
	}
		
	//editnew
	public function editwebpage(Request $request){
		if(session('website_setting_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.EditPage"));
		$language_id      =   '1';	
		$page_id     	  =   $request->id;	
		
		$result = array();
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$result['languages'] = $myVar->getLanguages();
		
				
		$pages = DB::table('pages')
			->leftJoin('pages_description', 'pages_description.page_id', '=', 'pages.page_id')
			->select('pages.*','pages_description.description','pages_description.language_id','pages_description.name' ,'pages_description.page_description_id')
			->where('pages.page_id','=', $page_id)
			->get();
			
		$description_data = array();		
		foreach($result['languages'] as $languages_data){
			
			$description = DB::table('pages_description')->where([
					['language_id', '=', $languages_data->languages_id],
					['page_id', '=', $page_id],
				])->get();
				
			if(count($description)>0){								
				$description_data[$languages_data->languages_id]['name'] = $description[0]->name;
				$description_data[$languages_data->languages_id]['description'] = $description[0]->description;
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;										
			}else{
				$description_data[$languages_data->languages_id]['name'] = '';
				$description_data[$languages_data->languages_id]['description'] = '';
				$description_data[$languages_data->languages_id]['language_name'] = $languages_data->name;
				$description_data[$languages_data->languages_id]['languages_id'] = $languages_data->languages_id;	
			}
		}
		
		$result['description'] = $description_data;			
		$result['editPage'] = $pages;
		
		return view("admin.editwebpage", $title)->with('result', $result);	
		}
	}
	
	
	//updatePage
	public function updatewebpage(Request $request){
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$page_id      =   $request->id;	
			
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$languages = $myVar->getLanguages();
		
		$slug = str_replace(' ','-' ,trim($request->slug));
		$slug = str_replace('_','-' ,$slug);
		
		DB::table('pages')->where('page_id','=',$page_id)->update([
					'slug'		 			 =>   $slug,
					'type'		 			 =>   2,
					'status'		 		 =>   $request->status,					
					]);
		
		
		foreach($languages as $languages_data){
			$name = 'name_'.$languages_data->languages_id;
			$description = 'description_'.$languages_data->languages_id;
			
			$checkExist = DB::table('pages_description')->where('page_id','=',$page_id)->where('language_id','=',$languages_data->languages_id)->get();
			
			if(count($checkExist)>0){
				DB::table('pages_description')->where('page_id','=',$page_id)->where('language_id','=',$languages_data->languages_id)->update([
					'name'  	    		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'description'			 =>   addslashes($request->$description)
					]);
			}else{
				DB::table('pages_description')->insert([
					'name'  	    		 =>   $request->$name,
					'language_id'			 =>   $languages_data->languages_id,
					'page_id'				 =>   $page_id,
					'description'			 =>   addslashes($request->$description)
					]);
			}
		}
		
		
		$message = Lang::get("labels.PageUpdateMessage");				
		return redirect()->back()->withErrors([$message]);
		}
	}
	
		
	//pageStatus
	public function pageWebStatus(Request $request){
		if(session('website_setting_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		if(!empty($request->id)){
			if($request->active=='no'){
				$status = '0';
			}elseif($request->active=='yes'){
				$status = '1';
			}
			DB::table('pages')->where('page_id', '=', $request->id)->update([
				'status'		 =>	  $status
				]);	
			}
			
			return redirect()->back()->withErrors([Lang::get("labels.PageStatusMessage")]);
		}
	}
	
	
}
