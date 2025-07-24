@extends('layouts.dashboard')

@section('title', 'Edit Supplier')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Edit Supplier</h2>
        </div>

        <div class="p-4">
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="name" class="block text-gray-300 mb-2">Nama Supplier</label>
                        <input type="text" name="name" id="name" required
                            class="w-full dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('name', $supplier->name) }}" placeholder="Masukan nama supplier">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-gray-300 mb-2">Telepon</label>
                        <input type="text" name="phone" id="phone" required
                            class="w-full dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('phone', $supplier->phone) }}" placeholder="Masukan Nomor Telepon supplier">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('email', $supplier->email) }}" placeholder="Masukan email supplier">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-gray-300 mb-2">Alamat</label>
                    <textarea name="address" id="address" rows="3" required
                        class="w-full dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('suppliers.index') }}"
                        class="bg-gray-600 hover:dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 text-white px-6 py-2 rounded-md">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
