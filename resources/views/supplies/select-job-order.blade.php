{{-- resources/views/supplies/select-job-order.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Pilih Job Order')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        /* Tambahkan style sesuai kebutuhan */
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            {{-- <h2 class="text-xl font-semibold text-white">Pilih Job Order</h2>
            <a href="{{ route('supplies.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                Kembali
            </a> --}}


            <ol
                class="flex items-center w-full text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base">
                <li
                    class="flex md:w-full items-center text-blue-600 dark:text-blue-500 sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700">
                    <span
                        class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                        </svg>
                        Personal <span class="hidden sm:inline-flex sm:ms-2">Info</span>
                    </span>
                </li>
                <li
                    class="flex md:w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700">
                    <span
                        class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                        <span class="me-2">2</span>
                        Account <span class="hidden sm:inline-flex sm:ms-2">Info</span>
                    </span>
                </li>
                <li class="flex items-center">
                    <span class="me-2">3</span>
                    Confirmation
                </li>
            </ol>

        </div>

        <div class="p-4">
            <table id="job-orders-table" class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs uppercase bg-gray-700 text-gray-300">
                    <tr>
                        <th class="p-3">ID Job Order</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Kendaraan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#job-orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('supplies.select-job-order') }}",
                columns: [{
                        data: 'unique_id',
                        name: 'unique_id'
                    },
                    {
                        data: 'customer_vehicle.customer.name',
                        name: 'customerVehicle.customer.name',
                        render: function(data, type, row) {
                            return data + ' (' + row.customer_vehicle.customer.phone + ')';
                        }
                    },
                    {
                        data: 'customer_vehicle.vehicle',
                        name: 'customerVehicle.vehicle',
                        render: function(data) {
                            return data.merk + ' ' + data.tipe + ' (' + data.no_pol + ')';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
