@extends('layouts.print')
@section('content')
    <div class="p-4 rounded-lg border-gray-600">
        <div class="overflow-x-auto">
            <p class="mb-1">Informasi Pelanggan</p>
            <table style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="text-left px-2" width="100px">Nama</th>
                        <td class="text-left px-2">{{ $sale->customer->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-2" width="100px">Email</th>
                        <td class="text-left px-2">{{ $sale->customer->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-2" width="100px">No.Telp</th>
                        <td class="text-left px-2">{{ $sale->customer->phone }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-2" width="100px">Alamat</th>
                        <td class="text-left px-2">{{ $sale->customer->address }}</td>
                    </tr>

                </thead>
            </table>
        </div>
    </div>
    <div class="px-1">

        <div class="px-4 rounded-lg border-gray-600 mt-2">
            <div class="flex justify-between items-center">
                <p class="mb-1">Sparepart</p>

            </div>
            <div class="overflow-x-auto">
                <table class="" style="width: 100%; table-layout: fixed;">
                    <thead class="">
                        <tr>

                            <th class="py-1" width="20px">No</th>
                            <th class="px-4 py-1 text-left" width="50%">Sparepart/Jasa</th>
                            <th class="px-4 py-1 text-right">QTY</th>
                            <th class="px-4 py-1 text-right">Harga</th>
                            <th class="py-1 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sale->items as $item)
                            <tr class="border">

                                <td class="py-1 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-1 " width="40%">{{ $item->product->name }}</td>
                                <td class="px-4 py-1 text-right">{{ $item->quantity }}
                                </td>
                                <td class="px-4 py-1 text-right">
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class=" py-1 text-right">
                                    {{ number_format($item->total_price, 0, ',', '.') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-4 rounded-lg border-gray-600 mt-4">
            <div class="flex justify-between items-center">
                <p class="mb-1">Catatan : </p>

            </div>
            <div class="overflow-x-auto">
                <span>{{ $sale->notes ?? 'Tidak Ada Catatan' }}</span>
            </div>
        </div>

        <div class="p-4 rounded-lg border-gray-600 mt-2">
            <p class="mb-1">Rincian Biaya</p>

            <div class="overflow-x-auto">
                <table style="width: 100%; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th class="text-left px-2" width="100px">Subtotal</th>
                            <td class="text-right font-bold px-2">Rp
                                {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="text-left px-2" width="100px">Diskon</th>
                            <td class="text-right font-bold px-2">
                                @if ($sale->diskon_unit == 'percentage')
                                    ({{ $sale->diskon_value }}%)
                                @else
                                    Rp
                                    {{ number_format($sale->diskon_value, 2, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left px-2" width="100px">Total</th>
                            <td class="text-right font-bold px-2">Rp
                                {{ number_format($sale->total, 2, ',', '.') }}</td>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>




    </div>
@endsection
