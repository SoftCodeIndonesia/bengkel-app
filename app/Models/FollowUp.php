<?php

namespace App\Models;

use App\Models\User;
use App\Models\CustomerVehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_vehicle_id',
        'last_service_date',
        'contacted',
        'contact_date',
        'notes',
        'contact_method',
        'response_status',
        'job_order_id',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'contact_date' => 'date',
        'contacted' => 'boolean'
    ];

    // Relasi ke CustomerVehicle
    public function customerVehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }

    // Relasi ke User (jika ada yang menangani follow up)
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'job_order_id');
    }

    // Scope untuk follow up aktif
    public function scopeActive($query)
    {
        return $query->where('contacted', false)
            ->orWhere('contact_date', '>=', now()->subDays(30));
    }

    // Scope untuk kendaraan yang perlu difollow up
    public function scopeNeedFollowUp($query)
    {
        return $query->where('last_service_date', '<=', now()->subMonths(3))
            ->where(function ($q) {
                $q->where('contacted', false)
                    ->orWhere('contact_date', '<=', now()->subMonths(1));
            });
    }
}
