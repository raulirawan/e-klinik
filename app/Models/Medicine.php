<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'harga',
    ];

    public function stockMedicine()
    {
        return $this->hasMany(Stock::class,'medicine_id','id');
    }
}
