@extends('layouts.dashboard')

@section('title', 'Buat Stok Opname')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #f3f4f6 !important;
        }

        .ts-dropdown {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }

        .ts-dropdown .active {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
        }

        .ts-control,
        .ts-control input {
            /* background-color: transparent !important;
                                    border: none !important;
                                    padding: 2px !important; */
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Stok Opname</h2>
        </div>

        <form action="{{ route('stock-opname.store') }}" method="POST">
            @csrf
            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                    <div>
                        <label for="opname_date" class="block text-sm font-medium text-gray-300 mb-1">Tanggal Opname</label>
                        <input type="date" id="opname_date" name="opname_date" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                    <textarea id="notes" name="notes" rows="2"
                        class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-white mb-3">Item Stok Opname</h3>

                    <div class="mb-4">
                        <label for="product_search" class="block text-sm font-medium text-gray-300 mb-1">Cari Produk</label>
                        <select id="product_search" class="w-full" placeholder="Cari produk..."></select>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Produk</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Stok Sistem</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Stok Fisik</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Selisih</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Catatan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="opname_items" class="bg-gray-800 divide-y divide-gray-600">
                                <!-- Items will be added here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- <input type="hidden" name="items" id="items_data"> --}}

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Stok Opname
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tom Select for product search
            const productSearch = new TomSelect('#product_search', {
                valueField: 'id',
                labelField: 'text',
                searchField: ['text', 'barcode'],
                load: function(query, callback) {
                    var url = base_url + '/api/products/search?q=' + encodeURIComponent(query) +
                        '&tipe=' + encodeURIComponent('barang');
                    fetch(url)
                        .then(response => response.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                },
                render: {
                    option: function(item, escape) {
                        return `
                            <div class="flex justify-between items-center p-2 hover:bg-gray-700">
                                <div>
                                    <span class="text-gray-300">${escape(item.text)}</span>
                                    <small class="block text-gray-400">${escape(item.barcode || 'No barcode')}</small>
                                </div>
                                <span class="text-gray-300">Stok: ${escape(item.stok)}</span>
                            </div>
                        `;
                    },
                    item: function(item, escape) {
                        return `<div>${escape(item.text)}</div>`;
                    }
                },
                onChange: function(id) {
                    if (id) {
                        addProductToOpname(id);
                        this.clear();
                    }
                }
            });

            const opnameItems = [];

            function addProductToOpname(productId) {
                var product = JSON.parse(productId);


                // Check if product already exists in the list
                if (opnameItems.some(item => item.product_id == product.id)) {
                    alert('Produk sudah ada dalam daftar opname');
                    return;
                }

                const item = {
                    product_id: product.id,
                    product_name: product.name,
                    system_stock: product.stok,
                    physical_stock: product.stok,
                    difference: 0,
                    notes: ''
                };

                opnameItems.push(item);
                renderOpnameItems();
            }

            function renderOpnameItems() {
                const container = document.getElementById('opname_items');
                container.innerHTML = '';

                if (opnameItems.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-400">Tidak ada item</td>
                        </tr>
                    `;
                    return;
                }

                opnameItems.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-700';
                    row.innerHTML = `
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-300">${item.product_name}</div>
                            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-300">${item.system_stock}</div>
                            <input type="hidden" name="items[${index}][system_stock]" value="${item.system_stock}">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <input type="number" name="items[${index}][physical_stock]" value="${item.physical_stock}" min="0"
                                class="bg-gray-700 border border-gray-600 rounded-md shadow-sm py-1 px-2 text-white w-20"
                                onchange="updateDifference(${index}, this.value)">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-300 difference-${index}">${item.difference}</div>
                            <input type="hidden" name="items[${index}][difference]" value="${item.difference}">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <input type="text" name="items[${index}][notes]" value="${item.notes}"
                                class="bg-gray-700 border border-gray-600 rounded-md shadow-sm py-1 px-2 text-white">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <button type="button" onclick="removeItem(${index})" class="text-red-500 hover:text-red-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    `;
                    container.appendChild(row);
                });

                // Update hidden field with items data
                // document.getElementById('items_data').value = JSON.stringify(opnameItems);
            }

            window.updateDifference = function(index, physicalStock) {
                physicalStock = parseInt(physicalStock) || 0;
                const systemStock = opnameItems[index].system_stock;
                const difference = physicalStock - systemStock;

                opnameItems[index].physical_stock = physicalStock;
                opnameItems[index].difference = difference;

                document.querySelector(`.difference-${index}`).textContent = difference;
                document.querySelector(`input[name="items[${index}][difference]"]`).value = difference;
                // document.getElementById('items_data').value = JSON.stringify(opnameItems);
            };

            window.removeItem = function(index) {
                opnameItems.splice(index, 1);
                renderOpnameItems();
            };

            // Before form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                if (opnameItems.length === 0) {
                    e.preventDefault();
                    alert('Tambahkan minimal satu item untuk stok opname');
                    return false;
                }
            });
        });
    </script>
@endpush
