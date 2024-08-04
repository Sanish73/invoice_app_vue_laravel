<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Counter;
use Carbon\Carbon;
class InvoiceController extends Controller
{
    public function get_all_invoice(Request $request){
        $perPage = $request->input('per_page',5);  
        $invoices = Invoice::with('customer')->orderBy('id', 'ASC')->paginate($perPage);
            return response()->json([
                'invoices' =>$invoices,
            ],200);
    }


    public function show_Selected_Invoices($id)
    {
        $invoice = Invoice::with(['customer' , 'invoice_item.product_list'])->find($id);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json($invoice);
    }

    public function add_invoice(Request $request)
    {
        $rules = [
            'Customer_Id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'number' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'terms_and_conditions' => 'nullable|string',
            'invoice_item' => 'required|json',
        ];
        $validatedData = $request->validate($rules);

        $invoiceItemsJson = $validatedData['invoice_item'];
        $invoiceItemsArray = json_decode($invoiceItemsJson, true);

        $invoiceData = [
            'Customer_Id' => $validatedData['Customer_Id'],
            'date' => $validatedData['date'],
            'due_date' => $validatedData['due_date'],
            'number' => $validatedData['number'],
            'reference' => $validatedData['reference'],
            'discount' => $validatedData['discount'],
            'sub_total' => $validatedData['subtotal'],
            'total' => $validatedData['total'],
            'terms_and_conditions' => $validatedData['terms_and_conditions'],
        ];

        try {
            
            $invoice = Invoice::create($invoiceData);
                foreach ($invoiceItemsArray as $item) {
                    $itemData = [
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'product_id' => $item['id'],
                        'invoice_id' => $invoice->id,
                    ];
                    $invoiceItem = InvoiceItem::create($itemData);
                       
                }
            return response()->json([
                'success' => true,
                'message' => 'Invoice added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage()
            ], 500);
        }
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
            'terms_and_conditions' =>'Default Terms and conditions',
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


    public function get_All_Invoice_customer()
    {
        $invoice = Invoice::with('customer','invoice_item')->get();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json($invoice, 200);
    }

    

}
