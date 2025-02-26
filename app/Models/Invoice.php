<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable=[
        'Customer_Id',
        'date',
        'due_date',
        'number',
        'reference',
        'discount',
        'sub_total',
        'total',
        'terms_and_conditions'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function invoice_item(){
        return $this->hasMany(InvoiceItem::class);
    }
}
