<?php

namespace App\Models;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\JobOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_vehicle';
    protected $fillable = ['customer_id', 'vehicle_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeNeedsFollowUp($query)
    {
        return $query->whereHas('jobOrders', function ($q) {
            $q->whereBetween('service_at', [
                now()->subMonths(6)->toDateTimeString(),
                now()->subMonths(3)->toDateTimeString()
            ])
                ->orderBy('service_at', 'desc')
                ->limit(1);
        });
    }

    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }

    public function latestJobOrder()
    {
        return $this->hasOne(JobOrder::class)->latest();
    }
}
