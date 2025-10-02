@extends('layouts.dashboard')

@section('title', 'Laporan Pemasukan')



@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Preview Laporan Pemasukan</h2>
            <a href="{{ route('reports.export-excel', request()->only(['start_date', 'end_date'])) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                Export Excel
            </a>
        </div>

        <div class="p-4">
            <div class="relative overflow-x-auto">
                <table class="w-max text-sm text-left text-gray-400" id="datatables-report">
                    <thead class="uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="p-3 w-32">Sumber</th>
                            <th class="p-3 w-64">Nomor Dokumen</th>
                            <th class="p-3 w-64">Tanggal</th>
                            <th class="p-3 w-64">Nama Jasa</th>
                            <th class="p-3 w-20">FRT</th>
                            <th class="p-3 w-40">Diskon Jasa</th>
                            <th class="p-3 w-40">Total Jasa</th>
                            <th class="p-3 w-64">Nama Part</th>
                            <th class="p-3 w-20">QTY</th>
                            <th class="p-3 w-40">Harga Beli Satuan</th>
                            <th class="p-3 w-40">Harga Jual Satuan</th>
                            <th class="p-3 w-40">Total Setelah Diskon</th>
                            <th class="p-3 w-40">Margin Part (%)</th>
                            <th class="p-3 w-40">Margin Part (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @php
                            $totalJasa = 0;
                            $totalQtyPart = 0;
                            $totalHargaPart = 0;
                            $totalHargaBeliPart = 0;
                            $totalPart = 0;
                            $totalMarginNominal = 0;
                            $row = 1;
                        @endphp

                        {{-- Work Orders --}}
                        @foreach ($jobOrderIncome as $keyWo => $wo)
                            @foreach ($wo->service as $keyJasa => $jasa)
                                @foreach ($wo->sparepart as $keyPart => $part)
                                    <tr>
                                        <td class="p-3 w-64">{{ 'WO' }}</td>
                                        <td class="p-3 w-64">
                                            {{ $keyPart == 0 ? $wo->unique_id : '' }}</td>
                                        <td class="p-3 w-64">{{ $keyPart == 0 ? $wo->service_at->format('d-m-Y') : '' }}
                                        </td>
                                        <td class="p-3 w-64">{{ $keyPart == 0 ? $jasa->product->name : '' }}</td>
                                        <td class="p-3 w-64">{{ $keyPart == 0 ? $jasa->quantity : '' }}</td>

                                        <td class="p-3 w-64">
                                            {{ $keyPart == 0 ? number_format(100000 * $jasa->quantity * ($jasa->diskon_value / 100), 0, ',', '.') : '' }}
                                        </td>
                                        <td class="p-3 w-64">
                                            {{ $keyPart == 0 ? number_format($jasa->price_after_diskon, 0, ',', '.') : '' }}
                                        </td>
                                        <td class="p-3 w-64">{{ $part->product->name }}</td>
                                        <td class="p-3 w-64">{{ $part->quantity }}</td>
                                        <td class="p-3 w-64">{{ number_format($part->product->buying_price, 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 w-64">{{ number_format($part->unit_price, 0, ',', '.') }}</td>
                                        <td class="p-3 w-64">{{ number_format($part->price_after_diskon, 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 w-64">
                                            @php
                                                if ($part->price_after_diskon > 0) {
                                                    $margin =
                                                        (($part->price_after_diskon -
                                                            $part->product->buying_price * $part->quantity) /
                                                            $part->price_after_diskon) *
                                                        100;
                                                } else {
                                                    $margin = 0; // atau bisa juga null tergantung kebutuhan
                                                }
                                            @endphp
                                            {{ round($margin, 2) }}%
                                        </td>
                                        <td class="p-3 w-64">
                                            @php
                                                $marginNominal =
                                                    $part->price_after_diskon -
                                                    $part->product->buying_price * $part->quantity;
                                            @endphp
                                            {{ number_format($marginNominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @php
                                        $totalJasa += $jasa->total_price;
                                        $totalQtyPart += $part->quantity;
                                        $totalHargaPart += $part->unit_price * $part->quantity;
                                        $totalHargaBeliPart += $part->product->buying_price * $part->quantity;
                                        $totalPart += $part->price_after_diskon;
                                        $totalMarginNominal += $marginNominal;
                                    @endphp
                                @endforeach
                                @php
                                    $totalJasa += $jasa->total_price;
                                @endphp
                            @endforeach
                        @endforeach

                        {{-- Sales Orders --}}

                        @foreach ($salesIncome as $key => $so)
                            @foreach ($so->items as $keyPart => $part)
                                <tr>
                                    <td class="p-3 w-64">{{ 'SO' }}</td>
                                    <td class="p-3 w-64">{{ $keyPart == 0 ? $so->unique_id : '' }}</td>
                                    <td class="p-3 w-64">{{ $so->created_at->format('d-m-Y') }}</td>
                                    <td class="p-3 w-64">-</td>
                                    <td class="p-3 w-64">-</td>

                                    <td class="p-3 w-64">-</td>
                                    <td class="p-3 w-64">-</td>
                                    <td class="p-3 w-64">{{ $part->product->name }}</td>
                                    <td class="p-3 w-64">{{ $part->quantity }}</td>
                                    <td class="p-3 w-64">{{ number_format($part->product->buying_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 w-64">{{ number_format($part->unit_price, 0, ',', '.') }}</td>
                                    <td class="p-3 w-64">{{ number_format($part->price_after_discount, 0, ',', '.') }}</td>
                                    <td class="p-3 w-64">
                                        @php
                                            if ($part->price_after_discount > 0) {
                                                $margin =
                                                    (($part->price_after_discount -
                                                        $part->product->buying_price * $part->quantity) /
                                                        $part->price_after_discount) *
                                                    100;
                                            } else {
                                                $margin = 0;
                                            }
                                        @endphp
                                        {{ round($margin, 2) }}%
                                    </td>
                                    <td class="p-3 w-64">
                                        @php
                                            $marginNominal =
                                                $part->price_after_discount -
                                                $part->product->buying_price * $part->quantity;
                                        @endphp
                                        {{ number_format($marginNominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @php
                                    $totalQtyPart += $part->quantity;
                                    $totalHargaPart += $part->unit_price * $part->quantity;
                                    $totalPart += $part->price_after_discount;
                                    $totalHargaBeliPart += $part->product->buying_price * $part->quantity;
                                    $totalMarginNominal += $marginNominal;
                                @endphp
                            @endforeach
                        @endforeach

                        {{-- Total Row --}}
                        <tr class="bg-gray-700 font-bold text-white">
                            <td class="p-3 w-64" colspan="3">TOTAL</td>
                            <td class="p-3 w-64"></td>
                            <td class="p-3 w-64"></td>

                            <td class="p-3 w-64"></td>
                            <td class="p-3 w-64">{{ number_format($totalJasa, 0, ',', '.') }}</td>
                            <td class="p-3 w-64"></td>
                            <td class="p-3 w-64">{{ $totalQtyPart }}</td>
                            <td class="p-3 w-64">{{ number_format($totalHargaBeliPart, 0, ',', '.') }}</td>
                            <td class="p-3 w-64">{{ number_format($totalHargaPart, 0, ',', '.') }}</td>
                            <td class="p-3 w-64">{{ number_format($totalPart, 0, ',', '.') }}</td>
                            <td class="p-3 w-64"></td>
                            <td class="p-3 w-64">{{ number_format($totalMarginNominal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
