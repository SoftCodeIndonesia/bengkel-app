<?php

// app/Models/Purchase.php
namespace App\Models;

use App\Models\Supplier;
use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'purchase_date',
        'total',
        'notes',
        'status',
        'source_documents',
        'original_filename',
    ];

    protected $statusColors = [
        'draft' => 'bg-blue-600 text-white',
        'unpaid' => 'bg-grey-600 text-white',
        'paid' => 'bg-green-600 text-white',
    ];
    protected $statusText = [
        'draft' => 'Draft',
        'unpaid' => 'Belum Lunas',
        'paid' => 'Lunas',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
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
