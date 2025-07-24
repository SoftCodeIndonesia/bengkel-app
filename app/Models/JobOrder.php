<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Breakdown;
use App\Models\OrderItem;
use App\Models\CustomerVehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id',
        'customer_vehicle_id',
        'km',
        'service_at',
        'status',
        'subtotal',
        'diskon_unit',
        'diskon_value',
        'total'
    ];

    protected $casts = [
        'service_at' => 'datetime',
    ];

    protected $statuses = [
        'estimation',
        'draft',
        'progress',
        'completed',
        'cancelled'
    ];

    public function customerVehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }

    public function breakdowns()
    {
        return $this->hasMany(Breakdown::class, 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'reference_id', 'id')
            ->where('tipe', 'service');
    }

    public function sparepart()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')
            ->whereHas('product', function ($query) {
                $query->where('tipe', '!=', 'jasa');
            });
    }

    public function service()
    {
        return $this->hasMany(OrderItem::class)
            ->whereHas('product', function ($query) {
                $query->where('tipe', 'jasa');
            });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobOrder) {
            $now = now();
            $prefix = 'JO';
            $tanggal = $now->format('d');
            $bulan = $now->format('m');
            $tahun = $now->format('y'); // hanya 2 digit tahun

            // Ambil job order terakhir di tahun yang sama
            $latest = self::whereYear('created_at', $now->year)
                ->where('unique_id', 'like', "{$prefix}/%/%/{$tahun}/%")
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

    public function statuses()
    {
        return $this->statuses;
    }

    public function getDisplayStatusAction()
    {
        if ($this->status == 'estimation') {
            return 'Estimasi';
        } else if ($this->status == 'draft') {
            return 'Draft';
        } else if ($this->status == 'progress') {
            return 'Proses';
        } else if ($this->status == 'completed') {
            return 'Selesai';
        } else if ($this->status == 'cancelled') {
            return 'Batalkan';
        }
    }
    public function getDisplayStatus()
    {
        if ($this->status == 'estimation') {
            return 'Estimasi';
        } else if ($this->status == 'draft') {
            return 'Draft';
        } else if ($this->status == 'progress') {
            return 'Diproses';
        } else if ($this->status == 'completed') {
            return 'Selesai';
        } else if ($this->status == 'cancelled') {
            return 'Dibatalkan';
        }
    }

    public function toStatus()
    {
        if ($this->status == 'Estimasi') {
            return 'estimation';
        } else if ($this->status == 'Draft') {
            return 'draft';
        } else if ($this->status == 'Proses' || $this->status == 'Diproses') {
            return 'progress';
        } else if ($this->status == 'Selesai') {
            return 'completed';
        } else if ($this->status == 'Dibatalkan' || $this->status == 'Batalkan') {
            return 'cancelled';
        }
    }

    public function recalculateTotals()
    {
        // Calculate subtotal (sum of all item prices before discount)
        $subtotal = $this->orderItems()->sum('price_after_diskon');

        // Calculate total discount from all items
        $totalDiscount = 0;

        $this->orderItems->each(function ($item) use (&$totalDiscount) {
            // Calculate discount for each item (percentage of item's total price)
            $itemDiscount = $item->total_price * ($item->diskon_value / 100);
            $totalDiscount += $itemDiscount;
        });

        // Calculate final total
        $total = $subtotal - $totalDiscount;

        // Update job order with new totals
        $this->update([
            'subtotal' => $subtotal,
            'diskon_value' => $totalDiscount, // Store as nominal value
            'diskon_unit' => 'nominal', // Always store as nominal in job order
            'total' => $total
        ]);
    }
}
