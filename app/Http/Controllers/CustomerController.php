<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function all_customers(Request $reqest){
        $customers  =Customer::orderBy('id' , 'DESC')->get();
        return response()->json([
            'customers' => $customers
        ] , 200);
    }
}
