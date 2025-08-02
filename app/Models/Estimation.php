<?php

namespace App\Models;

use App\Models\JobOrder;
use App\Scopes\EstimationScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estimation extends JobOrder
{
    use HasFactory;

    protected $table = 'job_orders'; // Gunakan tabel yang sama

    protected static function booted()
    {
        static::addGlobalScope(new EstimationScope);
        static::creating(function ($jobOrder) {
            $now = now();
            $prefix = 'ES';
            $tanggal = $now->format('d');
            $bulan = $now->format('m');
            $tahun = $now->format('y'); // hanya 2 digit tahun

            // Ambil job order terakhir di tahun yang sama
            $latest = self::whereYear('created_at', $now->year)
                ->where('unique_id', 'like', "{$prefix}/%/%/{$tahun}/%")
                ->where('status', 'estimation')
                ->orderByDesc('created_at')
                ->first();

            // Ambil nomor urut dari unique_id terakhir
            if ($latest) {
                // Pecah format: JO/dd/mm/yy/0001
                $parts = explode('/', $latest->unique_id);
                $lastUrut = (int) ($parts[4] ?? 0); // ambil bagian terakhir
            } else {
                $lastUrut = 0;
            }

            // Tambah 1
            $nextUrut = $lastUrut + 1;

            // Format dengan padding 4 digit
            $nomorUrut = str_pad($nextUrut, 4, '0', STR_PAD_LEFT);

            $jobOrder->unique_id = "{$prefix}/{$tanggal}/{$bulan}/{$tahun}/{$nomorUrut}";
        });
    }
}
