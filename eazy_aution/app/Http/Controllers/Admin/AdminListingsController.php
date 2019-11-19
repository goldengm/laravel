<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: Tsegts
Author URI: http://vectorcoder.com/

 */
namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use DB;
//for password encryption or hash protected
use Illuminate\Http\Request;

//for authenitcate login data
use Lang;

//for requesting a value

class AdminListingsController extends Controller
{

    public function addlisting(Request $request)
    {
        // if (session('listings_create') == 0) {
        //     print Lang::get("labels.You do not have to access this route");
        // } else {
            $title = array('pageTitle' => Lang::get("labels.AddProduct"));
            $language_id = '1';

            $result = array();

            //get function from other controller
            $myVar = new AdminCategoriesController();
            $result['categories'] = $myVar->allCategories($language_id);

            return view("admin.addlisting", $title)->with('result', $result);
        // }
    }

    //addNewProduct
    public function addnewlisting(Request $request)    
    {        
        if (session('listings_create') == 0) {
            print Lang::get("labels.You do not have to access this route");
        } else {
            $title = array('pageTitle' => Lang::get("labels.AddAttributes"));
            $language_id = '1';
            $date_added = date('Y-m-d h:i:s');

            //get function from other controller
            $myVar = new AdminSiteSettingController();
            $languages = $myVar->getLanguages();
            $extensions = $myVar->imageType();

            $expiryDate = str_replace('/', '-', $request->expires_date);
            $expiryDateFormate = strtotime($expiryDate);

            if ($request->hasFile('listings_image') and in_array($request->listings_image->extension(), $extensions)) {
                $image = $request->products_image;
                $fileName = time() . '.' . $image->getClientOriginalName();
                $image->move('resources/assets/images/product_images/', $fileName);
                $uploadImage = 'resources/assets/images/product_images/' . $fileName;
            } else {
                $uploadImage = '';
            }

            $listings_id = DB::table('listings')->insertGetId([
                'listing_cover_url' => $uploadImage,
                'listing_category' => $request->category,
                'listing_title' => $request->title,
                'listing_desc' => $request->desc,
                'listing_condition' => $request->condition,
                'listing_price' => $request->price,
                'listing_shipping' => $request->shipping,
                'listing_acceptoffer' => $request->acceptoffer,
                'listing_autorelisting' => $request->autorelisting,
                'listing_listdate' => $request->listdate,
                'listing_auction_style' => $request->auction_style,
                'listing_additional_desc' => $request->additional_desc,
                'listing_date_added' => $date_added,
                'listing_last_modified' => $date_added,
            ]);

            $result['data'] = array('listings_id' => $listings_id, 'language_id' => $language_id);

            // //notify users
            // $myVar = new AdminAlertController();
            // $alertSetting = $myVar->newProductNotification($products_id);
            return response()->json([
                "success" => true,
                "listings_id" => $listings_id
            ]);
        }
    }

}
