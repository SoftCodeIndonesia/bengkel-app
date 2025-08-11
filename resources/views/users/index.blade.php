@extends('layouts.dashboard')

@section('title', 'Manajemen User')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Daftar User</h2>
            @can('create users')
                <a href="{{ route('users.create') }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah User
                </a>
            @endcan
        </div>

        <div class="p-4">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400" id="usersTable">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Role</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                <td class="p-3 flex items-center">
                                    @if ($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" class="w-8 h-8 rounded-full mr-2"
                                            alt="{{ $user->name }}">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center mr-2">
                                            <span class="text-xs">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    {{ $user->name }}
                                </td>
                                <td class="p-3">{{ $user->email }}</td>
                                <td class="p-3">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-gray-600 text-gray-300">{{ $role->name }}</span>
                                    @endforeach
                                </td>

                                <td class="p-3 text-right">
                                    @can('edit users')
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-blue-400 hover:text-blue-300 mr-2">Edit</a>
                                    @endcan
                                    @can('delete users')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300">Hapus</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangani form delete
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "User yang dihapus tidak dapat dikembalikan!",
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
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
