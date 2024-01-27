<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayWork extends Model
{
    use HasFactory;

    protected $table = 'day_works';

    public function dokter()
    {
        return $this->hasOne(User::class,'id','dokter_id');
    }
}
