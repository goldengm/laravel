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
//for redirect
use Illuminate\Support\Facades\Redirect;


//for requesting a value 
use Illuminate\Http\Request;

class AdminCustomersController extends Controller
{	
	//add listingCustomers
	public function customers(Request $request){
		if(session('customers_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.ListingCustomers"));
		$language_id            				=   '1';			
		
		$customerData = array();
		$message = array();
		$errorMessage = array();
		
		$customers = DB::table('customers')
			->LeftJoin('address_book','address_book.address_book_id','=', 'customers.customers_default_address_id')
			->LeftJoin('countries','countries.countries_id','=', 'address_book.entry_country_id')
			->LeftJoin('zones','zones.zone_id','=', 'address_book.entry_zone_id')
			->LeftJoin('customers_info','customers_info.customers_info_id','=', 'customers.customers_id')
			->select('customers.*', 'address_book.entry_gender as entry_gender', 'address_book.entry_company as entry_company', 'address_book.entry_firstname as entry_firstname', 'address_book.entry_lastname as entry_lastname', 'address_book.entry_street_address as entry_street_address', 'address_book.entry_suburb as entry_suburb', 'address_book.entry_postcode as entry_postcode', 'address_book.entry_city as entry_city', 'address_book.entry_state as entry_state', 'countries.*', 'zones.*')
			->orderBy('customers.customers_id','ASC')
			->paginate(50);
			
		$result = array();
		$index = 0;
		foreach($customers as $customers_data){
			array_push($result, $customers_data);
			
			$devices = DB::table('devices')->where('customers_id','=',$customers_data->customers_id)->orderBy('register_date','DESC')->take(1)->get();
			$result[$index]->devices = $devices;
			$index++;
		}
		
		$customerData['message'] = $message;
		$customerData['errorMessage'] = $errorMessage;
		$customerData['result'] = $customers;
		
		return view("admin.customers",$title)->with('customers', $customerData);
		}
	}
	
	//add addcustomers page
	public function addcustomers(Request $request){
		if(session('customers_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.AddCustomer"));
		$language_id            				=   '1';	
		
		$customerData = array();
		$message = array();
		$errorMessage = array();
		
		//get function from ManufacturerController controller
		$myVar = new AddressController();
		$customerData['countries'] = $myVar->getAllCountries();
		
		
		$customerData['message'] = $message;
		$customerData['errorMessage'] = $errorMessage;
		
		return view("admin.addcustomers",$title)->with('customers', $customerData);
		}
	}
	
	//add addcustomers data and redirect to address
	public function addnewcustomers(Request $request){
		if(session('customers_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{		
		$language_id            				=   '1';
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();	
		$extensions = $myVar->imageType();			
		
		$customerData = array();
		$message = array();
		$errorMessage = array();
		
		//check email already exists
		$existEmail = DB::table('customers')->where('email', '=', $request->email)->get();
		if(count($existEmail)>0){
			$title = array('pageTitle' => 'Add Customer');
			
			$customerData['message'] = $message;
			$customerData['errorMessage'] = Lang::get("labels.Email address already exist");
			return view("admin.addcustomers",$title)->with('customers', $customerData);
		}else{
						
			if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
				$image = $request->newImage;
				$fileName = time().'.'.$image->getClientOriginalName();
				$image->move('resources/assets/images/user_profile/', $fileName);
				$customers_picture = 'resources/assets/images/user_profile/'.$fileName; 
			}	else{
				$customers_picture = '';
			}			
			
			$customers_id = DB::table('customers')->insertGetId([
						'customers_gender'   		 	=>   $request->customers_gender,
						'customers_firstname'		 	=>   $request->customers_firstname,
						'customers_lastname'		 	=>   $request->customers_lastname,
						'customers_dob'	 			 	=>	 $request->customers_dob,
						'customers_gender'   		 	=>   $request->customers_gender,
						'email'	 	=>   $request->email,
						'customers_default_address_id' 	=>   $request->customers_default_address_id,
						'customers_telephone'	 		=>	 $request->customers_telephone,
						'customers_fax'   				=>   $request->customers_fax,
						'password'		 				=>   Hash::make($request->password),
						'isActive'		 	 			=>   $request->isActive,
						'customers_picture'	 			=>	 $customers_picture,
						'created_at'					 =>	 time()
						]);
					
			return redirect('admin/addaddress/'.$customers_id);		
		}
		}
	}
	
	
	//addcustomers data and redirect to address
	public function addaddress(Request $request){
		if(session('customers_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		
		$title = array('pageTitle' => Lang::get("labels.AddAddress"));
				
		$language_id            				=   $request->language_id;
		$customers_id            				=   $request->id;		
		
		$customerData = array();
		$message = array();
		$errorMessage = array();
		
		$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('customers_id', '=', $customers_id)->get();	
		
		$countries = DB::table('countries')->get();	
		
		$customerData['message'] = $message;
		$customerData['errorMessage'] = $errorMessage;
		$customerData['customer_addresses'] = $customer_addresses;	
		$customerData['countries'] = $countries;
		$customerData['customers_id'] = $customers_id;	
		
		return view("admin.addaddress",$title)->with('data', $customerData);
		}
	}
	
	//add Customer address
	public function addNewCustomerAddress(Request $request){
		if(session('customers_create')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
				
		$address_id = DB::table('address_book')->insertGetId([
						'customers_id'   		=>   $request->customers_id,
						'entry_gender'		 	=>   $request->entry_gender,
						'entry_company'		 	=>   $request->entry_company,
						'entry_firstname'	 	=>	 $request->entry_firstname,
						'entry_lastname'   		=>   $request->entry_lastname,
						'entry_street_address'	=>   $request->entry_street_address,
						'entry_suburb' 			=>   $request->entry_suburb,
						'entry_postcode'	 	=>	 $request->entry_postcode,
						'entry_city'   			=>   $request->entry_city,
						'entry_state'		 	=>   $request->entry_state,
						'entry_country_id'		=>   $request->entry_country_id,
						'entry_zone_id'	 		=>	 $request->entry_zone_id
						]);
						
		//set default address
		if($request->is_default=='1'){
				DB::table('customers')->where('customers_id','=', $request->customers_id)->update([
						'customers_default_address_id'		 	=>   $address_id
						]);
		}
		
		$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('customers_id', '=', $request->customers_id)->get();
			return ($customer_addresses);
		}
	}
	
	//edit Customers address
	public function editAddress(Request $request){
		if(session('customers_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		
		$customers_id            =   $request->customers_id;	
		$address_book_id         =   $request->address_book_id;	
		
		$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('address_book_id', '=', $address_book_id)->get();	
		
		$countries = DB::table('countries')->get();	
		$zones = DB::table('zones')->where('zone_country_id','=', $customer_addresses[0]->entry_country_id)->get();
		
		$customers = DB::table('customers')->where('customers_id','=', $customers_id)->get();	
		
		$customerData['customers_id'] = $customers_id;	
		$customerData['customer_addresses'] = $customer_addresses;	
		$customerData['countries'] = $countries;
		$customerData['zones'] = $zones;
		$customerData['customers'] = $customers;
		
		return view("admin/editAddressForm")->with('data', $customerData);
		}
	}
	
	//update Customers address
	public function updateAddress(Request $request){
		if(session('customers_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		$customers_id            =   $request->customers_id;	
		$address_book_id         =   $request->address_book_id;	
		
		 DB::table('address_book')->where('address_book_id','=', $address_book_id)->update([
				'entry_gender'		 	=>   $request->entry_gender,
				'entry_company'		 	=>   $request->entry_company,
				'entry_firstname'	 	=>	 $request->entry_firstname,
				'entry_lastname'   		=>   $request->entry_lastname,
				'entry_street_address'	=>   $request->entry_street_address,
				'entry_suburb' 			=>   $request->entry_suburb,
				'entry_postcode'	 	=>	 $request->entry_postcode,
				'entry_city'   			=>   $request->entry_city,
				'entry_state'		 	=>   $request->entry_state,
				'entry_country_id'		=>   $request->entry_country_id,
				'entry_zone_id'	 		=>	 $request->entry_zone_id
				]);
						
		//set default address
		if($request->is_default=='1'){
				DB::table('customers')->where('customers_id','=', $customers_id)->update([
						'customers_default_address_id'		 	=>   $address_book_id
						]);
		}
		
		$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('address_book_id', '=', $address_book_id)->get();	
		
		$countries = DB::table('countries')->get();	
		$zones = DB::table('zones')->where('zone_country_id','=', $customer_addresses[0]->entry_country_id)->get();	
		
		$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('customers_id', '=', $request->customers_id)->get();
			
		return ($customer_addresses);
		}
	}
	
	
	//delete Customers address
	public function deleteAddress(Request $request){
		if(session('customers_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
			
		$customers_id            =   $request->customers_id;	
		$address_book_id         =   $request->address_book_id;	
		
		DB::table('address_book')->where('address_book_id','=', $address_book_id)->delete();
		
		$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('customers_id', '=', $request->customers_id)->get();
			
		return ($customer_addresses);
		}
	}
	
	
	//editcustomers data and redirect to address
	public function editcustomers(Request $request){
		if(session('customers_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.EditCustomer"));
		$language_id             =   '1';	
		$customers_id        	 =   $request->id;			
		
		$customerData = array();
		$message = array();
		$errorMessage = array();
		
		DB::table('customers')->where('customers_id', '=', $customers_id)->update(['is_seen' => 1 ]);
		
		$customers = DB::table('customers')->where('customers_id','=', $customers_id)->get();
		
		$customerData['message'] = $message;
		$customerData['errorMessage'] = $errorMessage;
		$customerData['customers'] = $customers;
		
		return view("admin.editcustomers",$title)->with('data', $customerData);
		}
	}
		
	//add addcustomers data and redirect to address
	public function updatecustomers(Request $request){
		if(session('customers_update')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{		
		$language_id            		=   '1';			
		$customers_id					=	$request->customers_id;
		
		$customerData = array();
		$message = array();
		$errorMessage = array();
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();	
		$extensions = $myVar->imageType();	
				
		if($request->hasFile('newImage') and in_array($request->newImage->extension(), $extensions)){
			$image = $request->newImage;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move('resources/assets/images/user_profile/', $fileName);
			$customers_picture = 'resources/assets/images/user_profile/'.$fileName; 
		}	else{
			$customers_picture = $request->oldImage;
		}		
		
		$customer_data = array(
			'customers_gender'   		 	=>   $request->customers_gender,
			'customers_firstname'		 	=>   $request->customers_firstname,
			'customers_lastname'		 	=>   $request->customers_lastname,
			'customers_dob'	 			 	=>	 $request->customers_dob,
			'customers_gender'   		 	=>   $request->customers_gender,
			'email'	 						=>   $request->email,
			'customers_default_address_id' 	=>   $request->customers_default_address_id,
			'customers_telephone'	 		=>	 $request->customers_telephone,
			'customers_fax'   				=>   $request->customers_fax,
			'isActive'		 	 			=>   $request->isActive,
			'customers_picture'	 			=>	 $customers_picture,
		);
		
		if($request->changePassword == 'yes'){
			$customer_data['password'] = Hash::make($request->password);
		}
		
		//check email already exists
		if($request->old_email_address!=$request->email){
			$existEmail = DB::table('customers')->where('email', '=', $request->email)->get();
			if(count($existEmail)>0){
				$title = array('pageTitle' => Lang::get("labels.EditCustomer"));
				
				$customerData['message'] = $message;
				$customerData['errorMessage'] = 'Email address already exist.';
				return view("admin.editcustomers",$title)->with('customers', $customerData);
			}else{
				DB::table('customers')->where('customers_id', '=', $customers_id)->update($customer_data);					 
				return redirect('admin/addaddress/'.$customers_id);		
			}
		}else{
			DB::table('customers')->where('customers_id', '=', $customers_id)->update($customer_data);					 
			return redirect('admin/addaddress/'.$customers_id);
		}
		}
	}
	
	
	//deleteProduct
	public function deletecustomers(Request $request){
		if(session('customers_delete')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$customers_id = $request->customers_id;
		
		DB::table('customers')->where('customers_id','=', $customers_id)->delete();
		DB::table('address_book')->where('customers_id','=', $customers_id)->delete();
		
		return redirect()->back()->withErrors([Lang::get("labels.DeleteCustomerMessage")]);
		}
	}
	
	
}
