@extends('layouts.dashboard')

@section('title', 'Laporan Absensi Karyawan')
@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Laporan Absensi Karyawan</h2>
            <div class="flex space-x-2">
                <form method="GET" action="{{ route('attendances.report') }}" class="flex items-center">
                    <select name="month" id="month" onchange="this.form.submit()"
                        class="bg-gray-700 border border-gray-600 text-white rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}"
                                {{ $month == request('month', date('m')) ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" id="year" onchange="this.form.submit()"
                        class="bg-gray-700 border border-gray-600 text-white rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        @foreach (range(date('Y') - 5, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $year == request('year', date('Y')) ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('attendances.index') }}"
                    class="text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg">
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Nama Karyawan</th>
                        <th class="px-6 py-3 text-center">Hadir</th>
                        <th class="px-6 py-3 text-center">Terlambat</th>
                        <th class="px-6 py-3 text-center">Izin</th>
                        <th class="px-6 py-3 text-center">Tidak Hadir</th>
                        <th class="px-6 py-3 text-center">Total Hari Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        @php
                            $presentCount = $employee->attendances->where('status', 'present')->count();
                            $lateCount = $employee->attendances->where('status', 'late')->count();
                            $leaveCount = $employee->attendances->where('status', 'leave')->count();
                            $absentCount = $employee->attendances->where('status', 'absent')->count();
                            $totalWorkingDays = $presentCount + $lateCount;
                        @endphp
                        <tr class="border-b bg-gray-800 border-gray-700 hover:bg-gray-700">
                            <td class="px-6 py-4 font-medium whitespace-nowrap text-white">{{ $employee->name }}</td>
                            <td class="px-6 py-4 text-center">{{ $presentCount }}</td>
                            <td class="px-6 py-4 text-center">{{ $lateCount }}</td>
                            <td class="px-6 py-4 text-center">{{ $leaveCount }}</td>
                            <td class="px-6 py-4 text-center">{{ $absentCount }}</td>
                            <td class="px-6 py-4 text-center">{{ $totalWorkingDays }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
