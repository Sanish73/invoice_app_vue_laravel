<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get_all_invoice',[InvoiceController::class , 'get_all_invoice']);
Route::get('/search_invoices',[InvoiceController::class , 'search_invoices']);
Route::get('/create_invoice',[InvoiceController::class , 'create_invoice']);
Route::get('/customers',[CustomerController::class , 'all_customers']);
Route::get('/products',[ProductController::class , 'all_products']);
Route::post('/add_invoice',[InvoiceController::class , 'add_invoice']);
// Route::post('/showSelectedInvoices',[InvoiceController::class , 'show_Selected_Invoices']);
Route::get('/show_Invoices_selec/{id}', [InvoiceController::class, 'show_Selected_Invoices']);
Route::get('/get_All_Invoice_customer', [InvoiceController::class, 'get_All_Invoice_customer']);