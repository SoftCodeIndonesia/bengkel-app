<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes'
    ];

    protected $statusColors = [
        'present' => 'bg-green-600 text-white',
        'late' => 'bg-yellow-600 text-white',
        'absent' => 'bg-red-600 text-white',
        'leave' => 'bg-blue-600 text-white',
        'permit' => 'bg-grey-600 text-white',
    ];
    protected $statusText = [
        'present' => 'Hadir',
        'late' => 'Terlambat',
        'absent' => 'Tidak Hadir',
        'leave' => 'Cuti',
        'permit' => 'Izin',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function statusText()
    {
        return $this->statusText[$this->status];
    }
    public function statusColor()
    {


        return $this->statusColors[$this->status];
    }
}
