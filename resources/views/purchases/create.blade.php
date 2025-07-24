@extends('layouts.dashboard')

@section('title', 'Tambah Pembelian')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
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
            /* padding: 0 !important;
                                                                                                                        margin: 0 !important; */
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
            padding: 3px !important;
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
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Pembelian</h2>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-300">Supplier <span
                            class="text-red-500">*</span></label>
                    <select id="supplier_id" name="supplier_id" required
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="invoice_number" class="block mb-2 text-sm font-medium text-gray-300">No Invoice </label>
                    <input type="text" id="invoice_number" value="{{ old('invoice_number') }}" name="invoice_number"
                        required
                        class="bg-gray-700 border border-gray-600 dark:placeholder-gray-400 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="INV-001">
                </div>

                <div>
                    <label for="purchase_date" class="block mb-2 text-sm font-medium text-gray-300">Tanggal
                        Pembelian <span class="text-red-500">*</span></label>
                    <input type="date" id="purchase_date" value="{{ old('purchase_date') }}" name="purchase_date"
                        required
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Upload
                        file Pendukung</label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="source_document" name="source_document" id="source_document" type="file">
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="source_document">Upload file bukti
                        pembelian atau dokumen lainya</div>
                </div>

                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-300">Status <span
                            class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="draft">DRAFT</option>
                        <option value="unpaid">Belum Lunas</option>
                        <option value="paid">Lunas</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium text-white">Daftar Barang</h3>
                    <button type="button" id="add-item"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Barang
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Nama Barang</th>
                                <th class="px-4 py-3">Qty</th>
                                <th class="px-4 py-3">Harga Beli</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <!-- Items will be added here -->
                        </tbody>
                        <tfoot class="bg-gray-700">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold">Total Pembelian</td>
                                <td id="grand-total" class="px-4 py-3 font-semibold">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mb-4">
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-300">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pembelian
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tom Select for product search
            let products = [];

            const supplierSelect = new TomSelect('#supplier_id', {
                valueField: 'id',
                labelField: 'text',
                searchField: ['text'],
                create: false,
                load: function(query, callback) {
                    const url = '/api/suppliers/search?q=' + encodeURIComponent(query);
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            callback(json.data);
                        })
                        .catch(() => {
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
                    // Set warna untuk dark mode
                    if (this.input.classList.contains('border-red-500')) {
                        this.wrapper.classList.add('error');
                    }

                    // Load data awal jika ada nilai old
                    @if (old('supplier_id'))
                        fetch('/api/suppliers/{{ old('supplier_id') }}')
                            .then(response => response.json())
                            .then(json => {
                                if (json.data) {
                                    this.addOption(json.data);
                                    this.setValue(json.data.id);
                                }
                            });
                    @endif
                }
            });

            // Fetch products from API
            fetch("{{ route('api.product.search') }}")
                .then(response => response.json())
                .then(data => {
                    products = data;
                });

            // Add item row
            let itemCount = 0;
            const addItemRow = () => {
                const container = document.getElementById('items-container');
                const rowId = `item-${itemCount}`;

                const row = document.createElement('tr');
                row.id = rowId;
                row.className = 'border-b border-gray-700';
                row.innerHTML = `
                    <td class="px-4 py-3">
                        <select name="items[${itemCount}][product_id]" required
                            class="product-select bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Barang</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" required
                            class="quantity bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-4 py-3">
                        <input type="text" name="items[${itemCount}][unit_price]"  required
                            class="unit-price bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-4 py-3 total-price">Rp 0</td>
                    <td class="px-4 py-3">
                        <button type="button" onclick="removeItem('${rowId}')" class="text-red-500 hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                `;

                container.appendChild(row);

                // Initialize Tom Select for this row
                new TomSelect(`#${rowId} .product-select`, {
                    valueField: 'id',
                    labelField: 'text',

                    options: products,
                    load: function(query, callback) {
                        var url = base_url + '/api/products/search?q=' + encodeURIComponent(
                            query) + '&tipe=barang'
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {
                                console.log(json);
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

                });



                // Add event listeners for quantity and price changes
                const quantityInput = row.querySelector('.quantity');
                const unitPriceInput = row.querySelector('.unit-price');

                $('.product-select').change(function(e) {
                    e.preventDefault();
                    const item = JSON.parse($(this).val());
                    console.log(item.buying_price);
                    unitPriceInput.value = formatRupiah(item.buying_price);
                    calculateRowTotal(row);
                });

                quantityInput.addEventListener('input', () => {
                    calculateRowTotal(row);
                    updateGrandTotal();
                });

                unitPriceInput.addEventListener('input', () => {
                    calculateRowTotal(row);
                    updateGrandTotal();
                });

                itemCount++;
            };

            $('.quantity').change(function(e) {
                e.preventDefault();


                const row = $(this).closest('tr');

                calculateRowTotal(row);
                updateGrandTotal();
            });

            // Calculate row total
            const calculateRowTotal = (row) => {
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const unitPrice = parseFloat(originalNumber(row.querySelector('.unit-price').value)) || 0;
                const total = quantity * unitPrice;
                row.querySelector('.total-price').textContent = formatRupiah(total);
                updateGrandTotal();
            };

            // Update grand total
            const updateGrandTotal = () => {
                let grandTotal = 0;
                document.querySelectorAll('#items-container tr').forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const unitPrice = parseFloat(originalNumber(row.querySelector('.unit-price')
                        .value)) || 0;
                    grandTotal += quantity * unitPrice;
                });
                document.getElementById('grand-total').textContent = formatRupiah(grandTotal);
            };

            // Remove item row
            window.removeItem = (rowId) => {
                const row = document.getElementById(rowId);
                if (row) {
                    row.remove();
                    updateGrandTotal();
                }
            };

            // Add first item row
            addItemRow();

            // Add item button click handler
            document.getElementById('add-item').addEventListener('click', addItemRow);

            // Set today's date as default
            document.getElementById('purchase_date').valueAsDate = new Date();

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // e.preventDefault();
                document.querySelectorAll('#items-container tr').forEach(row => {
                    const priceInput = row.querySelector('.unit-price');
                    console.log(priceInput);
                    priceInput.value = originalNumber(priceInput.value);
                });


            });

        });
    </script>
@endpush
