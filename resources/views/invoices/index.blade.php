@extends('layouts.dashboard')

@section('title', 'Data Invoice')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            background-color: #374151;
            /* bg-gray-700 */
            border-color: #4b5563;
            /* border-gray-600 */
            color: #f3f4f6;
            /* text-gray-300 */
        }


        #datatables-index {
            border-bottom: 1px solid #4b5563 !important;
        }

        /* Tambahan styling untuk dark mode */
        #datatables-index tbody tr {
            background-color: transparent !important;
            /* Background dark dan border */
        }

        .dataTables_info {
            color: #f3f4f6 !important;
        }
    </style>
@endpush
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Data Invoice</h2>
            <div class="flex space-x-2">
                <a href="{{ route('invoices.create', ['type' => 'sales']) }}"
                    class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Invoice Penjualan
                </a>
                <a href="{{ route('invoices.create', ['type' => 'services']) }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Invoice Service
                </a>
            </div>
        </div>

        <div class="p-4">
            <!-- Filter Section -->
            <div class="bg-gray-700 rounded-lg p-4 mb-4">
                <form id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Tanggal</label>
                            <input type="text" id="date-range" name="date_range"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Pilih rentang tanggal">
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Tipe Invoice</label>
                            <select name="type" id="type"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Tipe</option>
                                <option value="sales">Penjualan</option>
                                <option value="services">Service</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Status</label>
                            <select name="status" id="status"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 space-x-2">
                        <button type="button" id="reset-filter"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                            Reset
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="p-4">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="datatables-index">
                        <thead class="uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th class="p-3 text-sm font-semibold">No</th>
                                <th class="p-3 text-sm font-semibold">No. Invoice</th>
                                <th class="p-3 text-sm font-semibold">Tanggal</th>
                                <th class="p-3 text-sm font-semibold">Tipe</th>
                                <th class="p-3 text-sm font-semibold">Pelanggan</th>
                                <th class="p-3 text-sm font-semibold">Total</th>
                                <th class="p-3 text-sm font-semibold">Status</th>
                                <th class="p-3 text-sm font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#date-range').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD',
                        applyLabel: 'Pilih',
                        cancelLabel: 'Batal',
                        fromLabel: 'Dari',
                        toLabel: 'Sampai',
                        customRangeLabel: 'Custom',
                        daysOfWeek: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                            'September', 'Oktober', 'November', 'Desember'
                        ],
                        firstDay: 1
                    },
                    opens: 'right',
                    autoUpdateInput: false,
                    ranges: {
                        'Hari Ini': [moment(), moment()],
                        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                        '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                        'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    }
                });

                $('#date-range').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#date-range').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
                var table = $('#datatables-index').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('invoices.index') }}",
                        data: function(d) {
                            d.date_range = $('#date-range').val();
                            d.type = $('#type').val();
                            d.status = $('#status').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'unique_id',
                            name: 'unique_id'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'tipe',
                            name: 'tipe'
                        },
                        {
                            data: 'customer_name',
                            name: 'customer_name'
                        },
                        {
                            data: 'total',
                            name: 'total',
                            render: function(data, type, row) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data, type, row) {
                                const color = data === 'paid' ? 'bg-green-500' : 'bg-yellow-500';
                                return `<span class="px-2 py-1 rounded-full text-xs text-gray-800 ${color}">${data}</span>`;
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                    },
                    dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"pagination-container"p>>',
                    initComplete: function() {
                        // Styling untuk search input
                        $('.dataTables_length label').addClass(
                            'text-gray-400'
                        );
                        $('.dataTables_filter label').addClass(
                            'text-gray-400'
                        );

                        $('.dataTables_info').addClass(
                            'text-gray-400'
                        );


                        $('.dataTables_filter input').addClass(
                            'bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                        );

                        // Styling untuk length menu
                        $('.dataTables_length select').addClass(
                            'bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                        );
                        $('.dataTables_processing')
                            .css({
                                'background': 'transparent', // bg-gray-800/90
                                'color': 'white',
                            });
                    },
                    drawCallback: function() {
                        // Styling data info
                        $('.dataTables_info').addClass('text-gray-400');
                        // Styling untuk pagination setelah draw
                        $('.pagination-container .paginate_button').addClass(
                            'px-3 py-1 mx-1 text-gray-300 bg-gray-700 border border-gray-600 rounded-md hover:bg-gray-600 hover:text-white transition duration-150'
                        );
                        $('.pagination-container .paginate_button.current').addClass(
                            'bg-blue-600 text-white border-blue-600');

                        $('.dataTables_paginate').addClass('flowbite-pagination');
                        $('.paginate_button').each(function() {
                            // Hapus class bawaan DataTables
                            $(this).removeClass('paginate_button previous next first last');

                            // Tambahkan class sesuai jenis tombol
                            if ($(this).hasClass('current')) {
                                $(this).addClass('active bg-blue-600 text-white');
                            } else if ($(this).hasClass('disabled')) {
                                $(this).addClass('opacity-50 cursor-not-allowed');
                            }
                        });
                    }
                });

                $('#filter-form').on('submit', function(e) {
                    e.preventDefault();
                    table.draw();
                });

                // Reset filter
                $('#reset-filter').on('click', function() {
                    $('#date-range').val('');
                    $('#type').val('');
                    $('#status').val('');
                    table.draw();
                });
            });
        </script>
    @endpush
