@extends('layouts.dashboard')
@section('title', 'Edit Pergerakan Barang')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Check Barang Masuk</h2>
            <a href="{{ route('movement-items.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('movement-items.update', $movementItem->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Produk --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Produk</label>
                        <input type="text"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3"
                            value="{{ $movementItem->product->name }}" readonly>
                    </div>

                    {{-- Tipe Pergerakan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipe Pergerakan</label>
                        <input type="text"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3"
                            value="{{ $movementItem->move == 'in' ? 'Masuk' : 'Keluar' }}" readonly>
                    </div>

                    {{-- Quantity Awal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Quantity Real</label>
                        <input type="number"
                            class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3"
                            value="{{ old('quantity', 0) }}" name="quantity">
                    </div>

                    {{-- Estimasi Quantity --}}
                    <div>
                        <label for="est_quantity" class="block text-sm font-medium text-gray-300 mb-2">Estimasi Quantity
                            <span class="text-red-500">*</span></label>
                        <input type="number" name="est_quantity" id="est_quantity"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('est_quantity') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3"
                            value="{{ $movementItem->est_quantity }}" readonly min="0">
                        @error('est_quantity')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('status') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3"
                            required>
                            <option value="draft" {{ old('status', $movementItem->status) == 'draft' ? 'selected' : '' }}>
                                Draft</option>
                            <option value="pending"
                                {{ old('status', $movementItem->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="done" {{ old('status', $movementItem->status) == 'done' ? 'selected' : '' }}>
                                Done</option>
                            <option value="cancel"
                                {{ old('status', $movementItem->status) == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="md:col-span-2">
                        <label for="note" class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                        <textarea name="note" id="note" rows="3"
                            class="mt-1 block w-full bg-gray-700 border {{ $errors->has('note') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3">{{ old('note', $movementItem->note) }}</textarea>
                        @error('note')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('movement-items.index') }}"
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
            // Validasi client-side untuk estimasi quantity
            const estQuantityInput = document.getElementById('est_quantity');
            const statusSelect = document.getElementById('status');
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                if (statusSelect.value === 'done' && estQuantityInput.value <= 0) {
                    e.preventDefault();
                    alert('Estimasi quantity harus lebih dari 0 ketika status Done');
                    estQuantityInput.focus();
                }
            });
        });
    </script>
@endpush
