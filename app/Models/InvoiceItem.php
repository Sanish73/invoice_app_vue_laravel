<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable=[
       'quantity',
        'unit_price' ,
        'invoice_id',
        'product_id'
    ];

    public function product_list(){
        return $this->belongsTo(Product::class , 'product_id');
    }
}
