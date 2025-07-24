<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\JobOrder;
use App\Models\CustomerVehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id',
        'tipe',
        'reference_id',
        'customer_id',
        'customer_name',
        'customer_address',
        'status',
        'subtotal',
        'diskon_unit',
        'diskon_value',
        'total',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reference()
    {
        if ($this->tipe == 'sales') {
            return $this->belongsTo(Sales::class, 'reference_id');
        } else {
            return $this->belongsTo(JobOrder::class, 'reference_id');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->unique_id = 'INV-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
