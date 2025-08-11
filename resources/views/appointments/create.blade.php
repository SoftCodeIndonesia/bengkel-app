@extends('layouts.dashboard')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom TomSelect Theme */
        .ts-wrapper {
            --ts-pr-600: #2563eb;
            /* Warna primary */
            --ts-pr-200: #93c5fd;
            --ts-option-radius: 0.375rem;
            padding: 5px !important;
            /* rounded-md */
        }

        .ts-wrapper .item {
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            color: #f3f4f6 !important;
        }

        /* Wrapper dan Control */
        .ts-wrapper.single .ts-control {
            @apply bg-gray-700 border border-gray-600 text-gray-300;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        /* Dropdown */
        .ts-dropdown,
        .ts-dropdown .active {
            background-color: rgb(57 65 81) !important;
            border-color: rgb(57 65 81) !important;
        }



        /* Option */
        .ts-dropdown .option {
            @apply text-gray-300 hover:bg-gray-600;
        }

        /* Selected Option */
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

        /* Input Search */
        .ts-control input {
            @apply bg-gray-700 text-gray-300 placeholder-gray-400;
        }

        /* Focus State */
        .ts-control.focus {
            @apply ring-2 ring-blue-500 border-blue-500;
        }

        /* Error State */
        .ts-wrapper.error .ts-control {
            @apply border-red-500;
        }

        /* Item Selected */
        .ts-wrapper .item {
            @apply bg-gray-600 text-gray-300 rounded;
        }

        /* Clear Button */
        .ts-wrapper .clear-button {
            @apply text-gray-400 hover:text-gray-300;
        }
    </style>
@endpush
@section('title', 'Create Appointment')
@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Appointment Baru</h2>
        </div>

        <div class="p-4">
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-300 mb-1">Customer</label>
                        <select id="customer_id" name="customer_id" required
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full">
                            <option value="">Cari Customer</option>
                        </select>
                    </div>

                    <!-- Vehicle Selection -->
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-300 mb-1">Kendaraan</label>
                        <select id="vehicle_id" name="vehicle_id" required
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full">
                            <option value="">Cari Data Kendaraan</option>
                        </select>
                    </div>

                    <!-- Date and Time -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-300 mb-1">Tanggal</label>
                        <input type="date" id="date" name="date" required min="{{ date('Y-m-d') }}"
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full">
                    </div>

                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-300 mb-1">Jam</label>
                        <input type="time" id="time" name="time" required
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full">
                    </div>



                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                        <textarea id="notes" name="notes" rows="2"
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('appointments.index') }}"
                        class="mr-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">Cancel</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#customer_id').change(function() {
                const customerId = $(this).val();
                if (customerId) {
                    $.get(`/appointments/customer-vehicles/${customerId}`, function(data) {
                        $('#vehicle_id').empty().append('<option value="">Select Vehicle</option>');
                        $.each(data, function(key, vehicle) {
                            $('#vehicle_id').append(
                                `<option value="${vehicle.id}">${vehicle.merk} ${vehicle.tipe} - ${vehicle.no_pol}</option>`
                            );
                        });
                    });
                } else {
                    $('#vehicle_id').empty().append('<option value="">Select Vehicle</option>');
                }
            });

            new TomSelect('#vehicle_id', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                create: false,
                load: function(query, callback) {
                    var url = base_url + '/api/vehicle/search' + '?q=' + encodeURIComponent(
                        query) + '&customer_id=' + $('#customer_id').val();
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            console.log(json)
                            callback(json);
                        }).catch(() => {
                            console.log('error');
                            callback();
                        });
                },
                render: {
                    option: function(item, escape) {

                        return `
                                <div class="flex items-center p-2 bg-gray-700 text-gray-400" data-json="${item}">
                                    <div class="ml-2">
                                        <div class="text-gray-300">${escape(item.text)}</div>
                                    </div>
                                </div>`;
                    },
                    item: function(item, escape) {
                        return `<div class="bg-gray-600 text-gray-300 px-2 py-1 rounded">${escape(item.text)}</div>`;
                    },
                    no_results: function(data, escape) {

                        return `<div class="p-2 text-gray-400">Tidak ditemukan "${escape(data.input)}"</div>`;
                    },
                    option_create: function(data, escape) {
                        return `<div class="create p-2 text-gray-400 hover:bg-gray-600">Tambah baru: <strong>${escape(data.input)}</strong></div>`;
                    }
                },
                onInitialize: function() {
                    // Tambahkan class error jika ada validasi error
                    if (this.input.classList.contains('border-red-500')) {
                        this.wrapper.classList.add('error');
                    }
                },

            });

            new TomSelect('#customer_id', {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                create: false,
                load: function(query, callback) {
                    var url = base_url + '/api/customers/search' + '?q=' + encodeURIComponent(
                        query);
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            console.log(json)
                            callback(json);
                        }).catch(() => {
                            console.log('error');
                            callback();
                        });
                },
                render: {
                    option: function(item, escape) {

                        return `
                                <div class="flex items-center p-2 bg-gray-700 text-gray-400" data-json="${item}">
                                    <div class="ml-2">
                                        <div class="text-gray-300">${escape(item.text)}</div>
                                    </div>
                                </div>`;
                    },
                    item: function(item, escape) {
                        return `<div class="bg-gray-600 text-gray-300 px-2 py-1 rounded">${escape(item.text)}</div>`;
                    },
                    no_results: function(data, escape) {

                        return `<div class="p-2 text-gray-400">Tidak ditemukan "${escape(data.input)}"</div>`;
                    },
                    option_create: function(data, escape) {
                        return `<div class="create p-2 text-gray-400 hover:bg-gray-600">Tambah baru: <strong>${escape(data.input)}</strong></div>`;
                    }
                },
                onInitialize: function() {
                    // Tambahkan class error jika ada validasi error
                    if (this.input.classList.contains('border-red-500')) {
                        this.wrapper.classList.add('error');
                    }
                },

            });
        });
    </script>
@endpush
