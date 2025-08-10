@extends('layouts.dashboard')

@section('title', 'Barang Masuk')
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
            <h2 class="text-xl font-semibold text-white">Barang Masuk</h2>
            <div class="flex items-center space-x-4">
                <form method="GET" class="flex items-center space-x-2" id="form-filter">
                    <input type="date" name="start_date"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-3 py-2">
                    <span class="text-gray-400">s/d</span>
                    <input type="date" name="end_date"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-3 py-2">
                    @php
                        $statusText = [
                            'draft' => 'Draft',
                            'pending' => 'Pending',
                            'done' => 'Selesai',
                            'cancelled' => 'Batal',
                        ];
                    @endphp
                    <select name="status" id="status"
                        class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        @foreach ($statusText as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="reset-filter"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Reset
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Filter
                    </button>
                </form>

            </div>
        </div>

        <div class="p-4">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatables-index">
                    <thead class="uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3">Est Qty</th>
                            <th class="p-3">Real Qty</th>
                            <th class="p-3">Harga Beli</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Referensi</th>
                            <th class="p-3">Dibuat Oleh</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Aksi</th>
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
            var table = $('#datatables-index').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10, // Default 10 data/halaman
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                ajax: {
                    url: "{{ route('movement-items.index') }}",
                    data: function(d) {
                        d.move = 'in';
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
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'est_quantity',
                        name: 'est_quantity'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'buying_price',
                        name: 'buying_price'
                    },

                    {
                        data: 'grand_total',
                        name: 'grand_total'
                    },
                    {
                        data: 'reference_info',
                        name: 'reference'
                    },
                    {
                        data: 'created_by_name',
                        name: 'creator.name'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'aksi'
                    }
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
            $('#form-filter').submit(function(e) {
                e.preventDefault();
                table.draw();
            });


            $('#reset-filter').on('click', function() {
                $('input[name="start_date"]').val('');
                $('input[name="end_date"]').val('');
                $('#status').val('');
                table.draw();
            });

            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Hapus Barang Masuk?',
                    html: `Anda yakin ingin menghapus item <strong>${name}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/movement-items/${id}`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = $('meta[name="csrf-token"]').attr('content');
                        form.appendChild(csrfToken);

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
        });
    </script>
@endpush
