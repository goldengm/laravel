<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use DB;
//for password encryption or hash protected
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function postlisting(Request $request)
    {
        $post_params = $request->all();
        $listing_id = DB::table('listing')->insertGetId([
            'listing_title' => $post_params['title'],
            'listing_desc' => $post_params['description'],
        ]);

        if ($listing_id > 0) {        
            $responseData = [
                'success' => 1,
                'data' => $post_params,
                'message' => "Listing saved successfully.",
            ];

        } else {
            $responseData = [
                'success' => 0,
                'data' => array(),
                'message' => 'Error while creating listing entry',
            ];
        }
        print json_encode($responseData);
    }
}
