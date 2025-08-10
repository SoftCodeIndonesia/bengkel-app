<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SalesItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id',
        'customer_id',
        'customer_name',
        'customer_address',
        'sales_date',
        'subtotal',
        'diskon_unit',
        'diskon_value',
        'total',
    ];

    protected $casts = [
        'sales_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesItem::class, 'sales_id')->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $now = now();
            $prefix = 'SO';
            $tanggal = $now->format('d');
            $bulan = $now->format('m');
            $tahun = $now->format('y'); // hanya 2 digit tahun

            // Ambil SO terakhir di tahun yang sama
            $latest = self::whereYear('created_at', $now->year)
                ->where('unique_id', 'like', "{$prefix}/%/%/{$tahun}/%")
                ->orderByDesc('created_at')
                ->first();

            // Ambil nomor urut dari unique_id terakhir
            if ($latest) {
                // Pecah format: SO/dd/mm/yy/0001
                $parts = explode('/', $latest->unique_id);
                $lastUrut = (int) ($parts[4] ?? 0); // ambil bagian terakhir
            } else {
                $lastUrut = 0;
            }

            // Tambah 1
            $nextUrut = $lastUrut + 1;

            // Format dengan padding 4 digit
            $nomorUrut = str_pad($nextUrut, 4, '0', STR_PAD_LEFT);

            $sale->unique_id = "{$prefix}/{$tanggal}/{$bulan}/{$tahun}/{$nomorUrut}";
        });
    }
}
