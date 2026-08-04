@extends('layouts.dashboard')

@section('title', 'Job Orders')
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
    <div class="bg-gray-800 shadow overflow-hidden">
        <!-- Card Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4" id="summary-cards">
            <!-- Total Job Order -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Job Order</p>
                        <p class="text-2xl font-bold text-white" id="total-job-orders">{{ $totalJobOrders ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1" id="summary-period">
                            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Nominal Completed</p>
                        <p class="text-2xl font-bold text-green-400" id="total-completed">Rp {{ number_format($totalCompleted ?? 0, 2, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Job Order selesai</p>
                    </div>
                    <div class="p-3 bg-green-600 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Nominal Progress</p>
                        <p class="text-2xl font-bold text-yellow-400" id="total-progress">Rp {{ number_format($totalProgress ?? 0, 2, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Job Order dalam proses</p>
                    </div>
                    <div class="p-3 bg-yellow-600 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cancelled -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Nominal Cancelled</p>
                        <p class="text-2xl font-bold text-red-400" id="total-cancelled">Rp {{ number_format($totalCancelled ?? 0, 2, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Job Order dibatalkan</p>
                    </div>
                    <div class="p-3 bg-red-600 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 flex flex-col sm:flex-row justify-between items-center border-b border-gray-600">
            <h2 class="text-xl text-start font-semibold text-white">Job Orders</h2>
            <div class="flex flex-col sm:flex-row w-full sm:w-fit items-center sm:space-x-4 space-x-0">
                <form method="GET" class="flex flex-col sm:flex-row items-center w-full mb-3 sm:mb-0 sm:w-fit space-x-2"
                    id="form-filter">
                    <input type="date" name="start_date" value="{{ $startDate ?? date('Y-m-d') }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-3 py-2 w-full sm:w-fit ">
                    <span class="text-gray-400">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate ?? date('Y-m-d') }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-3 mb-3 sm:mb-0 py-2 w-full sm:w-fit">
                    @php
                        $statusText = [
                            'draft' => 'Draft',
                            'progress' => 'Progress',
                            'completed' => 'Selesai',
                            'cancelled' => 'Batal',
                        ];
                    @endphp
                    <select name="status" id="status"
                        class="w-full sm:w-fit bg-gray-800 border border-gray-600 rounded-md text-white mb-3 sm:mb-0 px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        @foreach ($statusText as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="reset-filter"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 mb-3 sm:mb-0 py-2 w-full rounded-md">
                        Reset
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-blue-700 w-full text-white px-4 py-2 rounded-md">
                        Filter
                    </button>
                </form>
                <a href="{{ route('job-orders.create') }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg w-full flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    Buat Job Order
                </a>
            </div>


        </div>

        <div class="p-4">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right  text-gray-400" id="datatables-index">
                    <thead class="uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Nomor JO</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Kendaraan</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Fee</th>
                            <th class="p-3">PPN</th>
                            <th class="p-3">Profit</th>
                            <th class="p-3 text-right">Aksi</th>
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
    <script>
        $(document).ready(function() {
            console.log('ok');

            function loadSummary() {
                $.ajax({
                    url: "{{ route('job-orders.summary') }}",
                    type: "GET",
                    data: {
                        start_date: $('input[name="start_date"]').val(),
                        end_date: $('input[name="end_date"]').val()
                    },
                    success: function(response) {
                        $('#total-job-orders').text(response.totalJobOrders);
                        $('#total-completed').text('Rp ' + numberFormat(response.totalCompleted));
                        $('#total-progress').text('Rp ' + numberFormat(response.totalProgress));
                        $('#total-cancelled').text('Rp ' + numberFormat(response.totalCancelled));
                        $('#summary-period').text(formatDate(response.startDate) + ' - ' + formatDate(response.endDate));
                    }
                });
            }
            var table = $('#datatables-index').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('job-orders.report') }}",
                    data: function(d) {
                        d.start_date = $('input[name="start_date"]').val();
                        d.end_date = $('input[name="end_date"]').val();
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
                        name: 'unique_id',
                        orderable: false,
                    },
                    {
                        data: 'customer_name',
                        name: 'customerVehicle.customer.name',
                        orderable: false,
                    },
                    {
                        data: 'vehicle',
                        name: 'customerVehicle.vehicle.merk',
                        orderable: false,
                    },
                    {
                        data: 'service_at',
                        name: 'service_at',
                        orderable: true,
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'formatted_total',
                        name: 'total',
                        orderable: true,
                    },
                    {
                        data: 'fee_amount',
                        name: 'FEE',
                        orderable: true,
                    },
                    {
                        data: 'ppn_amount',
                        name: 'PPN',
                        orderable: true,
                    },
                    {
                        data: 'net_profit',
                        name: 'Profit',
                        orderable: true,
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
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-4 md:mb-0"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-4 md:mb-0"i><"pagination"p>>',
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
                        'bg-gray-700 border border-gray-600 text-green-600 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
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

            function numberFormat(num) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(num);
            }

            function formatDate(dateStr) {
                var parts = dateStr.split('-');
                var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return parts[2] + ' ' + months[parseInt(parts[1]) - 1] + ' ' + parts[0];
            }

            loadSummary();

            $('#form-filter').submit(function(e) {
                e.preventDefault();
                table.draw();
                loadSummary();
            });

            $('#reset-filter').on('click', function() {
                $('input[name="start_date"]').val('');
                $('input[name="end_date"]').val('');
                $('#status').val('');
                table.draw();
                loadSummary();
            });
        });

        $(document).on('click', '.delete-jo', function() {
            const salesId = $(this).data('id');
            const joName = $(this).data('name');

            Swal.fire({
                title: 'Hapus Job Order?',
                html: `Anda yakin ingin menghapus Job Order <strong>${joName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form delete secara dinamis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/job-orders/${salesId}`;

                    // Tambahkan CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');
                    form.appendChild(csrfToken);

                    // Tambahkan method spoofing
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endpush
