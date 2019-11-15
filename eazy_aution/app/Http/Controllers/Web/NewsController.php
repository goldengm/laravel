<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
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
use Lang;
//for Carbon a value 
use Carbon;

//email
use Illuminate\Support\Facades\Mail;
use Session;



class NewsController extends DataController
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
	
	//getNewsCategories
	public function getNewsCategories(){		
							
		$data 		=	array();				
		$categories = DB::table('news_categories')
			->LeftJoin('news_categories_description', 'news_categories_description.categories_id', '=', 'news_categories.categories_id')
			->select('news_categories.categories_id as id',
				 'news_categories.categories_image as image',
				 'news_categories.news_categories_slug as slug',
				 'news_categories_description.categories_name as name'
				 )
			->where('news_categories_description.language_id','=', Session::get('language_id'))->get();
		
		if(count($categories)>0){			
			foreach($categories as $categories_data){				
				$categories_id = $categories_data->id;
				$news = DB::table('news_categories')
						->LeftJoin('news_to_news_categories', 'news_to_news_categories.categories_id', '=', 'news_categories.categories_id')
						->LeftJoin('news', 'news.news_id', '=', 'news_to_news_categories.news_id')
						->select('news_categories.categories_id', DB::raw('COUNT(DISTINCT news.news_id) as total_news'))
						->where('news_categories.categories_id','=', $categories_id)
						->get();
						
				$categories_data->total_news = $news[0]->total_news;
				array_push($data,$categories_data);
			}
		}			
		return($data);
	}
		
	//news 
	public function news(Request $request){
		
		$title = array('pageTitle' => Lang::get("website.News"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();
				
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 16;
		}
		
		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}
		
		//categories_id		
		if(!empty($request->category) and $request->category!='all'){
			$category = $request->category;
			$categories = DB::table('news_categories')->leftJoin('news_categories_description','news_categories_description.categories_id','=','news_categories.categories_id')->where('news_categories_slug',$category)->where('news_categories_description.language_id',Session::get('language_id'))->get();
			$categories_id = $categories[0]->categories_id;
			$categories_name = $categories[0]->categories_name;
		}else{
			$categories_id = '';
			$categories_name = '';
		}
		
		$data = array('page_number'=>0, 'type'=>$type, 'is_feature'=>'', 'limit'=>$limit, 'categories_id'=>$categories_id, 'load_news'=>0);		
		$news = $this->getAllNews($data);
		$result['news'] = $news;
		
		if($limit > $result['news']['total_record']){		
			$result['limit'] = $result['news']['total_record'];
		}else{
			$result['limit'] = $limit;
		}			
		$result['categories_name'] = $categories_name;
		return view("news", $title)->with('result', $result);
	
	}
	
	
	//loadMoreNews
	public function loadMoreNews(Request $request){
		
		if(!empty($request->page_number)){
			$page_number = $request->page_number;
		}else{
			$page_number = 0;
		}
		
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 16;
		}
		
		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}	
		
		
		//categories_id		
		if(!empty($request->category_id) and $request->category_id!='all'){
			$categories_id = $request->category_id;
		}else{
			$categories_id = '';
		}				
		
		$data = array('page_number'=>$page_number, 'type'=>$type, 'is_feature'=>'', 'limit'=>$limit, 'categories_id'=>$categories_id);		
		$news = $this->getAllNews($data);		
		$result['limit'] = $limit;
		$result['news'] = $news;
		
		return view("loadMoreNews")->with('result', $result);
		
		
	}
	
	//getAllNews 
	public function getAllNews($data){
		
		if(empty($data['page_number']) or $data['page_number'] == 0 ){
			$skip								=   $data['page_number'].'0';
		}else{
			$skip								=   $data['limit']*$data['page_number'];
		}
		
		$currentDate 							=   time();	
		$type									=	$data['type'];
		$take									=   $data['limit'];
		
		if($type=="atoz"){
			$sortby								=	"news_name";
			$order								=	"ASC";
		}elseif($type=="ztoa"){
			$sortby								=	"news_name";
			$order								=	"DESC";
		}elseif($type=="asc"){
			$sortby								=	"news.news_id";
			$order								=	"ASC";
		}else{
			$sortby = "news.news_id";
			$order = "desc";
		}
		
		$categories = DB::table('news_to_news_categories')
			->LeftJoin('news', 'news.news_id', '=', 'news_to_news_categories.news_id');
			$categories->leftJoin('news_description','news_description.news_id','=','news.news_id');
			$categories->select('news.*','news_description.*', 'news_to_news_categories.categories_id');			
					
		//get single category news
		if(!empty($data['categories_id'])){
			$categories->where('news_to_news_categories.categories_id','=', $data['categories_id']);
		}
		
		//get single news
		if(!empty($data['news_id']) && $data['news_id']!=""){
			$categories->where('news.news_id','=', $data['news_id']);
		}
		
		//get featured news
		if($data['is_feature']==1){
			$categories->where('news.is_feature','=', 1);
		}		
		
		$categories->where('news_description.language_id','=',Session::get('language_id'))->orderBy($sortby, $order);
		
		//count
		$total_record = $categories->get();
		
		$data  = $categories->skip($skip)->take($take)->get();
		$result = array();
		$index = 0;
		foreach($data as $news_data){
			array_push($result,$news_data);
			
			$news_description =  $news_data->news_description;
			$result[$index]->news_description = stripslashes($news_description);
			
			$index++;
		}
		
		//check if record exist
		if(count($data)>0){
				$responseData = array('success'=>'1', 'news_data'=>$result,  'message'=>"Returned all news.", 'total_record'=>count($total_record));
			}else{
				$responseData = array('success'=>'0', 'news_data'=>array(),  'message'=>"Empty record.", 'total_record'=>count($total_record));
			}		
						
		return ($responseData);
	}
	
	
	//newsdetail
	public function newsdetail(Request $request){
		$title = array('pageTitle' => Lang::get("website.News Detail"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();
		
		$news = DB::table('news')
					->leftjoin('news_description','news_description.news_id','=','news.news_id')
					->leftjoin('news_to_news_categories','news_to_news_categories.news_id','=','news.news_id')
					->leftjoin('news_categories','news_categories.categories_id','=','news_to_news_categories.categories_id')
					->leftjoin('news_categories_description','news_categories_description.categories_id','=','news_categories.categories_id')
					->where([
						['news.news_slug','=',$request->slug],
						['news_description.language_id','=',Session::get('language_id')],
						['news_categories_description.language_id','=',Session::get('language_id')]
					])->get();
					
		$result['news'] = $news;					
		
		return view("news-detail", $title)->with('result', $result); 
	}
	
}
