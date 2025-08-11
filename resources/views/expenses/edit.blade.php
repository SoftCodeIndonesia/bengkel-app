@extends('layouts.dashboard')

@section('title', 'Edit Pengeluaran')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Form Pengeluaran</h2>
        </div>

        <div class="p-4">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="date" class="block text-gray-400 mb-2">Tanggal</label>
                        <input type="date" name="date" id="date" required
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('date', $expense->date->format('yy-m-d')) }}">
                    </div>

                    <div>
                        <label for="expense_category_id" class="block text-gray-400 mb-2">Kategori</label>
                        <select name="expense_category_id" id="expense_category_id" required
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-gray-400 mb-2">Jumlah</label>
                    <input type="number" step="0.01" name="amount" id="amount" required
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('amount', $expense->amount) }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="payment_method" class="block text-gray-400 mb-2">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" required
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="cash"
                                {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Tunai
                            </option>
                            <option value="bank_transfer"
                                {{ old('payment_method', $expense->payment_method) == 'bank_transfer' ? 'selected' : '' }}>
                                Transfer Bank</option>
                            <option value="credit"
                                {{ old('payment_method', $expense->payment_method) == 'credit' ? 'selected' : '' }}>Kredit
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="invoice_number" class="block text-gray-400 mb-2">Nomor Invoice (opsional)</label>
                        <input type="text" name="invoice_number" id="invoice_number"
                            class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('invoice_number', $expense->invoice_number) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-400 mb-2">Deskripsi (opsional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $expense->description) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
