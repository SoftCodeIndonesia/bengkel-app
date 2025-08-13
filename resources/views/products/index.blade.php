@extends('layouts.dashboard')

@section('title', 'Data Sparepart')
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


        #productsTable {
            border-bottom: 1px solid #4b5563 !important;
        }

        /* Tambahan styling untuk dark mode */
        #productsTable tbody tr {
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
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Data Sparepart</h2>
            <div class="flex gap-3">
                <button id="delete-selected" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg hidden">
                    Hapus Terpilih
                </button>
                <a href="{{ route('products.create') }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    Tambah Sparepart
                </a>
                <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16M4 12h16"></path>
                    </svg>
                    Import Sparepart/Jasa
                </button>

            </div>
        </div>

        <div class="p-4">

            <!-- Sparepart Table -->
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="productsTable">
                    <thead class="uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="p-3 text-sm font-semibold">No</th>
                            <th class="p-3 text-sm font-semibold">Nama</th>
                            <th class="p-3 text-sm font-semibold">PN</th>
                            <th class="p-3 text-sm font-semibold">Stok</th>
                            <th class="p-3 text-sm font-semibold">Harga Beli</th>
                            <th class="p-3 text-sm font-semibold">Harga Jual</th>
                            <th class="p-3 text-sm font-semibold">Margin (%)</th>
                            <th class="p-3 text-sm font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>

                </table>
            </div>


        </div>
    </div>

    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold text-white mb-4">Import Data Sparepart</h2>

            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls"
                    class="block w-full text-gray-300 border border-gray-600 rounded p-2 bg-gray-700 focus:outline-none focus:border-blue-500 mb-4"
                    required>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'part_number',
                        name: 'part_number',
                        orderable: false
                    },
                    {
                        data: 'stok',
                        name: 'stok',
                        orderable: true
                    },
                    {
                        data: 'buying_price',
                        name: 'buying_price',
                        orderable: true
                    },

                    {
                        data: 'formatted_price',
                        name: 'unit_price',
                        orderable: true
                    },
                    {
                        data: 'margin',
                        name: 'margin',
                        orderable: true
                    },

                    {
                        data: 'action',
                        name: 'action',
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

            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleDeleteButton();
            });

            $(document).on('change', '.row-checkbox', function() {
                toggleDeleteButton();
            });

            function toggleDeleteButton() {
                let selected = $('.row-checkbox:checked').length;
                if (selected > 0) {
                    $('#delete-selected').removeClass('hidden');
                } else {
                    $('#delete-selected').addClass('hidden');
                }
            }

            $(document).on('click', '.remove-produk', function() {
                const salesId = $(this).data('id');
                const joName = $(this).data('name');

                Swal.fire({
                    title: 'Hapus Data Sparepart?',
                    html: `Anda yakin ingin menghapus Sparepart <strong>${joName}</strong>?`,
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
                        form.action = `/products/${salesId}`;

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

            $('#delete-selected').on('click', function() {
                Swal.fire({
                    title: 'Hapus Sparepart?',
                    html: `Anda yakin ingin menghapus data sparepart?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        let ids = $('.row-checkbox:checked').map(function() {
                            return $(this).val();
                        }).get();

                        $.ajax({
                            url: "{{ route('products.bulk-delete') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: ids
                            },
                            success: function(res) {
                                $('#productsTable').DataTable().ajax.reload();
                                $('#delete-selected').addClass('hidden');
                            }
                        });
                    }
                });


            });

        });
    </script>
@endpush
