@extends('layouts.dashboard')

@section('title', 'Reminder Work Order')

@section('content')
    <div class="">
        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-700 bg-red-900/40 p-4 text-sm text-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-700 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-white">Reminder Work Order</h1>
                    <p class="text-sm text-gray-400 mt-1">Pilih Work Order untuk dimasukkan ke antrean pengiriman reminder.</p>
                </div>
                <div class="text-sm text-gray-300">
                    Terpilih: <span id="selected-count" class="font-semibold text-white">0</span> / 100
                </div>
            </div>

            <form id="reminder-form" action="{{ route('reminders.send') }}" method="POST">
                @csrf
                <div class="p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-300">
                        <input id="select-current-page" type="checkbox" class="rounded border-gray-500 bg-gray-700 text-blue-600 focus:ring-blue-500">
                        Pilih semua di halaman ini
                    </label>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                        <button type="button" id="clear-selection" class="px-3 py-2 text-sm rounded-md bg-gray-700 text-gray-200 hover:bg-gray-600">
                            Kosongkan pilihan
                        </button>
                        <div class="relative">
                            <input type="hidden" name="queue_type" id="queue-type" value="">
                            <button type="button" id="send-reminders" disabled aria-haspopup="menu" aria-expanded="false" aria-controls="reminder-queue-menu"
                                class="px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Masukkan ke antrean <span aria-hidden="true">▾</span>
                            </button>
                            <div id="reminder-queue-menu" role="menu" class="hidden absolute right-0 z-20 mt-2 w-64 rounded-md border border-gray-600 bg-gray-800 py-1 shadow-lg">
                                <button type="button" role="menuitem" data-queue-type="after_service" class="block w-full px-4 py-2 text-left text-sm text-gray-200 hover:bg-gray-700">
                                    After Service
                                </button>
                                <button type="button" role="menuitem" data-queue-type="reminder" class="block w-full px-4 py-2 text-left text-sm text-gray-200 hover:bg-gray-700">
                                    Reminder
                                </button>
                                <button type="button" role="menuitem" data-queue-type="all" class="block w-full px-4 py-2 text-left text-sm text-gray-200 hover:bg-gray-700">
                                    Keduanya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-300">
                            <tr>
                                <th scope="col" class="px-4 py-3">Pilih</th>
                                <th scope="col" class="px-4 py-3">Nomor WO</th>
                                <th scope="col" class="px-4 py-3">Pelanggan</th>
                                <th scope="col" class="px-4 py-3">Kendaraan</th>
                                <th scope="col" class="px-4 py-3">Tanggal servis</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($jobOrders as $jobOrder)
                                @php
                                    $customerVehicle = $jobOrder->customerVehicle;
                                    $vehicle = $customerVehicle?->vehicle;
                                @endphp
                                <tr class="hover:bg-gray-700/50">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" class="job-order-checkbox rounded border-gray-500 bg-gray-700 text-blue-600 focus:ring-blue-500"
                                            value="{{ $jobOrder->id }}" aria-label="Pilih {{ $jobOrder->unique_id }}">
                                    </td>
                                    <td class="px-4 py-3 font-medium text-white whitespace-nowrap">{{ $jobOrder->unique_id }}</td>
                                    <td class="px-4 py-3">{{ $customerVehicle?->customer?->name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $vehicle ? $vehicle->merk . ' ' . $vehicle->tipe . ' · ' . $vehicle->no_pol : '—' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $jobOrder->service_at?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($jobOrder->status) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada Work Order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-700 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-400">Menampilkan {{ $jobOrders->firstItem() ?? 0 }}–{{ $jobOrders->lastItem() ?? 0 }} dari {{ $jobOrders->total() }} Work Order</p>
                    <div>{{ $jobOrders->links() }}</div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const storageKey = 'bengkel.reminder.job-order-selection';
            const checkboxes = [...document.querySelectorAll('.job-order-checkbox')];
            const selectedCount = document.getElementById('selected-count');
            const sendButton = document.getElementById('send-reminders');
            const queueMenu = document.getElementById('reminder-queue-menu');
            const queueType = document.getElementById('queue-type');
            const selectPage = document.getElementById('select-current-page');
            const form = document.getElementById('reminder-form');

            let selected = new Set();
            try {
                selected = new Set(JSON.parse(localStorage.getItem(storageKey) || '[]').map(String));
            } catch (_) {
                localStorage.removeItem(storageKey);
            }

            const save = () => localStorage.setItem(storageKey, JSON.stringify([...selected]));
            const refresh = () => {
                selectedCount.textContent = selected.size;
                sendButton.disabled = selected.size === 0 || selected.size > 100;
                selectPage.checked = checkboxes.length > 0 && checkboxes.every(box => selected.has(box.value));
                selectPage.indeterminate = checkboxes.some(box => selected.has(box.value)) && !selectPage.checked;
                checkboxes.forEach(box => {
                    box.checked = selected.has(box.value);
                    box.disabled = !box.checked && selected.size >= 100;
                });
                save();
            };

            checkboxes.forEach(box => box.addEventListener('change', () => {
                if (box.checked) selected.add(box.value);
                else selected.delete(box.value);
                refresh();
            }));

            selectPage.addEventListener('change', () => {
                checkboxes.forEach(box => {
                    if (selectPage.checked && (selected.has(box.value) || selected.size < 100)) selected.add(box.value);
                    if (!selectPage.checked) selected.delete(box.value);
                });
                refresh();
            });

            document.getElementById('clear-selection').addEventListener('click', () => {
                selected.clear();
                refresh();
            });

            sendButton.addEventListener('click', () => {
                const isOpen = !queueMenu.classList.contains('hidden');
                queueMenu.classList.toggle('hidden', isOpen);
                sendButton.setAttribute('aria-expanded', String(!isOpen));
            });

            queueMenu.querySelectorAll('[data-queue-type]').forEach(option => {
                option.addEventListener('click', () => {
                    queueType.value = option.dataset.queueType;
                    queueMenu.classList.add('hidden');
                    sendButton.setAttribute('aria-expanded', 'false');
                    form.requestSubmit();
                });
            });

            document.addEventListener('click', event => {
                if (!sendButton.contains(event.target) && !queueMenu.contains(event.target)) {
                    queueMenu.classList.add('hidden');
                    sendButton.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') {
                    queueMenu.classList.add('hidden');
                    sendButton.setAttribute('aria-expanded', 'false');
                }
            });

            form.addEventListener('submit', event => {
                if (selected.size === 0 || selected.size > 100) {
                    event.preventDefault();
                    return;
                }
                form.querySelectorAll('input[name="job_order_ids[]"]').forEach(input => input.remove());
                [...selected].forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'job_order_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
            });

            @if (session('reminder_queue_sent'))
                selected.clear();
                localStorage.removeItem(storageKey);
            @endif

            refresh();
        })();
    </script>
@endpush
