@extends('layouts.dashboard')

@section('title', 'Pergerakan Barang')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-wrapper {
            --ts-pr-600: #2563eb;
            --ts-pr-200: #93c5fd;
            --ts-option-radius: 0.375rem;
            padding: 5px !important;
        }

        .ts-wrapper .item {
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            color: #f3f4f6 !important;
        }

        .ts-wrapper.single .ts-control {
            @apply bg-gray-700 border border-gray-600 text-gray-300;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        .ts-dropdown,
        .ts-dropdown .active {
            background-color: rgb(57 65 81) !important;
            border-color: rgb(57 65 81) !important;
        }

        .ts-dropdown .option {
            @apply text-gray-300 hover:bg-gray-600;
        }

        .ts-dropdown .active {
            @apply bg-gray-600 text-white;
        }

        .ts-control,
        .ts-control input {
            background-color: transparent !important;
            border: none !important;
            padding: 2px !important;
            color: white;
        }

        .ts-control input {
            @apply bg-gray-700 text-gray-300 placeholder-gray-400;
        }

        .ts-control.focus {
            @apply ring-2 ring-blue-500 border-blue-500;
        }

        .ts-wrapper.error .ts-control {
            @apply border-red-500;
        }

        .ts-wrapper .item {
            @apply bg-gray-600 text-gray-300 rounded;
        }

        .ts-wrapper .clear-button {
            @apply text-gray-400 hover:text-gray-300;
        }

        .section-title {
            @apply text-lg font-medium text-gray-300 mb-3 pb-2 border-b border-gray-600;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: #374151;
            /* bg-gray-700 */
            border-color: #4b5563;
            /* border-gray-600 */
            color: #f3f4f6;
            /* text-gray-300 */
        }


        #movements-table {
            border-bottom: 1px solid #4b5563 !important;
        }

        /* Tambahan styling untuk dark mode */
        #movements-table tbody tr {
            background-color: transparent !important;
            /* Background dark dan border */
        }

        .dataTables_info {
            color: #f3f4f6 !important;
        }


        .summary-card {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border: 1px solid #4b5563;
            border-radius: 0.75rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
            z-index: 0;
        }

        .summary-card-content {
            position: relative;
            z-index: 1;
        }

        .summary-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Pergerakan Barang</h2>
            <a href="{{ route('movements.report') }}"
                class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Laporan Barang Keluar
            </a>
        </div>

        <div class="p-4">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6" id="summary-cards">
                <!-- Total Sales -->
                <div class="summary-card">
                    <div class="summary-card-content">
                        <div class="summary-icon bg-green-900/20">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Total Penjualan</h3>
                        <p class="text-2xl font-bold text-green-400 mt-2" id="total-sales">
                            Rp {{ number_format($summaryData['total_sales'], 2) }}
                        </p>
                        <p class="text-sm text-gray-400 mt-1">Pendapatan dari sales</p>
                    </div>
                </div>

                <!-- Total Services -->
                <div class="summary-card">
                    <div class="summary-card-content">
                        <div class="summary-icon bg-blue-900/20">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Total Services</h3>
                        <p class="text-2xl font-bold text-blue-400 mt-2" id="total-services">
                            Rp {{ number_format($summaryData['total_services'], 2) }}
                        </p>
                        <p class="text-sm text-gray-400 mt-1">Pendapatan dari job order</p>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="summary-card">
                    <div class="summary-card-content">
                        <div class="summary-icon bg-purple-900/20">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Total Keseluruhan</h3>
                        <p class="text-2xl font-bold text-purple-400 mt-2" id="grand-total">
                            Rp {{ number_format($summaryData['grand_total'], 2) }}
                        </p>
                        <p class="text-sm text-gray-400 mt-1">Total pendapatan</p>
                    </div>
                </div>
            </div>

            <div class="">
                <!-- Filter Form -->
                <form id="filter-form" class="mb-6 bg-gray-700 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date"
                                class="bg-gray-600 border border-gray-500 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date"
                                class="bg-gray-600 border border-gray-500 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Produk</label>
                            <select name="product_ids[]" id="product_ids" multiple
                                class="bg-gray-600 border border-gray-500 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                placeholder="Pilih produk...">
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Jenis Transaksi</label>
                            <select name="type" id="type"
                                class="bg-gray-600 border border-gray-500 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-sm p-2">
                                <option value="">Semua Jenis</option>
                                <option value="services">Services</option>
                                <option value="sales">Penjualan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" id="apply-filter"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2">
                            Terapkan Filter
                        </button>
                        <button type="button" id="reset-filter"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                            Reset
                        </button>
                    </div>
                </form>

                <!-- Movements Table -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-400" id="movements-table">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                <th scope="col" class="px-6 py-3">Harga Satuan</th>
                                <th scope="col" class="px-6 py-3">Total Harga</th>
                                <th scope="col" class="px-6 py-3">Jenis</th>
                                <th scope="col" class="px-6 py-3">Referensi</th>
                                <th scope="col" class="px-6 py-3">Pelanggan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan di-load oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script>
            $(document).ready(function() {
                var productSelect = new TomSelect('#product_ids', {
                    valueField: 'product_id',
                    labelField: 'text',
                    searchField: 'text',
                    options: [],
                    load: function(query, callback) {
                        var url = '{{ route('api.product.search') }}?q=' + encodeURIComponent(query);
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {

                                callback(json);
                            })
                            .catch(() => callback());
                    },
                    render: {
                        option: function(data, escape) {
                            return '<div class="text-gray-300">' + escape(data.text) + '</div>';
                        },
                        item: function(data, escape) {
                            return '<div class="text-white">' + escape(data.text) + '</div>';
                        }
                    },
                    plugins: ['remove_button'],
                    maxOptions: 20,
                    create: false,
                    maxItems: null
                });

                var table = $('#movements-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('movements.index') }}",
                        data: function(d) {
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                            d.product_ids = $('#product_ids').val();
                            d.type = $('#type').val();
                        },
                        dataSrc: function(json) {
                            // Update summary cards dengan data dari server
                            if (json.total_sales !== undefined) {
                                $('#total-sales').text('Rp ' + formatRupiah(json.total_sales));
                                $('#total-services').text('Rp ' + formatRupiah(json.total_services));
                                $('#grand-total').text('Rp ' + formatRupiah(json.grand_total));
                            }
                            return json.data;
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'movement_date',
                            name: 'movement_date'
                        },
                        {
                            data: 'product.name',
                            name: 'product.name',
                            render: function(data, type, row) {
                                return '<div class="text-white">' + data + '</div>';
                            }
                        },
                        {
                            data: 'quantity',
                            name: 'quantity'
                        },
                        {
                            data: 'unit_price',
                            name: 'unit_price'
                        },
                        {
                            data: 'total_price',
                            name: 'total_price'
                        },
                        {
                            data: 'type',
                            name: 'type',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'reference_number',
                            name: 'reference_number',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'customer_info',
                            name: 'customer_info'
                        }
                    ],

                    buttons: [{
                            extend: 'excel',
                            text: 'Excel',
                            className: 'bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded',
                            action: function(e, dt, button, config) {
                                e.preventDefault();

                                // Dapatkan data summary
                                var totalSales = $('#total-sales').text();
                                var totalServices = $('#total-services').text();
                                var grandTotal = $('#grand-total').text();
                                var startDate = $('#start_date').val() || 'Semua';
                                var endDate = $('#end_date').val() || 'Semua';

                                // Buat custom Excel
                                var excelData = [
                                    ['LAPORAN PERGERAKAN BARANG'],
                                    ['Periode: ' + startDate + ' sampai ' + endDate],
                                    [''],
                                    ['Total Penjualan', 'Total Services', 'Total Keseluruhan'],
                                    [totalSales, totalServices, grandTotal],
                                    [''],
                                    ['No', 'Tanggal', 'Produk', 'Jumlah', 'Harga Satuan',
                                        'Total Harga', 'Jenis', 'Referensi', 'Pelanggan'
                                    ]
                                ];

                                // Ambil semua data (bukan hanya yang ditampilkan di halaman)
                                $.ajax({
                                    url: "{{ route('movements.index') }}",
                                    data: {
                                        start_date: $('#start_date').val(),
                                        end_date: $('#end_date').val(),
                                        product_ids: $('#product_ids').val(),
                                        type: $('#type').val(),
                                        length: -1 // Get all records
                                    },
                                    success: function(response) {
                                        // Tambahkan data dari response
                                        response.data.forEach(function(row, index) {
                                            excelData.push([
                                                index + 1,
                                                new Date(row.movement_date)
                                                .toLocaleDateString(
                                                    'id-ID'),
                                                row.product.name + (row
                                                    .product.barcode ?
                                                    ' (' + row.product
                                                    .barcode + ')' : ''),
                                                parseFloat(row.quantity)
                                                .toLocaleString('id-ID', {
                                                    minimumFractionDigits: 2
                                                }),
                                                row.unit_price,
                                                row.total_price,
                                                row.reference_type,
                                                row.reference_code,
                                                row.customer_info
                                            ]);
                                        });

                                        // Export ke Excel
                                        var ws = XLSX.utils.aoa_to_sheet(excelData);
                                        var wb = XLSX.utils.book_new();
                                        XLSX.utils.book_append_sheet(wb, ws,
                                            "Pergerakan Barang");

                                        // Auto-size columns
                                        var colWidths = [5, 15, 30, 10, 15, 15, 15, 15, 30];
                                        ws['!cols'] = colWidths.map(function(width) {
                                            return {
                                                width: width
                                            };
                                        });

                                        XLSX.writeFile(wb, "laporan-pergerakan-barang-" +
                                            new Date().toISOString().split('T')[0] +
                                            ".xlsx");
                                    }
                                });
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            className: 'bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            customize: function(doc) {
                                // Tambahkan summary ke PDF
                                doc.content.splice(0, 0, {
                                    text: [
                                        'LAPORAN PERGERAKAN BARANG\n',
                                        'Periode: ' + ($('#start_date').val() || 'Semua') +
                                        ' - ' + ($('#end_date').val() || 'Semua') + '\n',
                                        'Total Penjualan: ' + $('#total-sales').text() +
                                        '\n',
                                        'Total Services: ' + $('#total-services').text() +
                                        '\n',
                                        'Total Keseluruhan: ' + $('#grand-total').text() +
                                        '\n\n'
                                    ],
                                    style: 'header',
                                    margin: [0, 0, 0, 10]
                                });
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            className: 'bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            customize: function(win) {
                                // Tambahkan summary ke print
                                $(win.document.body).prepend(
                                    '<h2>LAPORAN PERGERAKAN BARANG</h2>' +
                                    '<p>Periode: ' + ($('#start_date').val() || 'Semua') + ' - ' + (
                                        $('#end_date').val() || 'Semua') + '</p>' +
                                    '<p style="margin-top: 20px">Total Penjualan: ' + $(
                                        '#total-sales').text() + '</p>' +
                                    '<p>Total Services: ' + $('#total-services').text() + '</p>' +
                                    '<p style="margin-bottom: 20px">Total Keseluruhan: ' + $(
                                        '#grand-total').text() + '</p>'

                                );
                            }
                        }
                    ],
                    dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-4 md:mb-0"B><"mb-4 md:mb-0"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-4 md:mb-0"i><"pagination"p>>',
                    initComplete: function() {
                        $('.dataTables_length label').addClass('text-gray-400');
                        $('.dataTables_filter label').addClass('text-gray-400');
                        $('.dataTables_info').addClass('text-gray-400');
                        $('.dataTables_filter input').addClass(
                            'bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                        );
                        $('.dataTables_length select').addClass(
                            'bg-gray-700 border border-gray-600 text-green-600 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                        );
                        $('.dataTables_processing').css({
                            'background': 'transparent',
                            'color': 'white'
                        });
                    },
                    drawCallback: function() {
                        // Styling data info
                        $('.dataTables_info').addClass('text-gray-400');
                        // Styling untuk pagination setelah draw
                        $('.pagination .paginate_button').addClass(
                            'px-3 py-1 mx-1 text-gray-300 bg-gray-700 border border-gray-600 rounded-md hover:bg-gray-600 hover:text-white transition duration-150'
                        );
                        $('.pagination .paginate_button.current').addClass(
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

                // Apply filter
                $('#apply-filter').click(function() {
                    table.ajax.reload();
                });

                // Reset filter
                $('#reset-filter').click(function() {
                    $('#filter-form')[0].reset();
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
