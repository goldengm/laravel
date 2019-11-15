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


class AdminTaxController extends Controller
{
	//listingCountries
	public function countries(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingCountries"));		
		
		$countryData = array();
		$message = array();
		$errorMessage = array();
		
		$countries = DB::table('countries')
			->orderBy('countries_id','ASC')
			->paginate(60);
		
		
		$countryData['message'] = $message;
		$countryData['countries'] = $countries;
		
		return view("admin.countries",$title)->with('countryData', $countryData);
	}
	
	//addcountry
	public function addcountry(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddCountry") );
		
		$countryData = array();
		$message = array();
		$errorMessage = array();
		
		$countryData['message'] = $message;
		
		return view("admin.addcountry",$title)->with('countryData', $countryData);
	}
		
	//addnewcountry	
	public function addnewcountry(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.EditCountry"));
		$countryData = array();
		
		$categories_id = DB::table('countries')->insertGetId([
					'countries_name'  		 =>   $request->countries_name,
					'countries_iso_code_2'	 =>   $request->countries_iso_code_2,
					'countries_iso_code_3'	 =>   $request->countries_iso_code_3
					]);
									
		$countryData['message'] = Lang::get("labels.CountryAddedMessage");
		return view('admin.addcountry', $title)->with('countryData', $countryData);
	}
	
	//editCountry
	public function editcountry(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditCountry"));
		$countryData = array();		
		$countryData['message'] = array();
		
		$country = DB::table('countries')->where('countries_id', $request->id)->get();		
		$countryData['country'] = $country;		
		
		return view("admin.editcountry",$title)->with('countryData', $countryData);
	}
	
	//updatecountry
	public function updatecountry(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.EditCountry"));
		
		$countryData = array();		
		$countryData['message'] = Lang::get("labels.CountryUpdatedMessage");
				
		$countryUpdate = DB::table('countries')->where('countries_id', $request->id)->update([
					'countries_name'  		 =>   $request->countries_name,
					'countries_iso_code_2'	 =>   $request->countries_iso_code_2,
					'countries_iso_code_3'	 =>   $request->countries_iso_code_3
					]);
			
		$country = DB::table('countries')->where('countries_id', $request->id)->get();		
		$countryData['country'] = $country;	
		
		return view("admin.editcountry",$title)->with('countryData', $countryData);
	}
	
	//deletecountry
	public function deletecountry(Request $request){
		DB::table('countries')->where('countries_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.CountryDeletedMessage")]);
	}
	
	
	//listingzONES
	public function listingZones(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingZones"));		
		
		$result = array();
		$message = array();
		$errorMessage = array();
			
		$zones = DB::table('zones')
			->LeftJoin('countries','zones.zone_country_id','=','countries.countries_id')
			->paginate(60);
		
		
		$result['message'] = $message;
		$result['zones'] = $zones;
		
		return view("admin.listingZones",$title)->with('result', $result);
	}
	
	//addcountry
	public function addZone(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddZone"));
		
		$result = array();
		$message = array();
		
		$countries = DB::table('countries')->get();
		$result['countries'] = $countries;		
		$result['message'] = $message;
		
		return view("admin.addZone", $title)->with('result', $result);
	}
	
	//addNewZone	
	public function addNewZone(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.AddCountry"));
		$result = array();
		
		$zone_id = DB::table('zones')->insertGetId([
					'zone_country_id'  	=>   $request->zone_country_id,
					'zone_code'	 		=>   $request->zone_code,
					'zone_name'			=>   $request->zone_name
					]);
		
		$countries = DB::table('countries')->get();
		$result['countries'] = $countries;	
									
		$result['message'] = Lang::get("labels.ZoneAddedMessage");
		return view('admin.addZone', $title)->with('result', $result);
	}
	
	//editZone
	public function editZone(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditZone"));
		$result = array();		
		$result['message'] = array();
		
		$zones = DB::table('zones')->where('zone_id', $request->id)->get();
		$countries = DB::table('countries')->get();		
		$result['countries'] = $countries;
		$result['zones'] = $zones;			
		
		return view("admin.editZone",$title)->with('result', $result);
	}
	
	//updateZone
	public function updateZone(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.EditCountry"));
		
		$countryData = array();		
		$countryData['message'] = 'Zone has been updated successfully!';
				
		$countryUpdate = DB::table('zones')->where('zone_id', $request->zone_id)->update([
					'zone_name'  		 =>   $request->zone_name,
					'zone_code'			 =>   $request->zone_code,
					'zone_country_id'	 =>   $request->zone_country_id
					]);
			
		$country = DB::table('countries')->where('countries_id', $request->id)->get();		
		$countryData['country'] = $country;	
				
		return redirect()->back()->withErrors([Lang::get("labels.ZoneUpdatedTax")]);
	}
	
	//deleteZone
	public function deleteZone(Request $request){
		DB::table('zones')->where('zone_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.ZoneDeletedTax")]);
	}
	
	//taxclass
	public function taxclass(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingTaxClasses"));		
		
		$result = array();
		$message = array();
			
		$tax_class = DB::table('tax_class')->paginate(40);
		
		$result['message'] = $message;
		$result['tax_class'] = $tax_class;
		
		return view("admin.taxclass", $title)->with('result', $result);
	}
	
	//addtaxclass
	public function addtaxclass(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddTaxClass"));
		
		$result = array();
		$message = array();
			
		$result['message'] = $message;
		
		return view("admin.addtaxclass", $title)->with('result', $result);
	}
	
	//addNewZone	
	public function addnewtaxclass(Request $request){
		
		DB::table('tax_class')->insert([
				'tax_class_title'  		 =>   $request->tax_class_title,
				'tax_class_description'	 =>   $request->tax_class_description,
				'date_added'	 		 =>   date('Y-m-d H:i:s')
				]);
										
		$message = Lang::get("labels.TaxClassAddedTax");
		return redirect()->back()->withErrors([$message]);
	}
	
	//edittaxclass
	public function edittaxclass(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditCountry"));
		$result = array();		
		$result['message'] = array();
		
		$taxClass = DB::table('tax_class')->where('tax_class_id', $request->id)->get();
		$result['taxClass'] = $taxClass;			
		
		return view("admin.edittaxclass",$title)->with('result', $result);
	}
	
	//updatetaxclass
	public function updatetaxclass(Request $request){
		
		$title = array('pageTitle' => Lang::get("labels.EditCountry"));
		
		$countryData = array();		
		$message = Lang::get("labels.TaxClassUpdatedTax");
				
		$countryUpdate = DB::table('tax_class')->where('tax_class_id', $request->tax_class_id)->update([
					'tax_class_title'  		 =>   $request->tax_class_title,
					'tax_class_description'	 =>   $request->tax_class_description,
					'last_modified'	 		 =>   date('Y-m-d H:i:s')
					]);
				
		return redirect()->back()->withErrors([$message ]);
	}
	
	//deletetaxclass
	public function deletetaxclass(Request $request){
		DB::table('tax_class')->where('tax_class_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.TaxClassDeletedTax")]);
	}
	
	
	//listingTaxRates
	public function taxrates(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingTaxRates"));		
		
		$result = array();
		$message = array();
			
		$tax_rates = DB::table('tax_rates')
					->LeftJoin('zones','zones.zone_id', '=', 'tax_rates.tax_zone_id')
					->LeftJoin('tax_class','tax_class.tax_class_id', '=', 'tax_rates.tax_class_id')
					->paginate(40);
					
		$result['tax_rates'] = $tax_rates;
		return view("admin.taxrates", $title)->with('result', $result);
	}
	
	//addTaxRate
	public function addtaxrate(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddTaxClass"));
		
		$result = array();
		$message = array();
			
		$result['message'] = $message;
		
		$zones = DB::table('zones')->get();
		$tax_class = DB::table('tax_class')->get();
		$result['zones'] = $zones;
		$result['tax_class'] = $tax_class;
		
		return view("admin.addtaxrate", $title)->with('result', $result);
	}
	
	//addNewTaxRate	
	public function addnewtaxrate(Request $request){
		DB::table('tax_rates')->insert([
				'tax_class_id'  	 =>   $request->tax_class_id,
				'tax_zone_id'		 =>   $request->tax_zone_id,
				'tax_description'	 =>   $request->tax_description,
				'tax_rate'	 		 =>   $request->tax_rate,
				'date_added'	 	 =>   date('Y-m-d H:i:s')
				]);
										
		$message = Lang::get("labels.TaxRateAddededTax");
		return redirect()->back()->withErrors([$message]);
	}
	
	//editTaxRate
	public function edittaxrate(Request $request){		
		$title = array('pageTitle' => Lang::get("labels.EditTaxRate"));
		$result = array();		
		$result['message'] = array();
		
					
		$taxClass = DB::table('tax_rates')->where('tax_rates_id', $request->id)->get();
		$result['taxClass'] = $taxClass;	
		
		$zones = DB::table('zones')->get();
		$tax_class = DB::table('tax_class')->get();
		$result['zones'] = $zones;
		$result['tax_class'] = $tax_class;		
		
		return view("admin.edittaxrate",$title)->with('result', $result);
	}
	
	//updateTaxRate	
	public function updatetaxrate(Request $request){
		DB::table('tax_rates')->where('tax_rates_id', '=', $request->id)->update([
				'tax_class_id'  	 =>   $request->tax_class_id,
				'tax_zone_id'		 =>   $request->tax_zone_id,
				'tax_description'	 =>   $request->tax_description,
				'tax_rate'	 		 =>   $request->tax_rate,
				'last_modified'	 	 =>   date('Y-m-d H:i:s')
				]); 
										
		$message = Lang::get("labels.TaxRateUpdatedTax");
		return redirect()->back()->withErrors([$message]);
	}
	
	//deleteTaxRate
	public function deletetaxrate(Request $request){
		DB::table('tax_rates')->where('tax_rates_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.TaxRateDeletedTax")]);
	}
}
