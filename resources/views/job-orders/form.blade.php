<div class="bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b border-gray-600">
        <h2 class="text-xl font-semibold text-white">{{ $title }}</h2>
    </div>

    <div class="p-4">
        <form method="POST" action="{{ $action }}">
            @csrf
            @if (isset($jobOrder))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer & Vehicle Selection -->
                <div class="col-span-2">
                    <label for="customer_vehicle_id" class="block text-sm font-medium text-gray-300 mb-1">Pelanggan &
                        Kendaraan</label>
                    <select id="customer_vehicle_id" name="customer_vehicle_id" required
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Pilih Pelanggan & Kendaraan</option>
                        @foreach ($customerVehicles as $cv)
                            <option value="{{ $cv->id }}" @if (isset($jobOrder) && $jobOrder->customer_vehicle_id == $cv->id) selected @endif>
                                {{ $cv->customer->name }} - {{ $cv->vehicle->merk }} {{ $cv->vehicle->tipe }}
                                ({{ $cv->vehicle->no_pol }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Service Details -->
                <div>
                    <label for="km" class="block text-sm font-medium text-gray-300 mb-1">Kilometer</label>
                    <input type="number" step="0.01" id="km" name="km" required
                        value="{{ isset($jobOrder) ? $jobOrder->km : old('km') }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="service_at" class="block text-sm font-medium text-gray-300 mb-1">Tanggal Service</label>
                    <input type="datetime-local" id="service_at" name="service_at" required
                        value="{{ isset($jobOrder) ? $jobOrder->service_at->format('Y-m-d\TH:i') : old('service_at', now()->format('Y-m-d\TH:i')) }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                    <select id="status" name="status" required
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="estimation" @if (isset($jobOrder) && $jobOrder->status == 'estimation') selected @endif>Estimasi</option>
                        <option value="draft" @if (isset($jobOrder) && $jobOrder->status == 'draft') selected @endif>Draft</option>
                        <option value="progress" @if (isset($jobOrder) && $jobOrder->status == 'progress') selected @endif>Progress</option>
                        <option value="completed" @if (isset($jobOrder) && $jobOrder->status == 'completed') selected @endif>Selesai</option>
                        <option value="cancelled" @if (isset($jobOrder) && $jobOrder->status == 'cancelled') selected @endif>Batal</option>
                    </select>
                </div>
            </div>

            <!-- Breakdowns Section -->
            <div class="mt-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium text-gray-300">Kerusakan/Keluhan</h3>
                    <button type="button" id="add-breakdown"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah
                    </button>
                </div>

                <div id="breakdowns-container" class="space-y-2">
                    @if (isset($jobOrder) && $jobOrder->breakdowns->count() > 0)
                        @foreach ($jobOrder->breakdowns as $index => $breakdown)
                            <div class="flex items-center breakdown-item">
                                <input type="text" name="breakdowns[{{ $index }}][name]"
                                    value="{{ $breakdown->name }}"
                                    class="flex-1 bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                    placeholder="Deskripsi kerusakan/keluhan">
                                <button type="button" class="remove-breakdown ml-2 text-red-500 hover:text-red-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Items Section -->
            <div class="mt-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium text-gray-300">Items (Sparepart/Jasa)</h3>
                    <button type="button" id="add-item"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah
                    </button>
                </div>

                <div id="items-container" class="space-y-2">
                    @if (isset($jobOrder) && $jobOrder->items->count() > 0)
                        @foreach ($jobOrder->items as $index => $item)
                            <div class="grid grid-cols-12 gap-2 item-row">
                                <div class="col-span-5">
                                    <select name="items[{{ $index }}][product_id]" required
                                        class="product-select bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">Pilih Item</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-price="{{ $product->unit_price }}"
                                                @if ($item->product_id == $product->id) selected @endif>
                                                {{ $product->name }}
                                                ({{ $product->tipe == 'barang' ? 'Sparepart' : 'Jasa' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" step="0.01" min="1"
                                        name="items[{{ $index }}][quantity]" required
                                        value="{{ $item->quantity }}"
                                        class="quantity-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="col-span-3">
                                    <input type="text" readonly
                                        value="Rp {{ number_format($item->unit_price, 0, ',', '.') }}"
                                        class="price-display bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <input type="text" readonly
                                        value="Rp {{ number_format($item->total_price, 0, ',', '.') }}"
                                        class="total-price-display bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <button type="button" class="remove-item ml-2 text-red-500 hover:text-red-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Discount & Total Section -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="diskon_unit" class="block text-sm font-medium text-gray-300 mb-1">Tipe Diskon</label>
                    <select id="diskon_unit" name="diskon_unit"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Tanpa Diskon</option>
                        <option value="percentage" @if (isset($jobOrder) && $jobOrder->diskon_unit == 'percentage') selected @endif>Persentase (%)
                        </option>
                        <option value="nominal" @if (isset($jobOrder) && $jobOrder->diskon_unit == 'nominal') selected @endif>Nominal (Rp)
                        </option>
                    </select>
                </div>
                <div>
                    <label for="diskon_value" class="block text-sm font-medium text-gray-300 mb-1">Nilai
                        Diskon</label>
                    <input type="number" step="0.01" min="0" id="diskon_value" name="diskon_value"
                        value="{{ isset($jobOrder) ? $jobOrder->diskon_value : old('diskon_value', 0) }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Total</label>
                    <input type="text" id="total-display" readonly
                        value="Rp {{ isset($jobOrder) ? number_format($jobOrder->total, 0, ',', '.') : '0' }}"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('job-orders.index') }}"
                    class="mr-4 text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg">
                    Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add Breakdown
            document.getElementById('add-breakdown').addEventListener('click', function() {
                const container = document.getElementById('breakdowns-container');
                const index = container.querySelectorAll('.breakdown-item').length;

                const div = document.createElement('div');
                div.className = 'flex items-center breakdown-item';
                div.innerHTML = `
                            <input type="text" name="breakdowns[${index}][name]" 
                                class="flex-1 bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                placeholder="Deskripsi kerusakan/keluhan">
                            <button type="button" class="remove-breakdown ml-2 text-red-500 hover:text-red-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        `;

                container.appendChild(div);
            });

            // Remove Breakdown
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-breakdown')) {
                    e.target.closest('.breakdown-item').remove();
                    reindexBreakdowns();
                }
            });

            function reindexBreakdowns() {
                const container = document.getElementById('breakdowns-container');
                const items = container.querySelectorAll('.breakdown-item');

                items.forEach((item, index) => {
                    const input = item.querySelector('input');
                    input.name = `breakdowns[${index}][name]`;
                });
            }

            // Add Item
            document.getElementById('add-item').addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const index = container.querySelectorAll('.item-row').length;

                const div = document.createElement('div');
                div.className = 'grid grid-cols-12 gap-2 item-row';
                div.innerHTML = `
                <div class="col-span-5">
                    <select name="items[${index}][product_id]" required
                        class="product-select bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Pilih Item</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">
                                {{ $product->name }} ({{ $product->tipe == 'barang' ? 'Sparepart' : 'Jasa' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <input type="number" step="0.01" min="1" name="items[${index}][quantity]" required value="1"
                        class="quantity-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div class="col-span-3">
                    <input type="text" readonly value="Rp 0"
                        class="price-display bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div class="col-span-2 flex items-center">
                    <input type="text" readonly value="Rp 0"
                        class="total-price-display bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <button type="button" class="remove-item ml-2 text-red-500 hover:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

                container.appendChild(div);
            });

            // Remove Item
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.item-row').remove();
                    reindexItems();
                    calculateTotal();
                }
            });

            function reindexItems() {
                const container = document.getElementById('items-container');
                const rows = container.querySelectorAll('.item-row');

                rows.forEach((row, index) => {
                    const productSelect = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');

                    productSelect.name = `items[${index}][product_id]`;
                    quantityInput.name = `items[${index}][quantity]`;
                });
            }

            // Product selection change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select')) {
                    const row = e.target.closest('.item-row');
                    const priceDisplay = row.querySelector('.price-display');
                    const totalDisplay = row.querySelector('.total-price-display');
                    const quantityInput = row.querySelector('.quantity-input');
                    const selectedOption = e.target.options[e.target.selectedIndex];

                    if (selectedOption.value) {
                        const price = parseFloat(selectedOption.getAttribute('data-price'));
                        const quantity = parseFloat(quantityInput.value) || 1;

                        priceDisplay.value = 'Rp ' + price.toLocaleString('id-ID');
                        totalDisplay.value = 'Rp ' + (price * quantity).toLocaleString('id-ID');
                    } else {
                        priceDisplay.value = 'Rp 0';
                        totalDisplay.value = 'Rp 0';
                    }

                    calculateTotal();
                }
            });

            // Quantity input change
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const row = e.target.closest('.item-row');
                    const productSelect = row.querySelector('.product-select');
                    const totalDisplay = row.querySelector('.total-price-display');
                    const selectedOption = productSelect.options[productSelect.selectedIndex];

                    if (selectedOption.value) {
                        const price = parseFloat(selectedOption.getAttribute('data-price'));
                        const quantity = parseFloat(e.target.value) || 0;

                        totalDisplay.value = 'Rp ' + (price * quantity).toLocaleString('id-ID');
                        calculateTotal();
                    }
                }
            });

            // Discount change
            document.getElementById('diskon_unit').addEventListener('change', calculateTotal);
            document
                .getElementById('diskon_value').addEventListener('input', calculateTotal);

            // Calculate total
            function calculateTotal() {
                let subtotal = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const totalDisplay = row.querySelector('.total-price-display');
                    const totalValue = totalDisplay.value.replace('Rp ', '').replace(/\./g, '');
                    subtotal += parseFloat(totalValue) || 0;
                });

                const diskonUnit = document.getElementById('diskon_unit').value;
                const diskonValue = parseFloat(document.getElementById('diskon_value').value) || 0;

                let total = subtotal;

                if (diskonUnit === 'percentage' && diskonValue > 0) {
                    total = subtotal - (subtotal * diskonValue / 100);
                } else if (diskonUnit === 'nominal' && diskonValue > 0) {
                    total = subtotal - diskonValue;
                }

                document.getElementById('total-display').value = 'Rp ' + Math.max(0, total).toLocaleString(
                    'id-ID');
            }
        });
    </script>
@endpush
