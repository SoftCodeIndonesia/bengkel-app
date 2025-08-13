@extends('layouts.dashboard')

@section('title', 'Buat Paket Service')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom TomSelect Theme */
        .ts-wrapper {
            --ts-pr-600: #2563eb;
            --ts-pr-200: #93c5fd;
            --ts-option-radius: 0.375rem;
            padding: 5px !important;
        }

        .ts-wrapper .item {
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            color: #f3f4f6 !important;
        }

        .ts-wrapper.single .ts-control {
            @apply bg-gray-700 border border-gray-600 text-gray-300;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        .ts-dropdown,
        .ts-dropdown .active {
            background-color: rgb(57 65 81) !important;
            border-color: rgb(57 65 81) !important;
        }

        .ts-dropdown .option {
            @apply text-gray-300 hover:bg-gray-600;
        }

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

        .ts-control input {
            @apply bg-gray-700 text-gray-300 placeholder-gray-400;
        }

        .ts-control.focus {
            @apply ring-2 ring-blue-500 border-blue-500;
        }

        .ts-wrapper.error .ts-control {
            @apply border-red-500;
        }

        .ts-wrapper .item {
            @apply bg-gray-600 text-gray-300 rounded;
        }

        .ts-wrapper .clear-button {
            @apply text-gray-400 hover:text-gray-300;
        }

        .section-title {
            @apply text-lg font-medium text-gray-300 mb-3 pb-2 border-b border-gray-600;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Paket Service Baru</h2>
            <a href="{{ route('service-packages.index') }}"
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
            <form action="{{ route('service-packages.update', $servicePackage->id) }}" method="POST"
                id="servicePackageForm">
                @csrf
                @method('PUT')
                <!-- Package Info -->
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Paket <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $servicePackage->name) }}"
                            placeholder="Contoh: Paket Ganti Oli" required
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="2"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Deskripsi paket service">{{ old('description', $servicePackage->description) }}</textarea>
                    </div>


                </div>

                @php
                    $indexCounter = 0;
                @endphp
                <!-- Spareparts Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="section-title text-white">Sparepart (Barang)</h3>
                        <button type="button" id="add-sparepart"
                            class="text-blue-500 hover:text-blue-400 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Sparepart
                        </button>
                    </div>

                    <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm" id="sparepart-table">
                        <thead class="uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th class="p-2">Produk</th>
                                <th class="p-2">Kategori</th>
                                <th class="p-2">QTY</th>
                                <th class="p-2">Harga Satuan</th>
                                <th class="p-2">Subtotal</th>
                                <th class="p-2">Diskon (%)</th>
                                <th class="p-2">Total</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sparepart-items-container">

                        </tbody>
                    </table>
                </div>

                <!-- Services Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="section-title text-white">Jasa (Service)</h3>
                        <button type="button" id="add-service" class="text-blue-500 hover:text-blue-400 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Jasa
                        </button>
                    </div>

                    <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm" id="service-table">
                        <thead class="uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th class="p-2">Jasa</th>
                                <th class="p-2">Kategori</th>
                                <th class="p-2">FRT (Jam)</th>
                                <th class="p-2">Subtotal</th>
                                <th class="p-2">Diskon (%)</th>
                                <th class="p-2">Total</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="service-items-container">
                            <!-- Service rows will be added here -->
                        </tbody>
                    </table>
                </div>


                <!-- Summary Section -->
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
                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('service-packages.index') }}"
                        class="px-4 py-2 text-gray-300 bg-gray-600 hover:bg-gray-500 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-200">
                        Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi counter untuk items dan breakdowns
            let itemCounter = 1;
            let breakdownCounter = 1;
            let customer_form_active = false;

            var package = @json($servicePackage);
            console.log(package);

            var product_selected = [];
            var service_selected = [];



            for (let index = 0; index < package.items.length; index++) {
                const element = package.items[index];
                if (element.product.tipe == 'jasa') {

                    service_selected.push({
                        id: JSON.stringify(element.product),
                        text: element.product.name,
                        price: element.product.unit_price,
                    });
                    inititalItemRow('jasa', 'service-items-container', element);
                } else {

                    product_selected.push({
                        id: JSON.stringify(element.product),
                        text: element.product.name,
                        price: element.product.unit_price,
                    });
                    inititalItemRow('barang', 'sparepart-items-container', element);
                }
            }

            calculateTotal();

            // Add sparepart row
            document.getElementById('add-sparepart').addEventListener('click', function() {
                addItemRow('barang', 'sparepart-items-container');
            });

            // Add service row
            document.getElementById('add-service').addEventListener('click', function() {
                addItemRow('jasa', 'service-items-container');
            });

            // Function to add item row
            function addItemRow(type, containerId) {
                const tbody = document.getElementById(containerId);
                const rowId = `item-row-${itemCounter}`;

                const row = document.createElement('tr');
                row.id = rowId;
                row.classList.add('border-b', 'border-gray-600', 'item-row');

                if (type === 'barang') {
                    row.innerHTML = `
                        <td class="p-2" width="300px">
                            <select name="items[${itemCounter}][product_id]" data-tipe="${type}" required
                                class="product-select tom-autocomplete w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2">
                            </select>
                            <input type="hidden" name="items[${itemCounter}][type]" value="barang">
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">-</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="1" value="1" step="0.01"
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
                } else {
                    row.innerHTML = `
                        <td class="p-2" width="300px">
                            <select name="items[${itemCounter}][product_id]" data-tipe="${type}" required
                                class="product-select tom-autocomplete w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2">
                            </select>
                            <input type="hidden" name="items[${itemCounter}][type]" value="jasa">
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">-</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="0.1" step="0.01"
                                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
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
                }

                tbody.appendChild(row);

                // Initialize TomSelect for the new row
                const select = row.querySelector('.product-select');
                initializeProductSelect(select, type);

                // Add event listeners for calculations
                initItemRowEvents(row, type);

                itemCounter++;
            }


            // Function to add item row
            function inititalItemRow(type, containerId, item) {
                const tbody = document.getElementById(containerId);
                const rowId = `item-row-${itemCounter}`;

                const row = document.createElement('tr');
                row.id = rowId;
                row.classList.add('border-b', 'border-gray-600', 'item-row');
                console.log(item);
                if (type === 'barang') {
                    row.innerHTML = `
                        <td class="p-2" width="300px">
                            <select name="items[${itemCounter}][product_id]" data-tipe="${type}" required
                                class="product-select tom-autocomplete w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2">
                            </select>
                            <input type="hidden" name="items[${itemCounter}][id]" value="${item.id}">
                            <input type="hidden" name="items[${itemCounter}][type]" value="barang">
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">${item.product.tipe}</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="1" value="${item.quantity}"
                                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                        </td>
                        <td class="p-2 text-right">
                            <span class="unit-price text-gray-300">Rp ${formatRupiah(item.product.unit_price)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <span class="subtotal text-gray-300">Rp ${formatRupiah(item.subtotal ?? 0)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                                value="${item.discount}"
                                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                                placeholder="%">
                        </td>
                        <td class="p-2 text-right">
                            <span class="total-after-diskon text-gray-300">Rp ${formatRupiah(item.total ?? 0)}</span>
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
                } else {
                    row.innerHTML = `
                        <td class="p-2" width="300px">
                            <select name="items[${itemCounter}][product_id]" data-tipe="${type}" required
                                class="product-select tom-autocomplete w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2">
                            </select>
                            <input type="hidden" name="items[${itemCounter}][id]" value="${item.id}">
                            <input type="hidden" name="items[${itemCounter}][type]" value="jasa">
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">jasa</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="0.1" step="0.1" value="${item.quantity}"
                                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                        </td>
                        
                        <td class="p-2 text-right">
                            <span class="subtotal text-gray-300">Rp ${formatRupiah(item.subtotal ?? 0)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                                value="${item.discount}"
                                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                                placeholder="%">
                        </td>
                        <td class="p-2 text-right">
                            <span class="total-after-diskon text-gray-300">Rp ${formatRupiah(item.total ?? 0)}</span>
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
                }

                tbody.appendChild(row);

                // Initialize TomSelect for the new row
                const select = row.querySelector('.product-select');
                // console.log(service_selected);
                initializeProductSelectExit(select, type, item);

                // Add event listeners for calculations
                initItemRowEvents(row, type);

                itemCounter++;
            }


            // Initialize product select
            function initializeProductSelect(element, type) {
                new TomSelect(element, {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    create: false,
                    load: function(query, callback) {
                        var url = base_url + '/api/products/search?q=' + encodeURIComponent(query) +
                            '&tipe=' + encodeURIComponent(type);
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {
                                callback(json);
                            }).catch(() => {
                                callback();
                            });
                    },
                    render: {
                        option: function(item, escape) {
                            return `
                                <div class="flex items-center p-2 bg-gray-700 text-gray-400" data-json="${item}">
                                    <div class="ml-2">
                                        <div class="text-gray-300">${escape(item.text)}</div>
                                        <div class="text-xs text-gray-400">${type == 'jasa' ? '-' :escape(item.price)}</div>
                                    </div>
                                </div>`;
                        },
                        item: function(item, escape) {
                            return `<div class="bg-gray-600 text-gray-300 px-2 py-1 rounded">${escape(item.text)}</div>`;
                        }
                    }
                });
            }

            function initializeProductSelectExit(element, type, item) {



                new TomSelect(element, {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    items: JSON.stringify(item.product),
                    options: type == 'jasa' ? service_selected : product_selected,
                    create: false,
                    load: function(query, callback) {
                        var url = base_url + '/api/products/search?q=' + encodeURIComponent(query) +
                            '&tipe=' + encodeURIComponent(type);
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {
                                callback(json);
                            }).catch(() => {
                                callback();
                            });
                    },
                    render: {
                        option: function(item, escape) {
                            return `
                                <div class="flex items-center p-2 bg-gray-700 text-gray-400" data-json="${item}">
                                    <div class="ml-2">
                                        <div class="text-gray-300">${escape(item.text)}</div>
                                        <div class="text-xs text-gray-400">${type == 'jasa' ? '-' :escape(item.price)}</div>
                                    </div>
                                </div>`;
                        },
                        item: function(item, escape) {
                            return `<div class="bg-gray-600 text-gray-300 px-2 py-1 rounded">${escape(item.text)}</div>`;
                        }
                    }
                });
            }

            // Initialize item row events
            function initItemRowEvents(row, type) {
                const select = row.querySelector('.product-select');
                const qtyInput = row.querySelector('.quantity');
                const diskonValue = row.querySelector('.diskon-value');
                const kategori = row.querySelector('.kategori');
                const priceText = row.querySelector('.unit-price');
                const subtotalText = row.querySelector('.subtotal');
                const totalAfterDiskonText = row.querySelector('.total-after-diskon');

                const calculateItemTotal = () => {
                    const data = select.tomselect ? select.tomselect.items[0] : null;
                    console.log(select.tomselect);
                    if (!data) return;



                    const jsonData = JSON.parse(data);

                    if (type == 'jasa' & qtyInput.value == '') {
                        qtyInput.value = jsonData.stok;
                    }

                    const price = parseFloat(jsonData.unit_price) || 0;
                    const qty = parseFloat(qtyInput.value) || (type === 'jasa' ? jsonData.stok : 1);
                    const diskon = parseFloat(diskonValue.value) || 0;

                    var subtotal = 0;
                    if (type == 'jasa') {
                        subtotal = 100000 * qty;
                    } else {
                        subtotal = price * qty;
                    }


                    const totalAfterDiskon = subtotal * (1 - (diskon / 100));

                    kategori.textContent = type === 'jasa' ? 'Jasa' : 'Sparepart';
                    if (type != 'jasa') {
                        priceText.textContent = 'Rp ' + formatNumber(price);
                    }
                    subtotalText.textContent = 'Rp ' + formatNumber(subtotal);
                    totalAfterDiskonText.textContent = 'Rp ' + formatNumber(totalAfterDiskon);

                    calculateTotal();
                };

                select.addEventListener('change', calculateItemTotal);
                qtyInput.addEventListener('input', calculateItemTotal);
                diskonValue.addEventListener('input', calculateItemTotal);

                row.querySelector('.remove-item').addEventListener('click', () => {
                    const itemId = row.querySelector('input[name*="[id]"]')?.value;
                    if (itemId) {
                        // Existing item - show confirmation
                        Swal.fire({
                            title: 'Hapus Item?',
                            text: "Anda yakin ingin menghapus item ini?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Mark for deletion
                                row.querySelector('input[name*="[id]"]').value =
                                    'delete_' + itemId;
                                row.classList.add('to-be-deleted');
                                row.style.display = 'none';
                                calculateTotal();
                            }
                        });
                    } else {
                        // New item - just remove
                        row.remove();
                        calculateTotal();
                    }

                });
            }


            // Calculate total
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

                    if (itemType != 'jasa' && itemType != 'Jasa') {
                        totalSparepart += subtotalValue;
                    } else {
                        totalJasa += subtotalValue;
                    }

                    totalDiskonItem += (subtotalValue - totalAfterDiskon);
                    subtotal += totalAfterDiskon;
                });

                document.getElementById('total-sparepart').value = 'Rp ' + formatNumber(totalSparepart);
                document.getElementById('total-jasa').value = 'Rp ' + formatNumber(totalJasa);
                document.getElementById('total-diskon-item').value = 'Rp ' + formatNumber(totalDiskonItem);
                document.getElementById('total').value = 'Rp ' + formatNumber(subtotal);

                // Update hidden inputs
                document.querySelector('input[name="total"]').value = formatNumber(subtotal);
                document.querySelector('input[name="total_sparepart"]').value = formatNumber(totalSparepart);
                document.querySelector('input[name="total_jasa"]').value = formatNumber(totalJasa);
                document.querySelector('input[name="total_diskon_item"]').value = formatNumber(totalDiskonItem);
            }

            // Format number
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            // Form validation
            document.getElementById('servicePackageForm').addEventListener('submit', function(e) {



                $('input[name="total_sparepart"]').val(originalNumber($('input[name="total_sparepart"]')
                    .val()));
                $('input[name="total_jasa"]').val(originalNumber($('input[name="total_jasa"]').val()));
                $('input[name="total"]').val(originalNumber($('input[name="total"]').val()));
                $('input[name="total_diskon_item"]').val(originalNumber($('input[name="total_diskon_item"]')
                    .val()));
            });


        });
    </script>
@endpush
