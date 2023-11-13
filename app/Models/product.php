<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'amount', 'description'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
