<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table="transactions";

    public function bisnis()
    {
        return $this->belongsTo(Bisnis::class,'bisnis_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class,'cabang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function payment()
    {
        return $this->belongsTo(PayMethod::class,'payment_id');
    }

    public function detail()
    {
        return $this->hasMany(TransactionDetail::class,'transaction_id');
    }
}
