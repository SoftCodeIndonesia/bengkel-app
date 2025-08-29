@extends('layouts.print')
@section('content')
    <div class="p-4 rounded-lg border-gray-600">
        <div class="overflow-x-auto">
            <p class="mb-1">Informasi Pelanggan</p>
            <table style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="text-left px-2" width="100px">Nama</th>
                        <td class="text-left px-2">{{ $invoice->customer->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-2" width="100px">Email</th>
                        <td class="text-left px-2">{{ $invoice->customer->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-2" width="100px">No.Telp</th>
                        <td class="text-left px-2">{{ $invoice->customer->phone }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-2" width="100px">Alamat</th>
                        <td class="text-left px-2">{{ $invoice->customer->address }}</td>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
    <div class="px-1">



        <div class="px-4 rounded-lg border-gray-600 mt-2">
            <div class="flex justify-between items-center">
                <p class="mb-1">Sparepart / Jasa</p>

            </div>
            <div class="overflow-x-auto">
                <table class="" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th class="py-1 px-2 text-left">
                                Deskripsi</th>
                            <th class="py-1 px-2 text-end">Harga Satan
                            </th>
                            <th class="py-1 px-2 text-end">Qty
                            </th>
                            <th class="py-1 px-2 text-end">Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $reference = $invoice->reference()->get()->first();
                        @endphp

                        @if ($invoice->tipe === 'sales')
                            @foreach ($reference->items ?? [] as $item)
                                <tr>
                                    <td class="py-1 px-2 text-left">{{ $item->product->name }}
                                    </td>
                                    <td class="py-1 px-2 text-end">Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="py-1 px-2 text-end">
                                        {{ $item->quantity }}</td>
                                    <td class="py-1 px-2 text-end">Rp
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($reference->orderItems ?? [] as $item)
                                <tr>
                                    <td class="py-1 px-2 text-left">{{ $item->product->name }}
                                    </td>
                                    <td class="py-1 px-2 text-end">
                                        {{ $item->product->tipe == 'jasa' ? '' : 'Rp ' . number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-1 px-2 text-end">
                                        {{ $item->quantity }}</td>
                                    <td class="py-1 px-2 text-end">Rp
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
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
                                {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="text-left px-2" width="100px">Diskon</th>
                            <td class="text-right font-bold px-2">
                                @if ($invoice->diskon_unit == 'percentage')
                                    ({{ $invoice->diskon_value }}%)
                                @else
                                    Rp
                                    {{ number_format($invoice->diskon_value, 2, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left px-2" width="100px">Total</th>
                            <td class="text-right font-bold px-2">Rp
                                {{ number_format($invoice->total, 2, ',', '.') }}</td>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
    {{-- <div class="max-w-4xl mx-auto" style="width: 100%">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <p class="text-2xl font-bold">Invoice<span class=""> #{{ $invoice->unique_id }}</span></p>
            <p class="text-gray-600">{{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="flex justify-between items-start mb-8">
            <div>

                <p class="text-gray-600">Bengkel 88AutoCare</p>
                <p class="text-gray-600">Jl. Raya No. 123, Jakarta</p>
                <p class="text-gray-600">Telp: 021-12345678</p>
            </div>
            <div class="text-right">

                <img src="{{ asset('assets/app/img/logo-bengkel-1.png') }}" alt="logo" width="200px">
            </div>
        </div>

        <!-- Customer Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class=" rounded">
                <h2 class="text-lg font-semibold mb-2">Kepada:</h2>
                <p class="font-medium">{{ $invoice->customer_name }}</p>
                <p class="text-gray-600">{{ $invoice->customer_address }}</p>
            </div>

        </div>

        <!-- Transaction Details Table -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-4">Detail Transaksi</h2>
            <table class="w-full border-collapse" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr class="bg-gray-100" style="background-color: #f3f4f6;">
                        <th class="py-2 px-4 border border-gray-300 text-left"
                            style="width: 200px;border: 1px solid #d1d5db; padding: 8px 16px; text-align: left;">
                            Deskripsi</th>
                        <th class="py-2 px-4 border border-gray-300 text-right"
                            style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Harga Satan
                        </th>
                        <th class="py-2 px-4 border border-gray-300 text-right"
                            style="width: 100px;border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Qty
                        </th>
                        <th class="py-2 px-4 border border-gray-300 text-right"
                            style="width: 150px;border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $reference = $invoice->reference()->get()->first();
                    @endphp

                    @if ($invoice->tipe === 'sales')
                        @foreach ($reference->items ?? [] as $item)
                            <tr>
                                <td class="py-2 px-4 border border-gray-300"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px;">{{ $item->product->name }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300 text-right"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Rp
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">
                                    {{ $item->quantity }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Rp
                                    {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($reference->items ?? [] as $item)
                            <tr>
                                <td class="py-2 px-4 border border-gray-300"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px;">{{ $item->product->name }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300 text-right"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Rp
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">
                                    {{ $item->quantity }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right"
                                    style="border: 1px solid #d1d5db; padding: 8px 16px; text-align: right;">Rp
                                    {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="flex justify-end">
            <div class="w-full md:w-1/3">
                <div class="border-t-2 border-gray-300 pt-4" style="border-top: 2px solid #d1d5db; padding-top: 16px;">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Subtotal:</span>
                        <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($invoice->diskon_value > 0)
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Diskon:</span>
                            <span>- Rp {{ number_format($invoice->diskon_value, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold mt-4">
                        <span>Total:</span>
                        <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div> --}}
@endsection
