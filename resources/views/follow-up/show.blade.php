@extends('layouts.dashboard')

@section('title', 'Detail Follow Up')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Follow Up</h2>
            <div class="flex space-x-2">
                <a href="{{ route('follow-ups.edit', $followUp->id) }}"
                    class="text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('follow-ups.index') }}"
                    class="text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Utama -->
                <div class="bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Informasi Follow Up</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Status Kontak</label>
                            <p class="text-white">
                                @if ($followUp->contacted)
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-500 text-gray-800">Sudah
                                        Dihubungi</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-500 text-gray-800">Belum
                                        Dihubungi</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Tanggal Servis Terakhir</label>
                            <p class="text-white">{{ $followUp->last_service_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Tanggal Kontak</label>
                            <p class="text-white">
                                {{ $followUp->contact_date ? $followUp->contact_date->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Job Order Terkait</label>
                            <p class="text-white">
                                @if ($followUp->jobOrder)
                                    <a href="{{ route('job-orders.show', $followUp->jobOrder->id) }}"
                                        class="text-blue-400 hover:underline">
                                        {{ $followUp->jobOrder->unique_id }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kendaraan -->
                <div class="bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Informasi Kendaraan</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Nomor Polisi</label>
                            <p class="text-white">{{ $followUp->customerVehicle->vehicle->no_pol }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Model/Merek</label>
                            <p class="text-white">{{ $followUp->customerVehicle->vehicle->merk }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Tipe</label>
                            <p class="text-white">{{ $followUp->customerVehicle->vehicle->tipe }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Pemilik</label>
                            <p class="text-white">{{ $followUp->customerVehicle->customer->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mt-6 bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Catatan</h3>
                <div class="text-white">
                    {!! $followUp->notes ? nl2br(e($followUp->notes)) : '<span class="text-gray-400">Tidak ada catatan</span>' !!}
                </div>
            </div>
        </div>
    </div>
@endsection
