@extends('layouts.dashboard')

@section('title', 'Buat Invoice Baru')
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
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Invoice Baru</h2>
        </div>

        <div class="p-4">
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    @foreach ($errors->all() as $error)
                        <span class="font-medium">{{ $error }}</span>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-gray-300 mb-2">Tipe Invoice</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="tipe" value="sales"
                                    {{ old('tipe', $type) === 'sales' ? 'checked' : '' }}
                                    class="form-radio text-blue-500 bg-gray-700 border-gray-600">
                                <span class="ml-2 text-gray-300">Penjualan</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="tipe" value="services"
                                    {{ old('tipe', $type) === 'services' ? 'checked' : '' }}
                                    class="form-radio text-blue-500 bg-gray-700 border-gray-600">
                                <span class="ml-2 text-gray-300">Service</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-1">
                            <input type="hidden" name="reference_id">
                            <label for="referensi" class="block text-gray-300 mb-2">Pilih Referensi</label>
                            <input type="hidden" name="customer_id" value="{{ $customer->id ?? '' }}">
                            <select name="referensi" id="referensi" aria-placeholder="Pilih Referensi JO/SO"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            </select>
                        </div>

                    </div>
                </div>
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-white mb-4">Detail Transaksi</h3>

                    <div class="reference-detail">
                        <div class="bg-gray-700 rounded-md p-4 text-gray-300">
                            Pilih referensi untuk melihat detail transaksi
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Subtotal</label>
                        <input type="text" name="subtotal" id="subtotal"
                            value="{{ $reference ? $reference->total : 0 }}"
                            class="numeric-input w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>

                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Diskon (Rp)</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" name="diskon_value" id="diskon_value" value="0" min="0"
                                readonly
                                class="numeric-input w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="diskon_unit" id="diskon_unit" value="nominal"
                                class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                    </div>

                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Total</label>
                        <input type="text" name="total" id="total" value="{{ $reference ? $reference->total : 0 }}"
                            class="numeric-input w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        Buat Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const diskonUnit = document.getElementById('diskon_unit');
            const diskonValue = document.getElementById('diskon_value');
            const subtotal = document.getElementById('subtotal');
            const total = document.getElementById('total');

            function calculateTotal() {

                let subtotalValue = parseFloat(subtotal.value.replace('.', '')) || 0;
                let diskon = parseFloat(diskonValue.value) || 0;

                if (diskonUnit.value === 'percentage') {
                    diskon = subtotalValue * (diskon / 100);
                }

                // Pastikan diskon tidak melebihi subtotal
                diskon = Math.min(diskon, subtotalValue);

                total.value = formatNumber((subtotalValue - diskon));
            }

            diskonUnit.addEventListener('change', calculateTotal);
            diskonValue.addEventListener('input', calculateTotal);

            new TomSelect('#referensi', {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                create: false,
                load: function(query, callback) {
                    var url = base_url + '/api/invoice-reference/search' + '?q=' +
                        encodeURIComponent(
                            query) + '&tipe=' + encodeURIComponent($('input[name="tipe"]:checked')
                            .val());
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
                        console.log(item);
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

            $('#referensi').change(function(e) {
                e.preventDefault();
                const reference = JSON.parse($(this).val());
                console.log(reference);
                // $('input[name="customer_name"]').val(reference.customer_name);

                subtotal.value = formatNumber(reference.subtotal);
                total.value = formatNumber(reference.total);
                diskonValue.value = formatNumber(reference.diskon_value);
                diskonUnit.value = formatNumber(reference.diskon_unit);
                if ($('input[name="tipe"]:checked')
                    .val() == 'sales') {



                    $('input[name="customer_id"]').val(reference.customer_id);
                    $('input[name="reference_id"]').val(reference.id);
                    $('.reference-detail').html('');

                    $('.reference-detail').append(layoutSales(reference));
                } else if ($('input[name="tipe"]:checked')
                    .val() == 'services') {



                    $('input[name="customer_id"]').val(reference.customer_vehicle.customer_id);
                    $('input[name="reference_id"]').val(reference.id);
                    $('.reference-detail').html('');

                    $('.reference-detail').append(layoutJO(reference));
                }
            });

            function layoutSales(sales) {
                return `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-300 mb-3">Informasi Pelanggan</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-400">Nama:</span>
                                <span class="text-white ml-2">${sales.customer_name}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Alamat:</span>
                                <span class="text-white ml-2">${sales.customer_address}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-300 mb-3">Informasi Transaksi</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-400">Tanggal:</span>
                                <span class="text-white ml-2">${sales.sales_date}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Status:</span>
                                <span class="text-white ml-2">
                                    <span class="px-2 py-1 bg-green-600 text-white rounded-full text-xs">Selesai</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Item Penjualan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gray-600 text-gray-300">
                                <tr>
                                    <th class="py-3 px-4 text-left">Produk/Jasa</th>
                                    <th class="py-3 px-4 text-right">Harga Satuan</th>
                                    <th class="py-3 px-4 text-right">Jumlah</th>
                                    <th class="py-3 px-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                            ${layoutItemSales(sales.items)}
                            </tbody>
                        </table>
                    </div>
                </div>
            `
            }

            function layoutJO(jobOrder) {
                var statusClasses = {
                    'draft': 'bg-gray-500',
                    'estimation': 'bg-yellow-500',
                    'progress': 'bg-blue-100 text-blue-800',
                    'completed': 'bg-green-500',
                    'cancelled': 'bg-red-500',
                };
                var statusText = {
                    'draft': 'Draft',
                    'estimation': 'Estimasi',
                    'progress': 'Progress',
                    'completed': 'Selesai',
                    'cancelled': 'Batal',
                };
                return `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                            <h3 class="text-lg font-medium text-white mb-4">Informasi Job Order</h3>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-sm text-gray-400">ID Job Order</p>
                                    <p class="text-white">${jobOrder.unique_id}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Tanggal Service</p>
                                    <p class="text-white">${jobOrder.service_at}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Kilometer</p>
                                    <p class="text-white">${jobOrder.km} km</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Status</p>
                                    <span class="px-2 py-1 text-xs rounded-full ${statusClasses[jobOrder.status]}">
                                    ${statusText[jobOrder.status]}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                            <h3 class="text-lg font-medium text-white mb-4">Informasi Pelanggan & Kendaraan</h3>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-sm text-gray-400">Nama Pelanggan</p>
                                    <p class="text-white">${ jobOrder.customer_vehicle.customer.name }</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Telepon</p>
                                    <p class="text-white">${ jobOrder.customer_vehicle.customer.phone }</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Kendaraan</p>
                                    <p class="text-white">
                                        ${ jobOrder.customer_vehicle.vehicle.merk }
                                        ${ jobOrder.customer_vehicle.vehicle.tipe }
                                        (${ jobOrder.customer_vehicle.vehicle.no_pol })
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 mb-6">
                        <h3 class="text-lg font-medium text-white mb-4">Items (Sparepart)</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-400">
                                <thead class="text-xs uppercase bg-gray-600 text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Item</th>
                                        <th class="px-4 py-3 text-right">Qty</th>
                                        <th class="px-4 py-3 text-right">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ${layoutItemJO(jobOrder.order_items, 'barang')}
                                </tbody>

                            </table>

                        </div>
                    </div>
                    <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 mb-6">
                        <h3 class="text-lg font-medium text-white mb-4">Jasa</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-400">
                                <thead class="text-xs uppercase bg-gray-600 text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Jasa</th>
                                        <th class="px-4 py-3 text-right">FRT</th>
                                        <th class="px-4 py-3 text-right">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   ${layoutItemJO(jobOrder.order_items, 'jasa')}
                                </tbody>

                            </table>

                        </div>
                    </div>
            `
            }

            function layoutItemSales(items) {
                var itemHtml = '';
                items.forEach(element => {
                    itemHtml += `<tr>
                                <td class="py-3 px-4 text-white">${element.product.name}
                                </td>
                                <td class="py-3 px-4 text-right text-white">Rp
                                ${formatNumber(element.unit_price)}</td>
                                <td class="py-3 px-4 text-right text-white">${ element.quantity }</td>
                                <td class="py-3 px-4 text-right text-white">Rp
                                ${element.total_price}</td>
                            </tr>`
                });

                return itemHtml;
            }

            function layoutItemJO(items, tipe) {
                var html = '';
                items.forEach((element, index) => {
                    if (element.product.tipe == tipe) {
                        html += ` <tr class="border-b border-gray-600">
                                    <td class="px-4 py-3">${index}</td>
                                    <td class="px-4 py-3">${element.product.name}</td>
                                    <td class="px-4 py-3 text-right">${element.quantity}
                                    </td>
                                    <td class="px-4 py-3 text-right">Rp
                                    ${formatNumber(element.unit_price)}
                                    </td>
                                    <td class="px-4 py-3 text-right">Rp
                                    ${formatNumber(element.total_price)}</td>
                                </tr>`;
                    }
                });

                return html;
            }


            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        });
    </script>
@endpush
