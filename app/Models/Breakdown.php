<?php

namespace App\Models;

use App\Models\JobOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Breakdown extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'name'];

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }
}
