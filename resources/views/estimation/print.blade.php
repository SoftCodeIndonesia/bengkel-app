<x-app-layout>
    @push('styles')
        <style>
            @page {
                size: A4;
                /* Sets the page size to A4 */
                /* margin: 20mm; */
                /* Sets a 20mm margin on all sides */
            }

            body {
                display: flex;
                font-size: 15px !important;
            }
        </style>
    @endpush
    <div class="bg-white shadow overflow-hidden border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <p class="text-black">{{ $jobOrder->unique_id }}</p>
            <p>{{ $jobOrder->service_at->format('d M Y H:i') }}</p>
        </div>
        <div class="p-4 rounded-lg border-gray-600 mt-6">
            <div class="overflow-x-auto">
                <div class="flex justify-between mb-2 items-center">
                    <div class="flex-1 space-y-1">
                        <div>
                            <p class="text-sm text-gray-600">Nama Pelanggan</p>
                            <p class="text-black">{{ $jobOrder->customerVehicle->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Telepon</p>
                            <p class="text-black">{{ $jobOrder->customerVehicle->customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Alamat</p>
                            <p class="text-black">{{ $jobOrder->customerVehicle->customer->address }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Catatan</p>
                            <p class="text-black">{{ $jobOrder->notes ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex-1 space-y-1">
                        <div>
                            <p class="text-sm text-gray-600">Merk</p>
                            <p class="text-black">
                                {{ $jobOrder->customerVehicle->vehicle->merk }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tipe</p>
                            <p class="text-black">
                                {{ $jobOrder->customerVehicle->vehicle->tipe }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor Polisi</p>
                            <p class="text-black">
                                {{ $jobOrder->customerVehicle->vehicle->no_pol }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kilometer</p>
                            <p class="text-black">{{ $jobOrder->km }}</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="px-1">

            <div class="px-4 rounded-lg border-gray-600 mt-3">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-black">Deskripsi Kerusakan</h3>

                </div>

                @if ($jobOrder->breakdowns->count() > 0)
                    <form id="delete-breakdowns-form"
                        action="{{ route('job-orders.delete-breakdowns', $jobOrder->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <ul class="space-y-2 list-disc px-3">
                            @foreach ($jobOrder->breakdowns as $breakdown)
                                <li class="text-black">

                                    {{ $breakdown->name }}
                                </li>
                            @endforeach
                        </ul>
                    </form>
                @else
                    <p class="text-gray-400">Tidak ada data breakdown pemeriksaan</p>
                @endif
            </div>


            <div class="px-4 rounded-lg border-gray-600 mt-6">
                <div class="flex justify-between items-center">
                    <p>Sparepart & Jasa</p>

                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-700 border rounded-lg overflow-hidden"
                        style="width: 100%; table-layout: fixed;">
                        <thead class="bg-gray-600 border border-t text-gray-300">
                            <tr>

                                <th class="py-3" width="20px">No</th>
                                <th class="px-4 py-3 text-left" width="50%">Sparepart/Jasa</th>
                                <th class="px-4 py-3 text-right">FRT/QTY</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobOrder->orderItems as $item)
                                @if ($item->product->tipe != 'jasa')
                                    <tr class="border">

                                        <td class="py-1 ">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-1 " width="40%">{{ $item->product->name }}</td>
                                        <td class="px-4 py-1 text-right">{{ $item->quantity }}
                                        </td>
                                        <td class="px-4 py-1 text-right">
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class=" py-1 text-right">
                                            {{ number_format($item->total_price, 0, ',', '.') }}</td>

                                    </tr>
                                @endif
                            @endforeach
                            @foreach ($jobOrder->orderItems as $item)
                                @if ($item->product->tipe == 'jasa')
                                    <tr class="border">

                                        <td class="py-1 ">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-1 " width="40%">{{ $item->product->name }}</td>
                                        <td class="px-4 py-1 text-right">{{ $item->quantity }}
                                        </td>
                                        <td class="px-4 py-1 text-right">
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class=" py-1 text-right">
                                            {{ number_format($item->total_price, 0, ',', '.') }}</td>

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-4 rounded-lg border-gray-600 mt-6">
                <h3 class="text-lg font-medium text-black mb-4">Rincian Biaya</h3>
                <div class="overflow-x-auto">
                    <div class="flex justify-between mb-2 items-center">
                        <span class="text-gray-600">Subtotal:</span>
                        <span id="subtotal" class="text-gray-600">Rp
                            {{ number_format($jobOrder->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Diskon:</span>
                        @if ($jobOrder->diskon_unit == 'percentage')
                            <span id="subtotal" class="text-gray-600">({{ $jobOrder->diskon_value }}%)</span>
                        @else
                            <span id="subtotal" class="text-gray-600">Rp
                                {{ number_format($jobOrder->diskon_value, 2, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-lg font-medium">
                        <span class="text-gray-600">Total:</span>
                        <span id="total" class="text-black font-bold">Rp
                            {{ number_format($jobOrder->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-16 pt-8  border-gray-300" style="margin-top: 64px; padding-top: 32px;">
            <div class="text-center">
                <p class="mb-4">Pemilik</p>
                <div class="mt-12">
                    <p class="font-medium">(__________________________)</p>
                    <p class="text-gray-600">{{ $jobOrder->customerVehicle->customer->name }}</p>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.print();
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
</x-app-layout>
