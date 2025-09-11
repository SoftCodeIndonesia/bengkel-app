@extends('layouts.dashboard')

@section('title', 'Daftar Pengeluaran')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        #expensesTable {
            border-bottom: 1px solid #4b5563 !important;
        }

        #expensesTable tbody tr {
            background-color: transparent !important;
        }

        .dataTables_info {
            color: #f3f4f6 !important;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="sm:text-xl text-sm font-semibold text-white">Daftar Pengeluaran</h2>
            <a href="{{ route('expenses.create') }}"
                class="sm:text-sm text-sm text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Pengeluaran
            </a>
        </div>

        <!-- Filter Section -->
        <div class="p-4 border-b border-gray-600">
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-300 text-sm mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date"
                        class="w-full bg-gray-700 text-white rounded-md border-gray-600">
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="w-full bg-gray-700 text-white rounded-md border-gray-600">
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-1">Kategori</label>
                    <select name="category_id" class="w-full bg-gray-700 text-white rounded-md border-gray-600">
                        <option value="">Semua</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-1">Metode</label>
                    <select name="payment_method" class="w-full bg-gray-700 text-white rounded-md border-gray-600">
                        <option value="">Semua</option>
                        <option value="cash">Tunai</option>
                        <option value="bank_transfer">Transfer</option>
                        <option value="credit">Kredit</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-600">
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-gray-400 text-sm">Total Tunai</h3>
                <p id="totalCash" class="text-xl font-bold text-white">Rp 0</p>
            </div>
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-gray-400 text-sm">Total Transfer</h3>
                <p id="totalTransfer" class="text-xl font-bold text-white">Rp 0</p>
            </div>
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-gray-400 text-sm">Total Semua</h3>
                <p id="grandTotal" class="text-xl font-bold text-white">Rp 0</p>
            </div>
        </div>

        <!-- Table -->
        <div class="p-4">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400" id="expensesTable">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3 text-right">Jumlah</th>
                            <th class="p-3">Metode</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#expensesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('expenses.getExpenses') }}",
                    data: function(d) {
                        d.start_date = $('[name="start_date"]').val();
                        d.end_date = $('[name="end_date"]').val();
                        d.category_id = $('[name="category_id"]').val();
                        d.payment_method = $('[name="payment_method"]').val();
                    },
                    dataSrc: function(json) {
                        // Update summary cards
                        $('#totalCash').text('Rp ' + new Intl.NumberFormat().format(json.total_cash ??
                            0));
                        $('#totalTransfer').text('Rp ' + new Intl.NumberFormat().format(json
                            .total_transfer ?? 0));
                        $('#grandTotal').text('Rp ' + new Intl.NumberFormat().format(json.grand_total ??
                            0));
                        return json.data;
                    }
                },
                columns: [{
                        data: 'date_formatted',
                        name: 'date'
                    },
                    {
                        data: 'category_name',
                        name: 'category.name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'amount_formatted',
                        name: 'amount',
                        className: 'text-right'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-right'
                    }
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
                        'bg-gray-700 border border-gray-600 text-green-600 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
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

            // Reload ketika filter berubah
            $('#filterForm').on('change', 'input, select', function() {
                table.ajax.reload();
            });

            // Delete button (sama seperti sebelumnya)
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Pengeluaran?',
                    text: 'Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/expenses/${id}`;
                        form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
