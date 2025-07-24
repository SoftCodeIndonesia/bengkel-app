<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\CustomerVehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['merk', 'tipe', 'no_pol'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_vehicle')
            ->withTimestamps();
    }

    public function jobOrders()
    {
        return $this->hasManyThrough(
            JobOrder::class,
            CustomerVehicle::class,
            'vehicle_id', // Foreign key on customer_vehicle table
            'customer_vehicle_id', // Foreign key on job_orders table
            'id', // Local key on vehicles table
            'id' // Local key on customer_vehicle table
        );
    }
}
