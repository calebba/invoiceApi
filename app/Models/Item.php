<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'product_id',
        'item_name',
        'item_quantity',
        'item_unit_price',
        'item_cost',
        'description',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
