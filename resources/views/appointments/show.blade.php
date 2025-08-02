@extends('layouts.dashboard')

@section('title', 'Appointment Detail')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Appointment Detail</h2>
            <div class="flex space-x-2">
                <a href="{{ route('appointments.edit', $appointment->id) }}"
                    class="text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
                <button type="button" data-id="{{ $appointment->id }}" data-name="{{ $appointment->customer->name }}"
                    class="delete-appointments text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Hapus
                </button>
                <a href="{{ route('appointments.index') }}"
                    class="text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Customer Information -->
                <div class="bg-gray-700 rounded-lg p-4 shadow">
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-600 pb-2">Information Pelanggan
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-400">Name</p>
                            <p class="text-white">{{ $appointment->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Phone</p>
                            <p class="text-white">{{ $appointment->customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Email</p>
                            <p class="text-white">{{ $appointment->customer->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Address</p>
                            <p class="text-white">{{ $appointment->customer->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="bg-gray-700 rounded-lg p-4 shadow">
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-600 pb-2">Information Kendaraan
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-400">Merke</p>
                            <p class="text-white">{{ $appointment->vehicle->merk }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Tipe</p>
                            <p class="text-white">{{ $appointment->vehicle->tipe }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Nomor Polisi</p>
                            <p class="text-white">{{ $appointment->vehicle->no_pol }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="bg-gray-700 rounded-lg p-4 shadow mb-6">
                <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-600 pb-2">Appointment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-400">Waktu & Tanggal</p>
                        <p class="text-white">{{ $appointment->date }}</p>
                    </div>
                    <div>
                        <p class="text-sm mb-1 text-gray-400">Status</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500',
                                'confirmed' => 'bg-blue-500',
                                'completed' => 'bg-green-500',
                                'cancelled' => 'bg-red-500',
                            ];
                        @endphp
                        <span
                            class="px-3 py-1 rounded-full text-xs {{ $statusColors[$appointment->status] ?? 'bg-gray-500' }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-400">Service Request</p>
                        <p class="text-white">{{ $appointment->service_request }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-400">Catatan</p>
                        <p class="text-white">{{ $appointment->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Created Information -->
            <div class="bg-gray-700 rounded-lg p-4 shadow">
                <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-600 pb-2">System Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-400">Created By</p>
                        <p class="text-white">{{ $appointment->createdBy->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Created At</p>
                        <p class="text-white">{{ $appointment->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Last Updated</p>
                        <p class="text-white">{{ $appointment->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.delete-appointments', function() {
            const salesId = $(this).data('id');
            const joName = $(this).data('name');

            Swal.fire({
                title: 'Hapus Appointments?',
                html: `Anda yakin ingin menghapus appointment <strong>${joName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form delete secara dinamis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/appointments/${salesId}`;

                    // Tambahkan CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');
                    form.appendChild(csrfToken);

                    // Tambahkan method spoofing
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endpush
