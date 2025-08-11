@extends('layouts.dashboard')

@section('title', 'Detail Stok Opname')

@push('styles')
    <style>
        /* Tambahkan sticky header untuk tabel panjang */
        #opname-items-table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #1f2937;
            /* bg-gray-800 */
        }

        /* Highligh untuk selisih besar */
        .large-difference {
            font-weight: bold;
        }

        /* Scrollbar styling */
        #opname-items-table::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        #opname-items-table::-webkit-scrollbar-track {
            background: #374151;
            /* bg-gray-700 */
        }

        #opname-items-table::-webkit-scrollbar-thumb {
            background: #4b5563;
            /* border-gray-600 */
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <!-- Header Section -->
        <div class="p-4 border-b border-gray-600">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <h2 class="text-xl font-semibold text-white">Detail Stok Opname</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('stock-opname.index') }}"
                        class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>

                </div>
            </div>

            <!-- Info Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-gray-700 p-3 rounded-lg border border-gray-600">
                    <p class="text-sm font-medium text-gray-300">Nomor Opname</p>
                    <p class="text-white font-semibold">{{ $stockOpname->opname_number }}</p>
                </div>
                <div class="bg-gray-700 p-3 rounded-lg border border-gray-600">
                    <p class="text-sm font-medium text-gray-300">Tanggal Opname</p>
                    <p class="text-white font-semibold">{{ $stockOpname->opname_date->format('d F Y') }}</p>
                </div>
                <div class="bg-gray-700 p-3 rounded-lg border border-gray-600">
                    <p class="text-sm font-medium text-gray-300">Dibuat Oleh</p>
                    <p class="text-white font-semibold">{{ $stockOpname->creator->name }}</p>
                </div>
            </div>

            <!-- Notes -->
            @if ($stockOpname->notes)
                <div class="bg-gray-700 p-3 rounded-lg border border-gray-600">
                    <p class="text-sm font-medium text-gray-300 mb-1">Catatan</p>
                    <p class="text-white">{{ $stockOpname->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Summary Stats -->
        <div class="p-4 m-4 border-b border-gray-600 bg-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-300">Total Item</p>
                    <p class="text-white font-semibold text-xl">{{ $stockOpname->items->count() }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-300">Item Lebih</p>
                    <p class="text-green-400 font-semibold text-xl">
                        {{ $stockOpname->items->where('difference', '>', 0)->count() }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-300">Item Kurang</p>
                    <p class="text-red-400 font-semibold text-xl">
                        {{ $stockOpname->items->where('difference', '<', 0)->count() }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-300">Total Selisih (Rp)</p>
                    <p class="text-white font-semibold text-xl">
                        {{ number_format($stockOpname->items->sum('total_difference'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-white">Item Stok Opname</h3>
                <div class="relative">
                    <input type="text" id="search-item" placeholder="Cari produk..."
                        class="bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 pl-10 text-white 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-600" id="opname-items-table">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">#
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stok
                                Sistem</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stok
                                Fisik</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Selisih</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Harga
                                Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total
                                Selisih</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-600" id="items-container">
                        @foreach ($stockOpname->items as $index => $item)
                            <tr class="hover:bg-gray-700 item-row"
                                data-product-name="{{ strtolower($item->product->name) }}">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-300">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-300">{{ $item->product->name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-300 text-center">{{ $item->system_stock }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-300 text-center">
                                    {{ $item->physical_stock }}</td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-center 
                                {{ abs($item->difference) >= 10 ? 'large-difference' : '' }}
                                {{ $item->difference > 0 ? 'text-green-400' : ($item->difference < 0 ? 'text-red-400' : 'text-gray-300') }}">
                                    {{ $item->difference }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-300 text-right">
                                    {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-right 
                                {{ abs($item->total_difference) >= 100000 ? 'large-difference' : '' }}
                                {{ $item->total_difference > 0 ? 'text-green-400' : ($item->total_difference < 0 ? 'text-red-400' : 'text-gray-300') }}">
                                    {{ number_format($item->total_difference, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-300 max-w-xs truncate"
                                    title="{{ $item->notes ?? '-' }}">
                                    {{ $item->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('search-item');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.item-row');

                rows.forEach(row => {
                    const productName = row.dataset.productName;
                    if (productName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Highlight large differences on hover
            const largeDiffCells = document.querySelectorAll('.large-difference');
            largeDiffCells.forEach(cell => {
                cell.addEventListener('mouseenter', function() {
                    this.parentElement.classList.add('bg-gray-700', 'bg-opacity-50');
                });
                cell.addEventListener('mouseleave', function() {
                    this.parentElement.classList.remove('bg-gray-700', 'bg-opacity-50');
                });
            });
        });
    </script>
@endpush
