<?php

namespace app\Http\Controllers;
use app\Http\Requests;
use app\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Usps;

class USPSController extends Controller
{
    public function index() {
        return response()->json(
            Usps::validate( 
                Request::input('Address'), 
                Request::input('Zip'), 
                Request::input('Apartment'), 
                Request::input('City'), 
                Request::input('State')
            )
        );
    }

    public function trackConfirm() {
        return response()->json(
            Usps::trackConfirm( 
                Request::input('id')
            )
        );
    }

    public function trackConfirmRevision1() {
        return response()->json(
            Usps::trackConfirm( 
                Request::input('id'),
                'Acme, Inc'
            )
        );
    }
}