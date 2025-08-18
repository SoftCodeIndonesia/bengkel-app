@extends('layouts.dashboard')

@section('title', 'Buat Penjualan Baru')
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
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Penjualan Baru</h2>
            <a href="{{ route('sales.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
                @csrf

                <div class="w-full mb-6">
                    <label for="customer_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Pelanggan
                    </label>
                    <select name="customer_id" id="customer_id"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Pilih Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-customer"
                        class="mt-2 text-blue-500 hover:text-blue-400 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Pelanggan Baru
                    </button>
                </div>

                <div class="my-4 hidden" id="customer-detail-container">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h4 class="section-title text-white mb-2">Detail Pelanggan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Details -->
                            <div>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-300">Nama:</p>
                                        <p class="text-white font-medium" id="customer-name">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-300">Telepon:</p>
                                        <p class="text-white font-medium" id="customer-phone">-</p>
                                    </div>

                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div>

                                <div class="space-y-3">
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
                        </div>
                    </div>
                </div>

                <div class="hidden mt-6" id="add-customer-section">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="customer_name" class="block text-sm font-medium text-gray-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" id="customer_name"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Nama lengkap pelanggan">
                            </div>

                            <div class="mb-4">
                                <label for="customer_email" class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" name="customer_email" id="customer_email"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="email@contoh.com">
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label for="customer_phone" class="block text-sm font-medium text-gray-300">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_phone" id="customer_phone"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="081234567890">
                            </div>

                            <div class="mb-4">
                                <label for="customer_address" class="block text-sm font-medium text-gray-300">
                                    Alamat
                                </label>
                                <textarea name="customer_address" id="customer_address" rows="3"
                                    class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Alamat lengkap pelanggan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="sales_date" class="block text-sm font-medium text-gray-300 mb-2">
                        Tanggal Penjualan <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="sales_date" id="sales_date" required
                        class="mt-1 block w-full placeholder-gray-400 text-white bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-300">Item Penjualan</h3>
                        <button type="button" id="add-item"
                            class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Barang
                        </button>
                    </div>
                    <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm" id="items-table">
                        <thead class="bg-gray-600">
                            <tr>
                                <th class="p-3 text-left">Produk</th>
                                <th class="p-3 text-right">Kategori</th>
                                <th class="p-3 text-right">Qty</th>
                                <th class="p-3 text-right">Harga Satuan</th>
                                <th class="p-3 text-right">Discount (%)</th>
                                <th class="p-3 text-right">Total</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <!-- Baris item akan ditambahkan di sini -->
                        </tbody>
                    </table>

                </div>



                <div class="bg-gray-700 p-4 rounded-lg mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Subtotal:</span>
                        <span id="subtotal-display" class="text-white">Rp 0</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Diskon:</span>
                        <span id="diskon-display" class="text-white">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-medium">
                        <span class="text-gray-300">Total:</span>
                        <span id="total-display" class="text-blue-400">Rp 0</span>
                    </div>
                </div>

                <input type="hidden" name="subtotal" id="subtotal" value="0">
                <input type="hidden" name="total" id="total" value="0">
                <input type="hidden" name="total_discount" id="total_discount" value="0">

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('sales.index') }}"
                        class="px-4 py-2 text-gray-300 bg-gray-600 hover:bg-gray-500 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-200">
                        Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="product-selection-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
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
                                    class="h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3" width="80%">Part</th>
                            <th class="px-4 py-3" width="10%">Harga</th>
                            <th class="px-4 py-3" width="10%">Stok</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">

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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            $('#product-selection-modal').addClass('hidden');
            // Inisialisasi counter untuk items
            let itemCounter = 0;
            let customerFormActive = false;
            let selectedProducts = [];

            // Inisialisasi Tom Select untuk customer
            const customerSelect = new TomSelect('#customer_id', {
                valueField: 'id',
                labelField: 'text',
                searchField: ['text', 'phone'],
                create: false,
                load: function(query, callback) {
                    const url = '{{ route('customers.search') }}?q=' + encodeURIComponent(query);
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            callback(json);
                        })
                        .catch(() => {
                            callback();
                        });
                },
                render: {
                    option: function(data, escape) {
                        return ` <div class="flex items-center p-2 bg-gray-700 text-gray-400" data-json="${data}">
                                    <div class="ml-2">
                                        <div class="text-gray-300">${escape(data.text)}</div>
                                    </div>
                                </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="bg-gray-600 text-gray-300 px-2 py-1 rounded">${escape(data.text)}</div>`;
                    }
                }
            });

            $('#customer_id').change(function(e) {
                e.preventDefault();
                const id = $(this).val();
                const detailContainer = document.getElementById('customer-detail-container');
                fetch(`${base_url}/api/customer/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update customer details
                            document.getElementById('customer-name').textContent = data.customer
                                .name || '-';
                            document.getElementById('customer-phone').textContent = data.customer
                                .phone || '-';
                            document.getElementById('customer-email').textContent = data.customer
                                .email || '-';
                            document.getElementById('customer-address').textContent = data.customer
                                .address || '-';

                            detailContainer.classList.remove('hidden');
                        } else {
                            detailContainer.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching customer details:', error);
                        detailContainer.classList.add('hidden');
                    });

            });
            // Toggle form tambah customer
            document.getElementById('add-customer').addEventListener('click', function() {
                customerFormActive = !customerFormActive;
                document.getElementById('add-customer-section').classList.toggle('hidden');
                customerSelect.clear();
            });

            // Set tanggal default ke hari ini
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
            document.getElementById('sales_date').value = localISOTime;

            // Fungsi untuk menambahkan item baru
            document.getElementById('add-item').addEventListener('click', function() {
                table.draw();
                document.getElementById('product-selection-modal').classList.remove('hidden');
            });

            function addItemRow(productId, productName, kategori, unit_price, dicount_percentage) {
                const tbody = document.getElementById('items-container');
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-600 item-row';
                row.innerHTML = `
                    <td class="p-3">
                        <input type="hidden" name="items[${itemCounter}][product_id]" value="${productId}">
                        <span>${productName}</span>
                    </td>
                    <td class="p-3 text-center Kategori">
                        ${kategori}
                    </td>
                    <td class="p-3 text-end">
                        <input type="number" name="items[${itemCounter}][quantity]" required min="1" value="1" step="1"
                            class="quantity w-20 bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <input type="hidden" name="items[${itemCounter}][unit_price]" value="${unit_price}" required
                            class="unit_price_input w-20 bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </td>
                    <td class="p-3 text-right unit-price">
                        Rp ${formatRupiah(unit_price)}
                    </td>
                    <td class="p-3 text-end">
                        <input type="text" name="items[${itemCounter}][dicount_percentage]" required value="${dicount_percentage}"
                            class="dicount_percentage w-20 bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </td>
                    <td class="p-3 text-right total-price">
                        Rp ${formatRupiah(1*unit_price)}
                    </td>
                    <td class="p-3 text-center">
                        <button type="button" class="remove-item text-red-500 hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
                itemCounter++;


                // Event listener untuk quantity
                row.querySelector('.quantity').addEventListener('input', function() {
                    calculateItemTotal(row);
                });

                row.querySelector('.dicount_percentage').addEventListener('input', function() {

                    calculateItemTotal(row);
                });


                // Event listener untuk hapus item
                row.querySelector('.remove-item').addEventListener('click', function() {
                    row.remove();
                    calculateTotal();
                });
            }

            // Fungsi untuk menghitung total per item
            function calculateItemTotal(row) {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity');
                const totalPriceCell = row.querySelector('.total-price');
                const unitPrice = row.querySelector('.unit-price');
                const unitPriceInput = row.querySelector('.unit_price_input');
                const discountPercentage = row.querySelector('.dicount_percentage');

                const price = parseFloat(unitPriceInput.value) || 0;
                const quantity = parseFloat(quantityInput.value) || 1;
                const total = price * quantity;

                var total_after_diskon = total;

                console.log(price);
                console.log(quantity);

                if (discountPercentage.value > 0) {
                    total_after_diskon = total * (1 - (discountPercentage.value / 100));
                    console.log(total_after_diskon);
                }

                totalPriceCell.textContent = 'Rp ' + formatNumber(total_after_diskon);
                unitPrice.textContent = 'Rp ' + formatNumber(price);
                calculateTotal();

            }

            // Fungsi untuk menghitung total keseluruhan
            function calculateTotal() {
                let subtotal = 0;
                let totalDiscount = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity').value);
                    const totalText = row.querySelector('.total-price').textContent;
                    const discount = parseFloat(row.querySelector('.dicount_percentage').value);
                    const unit_price = parseFloat(originalNumber(row.querySelector('.unit-price')
                        .textContent));



                    const total = unit_price * quantity;

                    if (discount > 0) {
                        totalDiscount += total * (discount / 100);
                    }

                    subtotal += total;
                });



                document.getElementById('subtotal-display').textContent = 'Rp ' + formatNumber(subtotal);
                document.getElementById('diskon-display').textContent = 'Rp ' + formatNumber(totalDiscount);
                document.getElementById('total-display').textContent = 'Rp ' + formatNumber(subtotal -
                    totalDiscount);

                // Update hidden inputs
                document.getElementById('subtotal').value = subtotal;
                document.getElementById('total').value = subtotal - totalDiscount;
                document.getElementById('total_discount').value = totalDiscount;
            }



            // Format number dengan separator ribuan
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            // Validasi form sebelum submit
            document.getElementById('salesForm').addEventListener('submit', function(e) {
                // Validasi customer
                const customerId = document.getElementById('customer_id').value;
                if (!customerId && !customerFormActive) {
                    alert('Silakan pilih pelanggan atau tambahkan pelanggan baru');
                    e.preventDefault();
                    return;
                }

                // Validasi customer form jika aktif
                if (customerFormActive) {
                    const customerName = document.getElementById('customer_name').value;
                    const customerPhone = document.getElementById('customer_phone').value;

                    if (!customerName) {
                        alert('Nama pelanggan harus diisi');
                        e.preventDefault();
                        return;
                    }

                    if (!customerPhone) {
                        alert('Nomor telepon pelanggan harus diisi');
                        e.preventDefault();
                        return;
                    }
                }

                // Validasi minimal 1 item
                const itemRows = document.querySelectorAll('.item-row');
                if (itemRows.length === 0) {
                    alert('Minimal harus ada 1 item penjualan');
                    e.preventDefault();
                    return;
                }

                // Validasi setiap item
                let allItemsValid = true;
                itemRows.forEach(row => {
                    const select = row.querySelector('.product-select');
                    const quantity = row.querySelector('.quantity').value;

                    if (!select.value) {
                        allItemsValid = false;
                        select.classList.add('border-red-500');
                    }

                    if (!quantity || parseFloat(quantity) <= 0) {
                        allItemsValid = false;
                        row.querySelector('.quantity').classList.add('border-red-500');
                    }
                });

                if (!allItemsValid) {
                    alert('Silakan lengkapi semua item penjualan');
                    e.preventDefault();
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

            document.getElementById('confirm-selection').addEventListener('click', function() {
                const checkboxes = document.querySelectorAll(
                    '#product-list input[type="checkbox"]:checked');
                checkboxes.forEach(checkbox => {
                    const productId = checkbox.value;
                    const productRow = checkbox.closest('tr');
                    const kategori = productRow.querySelector('.tipe').value;

                    const productName = productRow.querySelector('td:nth-child(2)').textContent;
                    const productPrice = productRow.querySelector('td:nth-child(3)').textContent;
                    // console.log(originalNumber(productPrice));
                    if (!selectedProducts.includes(productId)) {
                        selectedProducts.push(productId);
                        addItemRow(productId, productName, kategori, originalNumber(
                            productPrice), 0);
                    }
                });

                document.getElementById('product-selection-modal').classList.add('hidden');
                resetSelection();
            });

            // Close product selection modal
            document.getElementById('cancel-selection').addEventListener('click', function() {
                document.getElementById('product-selection-modal').classList.add('hidden');
                resetSelection();
            });

            // Reset product selection
            function resetSelection() {
                document.getElementById('select-all').checked = false;

                const checkboxes = document.querySelectorAll('#product-list input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });
    </script>
@endpush
