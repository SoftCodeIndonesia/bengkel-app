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
@section('title', 'Edit Follow Up')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Edit Follow Up</h2>
            <a href="{{ route('follow-ups.index') }}"
                class="text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="p-6">
            <form action="{{ route('follow-ups.update', $followUp->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label for="customer_vehicle_id" class="block text-gray-300 mb-2">Cari Customer</label>
                            <select name="customer_vehicle_id" id="customer_vehicle_id" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            </select>
                            @error('customer_vehicle_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_service_date" class="block text-gray-300 mb-2">Tanggal Servis Terakhir</label>
                            <input type="date" name="last_service_date" id="last_service_date" required
                                value="{{ old('last_service_date', $followUp->last_service_date->format('Y-m-d')) }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('last_service_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="job_order_search" class="block text-gray-300 mb-2">Job Order Terkait
                                (Opsional)</label>
                            <select name="job_order_id" id="job_order_search"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tidak ada</option>

                            </select>
                            @error('job_order_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Status Kontak</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="contacted" value="1"
                                        {{ $followUp->contacted ? 'checked' : '' }}
                                        class="text-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-gray-300">Sudah Dihubungi</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="contacted" value="0"
                                        {{ !$followUp->contacted ? 'checked' : '' }}
                                        class="text-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-gray-300">Belum Dihubungi</span>
                                </label>
                            </div>
                            @error('contacted')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="contact-date-container" style="{{ !$followUp->contacted ? 'display: none;' : '' }}">
                            <label for="contact_date" class="block text-gray-300 mb-2">Tanggal Kontak</label>
                            <input type="date" name="contact_date" id="contact_date"
                                value="{{ old('contact_date', $followUp->contact_date ? $followUp->contact_date->format('Y-m-d') : '') }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('contact_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-gray-300 mb-2">Catatan</label>
                            <textarea name="notes" id="notes" rows="4"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $followUp->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const contactedRadios = document.querySelectorAll('input[name="contacted"]');
                const contactDateContainer = document.getElementById('contact-date-container');

                contactedRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === '1') {
                            contactDateContainer.style.display = 'block';
                            document.getElementById('contact_date').required = true;
                        } else {
                            contactDateContainer.style.display = 'none';
                            document.getElementById('contact_date').required = false;
                        }
                    });
                });

                var follow_up = @json($followUp);

                console.log(follow_up);

                new TomSelect('#customer_vehicle_id', {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    items: [follow_up.customer_vehicle_id ?? ''],
                    options: [{
                        id: follow_up.customer_vehicle_id,
                        text: `${follow_up.customer_vehicle.customer.name} - ${follow_up.customer_vehicle.vehicle.merk} ${follow_up.customer_vehicle.vehicle.tipe} (${follow_up.customer_vehicle.vehicle.no_pol})`
                    }],
                    create: false,
                    load: function(query, callback) {
                        var url = base_url + '/api/customers_vehicle/search' + '?q=' + encodeURIComponent(
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
                new TomSelect('#job_order_search', {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    items: [follow_up.job_order.id ?? ''],
                    options: [{
                        id: follow_up.job_order.id,
                        text: `${follow_up.job_order.unique_id}`
                    }],
                    create: false,
                    load: function(query, callback) {
                        var url = base_url + '/api/job_order/search' + '?q=' + encodeURIComponent(
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
@endsection
