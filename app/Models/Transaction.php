<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function pasien()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function dokter()
    {
        return $this->hasOne(User::class,'id','dokter_id');
    }

    public function detailMedicine()
    {
        return $this->hasMany(TransactionDetail::class,'transaction_id','id')->whereNotNull('medicine_id');
    }

    public function detailService()
    {
        return $this->hasMany(TransactionDetail::class,'transaction_id','id')->whereNull('medicine_id');
    }
}
