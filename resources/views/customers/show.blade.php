@extends('layouts.dashboard')

@section('title', 'Detail Pelanggan')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <!-- Header Section -->
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Pelanggan</h2>
            <div class="flex gap-2">
                <a href="{{ route('customers.edit', $customer->id) }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('customers.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-white">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Email</label>
                            <p class="mt-1 text-sm text-white">{{ $customer->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Nomor Telepon</label>
                            <p class="mt-1 text-sm text-white">{{ $customer->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Alamat</label>
                            <p class="mt-1 text-sm text-white">{{ $customer->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Vehicles -->
                <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-white">Kendaraan</h3>
                        <a href="{{ route('vehicles.create', ['customer_id' => $customer->id]) }}"
                            class="text-sm text-blue-400 hover:text-blue-300">
                            Tambah Kendaraan
                        </a>
                    </div>

                    @if ($customer->vehicles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-600">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Merk/Tipe</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            No. Polisi</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-700 divide-y divide-gray-600">
                                    @foreach ($customer->vehicles as $vehicle)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                                {{ $vehicle->merk }} {{ $vehicle->tipe }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                                {{ $vehicle->no_pol }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                                        class="text-blue-400 hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <form
                                                        action="{{ route('customer-vehicles.destroy', $customer->vehicles->find($vehicle->id)->pivot->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            onclick="return confirm('Hapus kendaraan dari pelanggan ini?')"
                                                            class="text-red-400 hover:text-red-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-400">
                            Belum ada kendaraan terdaftar
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order History -->
            <div class="mt-6 bg-gray-700 p-6 rounded-lg border border-gray-600">
                <h3 class="text-lg font-medium text-white mb-4">Riwayat Order Servis</h3>

                @if ($customer->jobOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600">
                            <thead class="bg-gray-600">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        ID Order</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Kendaraan</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-700 divide-y divide-gray-600">
                                @foreach ($customer->jobOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                            {{ $order->unique_id }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                            {{ $order->customerVehicle->vehicle->merk }}
                                            ({{ $order->customerVehicle->vehicle->no_pol }})
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                            @php
                                                $statusColors = [
                                                    'new' => 'bg-yellow-600 text-yellow-300',
                                                    'draft' => 'bg-gray-600 text-gray-300',
                                                    'progress' => 'bg-blue-600 text-blue-100',
                                                    'completed' => 'bg-green-600 text-green-100',
                                                    'cancelled' => 'bg-red-600 text-red-100',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                            <a href="{{ route('job-orders.show', $order->id) }}"
                                                class="text-blue-400 hover:text-blue-300">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-gray-400">
                        Belum ada riwayat order servis
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
