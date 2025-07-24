@extends('layouts.dashboard')

@section('title', 'Tambah Pelanggan Baru')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Pelanggan Baru</h2>
            <a href="{{ route('customers.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Nama lengkap pelanggan">
                            @error('name')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="email@contoh.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div>
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-300">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="081234567890">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-300">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="3" required
                                class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Alamat lengkap pelanggan">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tambah Kendaraan Sekaligus (Opsional) -->
                <div class="mb-6 border-t border-gray-600 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-white">Tambah Kendaraan (Opsional)</h3>
                        <button type="button" id="add-vehicle-btn"
                            class="text-sm text-blue-400 hover:text-blue-300 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Kendaraan
                        </button>
                    </div>

                    <div id="vehicle-fields" class="space-y-4">
                        <!-- Vehicle fields akan ditambahkan dinamis di sini -->
                    </div>
                </div>
            </div>

            <!-- Footer Form -->
            <div class="px-4 py-3 bg-gray-700 text-right sm:px-6 border-t border-gray-600">
                <button type="reset"
                    class="inline-flex justify-center py-2 px-4 border border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    Reset
                </button>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Data Pelanggan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addVehicleBtn = document.getElementById('add-vehicle-btn');
                const vehicleFields = document.getElementById('vehicle-fields');
                let vehicleCount = 0;

                addVehicleBtn.addEventListener('click', function() {
                    vehicleCount++;
                    const fieldId = `vehicle-${vehicleCount}`;

                    const vehicleField = document.createElement('div');
                    vehicleField.className = 'bg-gray-700 p-4 rounded-lg border border-gray-600';
                    vehicleField.id = fieldId;
                    vehicleField.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="merk-${vehicleCount}" class="block text-sm font-medium text-gray-300">Merk</label>
                        <input type="text" name="vehicles[${vehicleCount}][merk]" id="merk-${vehicleCount}"
                            class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Contoh: Toyota">
                    </div>
                    <div>
                        <label for="tipe-${vehicleCount}" class="block text-sm font-medium text-gray-300">Tipe</label>
                        <input type="text" name="vehicles[${vehicleCount}][tipe]" id="tipe-${vehicleCount}"
                            class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Contoh: Avanza">
                    </div>
                    <div>
                        <label for="no_pol-${vehicleCount}" class="block text-sm font-medium text-gray-300">No. Polisi</label>
                        <div class="flex">
                            <input type="text" name="vehicles[${vehicleCount}][no_pol]" id="no_pol-${vehicleCount}"
                                class="mt-1 block w-full bg-gray-700 text-gray-400 border border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Contoh: B1234ABC">
                            <button type="button" onclick="document.getElementById('${fieldId}').remove()" class="ml-2 mt-1 text-red-400 hover:text-red-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

                    vehicleFields.appendChild(vehicleField);
                });
            });
        </script>
    @endpush
@endsection
