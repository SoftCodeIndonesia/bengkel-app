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
    <div class="bg-gray-800 shadow overflow-hidden">
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
            <form action="{{ route('invoices.store') }}" method="POST" id="form-create-invoice">
                @csrf

                <div class="space-y-4 mb-6">
                    <input type="hidden" name="tipe" value="{{ $type }}">

                    <div class="flex gap-3">
                        <div class="flex-1">
                            <input type="hidden" name="reference_id" value="{{ $reference->id }}">
                            <label for="referensi" class="block text-gray-300 mb-2">Cari Referensi</label>
                            <input type="hidden" name="customer_id"
                                value="{{ $type == 'services' ? $reference->customerVehicle->customer->id : $reference->customer->id }}">
                            <div class="relative w-full">
                                <input type="text" name="referensi" value="{{ $reference->unique_id }}"
                                    id="search-dropdown"
                                    class="block p-2.5 w-full z-20 text-sm  rounded-lg rounded-s-gray-100 rounded-s-2 border  focus:ring-blue-500  bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:border-blue-500"
                                    placeholder="Pilih WO/SO" readonly />
                                <button type="button" id="button-select-reference" data-modal-target="modal-reference"
                                    data-modal-toggle="modal-reference"
                                    class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><svg
                                        class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg></button>
                            </div>


                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-300 mb-2">Status Invoice</label>
                            <select name="status"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid
                                </option>
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

    <div id="modal-reference" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow-sm bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600 ">
                    <h3 class="text-xl font-medium  text-white">
                        Pilih WO/SO
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="modal-reference">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">


                    <div class="mb-4 border-b border-gray-600">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                            data-tabs-toggle="#default-tab-content" role="tablist">
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-1 rounded-t-lg hover:text-gray-100"
                                    id="sales-order-tab" data-tabs-target="#sales-order" type="button" role="tab"
                                    aria-controls="sales-order" aria-selected="false">Sales Order (SO)</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button
                                    class="inline-block p-4 border-b-1 rounded-t-lg  hover:border-gray-300 hover:text-gray-100"
                                    id="work-order-tab" data-tabs-target="#work-order" type="button" role="tab"
                                    aria-controls="work-order" aria-selected="false">Work Order (WO)</button>
                            </li>

                        </ul>
                    </div>
                    <div id="default-tab-content">
                        <div class="hidden p-4 rounded-lg bg-gray-800 dark:bg-gray-800" id="sales-order" role="tabpanel"
                            aria-labelledby="sales-order-tab">
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                                    id="datatables-index">
                                    <thead class="uppercase bg-gray-700 text-gray-400">
                                        <tr>
                                            <th class="p-3 text-sm font-semibold">No</th>
                                            <th class="py-3 text-sm font-semibold">Kode</th>
                                            <th class="py-3 text-sm font-semibold">Tanggal</th>
                                            <th class="py-3 text-sm font-semibold">Pelanggan</th>
                                            <th class="py-3 text-sm font-semibold text-right">Subtotal</th>
                                            <th class="py-3 text-sm font-semibold text-right">Diskon</th>
                                            <th class="py-3 text-sm font-semibold text-right">Total</th>
                                            <th class="p-3 text-sm font-semibold text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach ($sales as $key => $sale)
                                            <tr>
                                                <td class="py-3 px-3">{{ $key + 1 }}</td>
                                                <td class="py-3 ">{{ $sale->unique_id }}</td>
                                                <td class="py-3 ">{{ $sale->sales_date->format('d M Y') }}</td>
                                                <td class="py-3 ">{{ $sale->customer->name }}</td>
                                                <td class="py-3 text-right">Rp
                                                    {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                                                <td class="py-3 text-right">Rp
                                                    {{ number_format($sale->diskon_value, 0, ',', '.') }}</td>
                                                <td class="py-3 text-right">Rp
                                                    {{ number_format($sale->total, 0, ',', '.') }}
                                                </td>
                                                <td class="p-3 text-right">
                                                    <input checked id="select-reference-2" type="radio"
                                                        value="{{ json_encode(['value' => $sale->id, 'tipe' => 'sales']) }}"
                                                        name="select-reference"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="hidden p-4 rounded-lg bg-gray-800 dark:bg-gray-800" id="work-order" role="tabpanel"
                            aria-labelledby="work-order-tab">
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right  text-gray-400"
                                    id="datatables-index">
                                    <thead class="uppercase bg-gray-700 text-gray-400">
                                        <tr>
                                            <th class="p-3">No</th>
                                            <th class="py-3">Nomor JO</th>
                                            <th class="py-3">Pelanggan</th>
                                            <th class="py-3">Kendaraan</th>
                                            <th class="py-3">Tanggal</th>
                                            <th class="py-3 text-right">Subtotal</th>
                                            <th class="py-3 text-right">Diskon</th>
                                            <th class="py-3 text-right">Total</th>
                                            <th class="p-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach ($jobOrders as $key => $jo)
                                            <tr>
                                                <th class="p-3">{{ $key + 1 }}</th>
                                                <th class="py-3">{{ $jo->unique_id }}</th>
                                                <th class="py-3">{{ $jo->customerVehicle->customer->name }}</th>
                                                <th class="py-3">
                                                    {{ $jo->customerVehicle->vehicle->merk . ' ' . $jo->customerVehicle->vehicle->tipe . ' (' . $jo->customerVehicle->vehicle->no_pol . ')' }}
                                                </th>
                                                <th class="py-3">{{ $jo->service_at->format('d M Y') }}</th>
                                                <th class="py-3 text-right">Rp
                                                    {{ number_format($jo->subtotal, 0, ',', '.') }}</th>
                                                <th class="py-3 text-right">Rp
                                                    {{ number_format($jo->diskon_value, 0, ',', '.') }}</th>
                                                <th class="py-3 text-right">Rp
                                                    {{ number_format($jo->total, 0, ',', '.') }}</th>
                                                <td class="p-3 text-right">
                                                    <input checked id="select-reference-2" type="radio"
                                                        value="{{ json_encode(['value' => $jo->id, 'tipe' => 'services']) }}"
                                                        name="select-reference"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t rounded-b border-gray-600">

                    <button data-modal-hide="modal-reference" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium focus:outline-none  rounded-lg border  focus:z-10 focus:ring-4  focus:ring-gray-700 bg-gray-800 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-700">Batal</button>
                    <button data-modal-hide="modal-reference" type="button" id="confirm-reference"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalReference = document.getElementById('modal-reference');
            const confirmReference = document.getElementById('confirm-reference');
            const btnOpenReference = document.getElementById('button-select-reference');



            const referenceIdInput = document.querySelector('input[name="reference_id"]');
            const referenceInput = document.querySelector('input[name="referensi"]');
            const invoiceTipeField = document.querySelector('input[name="tipe"]');


            const diskonUnit = document.getElementById('diskon_unit');
            const diskonValue = document.getElementById('diskon_value');
            const subtotal = document.getElementById('subtotal');
            const total = document.getElementById('total');


            if (referenceIdInput.value && invoiceTipeField.value) {
                renderData(invoiceTipeField.value, referenceIdInput.value);
            }


            confirmReference.addEventListener('click', function() {
                const value = JSON.parse($('input[name="select-reference"]:checked').val());
                console.log(value);

                const tipe = value.tipe;
                const id = value.value;

                referenceIdInput.value = id;
                invoiceTipeField.value = tipe;

                renderData(tipe, id);
            });

            function renderData(tipe, id) {
                // Ambil data detail referensi via AJAX
                fetch(`/api/invoice-references/${tipe}/${id}`)
                    .then(response => response.json())
                    .then(reference => {

                        referenceIdInput.value = reference.id;
                        referenceInput.value = reference.unique_id;

                        // isi field summary
                        subtotal.value = formatNumber(reference.subtotal);
                        total.value = formatNumber(reference.total);
                        diskonValue.value = formatNumber(reference.diskon_value);
                        diskonUnit.value = reference.diskon_unit;

                        if (tipe == 'sales') {
                            // isi customer
                            $('input[name="customer_id"]').val(reference.customer_id);
                        } else {
                            // isi customer
                            $('input[name="customer_id"]').val(reference.customer_vehicle.customer_id);
                        }

                        // render detail
                        $('.reference-detail').html('');
                        if (tipe === 'sales') {
                            $('.reference-detail').append(layoutSales(reference));
                        } else {
                            $('.reference-detail').append(layoutJO(reference));
                        }

                        // // tutup modal
                        // const modal = document.querySelector('#modal-reference');
                        // modalReference.classList.add('hidden');
                        // modalReference.hide();






                        // modalReferenceEl.toggle();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal mengambil data referensi');
                    });
            }

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
                    if (tipe == 'jasa' && element.product.tipe == tipe) {
                        html += ` <tr class="border-b border-gray-600">
                                    
                                    <td class="px-4 py-3">${element.product.name}</td>
                                    <td class="px-4 py-3 text-right">${element.quantity}
                                    </td>
                                    <td class="px-4 py-3 text-right">Rp
                                    ${formatNumber(element.unit_price)}
                                    </td>
                                    <td class="px-4 py-3 text-right">Rp
                                    ${formatNumber(element.total_price)}</td>
                                </tr>`;
                    } else if (tipe != 'jasa' && element.product.tipe != 'jasa') {
                        html += ` <tr class="border-b border-gray-600">
                                    
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

            $('#form-create-invoice').submit(function(e) {

                $('input[name="subtotal"]').val(originalNumber($('input[name="subtotal"]').val()));
                $('input[name="diskon_value"]').val(originalNumber($('input[name="diskon_value"]').val()));
                $('input[name="total"]').val(originalNumber($('input[name="total"]').val()));
            });
        });
    </script>
@endpush
