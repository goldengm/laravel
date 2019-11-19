<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Message;
use App\ChatRooms;

use Mail;
use DB;
//for password encryption or hash protected
use Hash;
use DateTime;

class FollowController extends Controller
{
    public function getFollowers($customer_id) {
        $followers = DB::table('followers')
                        ->leftJoin('customers', 'followers.follower_id', "=", "customers.customers_id")
                        ->select('followers.id as id',
                                 'customers.customers_id',
                                 'customers.customers_firstname',
                                 'customers.customers_lastname',
                                 'customers.customers_picture',
                                 'customers.user_name')
                        ->where('followers.following_id', $customer_id)
                        ->get();
        if (count($followers) > 0) {
            $responseData = array('success'=>'1', 'data'=>$followers, 'message'=>'Returned followers');
        }else {
            $responseData = array('success'=>'0', 'data'=>array(), 'message'=>'No data');
        }
        print json_encode($responseData);
    }

    public function getFollowing($customer_id) {
        $followers = DB::table('followers')
                        ->leftJoin('customers', 'followers.follower_id', "=", "customers.customers_id")
                        ->select('followers.id as id',
                                 'customers.customers_id',
                                 'customers.customers_firstname',
                                 'customers.customers_lastname',
                                 'customers.customers_picture',
                                 'customers.user_name')
                        ->where('followers.follower_id', $customer_id)
                        ->get();
        if (count($followers) > 0) {
            $responseData = array('success'=>'1', 'data'=>$followers, 'message'=>'Returned followering');
        }else {
            $responseData = array('success'=>'0', 'data'=>array(), 'message'=>'Returned followers');
        }
        print json_encode($responseData);
    }

    public function followingUser(Request $request) {
        $following_id = $request->following_id;
        $follower_id = $request->follower_id;
        $consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
        $authenticate = $authController->apiAuthenticate($consumer_data);
        if ($authenticate == 1) {
            $row = [
                "following_id" => $following_id,
                "follower_id" => $follower_id
            ];
            $row_id = DB::table('followers')->insertGetId($row);
            if ($row_id > 0 ) {
                $responseData = array('success'=>'1', 'data'=>$row,  'message'=>"Following request successfully processed");
            }else {
                $responseData = array('success'=>'0', 'data'=>array(),  'message'=>"No data");
            }
        }else {
            $responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
        }
        print json_encode($responseData);
    }

    public function unFollowUser(Request $request) {
        $following_id = $request->following_id;
        $follower_id = $request->follower_id;
        $consumer_data 		 				  =  array();
		$consumer_data['consumer_key'] 	 	  =  request()->header('consumer-key');
		$consumer_data['consumer_secret']	  =  request()->header('consumer-secret');
		$consumer_data['consumer_nonce']	  =  request()->header('consumer-nonce');	
		$consumer_data['consumer_device_id']  =  request()->header('consumer-device-id');	
		$consumer_data['consumer_url']  	  =  __FUNCTION__;
		$authController = new AppSettingController();
        $authenticate = $authController->apiAuthenticate($consumer_data);
        if ($authenticate == 1) {
            DB::table('followers')->where('following_id', $following_id)
                                  ->where('follower_id', $follower_id)->delete();
            $responseData = array('success'=>'1', 'data'=>$row,  'message'=>"Unfollowed successfully.");
        }else {
            $responseData = array('success'=>'0', 'data'=>array(),  'message'=>"Unauthenticated call.");
        }
        print json_encode($responseData);
    }
}
