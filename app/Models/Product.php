<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'part_number',
        'description',
        'margin',
        'buying_price',
        'tipe',
        'unit_price',
        'stok',
        'supplier_id',
        'part_number',
        'barcode',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
