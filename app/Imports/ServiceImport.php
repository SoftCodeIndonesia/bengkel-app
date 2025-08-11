<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ServiceImport implements ToCollection, WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $index = 0;
        foreach ($collection as $row) {

            if ($row[1] != null) {
                $name = $row[1];
                $frt = $row[2];
                $unit_price = 100000 * $frt;
                // dump($row);
                $product = [
                    'name' => $name,
                    'tipe' => 'jasa',
                    'buying_price' => 0,
                    'part_number' => null,
                    'description' => '-',
                    'margin' => 0,
                    'unit_price' => $unit_price,
                    'stok' => $frt,
                ];

                $slug = Str::slug($name);

                $exist = Product::where('slug', $slug)->get()->first();

                if ($exist == null) {
                    Product::create($product);
                }
            }
            $index++;
        }
    }

    public function startRow(): int
    {
        return 3;
    }
}
