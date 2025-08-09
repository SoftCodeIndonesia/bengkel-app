@extends('layouts.dashboard')

@section('title', 'Buat Absensi Karyawan')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Absensi</h2>
        </div>


        <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-600">
                <h2 class="text-xl font-semibold text-white">{{ $attendance ? 'Edit' : 'Tambah' }} Data Absensi</h2>
            </div>

            <div class="p-4">
                <form method="POST"
                    action="{{ $attendance ? route('attendances.update', $attendance->id) : route('attendances.store') }}">
                    @csrf
                    @if ($attendance)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-300 mb-1">Karyawan</label>
                            <select id="employee_id" name="employee_id" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Pilih Karyawan</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('employee_id', $attendance?->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-300 mb-1">Tanggal</label>
                            <input type="date" id="date" name="date"
                                value="{{ old('date', $attendance?->date?->format('Y-m-d')) }}" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-300 mb-1">Check In</label>
                            <input type="time" id="check_in" name="check_in"
                                value="{{ old('check_in', $attendance?->check_in?->format('H:i')) }}"
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('check_in')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-300 mb-1">Check Out</label>
                            <input type="time" id="check_out" name="check_out"
                                value="{{ old('check_out', $attendance?->check_out?->format('H:i')) }}"
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('check_out')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                            <select id="status" name="status" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="present"
                                    {{ old('status', $attendance?->status) == 'present' ? 'selected' : '' }}>Hadir</option>
                                <option value="late"
                                    {{ old('status', $attendance?->status) == 'late' ? 'selected' : '' }}>
                                    Terlambat</option>
                                <option value="absent"
                                    {{ old('status', $attendance?->status) == 'absent' ? 'selected' : '' }}>
                                    Tidak Hadir (Tanpa Keterangan)</option>
                                <option value="permit"
                                    {{ old('status', $attendance?->status) == 'permit' ? 'selected' : '' }}>
                                    Izin</option>
                                <option value="leave"
                                    {{ old('status', $attendance?->status) == 'leave' ? 'selected' : '' }}>
                                    Cuti</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                            <input type="text" id="notes" name="notes"
                                value="{{ old('notes', $attendance?->notes) }}"
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('notes')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('attendances.index') }}"
                            class="mr-2 text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg">
                            Batal
                        </a>
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
