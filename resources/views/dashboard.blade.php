@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Dashboard</h2>
        </div>

        <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card: Today's Job Orders -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Job Order Hari Ini</p>
                        <h3 class="text-white text-2xl font-bold">{{ $todayJobOrders }}</h3>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('job-orders.index') }}" class="text-blue-400 text-sm hover:underline">Lihat semua</a>
                </div>
            </div>

            <!-- Card: Today's Sales -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Penjualan Hari Ini</p>
                        <h3 class="text-white text-2xl font-bold">{{ $todaySales }}</h3>
                    </div>
                    <div class="bg-green-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('sales.index') }}" class="text-blue-400 text-sm hover:underline">Lihat semua</a>
                </div>
            </div>

            <!-- Card: Today's Job Order Income -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Job Order</p>
                        <h3 class="text-white text-2xl font-bold">Rp {{ number_format($todayJobOrderIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="bg-yellow-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('invoices.index') }}" class="text-blue-400 text-sm hover:underline">Lihat invoice</a>
                </div>
            </div>

            <!-- Card: Today's Sales Income -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Penjualan</p>
                        <h3 class="text-white text-2xl font-bold">Rp {{ number_format($todaySalesIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('invoices.index') }}" class="text-blue-400 text-sm hover:underline">Lihat invoice</a>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="bg-gray-700 rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Follow Up Kendaraan</h3>
                    <p class="text-gray-400 text-sm">Kendaraan yang terakhir service lebih dari 3 bulan lalu</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-600">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Pelanggan</th>
                                <th class="px-4 py-3">Kendaraan</th>
                                <th class="px-4 py-3">No. Polisi</th>
                                <th class="px-4 py-3">Terakhir Service</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($followUpVehicles as $index => $vehicle)
                                <tr class="border-b border-gray-600 hover:bg-gray-600">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-white">
                                        {{ $vehicle->customerVehicle->customer->name }}<br>
                                        <span
                                            class="text-xs text-gray-400">{{ $vehicle->customerVehicle->customer->phone }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $vehicle->customerVehicle->vehicle->merk }}
                                        {{ $vehicle->customerVehicle->vehicle->tipe }}
                                    </td>
                                    <td class="px-4 py-3">{{ $vehicle->customerVehicle->vehicle->no_pol }}</td>
                                    <td class="px-4 py-3">
                                        {{ $vehicle->service_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <button
                                            onclick="openFollowUpModal({{ $vehicle->customer_vehicle_id }}, {{ $vehicle->id }})"
                                            data-jo="{{ $vehicle->id }}"
                                            class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-sm">
                                            Follow Up
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fast & Slow Moving Products -->
        <div class="p-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Fast Moving Products -->
            <div class="bg-gray-700 rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Fast Moving Products</h3>
                    <p class="text-gray-400 text-sm">Produk dengan penjualan tertinggi bulan ini</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-600">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-right">Terjual</th>
                                <th class="px-4 py-3 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fastMovingProducts as $product)
                                <tr class="border-b border-gray-600 hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-white">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->total_sold }}</td>
                                    <td class="px-4 py-3 text-right">Rp
                                        {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Slow Moving Products -->
            <div class="bg-gray-700 rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Slow Moving Products</h3>
                    <p class="text-gray-400 text-sm">Produk dengan penjualan terendah bulan ini</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-600">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-right">Stok</th>
                                <th class="px-4 py-3 text-right">Min. Stok</th>
                                <th class="px-4 py-3 text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slowMovingProducts as $product)
                                <tr class="border-b border-gray-600 hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-white">{{ $product->name }}</td>
                                    <td
                                        class="px-4 py-3 text-right {{ $product->stok < $product->min_stock ? 'text-red-400' : '' }}">
                                        {{ $product->stok }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->min_stock }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->total_sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="followUpModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b border-gray-600 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Follow Up Pelanggan</h3>
                <button onclick="closeFollowUpModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="followUpForm" method="POST" action="{{ route('follow-ups.store') }}" class="p-4">
                @csrf
                <input type="hidden" name="order_id" id="job_order_id">
                <input type="hidden" name="customer_vehicle_id" id="modal_vehicle_id">
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-2">Tanggal Follow Up</label>
                    <input type="date" name="contact_date"
                        class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeFollowUpModal()"
                        class="text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg mr-2">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function openFollowUpModal(vehicleId, jo_id) {
            document.getElementById('modal_vehicle_id').value = vehicleId;
            document.getElementById('followUpModal').classList.remove('hidden');
            document.getElementById('job_order_id').value = jo_id;
        }

        function closeFollowUpModal() {
            document.getElementById('followUpModal').classList.add('hidden');
        }
    </script>
@endpush
