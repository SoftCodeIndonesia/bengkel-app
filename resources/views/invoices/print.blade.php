<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->unique_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-800 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold">INVOICE</h1>
                <p class="text-gray-600">Bengkel Mobil Maju Jaya</p>
                <p class="text-gray-600">Jl. Raya No. 123, Jakarta</p>
                <p class="text-gray-600">Telp: 021-12345678</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-semibold">No. {{ $invoice->unique_id }}</p>
                <p class="text-gray-600">Tanggal: {{ $invoice->created_at->format('d/m/Y') }}</p>
                <p class="text-gray-600">Status:
                    <span
                        class="px-2 py-1 rounded-full text-xs {{ $invoice->status === 'paid' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                        {{ $invoice->status }}
                    </span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="border border-gray-200 p-4 rounded">
                <h2 class="text-lg font-semibold mb-2">Kepada:</h2>
                <p class="font-medium">{{ $invoice->customer_name }}</p>
                <p class="text-gray-600">{{ $invoice->customer_address }}</p>
            </div>

            <div class="border border-gray-200 p-4 rounded">
                <h2 class="text-lg font-semibold mb-2">Detail:</h2>
                <p class="text-gray-600">Tipe: {{ ucfirst($invoice->tipe) }}</p>
                <p class="text-gray-600">Referensi: {{ $invoice->reference_id }}</p>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-4">Detail Transaksi</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border border-gray-300 text-left">Deskripsi</th>
                        <th class="py-2 px-4 border border-gray-300 text-right">Harga Satuan</th>
                        <th class="py-2 px-4 border border-gray-300 text-right">Qty</th>
                        <th class="py-2 px-4 border border-gray-300 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $reference = $invoice->reference;
                    @endphp

                    @if ($invoice->tipe === 'sales')
                        @foreach ($reference->items as $item)
                            <tr>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->product->name }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right">Rp
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right">{{ $item->quantity }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right">Rp
                                    {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($reference->items as $item)
                            <tr>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->product->name }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right">Rp
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right">{{ $item->quantity }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-right">Rp
                                    {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <div class="w-full md:w-1/3">
                <div class="border-t-2 border-gray-300 pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Subtotal:</span>
                        <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($invoice->diskon_value > 0)
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Diskon:</span>
                            <span>- Rp {{ number_format($invoice->diskon_value, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold mt-4">
                        <span>Total:</span>
                        <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-gray-300">
            <div class="text-center">
                <p class="mb-4">Terima kasih atas kepercayaan Anda</p>
                <div class="mt-12">
                    <p class="font-medium">(__________________________)</p>
                    <p class="text-gray-600">Hormat kami,</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
