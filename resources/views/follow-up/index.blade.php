@extends('layouts.dashboard')

@section('title', 'Data Follow Up')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        #datatables-index {
            border-bottom: 1px solid #4b5563 !important;
        }

        #datatables-index tbody tr {
            background-color: transparent !important;
        }

        .dataTables_info {
            color: #f3f4f6 !important;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Data Follow Up</h2>

        </div>

        <div class="p-4">
            <!-- Filter Section -->
            <div class="bg-gray-700 rounded-lg p-4 mb-4">
                <form id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Tanggal Servis Terakhir</label>
                            <input type="text" id="last-service-date" name="last_service_date"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Pilih rentang tanggal">
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Status Kontak</label>
                            <select name="contacted" id="contacted"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="1">Sudah Dihubungi</option>
                                <option value="0">Belum Dihubungi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Tanggal Kontak</label>
                            <input type="text" id="contact-date" name="contact_date"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Pilih rentang tanggal">
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
                        <thead class="uppercase  bg-gray-700 text-gray-400">
                            <tr>
                                <th class="p-3 text-sm font-semibold">No</th>
                                <th class="p-3 text-sm font-semibold">Kendaraan</th>
                                <th class="p-3 text-sm font-semibold">Pemilik</th>
                                <th class="p-3 text-sm font-semibold">Servis Terakhir</th>
                                <th class="p-3 text-sm font-semibold">Status Kontak</th>
                                <th class="p-3 text-sm font-semibold">Tanggal Kontak</th>
                                <th class="p-3 text-sm font-semibold">Job Order</th>
                                <th class="p-3 text-sm font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
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
            // Date range picker untuk last service date
            $('#last-service-date').daterangepicker({
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

            $('#last-service-date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#last-service-date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // Date range picker untuk contact date
            $('#contact-date').daterangepicker({
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

            $('#contact-date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#contact-date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            var table = $('#datatables-index').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('follow-ups.index') }}",
                    data: function(d) {
                        d.last_service_date = $('#last-service-date').val();
                        d.contact_date = $('#contact-date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'vehicle',
                        name: 'vehicle',
                    },
                    {
                        data: 'customer',
                        name: 'customer',
                    },
                    {
                        data: 'last_service_date',
                        name: 'last_service_date',
                        render: function(data) {
                            return moment(data).format('DD MMM YYYY');
                        }
                    },
                    {
                        data: 'contacted',
                        name: 'contacted',
                        render: function(data) {
                            const color = data ? 'bg-green-500' : 'bg-yellow-500';
                            const text = data ? 'Sudah' : 'Belum';
                            return `<span class="px-2 py-1 rounded-full text-xs text-gray-800 ${color}">${text}</span>`;
                        }
                    },
                    {
                        data: 'contact_date',
                        name: 'contact_date',
                        render: function(data) {
                            return data ? moment(data).format('DD MMM YYYY') : '-';
                        }
                    },
                    {
                        data: 'jo_number',
                        name: 'jo_number',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="flex justify-end space-x-2">
                                <a href="/follow-ups/${row.id}/edit" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button type="button" class="text-red-500 hover:text-red-700 delete-jo" data-id="${row.id}" data-name="${row.customer_vehicle.customer.name}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            `;
                        }
                    },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"pagination-container"p>>',
                initComplete: function() {
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
                    $('.dataTables_info').addClass('text-gray-400');
                    $('.pagination-container .paginate_button').addClass(
                        'px-3 py-1 mx-1 text-gray-300 bg-gray-700 border border-gray-600 rounded-md hover:bg-gray-600 hover:text-white transition duration-150'
                    );
                    $('.pagination-container .paginate_button.current').addClass(
                        'bg-blue-600 text-white border-blue-600');

                    $('.dataTables_paginate').addClass('flowbite-pagination');
                    $('.paginate_button').each(function() {
                        $(this).removeClass('paginate_button previous next first last');
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

            $('#reset-filter').on('click', function() {
                $('#last-service-date').val('');
                $('#contacted').val('');
                $('#contact-date').val('');
                table.draw();
            });
        });

        $(document).on('click', '.delete-jo', function() {
            const followId = $(this).data('id');
            const joName = $(this).data('name');

            Swal.fire({
                title: 'Hapus Data Follow Up?',
                html: `Anda yakin ingin menghapus Follow Up <strong>${joName}</strong>?`,
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
                    form.action = `/follow-ups/${followId}`;

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
