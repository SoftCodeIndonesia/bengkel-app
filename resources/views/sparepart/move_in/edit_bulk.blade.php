@extends('layouts.dashboard')
@section('title', 'Edit Pergerakan Barang')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden border border-gray-600">
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
            <form action="{{ route('movement-items.update_bulk') }}" method="POST">
                @csrf
                <button type="button" id="btn-check-all"
                    class=" text-white mb-3 bg-green-700 hover:bg-green-600 px-4 py-2 rounded-lg flex items-center border border-green-600">
                    Checklist Semua
                </button>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatables-index">
                        <thead class="uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Produk</th>
                                <th class="p-3">Qty Beli</th>
                                <th class="p-3">Qty Diterima</th>
                                <th class="p-3">Harga Beli</th>
                                <th class="p-3">Total</th>


                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($items as $item)
                                <tr class="row-item">
                                    <td class="px-3 py-1">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-1">{{ $item->product->name }}</td>
                                    <td class="px-3 py-1">{{ $item->est_quantity }}</td>
                                    <td class="px-3 py-1" width="200px">
                                        <input type="hidden" name="id[{{ $loop->iteration }}]" value="{{ $item->id }}">
                                        <input type="hidden" name="est_quantity[{{ $loop->iteration }}]"
                                            value="{{ $item->est_quantity }}" class="est_quantity">
                                        <div class="relative w-full">
                                            <input type="number" name="quantity[{{ $loop->iteration }}]"
                                                class="block quantity w-full z-20 text-sm text-gray-50 bg-gray-700 rounded-lg rounded-s-gray-100 rounded-s-2 border border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                                                value="{{ old('items[' . $loop->index . '][quantity_fulfilled]', 0) }}"
                                                min="0" max="{{ $item->est_quantity }}" required />
                                            <button type="button" data-index="{{ $loop->index }}"
                                                class="absolute fill_all top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <svg class="w-4 h-4 text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd"
                                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                    </td>
                                    <td class="px-3 py-1">{{ number_format($item->buying_price, 0, ',', '.') }}</td>
                                    <td class="px-3 py-1">{{ number_format($item->total_price, 0, ',', '.') }}</td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            const checkAll = document.getElementById('btn-check-all');
            const estQuantityInput = document.getElementById('est_quantity');
            const statusSelect = document.getElementById('status');
            const form = document.querySelector('form');

            checkAll.addEventListener('click', function(e) {
                e.preventDefault();

                document.querySelectorAll('.row-item').forEach(row => {

                    const fieldQuantity = row.querySelector('.quantity');
                    const estQuantity = row.querySelector('.est_quantity').value;

                    fieldQuantity.value = estQuantity;
                });
            })

            document.querySelectorAll('.row-item').forEach(row => {
                const buttonCheckAll = row.querySelector('.fill_all');


                const fieldQuantity = row.querySelector('.quantity');
                const estQuantity = row.querySelector('.est_quantity').value;

                buttonCheckAll.addEventListener('click', function() {
                    fieldQuantity.value = estQuantity;
                })
            });

            $('input[name="quantity"]').keyup(function(e) {
                const quantity = $(this).val();

                if (parseInt(quantity) > estQuantityInput.value) {
                    $(this).val(estQuantityInput.value);
                }
            });

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
