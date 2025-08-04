<?php

namespace App\Models;

use App\Models\ServicePackageItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'total_discount',
        'discount_unit',
        'subtotal',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(ServicePackageItem::class);
    }
}
