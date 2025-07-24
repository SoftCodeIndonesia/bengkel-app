@extends('layouts.dashboard')

@section('title', 'Buat Invoice')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">
                Buat Invoice dari {{ $type === 'sales' ? 'Penjualan' : 'Service' }}
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipe" value="{{ $type }}">
                <input type="hidden" name="reference_id" value="{{ $reference->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Nomor {{ $type === 'sales' ? 'Penjualan' : 'Service' }}
                        </label>
                        <input type="text" class="w-full bg-gray-700 border border-gray-600 text-white rounded-md p-2"
                            value="{{ $reference->unique_id }}" readonly>
                    </div>

                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Pelanggan <span class="text-red-500">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" required
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-md p-2">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ $reference->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">
                            Jatuh Tempo
                        </label>
                        <input type="text" name="due_date" id="due_date"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-md p-2">
                    </div>

                    <div>
                        <label for="diskon_unit" class="block text-sm font-medium text-gray-300 mb-2">
                            Jenis Diskon
                        </label>
                        <select name="diskon_unit" id="diskon_unit"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-md p-2">
                            <option value="">Tidak ada diskon</option>
                            <option value="percentage">Persentase (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                    </div>

                    <div>
                        <label for="diskon_value" class="block text-sm font-medium text-gray-300 mb-2">
                            Nilai Diskon
                        </label>
                        <input type="number" name="diskon_value" id="diskon_value" min="0" value="0"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-md p-2">
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Subtotal:</span>
                        <span id="subtotal-display" class="text-white">
                            Rp {{ number_format($reference->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Diskon:</span>
                        <span id="diskon-display" class="text-white">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-medium">
                        <span class="text-gray-300">Total:</span>
                        <span id="total-display" class="text-blue-400">
                            Rp {{ number_format($reference->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <input type="hidden" name="subtotal" id="subtotal" value="{{ $reference->subtotal }}">
                <input type="hidden" name="total" id="total" value="{{ $reference->total }}">

                <div class="flex justify-end space-x-4">
                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 text-gray-300 bg-gray-600 hover:bg-gray-500 rounded-lg">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Buat Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi datepicker
            flatpickr("#due_date", {
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                minDate: "today",
                defaultDate: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000), // 7 hari dari sekarang
                locale: "id"
            });

            // Hitung ulang saat diskon berubah
            document.getElementById('diskon_unit').addEventListener('change', calculateTotal);
            document.getElementById('diskon_value').addEventListener('input', calculateTotal);

            function calculateTotal() {
                const subtotal = parseFloat(document.getElementById('subtotal').value);
                const diskonUnit = document.getElementById('diskon_unit').value;
                const diskonValue = parseFloat(document.getElementById('diskon_value').value) || 0;

                let diskon = 0;
                if (diskonUnit === 'percentage') {
                    diskon = subtotal * (diskonValue / 100);
                } else if (diskonUnit === 'nominal') {
                    diskon = diskonValue;
                }

                const total = subtotal - diskon;

                document.getElementById('diskon-display').textContent =
                    diskonUnit === 'percentage' ?
                    diskonValue + '%' : 'Rp ' + new Intl.NumberFormat('id-ID').format(diskon);

                document.getElementById('total-display').textContent =
                    'Rp ' + new Intl.NumberFormat('id-ID').format(total);

                document.getElementById('total').value = total;
            }
        });
    </script>
@endpush
