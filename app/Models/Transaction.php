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
}
