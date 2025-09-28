@extends('layouts.dashboard')

@section('title', 'Buat Retur Sparepart')
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
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600 flex items-center justify-between">
            <h2 class="sm:text-xl text-sm font-semibold text-white">Buat Retur Sparepart</h2>
            <a href="{{ route('returns.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('returns.store') }}" method="POST" id="returnForm">
            @csrf
            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="supply_id" class="block text-sm font-medium text-gray-300 mb-1">Supply</label>
                        {{-- <select id="supply_id" name="supply_id"
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="">Pilih Supply</option>
                            @foreach ($supplies as $supply)
                                <option value="{{ $supply->id }}">
                                    SPL-{{ $supply->id }} - {{ $supply->jobOrder->customerVehicle->vehicle->merk }}
                                    {{ $supply->jobOrder->customerVehicle->vehicle->tipe }}
                                    ({{ $supply->jobOrder->customerVehicle->customer->name }})
                                </option>
                            @endforeach
                        </select> --}}
                        <div class="relative w-full">
                            <input type="hidden" name="supply_id" />
                            <input type="text" id="no_supply" name="no_supply"
                                class="bg-gray-700 border border-gray-600 placeholder-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Cari Nomor Supply....." readonly>
                            <button type="button" id="modal-select-supply"
                                class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-blue-500 rounded-e-lg hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-blue-300 "><svg
                                    class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="supplyItemsContainer" class="hidden">
                    <h3 class="text-lg font-medium text-white mb-3">Item yang Dikembalikan</h3>
                    <div id="itemsContainer" class="space-y-3"></div>

                    {{-- <button type="button" id="addItemBtn"
                        class="mt-3 text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Item
                    </button> --}}
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="text-white bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Retur
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Template untuk item retur -->
    <template id="itemTemplate">
        <div class="item-card bg-gray-700 p-4 rounded-lg border border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Produk</label>

                    <input type="hidden" name="items[][product_id]"
                        class="product-id bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required readonly>
                    <input type="hidden" name="items[][order_item_id]"
                        class="order_item_id bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required readonly>
                    <input type="text" name="items[][product_name]"
                        class="product-view bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="items[][quantity]" min="1"
                        class="quantity-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Harga Satuan</label>
                    <input type="number" name="items[][unit_price]" min="0" step="0.01"
                        class="price-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Alasan Retur</label>
                    <input type="text" name="items[][reason]"
                        class="reason-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
            </div>
            <button type="button" class="remove-item-btn mt-2 text-red-400 hover:text-red-300 text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Hapus Item
            </button>
        </div>
    </template>


    <div id="supply-selection-modal"
        class="fixed hidden inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl h-full max-h-full flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">Cari Nomor Supply</h3>
            </div>

            <div class="relative overflow-x-auto flex-1 p-6">
                <table class="w-full text-sm text-left text-gray-400" id="supply-table" style="width: 100%;">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400
                    sticky top-0">
                        <tr>
                            <th class="py-3 text-sm font-semibold">No</th>
                            <th class="py-3 text-sm font-semibold">Job Order</th>
                            <th class="py-3 text-sm font-semibold">Jumlah Part</th>
                            <th class="py-3 text-sm font-semibold">Tanggal</th>
                            <th class="py-3 text-sm font-semibold">Status</th>
                            <th class="py-3 text-sm font-semibold">Dibuat Oleh</th>
                            <th class="py-3 text-sm font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="supply-list">

                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700 flex justify-end">
                <button type="button" id="cancel-retur-selection"
                    class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg">Batal</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalSelectSupply = document.getElementById('supply-selection-modal');



            const tableSupply = $('#supply-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('supply.datatable') }}",
                columnDefs: [{
                        targets: 6,
                        className: 'text-right'
                    }, // status_badge
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'job_order_unique',
                        name: 'job_order_unique',
                    },
                    {
                        data: 'count_part',
                        name: 'count_part',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'status_badge',
                        name: 'status'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                    },
                    {
                        data: 'action',
                        name: 'action',

                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button class="bg-green-600 text-white px-2 py-1 rounded-lg btn-selected">Pilih</button>`;
                        }
                    }
                ],
                order: [
                    [4, 'desc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"pagination-container"p>>',
                initComplete: function() {
                    $('.dataTables_length label').addClass('text-gray-400');
                    $('.dataTables_filter label').addClass('text-gray-400');
                    $('.dataTables_info').addClass('text-gray-400');
                    $('.dataTables_filter input').addClass(
                        'bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                    );
                    $('.dataTables_length select').addClass(
                        'bg-gray-700 border border-gray-600 text-green-600 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500'
                    );
                    $('.dataTables_processing').css({
                        'background': 'transparent',
                        'color': 'white'
                    });
                },
                drawCallback: function() {
                    $('.dataTables_info').addClass('text-gray-400');
                    $('.pagination-container .paginate_button').addClass(
                        'px-3 py-1 mx-1 text-gray-300 bg-gray-700 border border-gray-600 rounded-md hover:bg-gray-600 hover:text-white transition duration-150'
                    );
                    $('.pagination-container .paginate_button.current').addClass(
                        'bg-blue-600 text-white border-blue-600');
                    $('.dataTables_paginate').addClass('flowbite-pagination');
                    $('.paginate_button').each(function() {
                        $(this).removeClass('paginate_button previous next first last');
                        if ($(this).hasClass('current')) {
                            $(this).addClass('active bg-blue-600 text-white');
                        } else if ($(this).hasClass('disabled')) {
                            $(this).addClass('opacity-50 cursor-not-allowed');
                        }
                    });
                }
            });

            $('#supply-table').on('click', '.btn-selected', function(e) {
                e.preventDefault();
                const rowData = tableSupply.row($(this).closest('tr')).data();

                // contoh: isi hidden supply_id & no_supply
                document.querySelector('input[name="supply_id"]').value = rowData.id;
                document.getElementById('no_supply').value = `${rowData.job_order.unique_id}`;

                // tampilkan container items
                document.getElementById('supplyItemsContainer').classList.remove('hidden');

                console.log(rowData);

                const template = document.getElementById('itemTemplate');
                const clone = template.content.cloneNode(true);
                const container = document.getElementById('itemsContainer');
                const index = container.children.length;
                const itemCard = clone.querySelector('.item-card');
                // Update nama field dengan index
                // const inputs = itemCard.querySelectorAll('[name]');
                // inputs.forEach(input => {
                //     const name = input.getAttribute('name').replace('[]', `[${index}]`);
                //     input.setAttribute('name', name);
                // });


                rowData.items.forEach((element, index) => {

                    container.appendChild(createReturnItemForm(element, index));
                })


                // tutup modal
                document.getElementById('supply-selection-modal').classList.add('hidden');
            });

            document.getElementById('modal-select-supply').addEventListener('click', function() {
                modalSelectSupply.classList.remove('hidden');
                tableSupply.draw();
            });

            // Fungsi untuk membuat form item retur
            function createReturnItemForm(item, index) {
                console.log(item);
                const template = document.getElementById('itemTemplate');
                const clone = template.content.cloneNode(true);
                const itemCard = clone.querySelector('.item-card');

                // Isi data ke dalam form
                const productId = itemCard.querySelector('.product-view');
                productId.value = item.product.name;
                const productView = itemCard.querySelector('.product-id');
                productView.value = item.product_id;
                const orderitemId = itemCard.querySelector('.order_item_id');
                orderitemId.value = item.item_id;

                const quantityInput = itemCard.querySelector('.quantity-input');
                quantityInput.value = item.quantity_fulfilled || item.quantity_requested;
                quantityInput.max = item.quantity_fulfilled || item.quantity_requested;

                const priceInput = itemCard.querySelector('.price-input');
                priceInput.value = formatRupiah(item.unit_price);

                // Set nama field dengan index yang benar
                const inputs = itemCard.querySelectorAll('[name]');
                inputs.forEach(input => {
                    const name = input.getAttribute('name').replace('[]', `[${index}]`);
                    input.setAttribute('name', name);
                });

                // // Inisialisasi Tom Select untuk produk
                // new TomSelect(productSelect, {
                //     create: false,
                //     onChange: function(value) {
                //         const selectedOption = this.options[value];
                //         if (selectedOption) {
                //             priceInput.value = selectedOption.dataset.price || item.unit_price;
                //         }
                //     }
                // });

                // Event listener untuk tombol hapus
                itemCard.querySelector('.remove-item-btn').addEventListener('click', function() {
                    itemCard.remove();
                });

                return itemCard;
            }

            $('#cancel-retur-selection').click(function(e) {
                e.preventDefault();
                modalSelectSupply.classList.add('hidden');
            });

            // Tombol tambah item
            document.getElementById('addItemBtn').addEventListener('click', function() {
                const template = document.getElementById('itemTemplate');
                const clone = template.content.cloneNode(true);
                const container = document.getElementById('itemsContainer');

                // Set index untuk nama field
                const index = container.children.length;
                const itemCard = clone.querySelector('.item-card');

                // Update nama field dengan index
                const inputs = itemCard.querySelectorAll('[name]');
                inputs.forEach(input => {
                    const name = input.getAttribute('name').replace('[]', `[${index}]`);
                    input.setAttribute('name', name);
                });

                container.appendChild(clone);

                // // Inisialisasi Tom Select untuk produk baru
                // new TomSelect(itemCard.querySelector('.product-select'), {
                //     create: false,
                //     sortField: {
                //         field: "text",
                //         direction: "asc"
                //     },
                //     onChange: function(value) {
                //         const selectedOption = this.options[value];
                //         const priceInput = itemCard.querySelector('.price-input');
                //         if (selectedOption) {
                //             priceInput.value = selectedOption.dataset.price;
                //         }

                //         // Update order items berdasarkan produk yang dipilih
                //         updateOrderItemsSelect(itemCard, value);
                //     }
                // });

                // // Inisialisasi Tom Select untuk order item
                // new TomSelect(itemCard.querySelector('.order-item-select'), {
                //     create: false,
                //     sortField: {
                //         field: "text",
                //         direction: "asc"
                //     }
                // });

                // Event listener untuk tombol hapus
                itemCard.querySelector('.remove-item-btn').addEventListener('click', function() {
                    itemCard.remove();
                });
            });

            // Fungsi untuk mengupdate select order item berdasarkan produk
            function updateOrderItemsSelect(itemCard, productId) {
                const orderItemSelect = itemCard.querySelector('.order-item-select');
                const supplyId = document.getElementById('supply_id').value;

                if (!supplyId || !productId) {
                    orderItemSelect.innerHTML = '<option value="">Pilih Order Item</option>';
                    return;
                }

                fetch(`/api/supplies/${supplyId}/products/${productId}/order-items`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">Pilih Order Item</option>';
                        data.forEach(item => {
                            options +=
                                `<option value="${item.id}">ORD-${item.id} (Qty: ${item.quantity})</option>`;
                        });
                        orderItemSelect.innerHTML = options;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    </script>
@endpush
