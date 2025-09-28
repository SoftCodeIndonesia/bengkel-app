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
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Stok Opname</h2>

        </div>

        <form action="{{ route('stock-opname-print-form') }}" method="POST">
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

                        <button type="button" id="add-item"
                            class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Cari Sparepart
                        </button>
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
                        class="text-gray-300 mb-2 sm:mb-0 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Form
                    </button>
                </div>
            </div>
        </form>
    </div>

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
                                    class="h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3" width="80%">Part</th>
                            <th class="px-4 py-3" width="80%">Grade</th>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('add-item').addEventListener('click', function() {
                table.draw();
                document.getElementById('product-selection-modal').classList.remove('hidden');
            });

            let selectedProducts = [];

            var table = $('#product-table-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('api.product.list') }}",
                    data: function(d) {
                        d.tipe = 'barang';
                    }
                },
                columnDefs: [{
                    width: '30px',
                    targets: 1,
                }],
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
                        data: 'grade',
                        name: 'grade',
                        className: 'px-4 py-3',
                    },
                    {
                        data: 'formatted_price_buying',
                        name: 'formatted_price_buying',
                        className: 'px-4 py-3',
                    },
                    {
                        data: 'stok',
                        name: 'stok',
                        className: 'px-4 py-3'
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
                    const productGrade = productRow.querySelector('td:nth-child(3)').textContent;
                    const productPrice = productRow.querySelector('td:nth-child(4)').textContent;
                    const stok = productRow.querySelector('td:nth-child(5)').textContent;
                    // console.log(originalNumber(productPrice));
                    if (!selectedProducts.includes({
                            id: productId,
                            name: productName,
                            grade: productGrade,
                            stok: stok,
                            difference: 0,
                            notes: '',
                        })) {
                        selectedProducts.push({
                            id: productId,
                            name: productName,
                            grade: productGrade,
                            stok: stok,
                            difference: 0,
                            notes: '',
                        });
                    }
                });
                renderOpnameItems();

                document.getElementById('product-selection-modal').classList.add('hidden');
                resetSelection();
            });





            // Reset product selection
            function resetSelection() {
                document.getElementById('select-all').checked = false;
                // document.getElementById('product-search').value = '';
                const checkboxes = document.querySelectorAll('#product-list input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }

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

                if (selectedProducts.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-400">Tidak ada item</td>
                        </tr>
                    `;
                    return;
                }

                selectedProducts.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-700';
                    row.innerHTML = `
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-300">${item.name}</div>
                            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-300">${item.stok}</div>
                            <input type="hidden" name="items[${index}][system_stock]" value="${item.stok}">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            
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
                const systemStock = selectedProducts[index].stok;
                const difference = physicalStock - systemStock;

                selectedProducts[index].physical_stock = physicalStock;
                selectedProducts[index].difference = difference;

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
