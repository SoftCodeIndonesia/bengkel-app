@extends('layouts.dashboard')
@section('title', 'Tambah Sparepart')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Sparepart Baru</h2>
            <a href="{{ route('products.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('products.store') }}" method="POST" id="form-create">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Sparepart --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Sparepart <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3"
                            required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Part Number --}}
                    <div>
                        <label for="part_number" class="block text-sm font-medium text-gray-300 mb-2">Nomor Part</label>
                        <input type="text" name="part_number" id="part_number" value="{{ old('part_number') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('part_number') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3"
                            placeholder="Contoh: PN-001">
                        @error('part_number')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Beli --}}
                    <div>
                        <label for="buying_price" class="block text-sm font-medium text-gray-300 mb-2">Harga Beli</label>
                        <input type="text" name="buying_price" id="buying_price" value="{{ old('buying_price') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('buying_price') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3">
                        @error('buying_price')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Margin --}}
                    <div>
                        <label for="margin" class="block text-sm font-medium text-gray-300 mb-2">Margin (%)</label>
                        <input type="number" name="margin" id="margin" value="{{ old('margin') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('margin') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3"
                            step="0.01" min="0">
                        @error('margin')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Satuan --}}
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-300 mb-2">Harga Jual <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="unit_price" id="unit_price" value="{{ old('unit_price') }}"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('unit_price') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3"
                            required readonly>
                        @error('unit_price')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipe --}}
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-300 mb-2">Tipe Produk <span
                                class="text-red-500">*</span></label>
                        <select name="tipe" id="tipe"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('tipe') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3">
                            <option value="part" {{ old('tipe') == 'part' ? 'selected' : '' }}>Part</option>
                            <option value="oli" {{ old('tipe') == 'oli' ? 'selected' : '' }}>Oli</option>
                            <option value="material" {{ old('tipe') == 'material' ? 'selected' : '' }}>Material</option>
                        </select>
                        @error('tipe')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('products.index') }}"
                        class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buyingInput = document.getElementById('buying_price');
            const marginInput = document.getElementById('margin');
            const priceInput = document.getElementById('unit_price');

            // Format ke Rupiah saat input
            buyingInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.value = formatRupiah(value);
            });

            // Format ke Rupiah saat input
            priceInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.value = formatRupiah(value);
            });

            // Format ke angka saat submit
            const form = document.querySelector('form-create');
            form.addEventListener('submit', function(e) {
                priceInput.value = priceInput.value.replace(/\D/g, '');
                buyingInput.value = buyingInput.value.replace(/\D/g, '');
            });

            // Fungsi format Rupiah
            function formatRupiah(angka) {
                if (!angka) return '';

                angka = parseFloat(angka);
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Format nilai awal jika ada (untuk edit)
            if (priceInput.value) {
                priceInput.value = formatRupiah(priceInput.value);
            }



            function hitungHargaJual() {
                const beli = parseFloat(buyingInput.value.replace(/\D/g, '')) || 0;
                const margin = parseFloat(marginInput.value) || 0;
                const jual = beli + (beli * (margin / 100));
                priceInput.value = formatRupiah(Math.round(jual)); // atau jual.toFixed(0) jika ingin string
            }

            buyingInput.addEventListener('input', hitungHargaJual);
            marginInput.addEventListener('input', hitungHargaJual);
        });
    </script>
@endpush
