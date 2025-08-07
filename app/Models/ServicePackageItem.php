<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_package_id',
        'product_id',
        'quantity',
        'discount',
        'discount_unit',
        'subtotal',
        'total',
    ];

    public function package()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
