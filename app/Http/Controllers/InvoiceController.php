<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use Carbon\Carbon;



class InvoiceController extends Controller
{
    public function get_all_invoice(){
        $invoices = Invoice::with('customer')->orderBy('id', 'ASC')->get();
        return response()->json([
            'invoices' =>$invoices,
        ],200);
    }

    public function search_invoices(Request $req){
       $search = $req->get('s');
       if ($search != null) {
        $invoices = Invoice::with('customer')
                    ->whereHas('customer', function($query) use ($search) {
                        $query->where('firstname', 'LIKE', "%$search%");
                     })->get();
        return response()->json([
            'invoices' => $invoices,
        ], 200);
    } else {
        return $this->get_all_invoice();
    }
    }

    public function create_invoice(Request $reqest){
        $currentDate = Carbon::now()->format('Y-m-d');

        $counter = Counter::where('key' ,'invoice')->first();
        $random = Counter::where('key' , 'invoice')->first();
        
        $invoice = Invoice::orderBy('id', 'DESC')->first();

        if($invoice){
            $invoice_number = $invoice->id + 1;
            $counters = $counter->value + $invoice_number;
        }else{
            $counters = $counter->value;
        }

        $formData= [
            'number'=>$counter->prefix.$counters,
            'customer_id'=>null,
            'customer'=>null,
            'date'=>$currentDate,
            'due_date'=>null,
            'reference'=>null,
            'discount'=>0,
            'terms and conditions' =>'Default Terms and conditions',
            'tems'=>[
                [
                    'product_id'=>null,
                    'product'=>null,
                    'unit_price'=>0,
                    'quantity'=>1,
                ]
            ],

        ];
        return response()->json($formData,200);
    }

}
