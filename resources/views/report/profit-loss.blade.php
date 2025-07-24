@extends('layouts.dashboard')

@section('title', 'Laporan Laba Rugi')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Laporan Laba Rugi</h2>
            <div class="flex items-center space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-3 py-2">
                    <span class="text-gray-400">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-3 py-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Filter
                    </button>
                </form>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    PDF
                </a>
            </div>
        </div>

        <div class="p-4">
            <!-- Ringkasan Laba Rugi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Pendapatan -->
                <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                    <h3 class="text-lg font-medium text-gray-300 mb-2">Total Pendapatan</h3>
                    <p class="text-2xl font-bold text-green-500">Rp {{ number_format($totalIncome, 2) }}</p>
                    <div class="mt-2 text-sm text-gray-400">
                        <p>Job Order: Rp {{ number_format($jobOrderIncome, 2) }}</p>
                        <p>Penjualan: Rp {{ number_format($salesIncome, 2) }}</p>
                    </div>
                </div>

                <!-- Total Pengeluaran -->
                <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                    <h3 class="text-lg font-medium text-gray-300 mb-2">Total Pengeluaran</h3>
                    <p class="text-2xl font-bold text-red-500">Rp {{ number_format($totalExpenses, 2) }}</p>
                    <div class="mt-2 text-sm text-gray-400">
                        <p>Pembelian: Rp {{ number_format($purchaseExpenses, 2) }}</p>
                        <p>Operasional: Rp {{ number_format($operationalExpenses, 2) }}</p>
                    </div>
                </div>

                <!-- Laba/Rugi -->
                <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                    <h3 class="text-lg font-medium text-gray-300 mb-2">Laba/Rugi</h3>
                    <p class="text-2xl font-bold {{ $profitLoss >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        Rp {{ number_format(abs($profitLoss), 2) }}
                    </p>
                    <p class="text-sm text-gray-400 mt-1">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </p>
                </div>
            </div>

            <!-- Grafik Laba Rugi -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 mb-6">
                <h3 class="text-lg font-medium text-gray-300 mb-4">Trend Laba Rugi</h3>
                <canvas id="profitLossChart" height="100"></canvas>
            </div>

            <!-- Detail Pendapatan -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 mb-6">
                <h3 class="text-lg font-medium text-gray-300 mb-4">Detail Pendapatan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-800 text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Sumber</th>
                                <th class="px-6 py-3 text-right">Jumlah</th>
                                <th class="px-6 py-3">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-4">Job Order</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($jobOrderIncome, 2) }}</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-600 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: {{ $totalIncome > 0 ? ($jobOrderIncome / $totalIncome) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs">{{ $totalIncome > 0 ? number_format(($jobOrderIncome / $totalIncome) * 100, 2) : 0 }}%</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-4">Penjualan</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($salesIncome, 2) }}</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-600 rounded-full h-2.5">
                                        <div class="bg-green-600 h-2.5 rounded-full"
                                            style="width: {{ $totalIncome > 0 ? ($salesIncome / $totalIncome) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs">{{ $totalIncome > 0 ? number_format(($salesIncome / $totalIncome) * 100, 2) : 0 }}%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Pengeluaran -->
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                <h3 class="text-lg font-medium text-gray-300 mb-4">Detail Pengeluaran</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-800 text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3 text-right">Jumlah</th>
                                <th class="px-6 py-3">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-4">Pembelian Sparepart</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($purchaseExpenses, 2) }}</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-600 rounded-full h-2.5">
                                        <div class="bg-yellow-600 h-2.5 rounded-full"
                                            style="width: {{ $totalExpenses > 0 ? ($purchaseExpenses / $totalExpenses) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs">{{ $totalExpenses > 0 ? number_format(($purchaseExpenses / $totalExpenses) * 100, 2) : 0 }}%</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-4">Operasional</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($operationalExpenses, 2) }}</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-600 rounded-full h-2.5">
                                        <div class="bg-red-600 h-2.5 rounded-full"
                                            style="width: {{ $totalExpenses > 0 ? ($operationalExpenses / $totalExpenses) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs">{{ $totalExpenses > 0 ? number_format(($operationalExpenses / $totalExpenses) * 100, 2) : 0 }}%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('profitLossChart').getContext('2d');

            // Data untuk chart (contoh, bisa diganti dengan data dinamis dari controller)
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels ?? []) !!},
                    datasets: [{
                            label: 'Pendapatan',
                            data: {!! json_encode($incomeData ?? []) !!},
                            borderColor: 'rgba(34, 197, 94, 1)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($expenseData ?? []) !!},
                            borderColor: 'rgba(239, 68, 68, 1)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Laba/Rugi',
                            data: {!! json_encode($profitData ?? []) !!},
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#f3f4f6'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.raw
                                        .toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(75, 85, 99, 0.5)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(75, 85, 99, 0.5)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
@endpush
