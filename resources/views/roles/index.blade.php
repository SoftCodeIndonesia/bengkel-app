@extends('layouts.dashboard')

@section('title', 'Manajemen Role')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Daftar Role</h2>
            @can('create roles')
                <a href="{{ route('roles.create') }}"
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Role
                </a>
            @endcan
        </div>

        <div class="p-4">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400" id="rolesTable">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3">Jumlah User</th>
                            <th class="p-3">Jumlah Permission</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                <td class="p-3 font-medium">{{ $role->name }}</td>
                                <td class="p-3">{{ $role->description }}</td>
                                <td class="p-3">{{ $role->users_count }}</td>
                                <td class="p-3">{{ $role->permissions_count }}</td>
                                <td class="p-3 text-right">
                                    @can('edit roles')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="text-blue-400 hover:text-blue-300 mr-2">Edit</a>
                                    @endcan
                                    @can('delete roles')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300"
                                                onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection
