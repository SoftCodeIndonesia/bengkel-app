<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'name',
        'email',
        'phone',
        'address',
        'position',
        'hire_date',
        'salary'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
