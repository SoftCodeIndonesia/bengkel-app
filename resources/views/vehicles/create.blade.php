@extends('layouts.dashboard')
@section('title', 'Data Kendaraan')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Kendaraan Baru</h2>
            <a href="{{ route('vehicles.index') }}"
                class="text-gray-300 bg-gray-700 dark:placeholder-gray-400 dark:text-white hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Merk Field -->
                    <div>
                        <label for="merk" class="block text-sm font-medium text-gray-300 mb-2">Merk <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="merk" id="merk" value="{{ old('merk') }}"
                            class="mt-1 block w-full bg-gray-700 dark:placeholder-gray-400 dark:text-white  border {{ $errors->has('merk') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Contoh: Toyota" required>
                        @error('merk')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Field -->
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-300 mb-2">Tipe <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="tipe" id="tipe" value="{{ old('tipe') }}"
                            class="mt-1 block w-full bg-gray-700 dark:placeholder-gray-400 dark:text-white border {{ $errors->has('tipe') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required placeholder="Contoh: Avanza">
                        @error('tipe')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No Polisi Field -->
                    <div>
                        <label for="no_pol" class="block text-sm font-medium text-gray-300 mb-2">Nomor Polisi <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="no_pol" id="no_pol" value="{{ old('no_pol') }}"
                            class="mt-1 block w-full bg-gray-700 dark:placeholder-gray-400 dark:text-white border {{ $errors->has('no_pol') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required placeholder="Contoh: B1234ABC">
                        @error('no_pol')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer Field (Autocomplete) -->
                    <div>
                        @include('vehicles.partials.customer_select')
                        @error('customer_id')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('vehicles.index') }}"
                        class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
