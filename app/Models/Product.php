<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\SalesItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'slug',
        'grade',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function salesItems()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function getTotalSoldAttribute()
    {
        $jobOrdersCount = $this->orderItems()
            ->whereHas('jobOrder', function ($q) {
                $q->where('status', 'completed')
                    ->whereMonth('service_at', now()->month);
            })
            ->sum('quantity');

        $salesOrdersCount = $this->salesItems()
            ->whereHas('sale', function ($q) {
                $q->whereMonth('created_at', now()->month);
            })
            ->sum('quantity');

        return $jobOrdersCount + $salesOrdersCount;
    }

    public function getTotalRevenueAttribute()
    {
        $jobOrdersRevenue = $this->orderItems()
            ->whereHas('jobOrder', function ($q) {
                $q->where('status', 'completed')
                    ->whereMonth('service_at', now()->month);
            })
            ->sum(DB::raw('quantity * unit_price'));

        $salesOrdersRevenue = $this->salesItems()
            ->whereHas('sale', function ($q) {
                $q->whereMonth('created_at', now()->month);
            })
            ->sum(DB::raw('quantity * unit_price'));

        return $jobOrdersRevenue + $salesOrdersRevenue;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name . ' ' . $product->grade);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name . ' ' . $product->grade);
            }
        });
    }
}
