<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpenseCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaji Karyawan', 'type' => 'salary'],
            ['name' => 'Listrik', 'type' => 'utility'],
            ['name' => 'Air', 'type' => 'utility'],
            ['name' => 'Internet', 'type' => 'utility'],
            ['name' => 'Sewa Tempat', 'type' => 'operational'],
            ['name' => 'Pembelian Alat', 'type' => 'operational'],
            ['name' => 'Transportasi', 'type' => 'operational'],
            ['name' => 'Lain-lain', 'type' => 'operational'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
