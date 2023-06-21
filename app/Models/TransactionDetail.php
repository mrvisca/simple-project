<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    public function produk()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
