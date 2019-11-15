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
//for Carbon a value 
use Carbon;
use Illuminate\Support\Facades\Redirect;
use Session;
use Lang;

//email
use Illuminate\Support\Facades\Mail;

class ShippingAddressController extends DataController
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /* public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	//get all countries
	public function countries(){
		
		$allCountries = DB::table('countries')->get();	
		return($allCountries);
		
	}
	
	//get all zones
	public function ajaxZones(Request $request){
			
		$getZones = $this->zones($request->country_id);	
			
		return($getZones);
		
	}
	
	//get all zones
	public function zones($country_id){
			
		$zones = DB::table('zones');
		
		if(!empty($country_id)){
			$zones->where('zone_country_id', $country_id);	
		}
		
		$getZones = $zones->get();
		return($getZones);
		
	}
	
	//get all customer addresses url 
	public function shippingAddress(Request $request){
		
		$title = array('pageTitle' => Lang::get('website.Shipping Address'));
		$result = array();		
		$result['commonContent'] = $this->commonContent();
		
		//print_r($request->update);
		if(!empty($request->action)){
			$result['action'] = $request->action;			
		}else{
			$result['action'] = '';
		}
		
		// address book		
		$result['address'] = $this->getShippingAddress($address_id=''); 
		$result['countries'] = $this->countries(); 
		
		//edit address
		if(!empty($request->address_id)){
			$result['editAddress'] = $this->getShippingAddress($request->address_id); 
			$result['zones'] = $this->zones($result['editAddress'][0]->countries_id);
			
		}else{
			$result['editAddress'] = '';
			$result['zones']	   = '';
		}
		
		return view("shipping-address", $title)->with('result', $result); 
					
	}
	
	//get all customer addresses url 
	public function getShippingAddress($address_id){	
		
		$addresses = DB::table('address_book')
					->leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
					->leftJoin('zones', 'zones.zone_id', '=' ,'address_book.entry_zone_id')
					->leftJoin('customers', 'customers.customers_default_address_id', '=' , 'address_book.address_book_id')
					->select(
							'address_book.address_book_id as address_id',
							'address_book.entry_gender as gender',
							'address_book.entry_company as company',
							'address_book.entry_firstname as firstname',
							'address_book.entry_lastname as lastname',
							'address_book.entry_street_address as street',
							'address_book.entry_suburb as suburb',
							'address_book.entry_postcode as postcode',
							'address_book.entry_city as city',
							'address_book.entry_state as state',
							
							'countries.countries_id as countries_id',
							'countries.countries_name as country_name',
							
							'zones.zone_id as zone_id',
							'zones.zone_code as zone_code',
							'zones.zone_name as zone_name',
							'customers.customers_default_address_id as default_address'
							)
					->where('address_book.customers_id', auth()->guard('customer')->user()->customers_id);
		
		if(!empty($address_id)){
			$addresses->where('address_book_id', '=', $address_id);
		}
					$result = $addresses->get();
		
		return $result;
					
	}
	
	public function addMyAddress(Request $request){
		
		$customers_id            				=   auth()->guard('customer')->user()->customers_id;
		$entry_firstname            		    =   $request->entry_firstname;
		$entry_lastname             		    =   $request->entry_lastname;
		$entry_street_address       		    =   $request->entry_street_address;
		$entry_suburb             				=   $request->entry_suburb;
		$entry_postcode             			=   $request->entry_postcode;
		$entry_city             				=   $request->entry_city;
		$entry_state             				=   $request->entry_state;
		$entry_country_id             			=   $request->entry_country_id;
		$entry_zone_id             				=   $request->entry_zone_id;
		$entry_gender							=   $request->entry_gender;
		$entry_company							=   $request->entry_company;
		$customers_default_address_id			=   $request->customers_default_address_id;
							
		if(!empty($customers_id)){		
			$address_book_data = array(
				'entry_firstname'               =>   $entry_firstname,
				'entry_lastname'                =>   $entry_lastname,
				'entry_street_address'          =>   $entry_street_address,
				'entry_suburb'             		=>   $entry_suburb,
				'entry_postcode'            	=>   $entry_postcode,
				'entry_city'             		=>   $entry_city,
				'entry_state'            		=>   $entry_state,
				'entry_country_id'            	=>   $entry_country_id,
				'entry_zone_id'             	=>   $entry_zone_id,
				'customers_id'             		=>   $customers_id,
				'entry_gender'					=>   $entry_gender,
				'entry_company'					=>   $entry_company
			);	
			
			//add address into address book
			$address_book_id = DB::table('address_book')->insertGetId($address_book_data);
			
			//default address id
			if($customers_default_address_id == '1'){
				DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $address_book_id]);
			}
		}
		
		return redirect()->back()->with('success', 'Your address has been added successfully!');
		
	}
	
	
	//update shipping address 
	public function updateAddress(Request $request){
		
		$customers_id            				=   auth()->guard('customer')->user()->customers_id;
		$address_book_id            			=   $request->address_book_id;	
		$entry_firstname            		    =   $request->entry_firstname;
		$entry_lastname             		    =   $request->entry_lastname;
		$entry_street_address       		    =   $request->entry_street_address;
		$entry_suburb             				=   $request->entry_suburb;
		$entry_postcode             			=   $request->entry_postcode;
		$entry_city             				=   $request->entry_city;
		$entry_state             				=   $request->entry_state;
		$entry_country_id             			=   $request->entry_country_id;
		$entry_zone_id             				=   $request->entry_zone_id;	
		$entry_gender							=   $request->entry_gender;
		$entry_company							=   $request->entry_company;
		$customers_default_address_id			=   $request->customers_default_address_id;
							
		if(!empty($customers_id)){
		
			$address_book_data = array(
				'entry_firstname'               =>   $entry_firstname,
				'entry_lastname'                =>   $entry_lastname,
				'entry_street_address'          =>   $entry_street_address,
				'entry_suburb'             		=>   $entry_suburb,
				'entry_postcode'            	=>   $entry_postcode,
				'entry_city'             		=>   $entry_city,
				'entry_state'            		=>   $entry_state,
				'entry_country_id'            	=>   $entry_country_id,
				'entry_zone_id'             	=>   $entry_zone_id,
				'customers_id'             		=>   $customers_id,
				'entry_gender'					=>   $entry_gender,
				'entry_company'					=>   $entry_company
			);	
			
			//add address into address book
			DB::table('address_book')->where('address_book_id', $address_book_id)->update($address_book_data);
			
			//default address id
			if($customers_default_address_id == '1'){
				DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $address_book_id]);
			}
			return redirect('shipping-address?action=update');
		}
					
	}
	
	//delete shipping address 
	public function deleteAddress(Request $request){
		
		$customers_id            				=   auth()->guard('customer')->user()->customers_id;
		$address_book_id            			=   $request->address_id;	
							
		if(!empty($customers_id)){
		
			//delete address into address book
			DB::table('address_book')->where('address_book_id', $address_book_id)->delete();
			
			$defaultAddress = DB::table('customers')->where([['customers_id', $customers_id],
										 ['customers_default_address_id', $address_book_id],])->get();
			if(count($defaultAddress)>0){
				//default address id
				$customers_default_address_id = '0';
				DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $customers_default_address_id]);
			}
			
			//$address_book_data = DB::table('address_book')->get();
		}

		print 'success';
					
	}
	
	
	
	//update shipping address 
	public function myDefaultAddress(Request $request){
		
		$customers_id   	=   auth()->guard('customer')->user()->customers_id;	
		$address_book_id	=   $request->address_id;
		
		DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $address_book_id]);
		
	}
}