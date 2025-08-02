@extends('layouts.dashboard')

@section('title', 'Detail Estimasi')
@php
    use App\Models\JobOrder;
@endphp
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Estimasi: {{ $jobOrder->unique_id }}</h2>
            <div class="flex space-x-2">

                <a href="{{ route('to-job-order', $jobOrder->id) }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center ">
                    Buat Job Order
                </a>


                <a href="{{ route('estimation.edit', $jobOrder->id) }}"
                    class="text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg flex items-center ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>

                <a href="{{ route('estimation.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Job Order</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-400">ID Job Order</p>
                            <p class="text-white">{{ $jobOrder->unique_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Tanggal Service</p>
                            <p class="text-white">{{ $jobOrder->service_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Kilometer</p>
                            <p class="text-white">{{ number_format($jobOrder->km, 0, ',', '.') }} km</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Status</p>
                            @php
                                $statusClasses = [
                                    'estimation' => 'bg-yellow-500',
                                    'progress' => 'bg-green-500',
                                ];
                                $statusText = [
                                    'estimation' => 'Estimasi',
                                    'progress' => 'Progress',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusClasses[$jobOrder->status] }}">
                                {{ $statusText[$jobOrder->status] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Pelanggan & Kendaraan</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-400">Nama Pelanggan</p>
                            <p class="text-white">{{ $jobOrder->customerVehicle->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Telepon</p>
                            <p class="text-white">{{ $jobOrder->customerVehicle->customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Alamat</p>
                            <p class="text-white">{{ $jobOrder->customerVehicle->customer->address }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Kendaraan</p>
                            <p class="text-white">
                                {{ $jobOrder->customerVehicle->vehicle->merk }}
                                {{ $jobOrder->customerVehicle->vehicle->tipe }}
                                ({{ $jobOrder->customerVehicle->vehicle->no_pol }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white">Breakdown Pemeriksaan</h3>
                    <button id="delete-selected-breakdowns"
                        class="hidden bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                        Hapus Breakdown Terpilih
                    </button>
                </div>

                @if ($jobOrder->breakdowns->count() > 0)
                    <form id="delete-breakdowns-form" action="{{ route('job-orders.delete-breakdowns', $jobOrder->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <ul class="space-y-2 pl-4">
                            @foreach ($jobOrder->breakdowns as $breakdown)
                                <li class="text-white flex items-center">
                                    <input type="checkbox" name="breakdowns[]" value="{{ $breakdown->id }}"
                                        class="breakdown-checkbox mr-3 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    {{ $breakdown->name }}
                                </li>
                            @endforeach
                        </ul>
                    </form>
                @else
                    <p class="text-gray-400">Tidak ada data breakdown pemeriksaan</p>
                @endif
            </div>

            <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white">Sparepart/Jasa</h3>
                    <button id="delete-selected"
                        class="hidden bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                        Hapus Item Terpilih
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <form id="delete-items-form" action="{{ route('job-orders.delete-items', $jobOrder->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs uppercase bg-gray-600 text-gray-300">
                                <tr>
                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" id="select-all"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </th>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Sparepart/Jasa</th>
                                    <th class="px-4 py-3 text-right">FRT/QTY</th>
                                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                    <th class="px-4 py-3 text-right">Diskon</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobOrder->orderItems as $item)
                                    <tr class="border-b border-gray-600">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="items[]" value="{{ $item->id }}"
                                                class="item-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">{{ $item->product->name }}</td>
                                        <td class="px-4 py-3 text-right">{{ $item->quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-right">Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right">Rp
                                            {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right">Rp
                                            {{ number_format($item->unit_price * $item->quantity * ($item->diskon_value / 100), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right">Rp
                                            {{ number_format($item->price_after_diskon, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 mb-6">
                <h3 class="text-lg font-medium text-white mb-4">Rincian Biaya</h3>
                <div class="overflow-x-auto">
                    <div class="flex justify-between mb-2 items-center">
                        <span class="text-gray-300">Subtotal:</span>
                        <span id="subtotal" class="text-gray-300">Rp
                            {{ number_format($jobOrder->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Diskon:</span>
                        @if ($jobOrder->diskon_unit == 'percentage')
                            <span id="subtotal" class="text-gray-300">({{ $jobOrder->diskon_value }}%)</span>
                        @else
                            <span id="subtotal" class="text-gray-300">Rp
                                {{ number_format($jobOrder->diskon_value, 2, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-lg font-medium">
                        <span class="text-gray-300">Total:</span>
                        <span id="total" class="text-blue-400">Rp
                            {{ number_format($jobOrder->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <form action="{{ route('job-orders.destroy', $jobOrder->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg flex items-center"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus job order ini?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Status update confirmation
        $('.btn-update-status').click(function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Ubah Status?',
                text: "Pastikan status yang dipilih sudah sesuai.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, ubah',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Checkbox functionality
        $(document).ready(function() {
            // Select all checkbox
            $('#select-all').change(function() {
                $('.item-checkbox').prop('checked', $(this).prop('checked'));
                toggleDeleteButton();
            });

            // Individual checkbox
            $('.item-checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#select-all').prop('checked', false);
                }
                toggleDeleteButton();
            });

            // Toggle delete button visibility
            function toggleDeleteButton() {
                const anyChecked = $('.item-checkbox:checked').length > 0;
                $('#delete-selected').toggleClass('hidden', !anyChecked);
            }

            // Delete selected items
            $('#delete-selected').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus Item Terpilih?',
                    text: "Item yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    background: '#1f2937',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-items-form').submit();
                    }
                });
            });

            function toggleBreakdownDeleteButton() {
                const anyChecked = $('.breakdown-checkbox:checked').length > 0;
                $('#delete-selected-breakdowns').toggleClass('hidden', !anyChecked);
            }

            // Breakdown checkbox change event
            $('.breakdown-checkbox').change(function() {
                toggleBreakdownDeleteButton();
            });

            // Delete selected breakdowns
            $('#delete-selected-breakdowns').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus Breakdown Terpilih?',
                    text: "Breakdown yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    background: '#1f2937',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-breakdowns-form').submit();
                    }
                });
            });
        });
    </script>
@endpush
