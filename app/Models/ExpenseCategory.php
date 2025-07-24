<?php

namespace App\Models;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
