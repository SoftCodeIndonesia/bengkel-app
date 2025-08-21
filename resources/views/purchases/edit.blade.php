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

        .ts-wrapper .item {
            background: none !important;
            padding: 1px !important;
            margin: 0 !important;
            border: none !important;
            color: #f3f4f6 !important;
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
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Edit Pembelian</h2>
            <a href="{{ route('purchases.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" enctype="multipart/form-data"
            class="p-4" id="form-edit">
            @csrf
            @method('PUT') <!-- Tambahkan method spoofing untuk PUT -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-300">Supplier <span
                            class="text-red-500">*</span></label>
                    <select id="supplier_id" name="supplier_id" required
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Pilih Supplier</option>


                    </select>
                </div>

                <!-- Invoice Number -->
                <div>
                    <label for="invoice_number" class="block mb-2 text-sm font-medium text-gray-300">No Invoice</label>
                    <input type="text" id="invoice_number" name="invoice_number"
                        value="{{ old('invoice_number', $purchase->invoice_number) }}"
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block mb-2 text-sm font-medium text-gray-300">Tanggal Pembelian <span
                            class="text-red-500">*</span></label>
                    <input type="date" id="purchase_date" name="purchase_date"
                        value="{{ old('purchase_date', $purchase->purchase_date) }}" required
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300" for="source_document">File Pendukung</label>
                    <input
                        class="block w-full text-sm text-gray-400 border border-gray-600 rounded-lg cursor-pointer bg-gray-700 focus:outline-none"
                        aria-describedby="source_document" name="source_document" id="source_document" type="file">
                    @if ($purchase->source_document)
                        <div class="mt-2 text-sm text-blue-400">
                            <a href="{{ asset('storage/' . $purchase->source_document) }}" target="_blank">Lihat Dokumen
                                Saat Ini</a>
                        </div>
                    @endif
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-300">Status <span
                            class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="draft" {{ $purchase->status == 'draft' ? 'selected' : '' }}>DRAFT</option>
                        <option value="unpaid" {{ $purchase->status == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="paid" {{ $purchase->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
            </div>

            <!-- Daftar Barang -->
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
                                <th class="px-4 py-3">Margin (%)</th>
                                <th class="px-4 py-3">Harga Jual</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <!-- Item yang sudah ada akan dimuat di sini -->


                        </tbody>
                        <tfoot class="bg-gray-700">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold">Total Pembelian</td>
                                <td id="grand-total" class="px-4 py-3 font-semibold">
                                    {{ number_format($purchase->total, 0, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-4">
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-300">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('notes', $purchase->notes) }}</textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Pembelian
                </button>
            </div>
        </form>
    </div>

    <!-- Product Selection Modal -->
    <div id="product-selection-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl h-full max-h-full flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">Pilih Produk</h3>
            </div>

            <div class="relative overflow-x-auto flex-1 p-6">
                <table class="w-full text-sm text-left text-gray-400" id="product-table-list" style="width: 100%;">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400
                    sticky top-0">
                        <tr>
                            <th class="px-4 py-3" width="1%">
                                <input type="checkbox" id="select-all"
                                    class=" h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3" width="80%">Part</th>
                            <th class="px-4 py-3" width="10%">Harga</th>
                            <th class="px-4 py-3" width="10%">Stok</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                        <!-- Products will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700 flex justify-end">
                <button type="button" id="cancel-selection"
                    class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg">Batal</button>
                <button type="button" id="confirm-selection"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">Tambahkan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tom Select for product search
            let products = [];
            let selectedProducts = [];
            let itemCount = 0;

            const purchase = @json($purchase);

            purchase.items.forEach(element => {
                const marginNominal = element.movement_item.selling_price - element.movement_item
                    .buying_price;
                const markupPersen = (marginNominal / element.movement_item.buying_price) * 100;

                addItemRow(element.id, element.product_id, element.product.name, element.unit_price,
                    markupPersen);
            });

            const supplierSelect = new TomSelect('#supplier_id', {
                valueField: 'id',
                labelField: 'text',
                searchField: ['text'],
                create: false,
                items: [purchase.supplier.id ?? ''],
                options: [{
                    id: purchase.supplier.id,
                    text: `${purchase.supplier.name}`
                }],
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


            var table = $('#product-table-list').DataTable({
                processing: true,
                serverSide: true,
                columnDefs: [{
                    width: '30px',
                    targets: 1,
                }],
                ajax: {
                    url: "{{ route('api.product.list') }}",
                    data: function(d) {
                        d.tipe = 'barang';
                    }
                },
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'px-4 py-3 ',
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'px-4 py-3',
                    },
                    {
                        data: 'formatted_price',
                        name: 'unit_price',
                        className: 'px-4 py-3',
                    },
                    {
                        data: 'stok',
                        name: 'stok',
                        className: 'px-4 py-3'
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

            // Update selling price based on margin
            function updateSellingPrice(row) {
                const unitPrice = parseFloat(originalNumber(row.querySelector('.unit-price').value)) || 0;
                const margin = parseFloat(row.querySelector('.margin').value) || 0;
                const sellingPrice = unitPrice * (1 + (margin / 100));
                row.querySelector('.selling_price').value = formatRupiah(sellingPrice);
            }

            // // Fetch products from API
            // fetch("{{ route('api.product.search') }}")
            //     .then(response => response.json())
            //     .then(data => {
            //         products = data;
            //     });

            // Add item row


            function addItemRow(item_id, productId, productName, unitPrice, margin) {
                const container = document.getElementById('items-container');
                const rowId = `item-${itemCount}`;
                const selling_price = unitPrice * (1 + (margin / 100));
                const row = document.createElement('tr');
                row.id = rowId;
                row.className = 'border-b border-gray-700';
                row.innerHTML = `
                    <td class="px-4 py-3">
                        <input type="hidden" name="items[${itemCount}][id]" value="${item_id ?? null}">
                        <input type="hidden" name="items[${itemCount}][product_id]" value="${productId}">
                        <span>${productName}</span>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" required class="quantity bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-4 py-3">
                        <input type="text" name="items[${itemCount}][unit_price]" value="${formatRupiah(unitPrice)}" required class="unit-price bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-4 py-3 total-price">${formatRupiah(unitPrice)}</td>
                    
                    <td class="px-4 py-3">
                        <input type="number" value="${margin ?? 0}" name="items[${itemCount}][margin]" required class="margin bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-4 py-3">
                        <input type="text" name="items[${itemCount}][selling_price]" value="${formatRupiah(selling_price) ?? 0}" readonly class="selling_price bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-4 py-3">
                        <button type="button" class="remove-item text-red-500 hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                `;

                container.appendChild(row);

                // Add event listeners for quantity and price changes
                const quantityInput = row.querySelector('.quantity');
                const unitPriceInput = row.querySelector('.unit-price');
                const marginInput = row.querySelector('.margin');

                quantityInput.addEventListener('input', () => {
                    calculateRowTotal(row);
                    updateGrandTotal();
                });

                unitPriceInput.addEventListener('input', () => {
                    calculateRowTotal(row);
                    updateSellingPrice(row);
                    updateGrandTotal();
                });

                marginInput.addEventListener('input', () => {
                    updateSellingPrice(row);
                });

                itemCount++;
            }

            $('.quantity').change(function(e) {
                e.preventDefault();


                const row = this.closest('tr');

                calculateRowTotal(row);
                updateGrandTotal();
            });



            // Calculate row total
            const calculateRowTotal = (row) => {
                console.log(row);
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const unitPrice = parseFloat(originalNumber(row.querySelector('.unit-price').value)) || 0;
                const total = quantity * unitPrice;
                row.querySelector('.total-price').textContent = formatRupiah(total);
            };
            document.querySelectorAll('.item-row').forEach(row => {
                const quantityInput = row.querySelector('.quantity');
                quantityInput.addEventListener('input', () => {
                    calculateRowTotal(row);
                    updateGrandTotal();
                });
            })

            // Update grand total
            const updateGrandTotal = () => {
                let grandTotal = 0;
                document.querySelectorAll('#items-container tr').forEach(row => {
                    if (row.offsetParent === null) return;
                    console.log(row.querySelector('.quantity').value);
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const unitPrice = parseFloat(originalNumber(row.querySelector('.unit-price')
                        .value)) || 0;
                    grandTotal += quantity * unitPrice;
                });
                document.getElementById('grand-total').textContent = formatRupiah(grandTotal);
            };


            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const itemIdInput = row.querySelector('input[name*="[id]"]');

                    console.log('click', itemIdInput.value);
                    if (itemIdInput && itemIdInput.value) {
                        // Jika item sudah ada di database
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
                                // Tandai item untuk dihapus dengan prefix 'delete_'
                                itemIdInput.value = 'delete_' + itemIdInput.value;
                                row.style.display = 'none'; // Sembunyikan baris
                                updateGrandTotal();
                            }
                        });
                    } else {
                        // Jika item baru, langsung hapus
                        row.remove();
                        updateGrandTotal();
                    }
                });
            });


            // Show product selection modal
            document.getElementById('add-item').addEventListener('click', function() {
                // fetchProducts();
                table.draw()
                document.getElementById('product-selection-modal').classList.remove('hidden');
            });

            // Close product selection modal
            document.getElementById('cancel-selection').addEventListener('click', function() {
                document.getElementById('product-selection-modal').classList.add('hidden');
                resetSelection();
            });

            // Select all products
            document.getElementById('select-all').addEventListener('change', function(e) {
                const checkboxes = document.querySelectorAll('#product-list input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            });

            // Confirm product selection
            document.getElementById('confirm-selection').addEventListener('click', function() {
                const checkboxes = document.querySelectorAll(
                    '#product-list input[type="checkbox"]:checked');
                checkboxes.forEach(checkbox => {
                    const productId = checkbox.value;
                    const productRow = checkbox.closest('tr');
                    const productName = productRow.querySelector('td:nth-child(2)').textContent;
                    const productPrice = productRow.querySelector('td:nth-child(3)').textContent;
                    // console.log(originalNumber(productPrice));
                    if (!selectedProducts.includes(productId)) {
                        selectedProducts.push(productId);
                        addItemRow(null, productId, productName, originalNumber(productPrice), 0,
                            originalNumber(productPrice));
                    }
                });

                document.getElementById('product-selection-modal').classList.add('hidden');
                resetSelection();
            });


            // Reset product selection
            function resetSelection() {
                document.getElementById('select-all').checked = false;
                document.getElementById('product-search').value = '';
                const checkboxes = document.querySelectorAll('#product-list input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }


            const form = document.getElementById('form-edit');
            form.addEventListener('submit', function(e) {
                // e.preventDefault();
                document.querySelectorAll('#items-container tr').forEach(row => {
                    const priceInput = row.querySelector('.unit-price');
                    const selling_price = row.querySelector('.selling_price');
                    priceInput.value = originalNumber(priceInput.value);
                    selling_price.value = originalNumber(selling_price.value);
                });


            });
        });
    </script>
@endpush
