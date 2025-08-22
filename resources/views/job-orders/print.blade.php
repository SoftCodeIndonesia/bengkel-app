@extends('layouts.print')

@section('content')
    <div class="p-4 rounded-lg border-gray-600">
        <div class="overflow-x-auto">
            <p class="mb-1">Informasi Pelanggan</p>
            <div class="flex justify-between mb-2 items-center gap-5">
                <div class="flex-1 space-y-1">
                    <table style="width: 100%; table-layout: fixed;">
                        <thead>
                            <tr>
                                <th class="text-left px-2" width="100px">Nama</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->customer->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-left px-2" width="100px">Email</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->customer->email }}</td>
                            </tr>
                            <tr>
                                <th class="text-left px-2" width="100px">No.Telp</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->customer->phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-left px-2" width="100px">Alamat</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->customer->address }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="flex-1 space-y-1">
                    <table style="width: 100%; table-layout: fixed;">
                        <thead>
                            <tr>
                                <th class="text-left px-2" width="100px">Merk</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->vehicle->merk }}</td>
                            </tr>
                            <tr>
                                <th class="text-left px-2" width="100px">Tipe</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->vehicle->tipe }}</td>
                            </tr>
                            <tr>
                                <th class="text-left px-2" width="100px">No Polisi</th>
                                <td class="text-left px-2">{{ $jobOrder->customerVehicle->vehicle->no_pol }}</td>
                            </tr>
                            <tr>
                                <th class="text-left px-2" width="100px">Kilometer</th>
                                <td class="text-left px-2">{{ $jobOrder->km }}</td>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>

        </div>
    </div>
    <div class="px-1">

        <div class="px-4 rounded-lg border-gray-600 mt-2">
            <p class="mb-1">Deskripsi Kerusakan</p>

            <table class="" style="width: 100%; table-layout: fixed;">
                <thead class="">
                    <tr>
                        <th class=" py-1 text-left" width="20px">No</th>
                        <th class="px-4 py-1 text-left">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($jobOrder->breakdowns as $breakdown)
                        <tr>
                            <td class=" py-1 text-left">{{ $no }}</td>
                            <td class="px-4 py-1 text-left">{{ $breakdown->name }}</td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </tbody>
            </table>


        </div>


        <div class="px-4 rounded-lg border-gray-600 mt-2">
            <div class="flex justify-between items-center">
                <p class="mb-1">Sparepart & Jasa</p>

            </div>
            <div class="overflow-x-auto">
                <table class="" style="width: 100%; table-layout: fixed;">
                    <thead class="">
                        <tr>

                            <th class="py-3" width="20px">No</th>
                            <th class="px-4 py-3 text-left" width="50%">Sparepart/Jasa</th>
                            <th class="px-4 py-3 text-right">FRT/QTY</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobOrder->orderItems as $item)
                            @if ($item->product->tipe != 'jasa')
                                <tr class="border">

                                    <td class="py-1 ">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-1 " width="40%">{{ $item->product->name }}</td>
                                    <td class="px-4 py-1 text-right">{{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-1 text-right">
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class=" py-1 text-right">
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>

                                </tr>
                            @endif
                        @endforeach
                        @foreach ($jobOrder->orderItems as $item)
                            @if ($item->product->tipe == 'jasa')
                                <tr class="border">

                                    <td class="py-1 ">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-1 " width="40%">{{ $item->product->name }}</td>
                                    <td class="px-4 py-1 text-right">{{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-1 text-right">
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class=" py-1 text-right">
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
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
                                {{ number_format($jobOrder->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="text-left px-2" width="100px">Diskon</th>
                            <td class="text-right font-bold px-2">
                                @if ($jobOrder->diskon_unit == 'percentage')
                                    ({{ $jobOrder->diskon_value }}%)
                                @else
                                    Rp
                                    {{ number_format($jobOrder->diskon_value, 2, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left px-2" width="100px">Total</th>
                            <td class="text-right font-bold px-2">Rp
                                {{ number_format($jobOrder->total, 2, ',', '.') }}</td>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection
