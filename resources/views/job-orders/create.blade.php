@extends('layouts.dashboard')

@section('title', 'Buat Job Order')
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
    <div class="bg-gray-800 shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-sm sm:text-xl font-semibold text-white">Buat Work Order Baru</h2>
            <a href="{{ route('job-orders.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm  rounded-lg  bg-gray-800 text-red-400" role="alert">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('job-orders.store') }}" method="POST" id="jobOrderForm">
                @csrf

                <div class="mb-6">
                    <label for="package" class="block mb-2 text-sm font-medium text-white">Paket Service</label>
                    <select id="package" name="package"
                        class=" border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="custom" selected>Normal</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}" {{ $package->id == old('package') ? 'selected' : '' }}>
                                {{ $package->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer Section -->
                <div class="w-full" id="field-customer_vehicle_id">
                    <label for="customer_vehicle_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Kendaraan Pelanggan
                    </label>
                    <div class="relative w-full">
                        <input type="hidden" name="customer_vehicle_id" />
                        <input type="text" id="customer_vehicle_name" name="customer_vehicle_name"
                            class="bg-gray-700 border border-gray-600 placeholder-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Cari Pelanggan....." readonly>
                        <button type="button" id="modal-select-customer"
                            class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-gray-500 rounded-e-lg border border-gray-500 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-blue-300 "><svg
                                class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </div>
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

                <!-- Customer Details -->
                <div class="mt-4 hidden" id="customer-vehicle-detail-container">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Details -->
                            <div>
                                <h4 class="section-title text-white mb-2">Detail Pelanggan</h4>
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

                            <!-- Vehicle Details -->
                            <div>
                                <h4 class="section-title text-white mb-2">Detail Kendaraan</h4>
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

                <!-- New Customer Form -->
                <div class="mt-6 {{ old('customer_name') ? '' : 'hidden' }}" id="add-customer-section">
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <div class="mb-4" id="field-customer_name">
                                <label for="name" class="block text-sm font-medium text-gray-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" id="name"
                                    value="{{ old('customer_name') }}"
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

                <!-- Service Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
                    <div>
                        <label for="km" class="block text-sm font-medium text-gray-300 mb-2">Kilometer <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="km" id="km" value="{{ old('km') }}"
                            placeholder="Contoh: 100000" required min="0"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="service_at" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Servis <span
                                class="text-red-500">*</span></label>
                        <input type="datetime-local" value="{{ old('service_at') }}" name="service_at" id="service_at"
                            required
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <!-- Breakdown Section -->
                <div class="mb-6">
                    <h3 class="section-title text-white mb-2">Deskripsi Kerusakan</h3>
                    <div id="breakdowns-container">
                        @php $breakIndex = 0; @endphp
                        @if (old('breakdowns'))
                            @foreach (old('breakdowns') as $breakdown)
                                <div class="breakdown-row flex gap-4 mb-3">
                                    <div class="col-span-11 flex-1">
                                        <input type="text" name="breakdowns[{{ $breakIndex }}][name]"
                                            value="{{ $breakdown['name'] }}" placeholder="Masukan Kerusakan"
                                            class="w-full bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                        class="w-full breakdown-kerusakan bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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

                <!-- Services Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="section-title text-white">Jasa (Service)</h3>
                        <button type="button" id="add-service"
                            class="text-blue-500 hover:text-blue-400 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Jasa
                        </button>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm"
                            id="service-table">
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
                </div>

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

                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm"
                            id="sparepart-table">
                            <thead class="uppercase bg-gray-700 text-gray-400">
                                <tr>
                                    <th class="p-2 text-left">Sparepart</th>
                                    <th class="p-2">Grade</th>
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
                                <!-- Sparepart rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>



                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-300">
                        Catatan
                    </label>
                    <textarea type="text" name="notes" id="notes" value="{{ old('notes') }}"
                        class="mt-1 block w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
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
                    <a href="{{ route('job-orders.index') }}"
                        class="px-4 py-2 text-gray-300 bg-gray-600 hover:bg-gray-500 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-200">
                        Simpan
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
                            <th class="px-4 py-3" width="80%">Grade</th>
                            <th class="px-4 py-3" width="10%">Harga</th>
                            <th class="px-4 py-3" width="10%">Stok/FRT</th>
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

    <div id="select-customer" class="fixed  inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl h-full max-h-full flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">Pilih Customer</h3>
            </div>

            <div class="relative overflow-x-auto flex-1 p-6">
                <table class="w-full text-sm text-left text-gray-400" id="customer-table-list" style="width: 100%;">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400
                    sticky top-0">
                        <tr>
                            <th class="px-4 py-3" width="5%">No</th>
                            <th class="px-4 py-3" width="40%">Nama</th>
                            <th class="px-4 py-3">No.Telp</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3">Kendaraan</th>
                            <th class="px-4 py-3">No Polisi</th>
                            <th class="px-4 py-3" width="3%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="customer-list">

                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700 flex justify-end">
                <button type="button" id="cancel-customer-selection"
                    class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg">Batal</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalSelectProduct = document.getElementById('product-selection-modal');
            const modalSelectCustomer = document.getElementById('select-customer');
            modalSelectCustomer.classList.add('hidden');
            modalSelectProduct.classList.add('hidden');


            let selectedProducts = [];
            let tipe = 'barang';



            // Inisialisasi counter untuk items dan breakdowns
            let itemCounter = 1;
            let breakdownCounter = 1;
            let customer_form_active = false;

            $('#package').change(function(e) {
                e.preventDefault();

                var id = $(this).val();

                fetchPackage(id);

            });

            const fetchPackage = (id) => {
                if (id === 'custom') {
                    document.querySelectorAll('.item-row').forEach(row => {
                        row.remove();
                        calculateTotal();
                    })
                    return;
                }

                $.ajax({
                    type: "get",
                    url: base_url + "/api/package/" + id,
                    dataType: "json",
                    success: function(response) {
                        initialPackage(response);
                    }
                });
            }

            const packageId = "{{ old('package') }}";

            const initialPackage = (package) => {
                $('.breakdown-kerusakan').val(package.name);
                for (let index = 0; index < package.items.length; index++) {
                    const element = package.items[index];
                    if (element.product.tipe == 'jasa') {
                        addItemRowPackage('jasa', 'service-items-container', {

                            text: element.product.name,
                            quantity: element.quantity,
                            discount: element.discount,
                            subtotal: element.subtotal,
                            total: element.total,
                            ...element.product,
                        });
                    } else {
                        addItemRowPackage('barang', 'sparepart-items-container', {

                            text: element.product.name,
                            quantity: element.quantity,
                            discount: element.discount,
                            subtotal: element.subtotal,
                            total: element.total,
                            ...element.product,
                        });
                    }
                    calculateTotal();
                }
            }





            // Toggle customer form
            document.getElementById('add-customer').addEventListener('click', function() {
                customer_form_active = !customer_form_active;
                document.getElementById('add-customer-section').classList.toggle('hidden');
            });

            // Add sparepart row
            document.getElementById('add-sparepart').addEventListener('click', function() {
                tipe = 'barang';
                table.draw();
                modalSelectProduct.classList.remove('hidden');
            });

            // Add service row
            document.getElementById('add-service').addEventListener('click', function() {
                tipe = 'jasa';
                table.draw();
                modalSelectProduct.classList.remove('hidden');
            });

            // Function to add item row
            function addItemRow(kategori, productName, grade, productId, unit_price, quantity, buying_price) {

                let containerStr = '';
                if (tipe == 'jasa') {
                    containerStr = 'service-items-container';
                } else {
                    containerStr = 'sparepart-items-container';
                }

                const tbody = document.getElementById(containerStr);
                const rowId = `item-row-${itemCounter}`;

                const row = document.createElement('tr');
                row.id = rowId;
                row.classList.add('border-b', 'border-gray-600', 'item-row');

                if (tipe === 'barang') {
                    row.innerHTML = `
                        <td class="p-2 text-left" width="300px">
                            <input type="hidden" name="items[${itemCounter}][type]" value="barang">
                            <input type="hidden" name="items[${itemCounter}][product_id]" value="${productId}">
                            <span>${productName}</span>
                        </td>
                        <td class="p-2 text-center">
                            <span class="grade text-gray-300">${grade}</span>
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">${kategori}</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="1" value="1"
                                class="buying_price bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                            <input type="number" name="items[${itemCounter}][buying_price]" min="1" value="${buying_price}"
                                class="buying_price bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                        </td>
                        <td class="p-2 text-right">
                            <span class="unit-price text-gray-300">Rp ${formatRupiah(unit_price)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <span class="subtotal text-gray-300">Rp ${formatRupiah(unit_price * quantity)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                                value="0"
                                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                                placeholder="%">
                        </td>
                        <td class="p-2 text-right">
                            <span class="total-after-diskon text-gray-300">Rp ${formatRupiah(unit_price * quantity)}</span>
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
                    const base_price = 100000;
                    row.innerHTML = `
                        <td class="p-2" width="300px">
                            <input type="hidden" name="items[${itemCounter}][type]" value="jasa">
                            <input type="hidden" name="items[${itemCounter}][product_id]" value="${productId}">
                            <span>${productName}</span>
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">jasa</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" value="${quantity}" min="0.1" step="0.01"
                                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                        </td>
                        
                        <td class="p-2 text-right">
                            <span class="subtotal text-gray-300">Rp ${formatRupiah(base_price * quantity)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                                value="0"
                                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                                placeholder="%">
                        </td>
                        <td class="p-2 text-right">
                            <span class="total-after-diskon text-gray-300">Rp ${formatRupiah(base_price * quantity)}</span>
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
                // initializeProductSelect(select, tipe);

                // Add event listeners for calculations
                initItemRowEvents(row, tipe);

                itemCounter++;
            }

            function addItemRowPackage(type, containerId, data) {
                console.log(data);
                const tbody = document.getElementById(containerId);
                const rowId = `item-row-${itemCounter}`;

                const row = document.createElement('tr');
                row.id = rowId;
                row.classList.add('border-b', 'border-gray-600', 'item-row');

                if (type === 'barang') {
                    row.innerHTML = `
                        <td class="p-2" width="300px">
                            <input type="hidden" name="items[${itemCounter}][type]" value="barang">
                            <input type="hidden" name="items[${itemCounter}][product_id]" value="${data.id}">
                            <span>${data.name}</span>
                        </td>
                        <td class="p-2 text-center">
                            <span class="grade text-gray-300">${data.grade ?? '-'}</span>
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">${data.tipe}</span>
                        </td>
                        
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="1" value="${data.quantity}"
                                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                        </td>
                        <td class="p-2 text-right">
                            <span class="unit-price text-gray-300">Rp ${formatNumber(data.unit_price)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <span class="subtotal text-gray-300">Rp ${formatNumber(data.subtotal)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                                value="${data.discount}"
                                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                                placeholder="%">
                        </td>
                        <td class="p-2 text-right">
                            <span class="total-after-diskon text-gray-300">Rp ${formatNumber(data.total)}</span>
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
                            <input type="hidden" name="items[${itemCounter}][type]" value="jasa">
                            <input type="hidden" name="items[${itemCounter}][product_id]" value="${data.id}">
                            <span>${data.name}</span>
                        </td>
                        <td class="p-2 text-center">
                            <span class="kategori text-gray-300">${data.tipe}</span>
                        </td>
                        <td class="p-2" width="100px">
                            <input type="number" name="items[${itemCounter}][quantity]" min="0.01" step="0.01" value="${data.quantity}"
                                class="quantity bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2 w-full">
                        </td>
                        
                        <td class="p-2 text-right">
                            <span class="subtotal text-gray-300">Rp ${formatNumber(data.subtotal)}</span>
                        </td>
                        <td class="p-2 text-right">
                            <input type="number" name="items[${itemCounter}][diskon_value]" min="0" max="100" step="0.01"
                                value="${data.discount}"
                                class="diskon-value w-full bg-gray-700 border border-gray-600 text-white rounded-md py-1 px-2"
                                placeholder="%">
                        </td>
                        <td class="p-2 text-right">
                            <span class="total-after-diskon text-gray-300">Rp ${formatNumber(data.total)}</span>
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
                            '&tipe=' + encodeURIComponent(type != 'jasa' && tipe != 'Jasa' ? 'barang' :
                                'jasa');
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
                                        <div class="text-xs text-gray-400">${escape(item.price)}</div>
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

                const qtyInput = row.querySelector('.quantity');
                const diskonValue = row.querySelector('.diskon-value');
                const kategori = row.querySelector('.kategori');
                const priceText = row.querySelector('.unit-price');
                const subtotalText = row.querySelector('.subtotal');
                const totalAfterDiskonText = row.querySelector('.total-after-diskon');

                const calculateItemTotal = () => {
                    const price = originalNumber(priceText?.textContent ?? 0);
                    const qty = parseFloat(qtyInput.value);
                    const diskon = parseFloat(diskonValue.value) || 0;

                    var subtotal = 0;
                    if (type == 'jasa') {
                        subtotal = 100000 * qty;
                    } else {
                        subtotal = price * qty;
                    }


                    const totalAfterDiskon = subtotal * (1 - (diskon / 100));


                    if (type != 'jasa') {
                        priceText.textContent = 'Rp ' + formatNumber(price);
                    }
                    subtotalText.textContent = 'Rp ' + formatNumber(subtotal);
                    totalAfterDiskonText.textContent = 'Rp ' + formatNumber(totalAfterDiskon);

                    calculateTotal();
                };


                qtyInput.addEventListener('input', calculateItemTotal);
                diskonValue.addEventListener('input', calculateItemTotal);

                row.querySelector('.remove-item').addEventListener('click', () => {
                    row.remove();
                    calculateTotal();
                });

                calculateTotal();
            }

            // Add breakdown row
            document.getElementById('add-breakdown').addEventListener('click', function() {
                const newBreakdown = document.createElement('div');
                newBreakdown.className = 'breakdown-row flex gap-4 mb-3';
                newBreakdown.innerHTML = `
                    <div class="col-span-11 flex-1">
                        <input type="text" name="breakdowns[${breakdownCounter}][name]" placeholder="Nama pemeriksaan"
                            class="w-full bg-gray-700 border border-gray-600 placeholder-gray-400 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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

                newBreakdown.querySelector('.remove-breakdown').addEventListener('click', function() {
                    this.closest('.breakdown-row').remove();
                });
            });

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

                    if (itemType != 'jasa') {
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
            document.getElementById('jobOrderForm').addEventListener('submit', function(e) {

                const customer_vehicle_id = document.querySelector('input[name="customer_vehicle_id"]')
                    .value;
                const customer_name = document.querySelector('input[name="customer_name"]')?.value;
                const merk = document.querySelector('input[name="merk"]')?.value;
                const tipe = document.querySelector('input[name="tipe"]')?.value;
                const no_pol = document.querySelector('input[name="no_pol"]')?.value;

                if (customer_vehicle_id === '' && customer_form_active) {
                    if (!customer_name) {
                        document.getElementById('field-customer_name').insertAdjacentHTML('beforeend',
                            `<p class="mt-2 text-sm text-red-400">Nama Pelanggan Tidak Boleh Kosong!</p>`
                        );
                        e.preventDefault();
                    }
                    if (!merk) {
                        document.getElementById('field-merk').insertAdjacentHTML('beforeend',
                            `<p class="mt-2 text-sm text-red-400">Merk Tidak Boleh Kosong!</p>`);
                        e.preventDefault();
                    }
                    if (!tipe) {
                        document.getElementById('field-tipe').insertAdjacentHTML('beforeend',
                            `<p class="mt-2 text-sm text-red-400">Tipe Tidak Boleh Kosong!</p>`);
                        e.preventDefault();
                    }
                    if (!no_pol) {
                        document.getElementById('field-no-pol').insertAdjacentHTML('beforeend',
                            `<p class="mt-2 text-sm text-red-400">Nomor Polisi Tidak Boleh Kosong!</p>`);
                        e.preventDefault();
                    }
                } else if (customer_vehicle_id === '' && !customer_form_active) {
                    document.getElementById('field-customer_vehicle_id').insertAdjacentHTML('beforeend',
                        `<p class="mt-2 text-sm text-red-400">Pelanggan Tidak Boleh Kosong!</p>`);
                    e.preventDefault();
                }

                $('input[name="total_sparepart"]').val(originalNumber($('input[name="total_sparepart"]')
                    .val()));
                $('input[name="total_jasa"]').val(originalNumber($('input[name="total_jasa"]').val()));
                $('input[name="total"]').val(originalNumber($('input[name="total"]').val()));
                $('input[name="total_diskon_item"]').val(originalNumber($('input[name="total_diskon_item"]')
                    .val()));


            });

            // Set default date to today
            document.getElementById('service_at').value = new Date().toISOString().slice(0, 16);


            var table = $('#product-table-list').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                columnDefs: [{
                    width: '5%',
                    targets: 0,
                }, {
                    width: '30%',
                    targets: 1,
                }, {
                    width: '10%',
                    targets: [3, 2],
                }, {
                    width: '5%',
                    targets: 4,
                }],
                ajax: {
                    url: "{{ route('api.product.list') }}",
                    data: function(d) {
                        d.tipe = tipe;
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
                        data: 'grade',
                        name: 'grade',
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

            var tableCustomer = $('#customer-table-list').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                columnDefs: [{
                    width: '20%',
                    targets: 1,
                }, {
                    width: '5%',
                    targets: 6,
                }, {
                    width: '10%',
                    targets: 3,
                }],
                ajax: {
                    url: "{{ route('customer-vehicle-search-table') }}",

                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'address',
                        name: 'address',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'kendaraan',
                        name: 'kendaraan',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'no_pol',
                        name: 'no_pol',
                        className: 'px-4 py-1',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'px-4 py-1'
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
            $('#modal-select-customer').click(function(e) {
                e.preventDefault();
                tableCustomer.draw();
                modalSelectCustomer.classList.remove('hidden');
            });
            $('input[name="customer_vehicle_name"]').click(function(e) {
                e.preventDefault();
                tableCustomer.draw();
                modalSelectCustomer.classList.remove('hidden');
            });
            $('#cancel-customer-selection').click(function(e) {
                e.preventDefault();
                modalSelectCustomer.classList.add('hidden');
            });

            $(document).on('click', '.select-customer', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');
                handleCustomerSelect(id);
                $('input[name="customer_vehicle_name"]').val(name);
                $('input[name="customer_vehicle_id"]').val(id);
                modalSelectCustomer.classList.add('hidden');
            })

            const handleCustomerSelect = (id) => {
                const detailContainer = document.getElementById('customer-vehicle-detail-container');
                if (id) {
                    fetch(`${base_url}/api/customer_vehicles/${id}/details`)
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

                                // Update vehicle details
                                document.getElementById('vehicle-merk').textContent = data.vehicle
                                    .merk || '-';
                                document.getElementById('vehicle-type').textContent = data.vehicle
                                    .tipe || '-';
                                document.getElementById('vehicle-plate').textContent = data.vehicle
                                    .no_pol || '-';
                                document.getElementById('vehicle-year').textContent = data.vehicle
                                    .year || '-';

                                detailContainer.classList.remove('hidden');

                                // Auto-fill form if exists
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
            }

            document.getElementById('confirm-selection').addEventListener('click', function() {
                const checkboxes = document.querySelectorAll(
                    '#product-list input[type="checkbox"]:checked');
                checkboxes.forEach(checkbox => {
                    const productId = checkbox.value;
                    const productRow = checkbox.closest('tr');
                    const kategori = productRow.querySelector('.tipe').value;
                    const quantity = productRow.querySelector('.qty').value;

                    const productName = productRow.querySelector('td:nth-child(2)').textContent;
                    const grade = productRow.querySelector('td:nth-child(3)').textContent;
                    const productPrice = productRow.querySelector('td:nth-child(4)').textContent;

                    const buying_price = productRow.querySelector('.buying_price').value;
                    console.log('harga belii', buying_price);

                    // console.log(originalNumber(productPrice));
                    if (!selectedProducts.includes(productId)) {
                        selectedProducts.push(productId);
                        addItemRow(kategori, productName, grade, productId, originalNumber(
                            productPrice), quantity, originalNumber(
                            buying_price));
                    }
                });

                document.getElementById('product-selection-modal').classList.add('hidden');
                resetSelection();
            });

            // Close product selection modal
            document.getElementById('cancel-selection').addEventListener('click', function() {
                modalSelectProduct.classList.add('hidden');
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
