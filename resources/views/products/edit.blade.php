@extends('layouts.dashboard')
@section('title', 'Edit Sparepart')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Edit Sparepart</h2>
            <a href="{{ route('products.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Sparepart <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-300 mb-2">Harga Satuan <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="unit_price" id="unit_price"
                            value="{{ old('unit_price', $product->unit_price) }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('unit_price') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required min="0">
                        @error('unit_price')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-300 mb-2">Stok <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('stok') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required min="0">
                        @error('stok')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('products.index') }}"
                        class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Update
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

            // Format ke Rupiah saat input
            priceInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.value = formatRupiah(value);
            });

            // Format ke angka saat submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                priceInput.value = priceInput.value.replace(/\D/g, '');
            });

            // Fungsi format Rupiah
            function formatRupiah(angka) {
                if (!angka) return '';

                angka = parseInt(angka, 10);
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Format nilai awal jika ada (untuk edit)
            if (priceInput.value) {
                priceInput.value = formatRupiah(priceInput.value);
            }
        });
    </script>
@endpush
