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

    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }
}
