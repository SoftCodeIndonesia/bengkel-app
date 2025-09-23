@extends('layouts.dashboard')

@section('title', 'Detail Invoice')
@section('content')

    <div class="bg-gray-800 shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <div>
                <h2 class="text-xl font-semibold text-white">Invoice #{{ $invoice->unique_id }}</h2>
                <p class="text-gray-400 text-sm">
                    {{ $invoice->created_at->format('d M Y H:i') }} |
                    Status:
                    <span class="{{ $invoice->status === 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                        {{ strtoupper($invoice->status) }}
                    </span>
                </p>
            </div>
            <div class="flex space-x-2">

                @if ($invoice->status === 'unpaid')
                    <button id="to-paid-button"
                        class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Tandai sebagai Lunas
                    </button>
                @endif
                <a href="{{ route('invoice-print', $invoice->id) }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Informasi Invoice</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-400">Nomor:</span>
                            <span class="text-white ml-2">{{ $invoice->unique_id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Tanggal:</span>
                            <span class="text-white ml-2">{{ $invoice->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Jatuh Tempo:</span>
                            <span class="text-white ml-2">{{ $invoice->due_date?->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Referensi:</span>
                            <span class="text-white ml-2">{{ $invoice->reference->unique_id ?? 'Tidak ada referensi' }}
                                ({{ $invoice->tipe }})</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Informasi Pelanggan</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-400">Nama:</span>
                            <span class="text-white ml-2">{{ $invoice->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Alamat:</span>
                            <span class="text-white ml-2">{{ $invoice->customer_address }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Status Pembayaran</h3>
                    <div class="flex items-center">
                        <span
                            class="text-2xl font-bold {{ $invoice->status === 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                            {{ strtoupper($invoice->status) }}
                        </span>

                    </div>
                </div>
            </div>

            <div class="my-6">
                <h3 class="text-lg font-medium text-gray-300 mb-3">Item Invoice</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-600 text-gray-300">
                            <tr>
                                <th class="py-3 px-4 text-left">Produk/Jasa</th>
                                <th class="py-3 px-4 text-right">Grade</th>
                                <th class="py-3 px-4 text-right">Harga Satuan</th>
                                <th class="py-3 px-4 text-right">Jumlah</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @if ($invoice->tipe === 'sales')
                                @foreach ($invoice->reference->items ?? [] as $item)
                                    <tr>
                                        <td class="py-3 px-4 text-white">{{ $item->product->name }}</td>
                                        <td class="py-3 px-4 text-right text-white">Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-white">{{ $item->quantity }}</td>
                                        <td class="py-3 px-4 text-right text-white">Rp
                                            {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($invoice->reference->orderItems as $item)
                                    <tr>
                                        <td class="py-3 px-4 text-white">{{ $item->product->name }}
                                            ({{ $item->product->tipe === 'barang' ? 'Sparepart' : 'Jasa' }})
                                        </td>
                                        <td class="py-3 px-4 text-right text-white">
                                            @php
                                                $clases = [
                                                    'Genuine' => 'bg-green-500 text-white',
                                                    'OEM 1' => 'bg-yellow-500 text-white',
                                                    'OEM 2' => 'bg-red-500 text-white',
                                                    '-' => 'bg-blue-500 text-white',
                                                ];
                                            @endphp
                                            <span
                                                class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm {{ $clases[$item->product->grade ?? '-'] }}">{{ $item->product->grade ?? '-' }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-right text-white">Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-white">{{ $item->quantity }}</td>
                                        <td class="py-3 px-4 text-right text-white">Rp
                                            {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 my-6 gap-6">
                <div class="md:col-span-2"></div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-300">Subtotal:</span>
                        <span class="text-white font-medium">Rp
                            {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($invoice->diskon_unit)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-300">Diskon
                                ({{ $invoice->diskon_unit === 'percentage' ? $invoice->diskon_value . '%' : 'Nominal' }}):</span>
                            <span class="text-white font-medium">
                                @if ($invoice->diskon_unit === 'percentage')
                                    -Rp
                                    {{ number_format($invoice->subtotal * ($invoice->diskon_value / 100), 0, ',', '.') }}
                                @else
                                    -Rp {{ number_format($invoice->diskon_value, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    @endif
                    <div class="flex justify-between py-2 border-t border-gray-600">
                        <span class="text-gray-300 font-semibold">Total:</span>
                        <span class="text-white font-bold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#to-paid-button', function() {
            const invoice = @json($invoice);

            Swal.fire({
                title: 'Invoice Lunas?',
                html: `Anda yakin ingin mengubah status invoice <strong>${invoice.unique_id}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Lunas!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form delete secara dinamis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/invoices/status/${invoice.id}`;

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
                    methodInput.value = 'PUT';

                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = 'paid';

                    form.appendChild(statusInput);
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endpush
