<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/

*/
namespace App\Http\Controllers\Admin;


use Validator;

use DB;
//for password encryption or hash protected
use Hash;
use App\Administrator;

//for authenitcate login data
use Auth;



//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AddressController extends Controller
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
	public function getAllCountries(){		
		$allCountries = DB::table('countries')->get();	
		return($allCountries);
	}
	
	//get all zones
	public function getZones(Request $request){
		$getZones = DB::table('zones')->where('zone_country_id', $request->zone_country_id)->get();	
		
		$responseData = array('success'=>'1', 'data'=>$getZones, 'message'=>"Returned all states.");
		$zoneResponse = json_encode($responseData);
		print $zoneResponse;
	}
	
	//get all customer addresses url 
	public function getAllAddress($customers_id){
			
		//add address into address book
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
					->where('address_book.customers_id', $customers_id)->get();
		
		return($addresses);
					
	}
	//add shipping addShippingAddress 
	public function addShippingAddress(Request $request){
		
		$customers_id            				=   $request->customers_id;
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
			
			//$address_book_data = DB::table('address_book')->get();
		}
		$address_book_data = array();
		$responseData = array('success'=>'1', 'data'=>$address_book_data, 'message'=>"Shipping address has been added successfully!");
		$shippingResponse = json_encode($responseData);
		print $shippingResponse;
					
	}
	
	
	//update shipping address 
	public function updateShippingAddress(Request $request){
		
		$customers_id            				=   $request->customers_id;
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
			
			//$address_book_data = DB::table('address_book')->get();
		}
		$address_book_data = array();
		$responseData = array('success'=>'1', 'data'=>$address_book_data, 'message'=>"Shipping address has been updated successfully!");
		$shippingResponse = json_encode($responseData);
		print $shippingResponse;
					
	}
	
	//delete shipping address 
	public function deleteShippingAddress(Request $request){
		
		$customers_id            				=   $request->customers_id;
		$address_book_id            			=   $request->address_book_id;	
							
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
		$address_book_data = array();
		$responseData = array('success'=>'1', 'data'=>$address_book_data, 'message'=>"Shipping address has been deleted successfully!");
		$shippingResponse = json_encode($responseData);
		print $shippingResponse;
					
	}
	
	
	
	//update shipping address 
	public function updateDefaultAddress(Request $request){
		
		$customers_id   	=   $request->customers_id;	
		$address_book_id	=   $request->address_book_id;
		
		DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $address_book_id]);
		
		$addresses_data = array();
		$responseData = array('success'=>'1', 'data'=>$addresses_data, 'message'=>"Default address has been changed successfully");
		print json_encode($responseData);
	}
}