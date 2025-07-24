<?php

namespace App\Models;

use App\Models\User;
use App\Models\JobOrder;
use App\Models\SupplyItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_order_id',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class);
    }


    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
