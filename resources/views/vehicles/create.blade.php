@extends('layouts.dashboard')
@section('title', 'Data Kendaraan')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Kendaraan Baru</h2>
            <a href="{{ route('vehicles.index') }}"
                class="text-gray-300 bg-gray-700 dark:placeholder-gray-400 dark:text-white hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Merk Field -->
                    <div>
                        <label for="merk" class="block text-sm font-medium text-gray-300 mb-2">Merk <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="merk" id="merk" value="{{ old('merk') }}"
                            class="mt-1 block w-full bg-gray-700 placeholder-gray-400 text-white  border {{ $errors->has('merk') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Contoh: Toyota" required>
                        @error('merk')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Field -->
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-300 mb-2">Tipe <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="tipe" id="tipe" value="{{ old('tipe') }}"
                            class="mt-1 block w-full bg-gray-700 placeholder-gray-400 text-white border {{ $errors->has('tipe') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required placeholder="Contoh: Avanza">
                        @error('tipe')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No Polisi Field -->
                    <div>
                        <label for="no_pol" class="block text-sm font-medium text-gray-300 mb-2">Nomor Polisi <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="no_pol" id="no_pol" value="{{ old('no_pol') }}"
                            class="mt-1 block w-full bg-gray-700 placeholder-gray-400 text-white border {{ $errors->has('no_pol') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required placeholder="Contoh: B1234ABC">
                        @error('no_pol')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer Field (Autocomplete) -->
                    <div>
                        <label for="search-dropdown" class="block text-sm font-medium text-gray-300 mb-2">Pemilik <span
                                class="text-red-500">*</span></label>

                        <div class="relative">
                            <input type="hidden" name="customer_id">
                            <input type="text" id="search-dropdown" readonly name="customer_name"
                                class="mt-1 block w-full bg-gray-700 placeholder-gray-400 text-white border {{ $errors->has('customer_id') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Cari Pemilik" required />
                            <button type="button" id="modal-select-customer"
                                class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                        @error('customer_id')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('vehicles.index') }}"
                        class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="select-customer" class="fixed  inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl h-full max-h-full flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">Pilih Customer</h3>
            </div>

            <div class="relative overflow-x-auto flex-1 p-6">
                <table class="w-full text-sm text-left text-gray-400" id="customer-table-list" style="width: 100%;">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400
                    sticky top-0">
                        <tr>
                            <th class="px-4 py-3" width="5%">No</th>
                            <th class="px-4 py-3" width="30%">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">No.Telp</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3" width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="customer-list">

                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700 flex justify-end">
                <button type="button" id="cancel-customer-selection"
                    class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg">Batal</button>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerNameField = document.querySelector('input[name="customer_name"]');
            const modalSelectCustomer = document.getElementById('select-customer');
            const buttonSelectCustomer = document.getElementById('modal-select-customer');
            modalSelectCustomer.classList.add('hidden');

            buttonSelectCustomer.addEventListener('click', showModalCustomer);


            var tableCustomer = $('#customer-table-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('api.customer.search') }}",

                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'address',
                        name: 'address',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'px-4 py-1'
                    },

                ],

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


            function showModalCustomer() {
                tableCustomer.draw();
                modalSelectCustomer.classList.remove('hidden');
            }

            $(document).on('click', '.select-customer', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('input[name="customer_name"]').val(name);
                $('input[name="customer_id"]').val(id);
                modalSelectCustomer.classList.add('hidden');
            })

            $('#cancel-customer-selection').click(function(e) {
                e.preventDefault();
                modalSelectCustomer.classList.add('hidden');
            });
        });
    </script>
@endpush
