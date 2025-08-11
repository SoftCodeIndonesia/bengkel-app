<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SparepartImport implements ToCollection, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        $categories = [];

        $index = 0;

        foreach ($rows as $row) {
            if ($index == 0) {
                foreach ($row as $key => $value) {
                    array_push($categories, $value);
                }
            } else {
                foreach ($row as $key => $part) {
                    if ($part !== null) {
                        $product = [
                            'name' => $part,
                            'tipe' => $categories[$key],
                            'buying_price' => 0,
                            'part_number' => null,
                            'description' => '-',
                            'margin' => 0,
                            'unit_price' => 0,
                            'stok' => 0,
                        ];

                        $slug = Str::slug($part);

                        $exist = Product::where('slug', $slug)->get()->first();

                        if ($exist == null) {
                            Product::create($product);
                        }
                    }
                }
            }
            $index++;
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
