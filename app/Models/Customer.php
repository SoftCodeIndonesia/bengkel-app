<?php

namespace App\Models;

use App\Models\Vehicle;
use App\Models\JobOrder;
use App\Models\CustomerVehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'customer_vehicle')
            ->withTimestamps();
    }

    public function jobOrders()
    {
        return $this->hasManyThrough(
            JobOrder::class,
            CustomerVehicle::class,
            'customer_id',
            'customer_vehicle_id'
        );
    }
}
