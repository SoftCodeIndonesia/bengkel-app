@extends('layouts.dashboard')

@section('title', 'Tambah Jasa')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Jasa Baru</h2>
            <a href="{{ route('services.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Jasa <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-300 mb-2">Harga <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="unit_price" id="unit_price" value="{{ old('unit_price') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('unit_price') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required placeholder="Rp 0">
                        @error('unit_price')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="frt" class="block text-sm font-medium text-gray-300 mb-2">FRT <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="stok" id="frt" value="{{ old('stok') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('stok') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required min="0" step="0.1">
                        @error('stok')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi <span
                                class="text-red-500">*</span></label>
                        <textarea type="text" name="description" id="description"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('services.index') }}"
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('unit_price');

            priceInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.value = formatRupiah(value);
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                priceInput.value = priceInput.value.replace(/\D/g, '');
            });

            function formatRupiah(angka) {
                if (!angka) return '';
                angka = parseInt(angka, 10);
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            if (priceInput.value && !isNaN(priceInput.value)) {
                priceInput.value = formatRupiah(priceInput.value);
            }
        });
    </script>
@endpush
