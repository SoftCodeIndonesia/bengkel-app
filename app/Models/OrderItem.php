<?php

namespace App\Models;

use App\Models\Product;
use App\Models\JobOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'order_id', 'quantity', 'unit_price', 'total_price', 'diskon_value', 'price_after_diskon'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }

    public function calculateTotal()
    {
        $subtotal = $this->unit_price * $this->quantity;

        $diskon = $this->diskon_value;

        $this->price_after_diskon = $subtotal - $diskon;
        $this->total_price = $this->price_after_diskon;
        $this->save();
    }
}
