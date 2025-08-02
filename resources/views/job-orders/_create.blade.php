@extends('layouts.dashboard')

@section('title', 'Buat Job Order')
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
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Job Order Baru</h2>
            <a href="{{ route('job-orders.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('job-orders.store') }}" method="POST" id="jobOrderForm">
                @csrf

                <div class="w-full" id="field-customer_vehicle_id">
                    <label for="customer_vehicle_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Kendaraan Pelanggan </label>
                    <select name="customer_vehicle_id" id="customer_vehicle_id"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">

                    </select>
                    @error('customer_vehicle_id')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="button" id="add-customer"
                    class="mt-2 text-blue-500 dark:text-blue-500 hover:text-blue-400 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Pelanggan Baru
                </button>

                <div class="mt-4 hidden" id="customer-vehicle-detail-container">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Detail Customer -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-3 border-b border-gray-600 pb-2">Detail
                                    Pelanggan</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-300">Nama:</p>
                                        <p class="text-white font-medium" id="customer-name">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Telepon:</p>
                                        <p class="text-white font-medium" id="customer-phone">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Email:</p>
                                        <p class="text-white font-medium" id="customer-email">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Alamat:</p>
                                        <p class="text-white font-medium" id="customer-address">-</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Kendaraan -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-3 border-b border-gray-600 pb-2">Detail
                                    Kendaraan</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-300">Merk:</p>
                                        <p class="text-white font-medium" id="vehicle-merk">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Tipe:</p>
                                        <p class="text-white font-medium" id="vehicle-type">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Nomor Polisi:</p>
                                        <p class="text-white font-medium" id="vehicle-plate">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Tahun:</p>
                                        <p class="text-white font-medium" id="vehicle-year">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 {{ old('customer_name') ? '' : 'hidden' }}" id="add-customer-section">
                    <div class="flex gap-6" id="add-customer-section">
                        <div class="flex-1">
                            <div class="mb-4" id="field-customer_name">
                                <label for="name" class="block text-sm font-medium text-gray-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" id="name" value="{{ old('customer_name') }}"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Nama lengkap pelanggan">
                                @error('customer_name')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="email@contoh.com">

                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-300">
                                    Nomor Telepon
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="081234567890">

                            </div>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-300">
                                    Alamat
                                </label>
                                <textarea name="address" id="address" rows="3"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Alamat lengkap pelanggan">{{ old('address') }}</textarea>

                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="mb-4" id="field-merk">
                                <label for="merk" class="block text-sm font-medium text-gray-300">Merk <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="merk" id="merk" value="{{ old('merk') }}"
                                    class="mt-1 block w-full bg-gray-700 dark:placeholder-gray-400 dark:text-white  border {{ $errors->has('merk') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Contoh: Toyota">
                                @error('merk')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipe Field -->
                            <div class="mb-4" id="field-tipe">
                                <label for="tipe" class="block text-sm font-medium text-gray-300">Tipe <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="tipe" id="tipe" value="{{ old('tipe') }}"
                                    class="mt-1 block w-full bg-gray-700 dark:placeholder-gray-400 dark:text-white border {{ $errors->has('tipe') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Contoh: Avanza">
                                @error('tipe')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Polisi Field -->
                            <div id="field-no-pol">
                                <label for="no_pol" class="block text-sm font-medium text-gray-300">Nomor Polisi
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="no_pol" id="no_pol" value="{{ old('no_pol') }}"
                                    class="mt-1 block w-full bg-gray-700 dark:placeholder-gray-400 dark:text-white border {{ $errors->has('no_pol') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Contoh: B1234ABC">
                                @error('no_pol')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">


                    <div>
                        <label for="km" class="block text-sm font-medium text-gray-300 mb-2">Kilometer <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="km" id="km" value="{{ old('km') }}"
                            placeholder="Contoh: 100000" required min="0"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="service_at" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Servis <span
                                class="text-red-500">*</span></label>
                        <input type="datetime-local" value="{{ old('service_at') }}" name="service_at" id="service_at"
                            required
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Breakdown Kerusakan</h3>
                    <div id="breakdowns-container">
                        @php $breakIndex = 0; @endphp
                        @if (old('breakdowns'))
                            @foreach (old('breakdowns') as $breakdown)
                                <div class="breakdown-row flex gap-4 mb-3">
                                    <div class="col-span-11 flex-1">
                                        <input type="text" name="breakdowns[{{ $breakIndex }}][name]"
                                            value="{{ $breakdown['name'] }}" placeholder="Masukan Kerusakan"
                                            class="w-full bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <div class="col-span-1 flex items-center">
                                        <button type="button" class="remove-breakdown text-red-500 hover:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @php $breakIndex++; @endphp
                            @endforeach
                        @else
                            <div class="breakdown-row flex gap-4 mb-3">
                                <div class="col-span-11 flex-1">
                                    <input type="text" name="breakdowns[0][name]" placeholder="Masukan Kerusakan"
                                        class="w-full bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div class="col-span-1 flex items-center">
                                    <button type="button" class="remove-breakdown text-red-500 hover:text-red-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-breakdown"
                        class="mt-2 text-blue-500 dark:text-blue-500 hover:text-blue-400 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Breakdown
                    </button>
                </div>



                {{-- ===================== SPAREPART ===================== --}}
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Jasa/Sparepart (Barang)</h3>
                    <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm"
                        id="sparepart-table">
                        <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="p-2">Produk</th>
                                <th class="p-2">Kategori</th>
                                <th class="p-2">FRT/QTY</th>
                                <th class="p-2">Harga Satuan</th>
                                <th class="p-2">Subtotal</th>
                                <th class="p-2">Diskon (%)</th>
                                <th class="p-2">Total</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sparepart-items-container">
                            {{-- Baris sparepart akan ditambahkan di sini --}}

                        </tbody>
                    </table>
                    <div class="flex gap-4">
                        <button type="button" id="add-sparepart"
                            class="mt-3 text-blue-500 hover:text-blue-400 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Sparepart
                        </button>
                        <button type="button" id="add-service"
                            class="mt-3 text-blue-500 hover:text-blue-400 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Jasa
                        </button>
                    </div>
                </div>



                <div class="bg-gray-700 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="col-span-1">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-300">Total Sparepart:</span>
                                <input type="text" name="total_sparepart" id="total-sparepart" value="Rp 0"
                                    class="bg-gray-700 border-none text-end text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    readonly>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-300">Total Jasa:</span>
                                <input type="text" name="total_jasa" id="total-jasa" value="Rp 0"
                                    class="bg-gray-700 border-none text-end text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    readonly>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-300">Total Diskon:</span>
                                <input type="text" name="total_diskon_item" id="total-diskon-item" value="Rp 0"
                                    class="bg-gray-700 border-none text-end text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-600 pt-2 mt-2">
                        <div class="flex justify-between text-lg font-medium">
                            <span class="text-gray-300">Grand Total:</span>
                            <input type="text" name="total" id="total" value="Rp 0"
                                class="bg-gray-700 border-none text-end text-blue-400 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                readonly>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('job-orders.index') }}"
                        class="px-4 py-2 text-gray-300 bg-gray-600 hover:bg-gray-500 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-200">
                        Simpan Job Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @endpush
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi counter untuk items dan breakdowns
            let itemCounter = 1;
            let breakdownCounter = 1;

            new TomSelect('#customer_vehicle_id', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
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

            // Fungsi untuk menambahkan item baru
            // document.getElementById('add-item').addEventListener('click', function() {
            //     const newItem = document.createElement('div');
            //     newItem.className = 'item-row grid grid-cols-12 gap-4 mb-3';
            //     newItem.innerHTML = `
        //     <div class="col-span-6">
        //         <select name="items[${itemCounter}][product_id]" required
        //             class="product-select w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        //             <option value="">Pilih Produk</option>
        //             @foreach ($products as $product)
        //                 <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" data-type="{{ $product->tipe }}">
        //                     {{ $product->name }} ({{ $product->tipe === 'barang' ? 'Sparepart' : 'Jasa' }})
        //                 </option>
        //             @endforeach
        //         </select>
        //     </div>
        //     <div class="col-span-3">
        //         <input type="number" name="items[${itemCounter}][quantity]" required min="1" value="1"
        //             class="quantity w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        //     </div>
        //     <div class="col-span-2 flex items-center">
        //         <span class="price text-gray-300">Rp 0</span>
        //     </div>
        //     <div class="col-span-1 flex items-center">
        //         <button type="button" class="remove-item text-red-500 hover:text-red-400">
        //             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        //                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        //             </svg>
        //         </button>
        //     </div>
        // `;
            //     document.getElementById('items-container').appendChild(newItem);
            //     itemCounter++;

            //     // Tambahkan event listener untuk item baru
            //     addItemEventListeners(newItem);
            // });

            // Di dalam DOMContentLoaded, setelah inisialisasi TomSelect
            document.getElementById('customer_vehicle_id').addEventListener('change', function() {
                const selectedValue = this.value;
                const detailContainer = document.getElementById('customer-vehicle-detail-container');

                if (selectedValue) {
                    // Ambil data customer dan kendaraan dari API
                    fetch(`${base_url}/api/customer_vehicles/${selectedValue}/details`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Tampilkan detail customer
                                document.getElementById('customer-name').textContent = data.customer
                                    .name || '-';
                                document.getElementById('customer-phone').textContent = data.customer
                                    .phone || '-';
                                document.getElementById('customer-email').textContent = data.customer
                                    .email || '-';
                                document.getElementById('customer-address').textContent = data.customer
                                    .address || '-';

                                // Tampilkan detail kendaraan
                                document.getElementById('vehicle-merk').textContent = data.vehicle
                                    .merk || '-';
                                document.getElementById('vehicle-type').textContent = data.vehicle
                                    .tipe || '-';
                                document.getElementById('vehicle-plate').textContent = data.vehicle
                                    .no_pol || '-';
                                document.getElementById('vehicle-year').textContent = data.vehicle
                                    .year || '-';

                                // Tampilkan container
                                detailContainer.classList.remove('hidden');

                                // Auto-fill form tambah kendaraan jika ada
                                if (document.getElementById('merk')) {
                                    document.getElementById('merk').value = data.vehicle.merk || '';
                                }
                                if (document.getElementById('tipe')) {
                                    document.getElementById('tipe').value = data.vehicle.type || '';
                                }
                                if (document.getElementById('no_pol')) {
                                    document.getElementById('no_pol').value = data.vehicle.no_pol || '';
                                }
                            } else {
                                detailContainer.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching customer details:', error);
                            detailContainer.classList.add('hidden');
                        });
                } else {
                    detailContainer.classList.add('hidden');
                }
            });

            document.getElementById('add-sparepart').addEventListener('click', function() {
                addItemRow('barang');
            });

            var customer_form_active = false;

            $(document).on('click', '#add-customer', function() {
                if (customer_form_active) {
                    customer_form_active = false;
                } else {
                    customer_form_active = true;
                }
                $('#add-customer-section').toggle();
            })
            $(document).on('click', '#add-vehicle', function() {
                $('#add-vehicle-section').toggle();
            })
            document.getElementById('add-service').addEventListener('click', function() {
                addItemRow('jasa');
            });

            function addItemRow(type) {
                const tbody = document.getElementById('sparepart-items-container');

                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-600', 'item-row');
                row.innerHTML = `
        <td class="p-2" width="300px">
            <select name="items[${itemCounter}][product_id]" data-tipe="${type}" id="tom-autocomplete" required
                class="product-select tom-autocomplete w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2">
            </select>
        </td>
        <td class="p-2 text-center">
            <span class="kategori text-gray-300">-</span>
        </td>
        <td class="p-2" width="100px">
            <input type="number" name="items[${itemCounter}][quantity]" min="1" ${type !== 'jasa' ? 'value="1"' : ''} ${type == 'jasa' ? 'step="0.1"' : ''}
                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
        </td>
        <td class="p-2 text-right">
            <span class="unit-price text-gray-300">Rp 0</span>
        </td>
        <td class="p-2 text-right">
            <span class="subtotal text-gray-300">Rp 0</span>
        </td>
        <td class="p-2 text-right">
            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                value="0"
                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                placeholder="%">
        </td>
        <td class="p-2 text-right">
            <span class="total-after-diskon text-gray-300">Rp 0</span>
        </td>
        <td class="p-2 text-center">
            <button type="button" class="remove-item text-red-500 hover:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </td>
    `;
                tbody.appendChild(row);
                itemCounter++;

                // Event listener untuk kalkulasi
                initItemRowEvents(row, type);
            }

            function initItemRowEvents(row, type) {
                const select = row.querySelector('#tom-autocomplete');
                const qtyInput = row.querySelector('.quantity');
                const diskonValue = row.querySelector('.diskon-value');
                const kategori = row.querySelector('.kategori');
                const priceText = row.querySelector('.unit-price');
                const subtotalText = row.querySelector('.subtotal');
                const totalAfterDiskonText = row.querySelector('.total-after-diskon');


                initialTomSelect(select, type);

                const calculateItemTotal = () => {

                    const data = select.value ? JSON.parse(select.value) : null;
                    if (!data) return;

                    console.log(type);
                    console.log(qtyInput.value);

                    if (qtyInput.value == '') {
                        qtyInput.value = data.stok;
                    }

                    const price = parseFloat(data.unit_price) || 0;


                    const qty = parseFloat(qtyInput.value) || 1
                    const diskon = parseFloat(diskonValue.value) || 0;

                    const subtotal = price * qty;
                    const totalAfterDiskon = subtotal * (1 - (diskon / 100));

                    kategori.textContent = data.tipe;
                    priceText.textContent = 'Rp ' + formatNumber(price);
                    subtotalText.textContent = 'Rp ' + formatNumber(subtotal);
                    totalAfterDiskonText.textContent = 'Rp ' + formatNumber(totalAfterDiskon);

                    calculateTotal();
                };

                select.addEventListener('change', calculateItemTotal);
                qtyInput.addEventListener('input', calculateItemTotal);
                diskonValue.addEventListener('input', calculateItemTotal);

                row.querySelector('.remove-item').addEventListener('click', () => {
                    row.remove();
                    calculateTotal();
                });
            }

            function initialTomSelect(element, type) {
                console.log((element.tomselect));
                if (element.tomselect) return;
                new TomSelect(element, {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    create: false,
                    load: function(query, callback) {

                        var url = base_url + '/api/products/search?q=' + encodeURIComponent(
                            query) + '&tipe=' + encodeURIComponent(
                            type);
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {
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
                                        <div class="text-xs text-gray-400">${escape(item.price)}</div>
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
            }

            function initialTomSelectGlobal(element, endpoint) {
                new TomSelect(element, {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    create: false,
                    load: function(query, callback) {
                        console.log(base_url + endpoint + '?q=');
                        var url = base_url + endpoint + '?q=' + encodeURIComponent(
                            query);
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {
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
            }




            // Fungsi untuk menambahkan breakdown baru
            document.getElementById('add-breakdown').addEventListener('click', function() {
                const newBreakdown = document.createElement('div');
                newBreakdown.className = 'breakdown-row flex gap-4 mt-3';
                newBreakdown.innerHTML = `
                    <div class="col-span-11 flex-1">
                        <input type="text" name="breakdowns[${breakdownCounter}][name]" placeholder="Nama pemeriksaan"
                            class="w-full bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="col-span-1 flex items-center">
                        <button type="button" class="remove-breakdown text-red-500 hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                document.getElementById('breakdowns-container').appendChild(newBreakdown);
                breakdownCounter++;

                // Tambahkan event listener untuk breakdown baru
                addBreakdownEventListeners(newBreakdown);
            });

            // Fungsi untuk menghapus item
            // function addItemEventListeners(itemElement) {
            //     const productSelect = itemElement.querySelector('.product-select');
            //     const quantityInput = itemElement.querySelector('.quantity');
            //     const priceDisplay = itemElement.querySelector('.price');
            //     const removeBtn = itemElement.querySelector('.remove-item');

            //     productSelect.addEventListener('change', function() {
            //         const selectedOption = this.options[this.selectedIndex];
            //         const price = selectedOption.dataset.price || 0;
            //         const quantity = quantityInput.value || 1;
            //         const total = price * quantity;

            //         priceDisplay.textContent = 'Rp ' + formatNumber(total);
            //         calculateTotal();
            //     });

            //     quantityInput.addEventListener('input', function() {
            //         const productSelect = this.closest('.item-row').querySelector('.product-select');
            //         const selectedOption = productSelect.options[productSelect.selectedIndex];
            //         const price = selectedOption.dataset.price || 0;
            //         const quantity = this.value || 1;
            //         const total = price * quantity;

            //         this.closest('.item-row').querySelector('.price').textContent = 'Rp ' + formatNumber(
            //             total);
            //         calculateTotal();
            //     });

            //     removeBtn.addEventListener('click', function() {
            //         this.closest('.item-row').remove();
            //         calculateTotal();
            //     });
            // }

            function formatCurrencyToNumber(currencyString) {
                // Hapus semua karakter non-digit kecuali titik (untuk desimal)
                let numberString = currencyString.replace(/[^\d]/g, '');

                // Konversi string menjadi number
                return parseInt(numberString, 10);
            }

            // Fungsi untuk menghapus breakdown
            function addBreakdownEventListeners(breakdownElement) {
                const removeBtn = breakdownElement.querySelector('.remove-breakdown');

                removeBtn.addEventListener('click', function() {
                    this.closest('.breakdown-row').remove();
                });
            }

            // Fungsi untuk menghitung total
            function calculateTotal() {
                let totalSparepart = 0;
                let totalJasa = 0;
                let totalDiskonItem = 0;
                let subtotal = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const itemType = row.querySelector('.kategori').textContent;
                    const subtotalText = row.querySelector('.subtotal').textContent;
                    const totalAfterDiskonText = row.querySelector('.total-after-diskon').textContent;

                    const subtotalValue = parseFloat(subtotalText.replace('Rp ', '').replace(/\./g, '')) ||
                        0;
                    const totalAfterDiskon = parseFloat(totalAfterDiskonText.replace('Rp ', '').replace(
                        /\./g, '')) || 0;



                    if (itemType != 'jasa') {
                        totalSparepart += subtotalValue;
                    } else {
                        totalJasa += subtotalValue;
                    }

                    totalDiskonItem += (subtotalValue - totalAfterDiskon);
                    subtotal += totalAfterDiskon;
                });

                // const diskonUnit = document.getElementById('diskon_unit').value;
                // const diskonValue = parseFloat(document.getElementById('diskon_value').value) || 0;
                let diskonGlobal = 0;
                let grandTotal = subtotal;

                // diskonGlobal = subtotal * diskonValue / 100;
                // grandTotal = subtotal - diskonGlobal;

                // Update tampilan
                document.getElementById('total-sparepart').value = 'Rp ' + formatNumber(totalSparepart);
                document.getElementById('total-jasa').value = 'Rp ' + formatNumber(totalJasa);
                document.getElementById('total-diskon-item').value = 'Rp ' + formatNumber(totalDiskonItem);
                // document.getElementById('subtotal').value = 'Rp ' + formatNumber(subtotal);
                // document.getElementById('diskon-text').value = 'Rp ' + formatNumber(diskonGlobal);
                document.getElementById('total').value = 'Rp ' + formatNumber(grandTotal);

                // Update hidden inputs untuk form submit
                // document.querySelector('input[name="subtotal"]').value = subtotal;
                document.querySelector('input[name="total"]').value = 'Rp ' + formatNumber(grandTotal);;
            }

            // Format number dengan separator ribuan
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            // Enable/disable diskon value berdasarkan jenis diskon
            // document.getElementById('diskon_unit').addEventListener('change', function() {
            //     const diskonValueInput = document.getElementById('diskon_value');
            //     if (this.value) {
            //         diskonValueInput.disabled = false;
            //     } else {
            //         diskonValueInput.disabled = true;
            //         diskonValueInput.value = '';
            //     }
            //     calculateTotal();
            // });

            // Hitung ulang saat nilai diskon berubah
            // document.getElementById('diskon_value').addEventListener('input', calculateTotal);

            // Inisialisasi event listener untuk item pertama
            document.querySelectorAll('.item-row').forEach(item => {
                addItemEventListeners(item);
            });

            // Inisialisasi event listener untuk breakdown pertama
            document.querySelectorAll('.breakdown-row').forEach(breakdown => {
                addBreakdownEventListeners(breakdown);
            });

            // document.getElementById('diskon_value').addEventListener('input', calculateTotal);

            // Set tanggal default ke hari ini
            document.getElementById('service_at').value = new Date().toISOString().slice(0, 16);

            $('#jobOrderForm').submit(function(e) {
                // e.preventDefault();
                // console.log();
                // e.preventDefault();
                const customer_vehicle_id = $('select[name="customer_vehicle_id"]').val();

                const customer_name = $('input[name="customer_name"]').val();
                const merk = $('input[name="merk"]').val();
                const tipe = $('input[name="tipe"]').val();
                const no_pol = $('input[name="no_pol"]').val();
                // const email = $('input[name="email"]').val();
                // const phone = $('input[name="phone"]').val();

                if (customer_vehicle_id == '' && customer_form_active) {

                    if (customer_name == '') {
                        $('#field-customer_name').append(
                            `<p class="mt-2 text-sm text-red-400">Nama Pelanggan Tidak Boleh Kosong!</p>`
                        );
                        e.preventDefault();
                    } else if (merk == '') {
                        $('#field-merk').append(
                            `<p class="mt-2 text-sm text-red-400">Merk Tidak Boleh Kosong!</p>`
                        );
                        e.preventDefault();
                    } else if (tipe == '') {
                        $('#field-tipe').append(
                            `<p class="mt-2 text-sm text-red-400">Tipe Tidak Boleh Kosong!</p>`
                        );
                        e.preventDefault();
                    } else if (no_pol == '') {
                        $('#field-no-pol').append(
                            `<p class="mt-2 text-sm text-red-400">Merk Tidak Boleh Kosong!</p>`
                        );
                        e.preventDefault();
                    }

                } else if (customer_vehicle_id == '' && customer_form_active == false) {
                    $('#field-customer_vehicle_id').append(
                        `<p class="mt-2 text-sm text-red-400">Pelanggan Tidak Boleh Kosong!</p>`);
                    e.preventDefault();
                }


                // $('input[name="subtotal"]').val($('input[name="subtotal"]').val().replace(/[^0-9]/g, ''));
                $('input[name="total"]').val($('input[name="total"]').val().replace(/[^0-9]/g, ''));
                $('input[name="total_sparepart"]').val($('input[name="total_sparepart"]').val().replace(
                    /[^0-9]/g, ''));
                $('input[name="total_jasa"]').val($('input[name="total_jasa"]').val().replace(/[^0-9]/g,
                    ''));
                $('input[name="total_diskon_item"]').val($('input[name="total_diskon_item"]').val().replace(
                    /[^0-9]/g, ''));


            });
        });
    </script>
@endpush
