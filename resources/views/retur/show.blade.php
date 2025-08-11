@extends('layouts.dashboard')

@section('title', 'Detail Retur Sparepart')
@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Detail Retur Sparepart</h2>
            <a href="{{ route('returns.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-400">No. Supply</h3>
                    <p class="mt-1 text-white">SPL-{{ $returnItem->supply_id }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Produk</h3>
                    <p class="mt-1 text-white">{{ $returnItem->product->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Quantity</h3>
                    <p class="mt-1 text-white">{{ $returnItem->quantity }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Harga Satuan</h3>
                    <p class="mt-1 text-white">{{ number_format($returnItem->unit_price, 0, ',', '.') }}</p>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-400">Alasan Retur</h3>
                    <p class="mt-1 text-white">{{ $returnItem->reason }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Status</h3>
                    <p class="mt-1">
                        @if ($returnItem->status == 'pending')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-600 text-white">
                                Pending
                            </span>
                        @elseif($returnItem->status == 'approved')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-600 text-white">
                                Disetujui
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-600 text-white">
                                Ditolak
                            </span>
                        @endif
                    </p>
                </div>
                @if ($returnItem->processed_at)
                    <div>
                        <h3 class="text-sm font-medium text-gray-400">Diproses Oleh</h3>
                        <p class="mt-1 text-white">{{ $returnItem->processedBy->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-400">Waktu Proses</h3>
                        <p class="mt-1 text-white">{{ $returnItem->processed_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>

            @if (auth()->user()->can('approve-returns') && $returnItem->status == 'pending')
                <div class="pt-4 border-t border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-3">Proses Retur</h3>
                    <form action="{{ route('returns.update-status', $returnItem->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                                <textarea id="notes" name="notes" rows="2"
                                    class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" name="status" value="approved"
                                    class="w-full text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg">
                                    Setujui
                                </button>
                                <button type="submit" name="status" value="rejected"
                                    class="w-full text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg">
                                    Tolak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
