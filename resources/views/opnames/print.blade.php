@extends('layouts.print')

@section('content')
    <div class="px-1">

        <div class="px-4 rounded-lg border-gray-600 mt-4">
            <div class="overflow-x-auto">
                <table class="" style="width: 100%; table-layout: fixed;" cellpadding="5">
                    <thead class="">
                        <tr>
                            <th rowspan="2" class="px-2" width="30px">No</th>
                            <th rowspan="2" class="px-4 text-left" width="50%">Nama Part</th>
                            <th rowspan="2" class="px-4 text-left">SN/PN</th>
                            <th colspan="2" class="px-4 text-center">Quantity</th>
                            <th rowspan="2">Selisih</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="px-4" width="10px">Sistem</th>
                            <th class="px-4" width="10px">Gudang</th>
                        </tr>
                    </thead>
                    @php
                        $index = 1;
                    @endphp
                    <tbody>
                        @foreach ($products as $item)
                            <tr class="border">

                                <td class="px-2">{{ $index }}</td>
                                <td class="px-4" width="40%">{{ $item->name }}</td>
                                <td class="px-4">{{ $item->part_number }}</td>
                                <td class="px-4 text-center" width="10px">{{ $item->stok }}</td>
                                <td class="px-4 text-right" width="10px"></td>
                                <td class="px-2 text-right"></td>
                                <td class="px-2 text-right"></td>

                            </tr>
                            @php
                                $index++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 flex rounded-lg border-gray-600 mt-2">
            <div class="note flex-1">
                <p class="font-bold">Catatan</p>
                <p>{{ $data['notes'] }}</p>
            </div>


        </div>
    </div>
@endsection
