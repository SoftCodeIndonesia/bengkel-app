<?php

namespace App\Models;

use App\Models\User;
use App\Models\StockOpnameItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'opname_number',
        'opname_date',
        'created_by',
        'notes',
        'status'
    ];

    protected $casts = [
        'opname_date' => 'date',

    ];

    protected $statusColors = [
        'completed' => 'bg-green-600 text-white',
        'in_progress' => 'bg-blue-600 text-white',
        'draf' => 'bg-grey-600 text-white',
    ];
    protected $statusText = [
        'completed' => 'Selesai',
        'in_progress' => 'Dalam Proses',
        'draf' => 'Draft',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($opname) {
            $now = now();
            $prefix = 'OP';
            $tanggal = $now->format('d');
            $bulan = $now->format('m');
            $tahun = $now->format('y'); // hanya 2 digit tahun

            // Ambil job order terakhir di tahun yang sama
            $latest = self::whereYear('created_at', $now->year)
                ->where('opname_number', 'like', "{$prefix}/%/%/{$tahun}/%")
                ->orderByDesc('created_at')
                ->first();



            // Ambil nomor urut dari unique_id terakhir
            if ($latest) {
                // Pecah format: JO/dd/mm/yy/0001
                $parts = explode('/', $latest->opname_number);
                $lastUrut = (int) ($parts[4] ?? 0); // ambil bagian terakhir
            } else {
                $lastUrut = 0;
            }

            // Tambah 1
            $nextUrut = $lastUrut + 1;

            // Format dengan padding 4 digit
            $nomorUrut = str_pad($nextUrut, 4, '0', STR_PAD_LEFT);

            $opname->opname_number = "{$prefix}/{$tanggal}/{$bulan}/{$tahun}/{$nomorUrut}";
        });
    }


    public function items()
    {
        return $this->hasMany(StockOpnameItem::class, 'opname_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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
