<!-- resources/views/customer-vehicles/follow-ups.blade.php -->
<table class="min-w-full">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Kendaraan</th>
            <th>Terakhir Service</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->customer->name }}</td>
                <td>{{ $vehicle->merk }} {{ $vehicle->type }} ({{ $vehicle->no_pol }})</td>
                <td>
                    @if ($vehicle->latestJobOrder)
                        {{ $vehicle->latestJobOrder->service_at->format('d M Y') }}
                        ({{ $vehicle->latestJobOrder->service_at->diffForHumans() }})
                    @else
                        Belum pernah service
                    @endif
                </td>
                <td>
                    <a href="{{ route('job-orders.create', ['customer_vehicle_id' => $vehicle->id]) }}"
                        class="bg-blue-500 text-white px-3 py-1 rounded">
                        Buat Job Order Baru
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
