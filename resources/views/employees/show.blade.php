<!-- resources/views/employees/show.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Detail Karyawan')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Detail Karyawan</h2>
            <div class="flex space-x-2">
                <a href="{{ route('employees.edit', $employee->id) }}"
                    class="px-3 py-1 text-sm text-white bg-yellow-600 hover:bg-yellow-500 rounded-md flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('employees.index') }}"
                    class="px-3 py-1 text-sm text-white bg-gray-600 hover:bg-gray-500 rounded-md flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Kolom Kiri - Foto Profil -->
                <div class="w-full md:w-1/3 lg:w-1/4 flex flex-col items-center">
                    <div class="w-40 h-40 rounded-full bg-gray-700 mb-4 overflow-hidden flex items-center justify-center">
                        @if ($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto Profil"
                                class="w-full h-full object-cover">
                        @else
                            <span class="text-5xl text-gray-400">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div class="text-center mt-4">
                        <h3 class="text-xl font-semibold text-white">{{ $employee->name }}</h3>
                        <p class="text-blue-400">{{ $employee->position }}</p>
                    </div>

                    <div class="mt-6 w-full space-y-3">
                        <div class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ $employee->email }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span>{{ $employee->phone }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>Bergabung: {{ $employee->hire_date }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan - Detail Informasi -->
                <div class="w-full md:w-2/3 lg:w-3/4">
                    <div class="bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-600 pb-2">Informasi Karyawan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Baris 1 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Nama Lengkap</label>
                                <p class="mt-1 text-white">{{ $employee->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Posisi/Jabatan</label>
                                <p class="mt-1 text-white">{{ $employee->position }}</p>
                            </div>

                            <!-- Baris 2 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Email</label>
                                <p class="mt-1 text-white">{{ $employee->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Telepon</label>
                                <p class="mt-1 text-white">{{ $employee->phone }}</p>
                            </div>

                            <!-- Baris 3 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Tanggal Mulai Bekerja</label>
                                <p class="mt-1 text-white">{{ $employee->hire_date }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Gaji</label>
                                <p class="mt-1 text-white">
                                    {{ $employee->salary ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : '-' }}
                                </p>
                            </div>

                            <!-- Baris 4 - Full Width -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-400">Alamat</label>
                                <p class="mt-1 text-white whitespace-pre-line">{{ $employee->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="bg-gray-700 rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-600 pb-2">Informasi Sistem
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Dibuat Pada</label>
                                <p class="mt-1 text-white">{{ $employee->created_at->translatedFormat('d F Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Terakhir Diupdate</label>
                                <p class="mt-1 text-white">{{ $employee->updated_at->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
