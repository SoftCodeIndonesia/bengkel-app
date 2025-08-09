@extends('layouts.dashboard')

@section('title', 'Detail Absensi')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Absensi</h2>
            <div class="flex space-x-2">
                <a href="{{ route('attendances.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Card Informasi Utama -->
            <div class="bg-gray-700 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kolom 1: Data Karyawan -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-300 mb-4 border-b border-gray-600 pb-2">Data Karyawan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-400">Nama</p>
                                <p class="text-white">{{ $attendance->employee->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Posisi</p>
                                <p class="text-white">{{ $attendance->employee->position }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2: Data Kehadiran -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-300 mb-4 border-b border-gray-600 pb-2">Data Kehadiran</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-400">Tanggal</p>
                                <p class="text-white">{{ $attendance->date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Status</p>


                                <span class="px-3 py-1 rounded-full text-sm {{ $attendance->statusColor() }} text-white">
                                    {{ ucfirst($attendance->statusText()) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 3: Waktu -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-300 mb-4 border-b border-gray-600 pb-2">Waktu</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-400">Check In</p>
                                <p class="text-white">
                                    {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Check Out</p>
                                <p class="text-white">
                                    {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Durasi Kerja</p>
                                <p class="text-white">
                                    @if ($attendance->check_in && $attendance->check_out)
                                        {{ $attendance->check_in->diff($attendance->check_out)->format('%H jam %I menit') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-300 mb-4 border-b border-gray-600 pb-2">Catatan</h3>
                <p class="text-white">
                    {{ $attendance->notes ?? 'Tidak ada catatan' }}
                </p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('attendances.edit', $attendance->id) }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
