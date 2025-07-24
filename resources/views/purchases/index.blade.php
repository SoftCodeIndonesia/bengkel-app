@extends('layouts.dashboard')

@section('title', 'Data Pembelian')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Data Pembelian</h2>
            <a href="{{ route('purchases.create') }}"
                class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Tambah Pembelian
            </a>
        </div>

        <div class="p-4">
            @if (session('success'))
                <div class="bg-green-600 text-white p-4 rounded-md mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    id="datatables-index">
                    <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="p-3 text-sm font-semibold">No</th>
                            <th class="p-3 text-sm font-semibold">No. Invoice</th>
                            <th class="p-3 text-sm font-semibold">Tanggal</th>
                            <th class="p-3 text-sm font-semibold">Supplier</th>
                            <th class="p-3 text-sm font-semibold">Total</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const table = $('#datatables-index').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('purchases.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'purchase_date',
                        name: 'purchase_date'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier.name'
                    },
                    {
                        data: 'total',
                        name: 'total'
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
                    $('.dataTables_length label').addClass('text-gray-400');
                    $('.dataTables_filter label').addClass('text-gray-400');
                    $('.dataTables_info').addClass('text-gray-400');
                    $('.dataTables_filter input').addClass(
                        'bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                    );
                    $('.dataTables_length select').addClass(
                        'bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                    );
                    $('.dataTables_processing').css({
                        'background': 'transparent',
                        'color': 'white'
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

            // SweetAlert untuk delete
            $(document).on('click', '.delete-btn', function() {
                const purchaseId = $(this).data('id');
                const invoiceNumber = $(this).data('invoice');

                Swal.fire({
                    title: 'Hapus Pembelian?',
                    html: `Apakah Anda yakin ingin menghapus pembelian <strong>${invoiceNumber}</strong>?<br><small>Stok produk akan dikurangi sesuai jumlah pembelian.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    background: '#1f2937',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchases.destroy', '') }}/" + purchaseId,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Terhapus!',
                                        text: response.message,
                                        icon: 'success',
                                        background: '#1f2937',
                                        color: '#fff'
                                    });
                                    table.draw();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan. Silakan coba lagi.',
                                    icon: 'error',
                                    background: '#1f2937',
                                    color: '#fff'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
