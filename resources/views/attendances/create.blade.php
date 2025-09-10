@extends('layouts.dashboard')

@section('title', 'Buat Absensi Karyawan')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">{{ $attendance ? 'Edit' : 'Tambah' }} Data Absensi</h2>
            <a href="{{ route('attendances.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-4">
                <form method="POST"
                    action="{{ $attendance ? route('attendances.update', $attendance->id) : route('attendances.store') }}">
                    @csrf
                    @if ($attendance)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-300 mb-1">Tanggal</label>
                            <input type="date" id="date" name="date"
                                value="{{ old('date', $attendance?->date?->format('Y-m-d')) }}" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatables-index">
                            <thead class="uppercase bg-gray-700 text-gray-400">
                                <tr>
                                    <th class="p-3">No</th>
                                    <th class="p-3">Karyawan</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Check In</th>
                                    <th class="p-3">Check Out</th>
                                    <th class="p-3">Catatan</th>
                                </tr>
                            </thead>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="px-3 py-1">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-1">{{ $employee->name }}</td>
                                    <td class="px-3 py-1">
                                        <input type="hidden" name="employee_id[{{ $loop->iteration }}]"
                                            value="{{ $employee->id }}">

                                        <select id="status" name="status[{{ $loop->iteration }}]" required
                                            class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('status.' . $loop->iteration) border-red-500 @enderror">
                                            <option value="present"
                                                {{ old('status.' . $loop->iteration, $attendance?->status) == 'present' ? 'selected' : '' }}>
                                                Hadir</option>
                                            <option value="late"
                                                {{ old('status.' . $loop->iteration, $attendance?->status) == 'late' ? 'selected' : '' }}>
                                                Terlambat</option>
                                            <option value="absent"
                                                {{ old('status.' . $loop->iteration, $attendance?->status) == 'absent' ? 'selected' : '' }}>
                                                Tidak Hadir (Tanpa Keterangan)</option>
                                            <option value="permit"
                                                {{ old('status.' . $loop->iteration, $attendance?->status) == 'permit' ? 'selected' : '' }}>
                                                Izin</option>
                                            <option value="leave"
                                                {{ old('status.' . $loop->iteration, $attendance?->status) == 'leave' ? 'selected' : '' }}>
                                                Cuti</option>
                                        </select>
                                        @error('status.' . $loop->iteration)
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-3 py-1">
                                        <input type="time" name="check_in[{{ $loop->iteration }}]"
                                            value="{{ old('check_in.' . $loop->iteration) }}"
                                            class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('check_in.' . $loop->iteration) border-red-500 @enderror">
                                        @error('check_in.' . $loop->iteration)
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-3 py-1">
                                        <input type="time" name="check_out[{{ $loop->iteration }}]"
                                            value="{{ old('check_out.' . $loop->iteration) }}"
                                            class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('check_out.' . $loop->iteration) border-red-500 @enderror">
                                        @error('check_out.' . $loop->iteration)
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-3 py-1">
                                        <input type="text" name="notes[{{ $loop->iteration }}]"
                                            value="{{ old('notes.' . $loop->iteration) }}"
                                            placeholder="Masukan catatan (jika ada)"
                                            class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('notes.' . $loop->iteration) border-red-500 @enderror">
                                        @error('notes.' . $loop->iteration)
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </table>
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
