{{-- // resources/views/users/create.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Form Tambah User</h2>
        </div>

        <div class="p-4">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kolom Kiri -->
                    <div>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-400 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-400 mb-2">Email</label>
                            <input type="email" name="email" id="email" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-400 mb-2">Password</label>
                            <input type="password" name="password" id="password" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-400 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- <div class="mb-4">
                            <label for="photo" class="block text-gray-400 mb-2">Foto Profil (Opsional)</label>
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('photo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked
                                    class="rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-gray-400">Akun Aktif</span>
                            </label>
                        </div> --}}
                    </div>
                </div>

                <!-- Role Assignment -->
                <div class="mb-6">
                    <label class="block text-gray-400 mb-2">Role</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" name="roles[]" id="role_{{ $role->id }}"
                                    value="{{ $role->name }}"
                                    class="rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                                <label for="role_{{ $role->id }}" class="ml-2 text-gray-300">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('users.index') }}"
                        class="mr-3 text-gray-400 hover:text-white bg-gray-700 px-4 py-2 rounded-lg">
                        Batal
                    </a>
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview image sebelum upload
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photo-preview');
                    if (!preview) {
                        const previewDiv = document.createElement('div');
                        previewDiv.id = 'photo-preview';
                        previewDiv.className = 'mt-2';
                        previewDiv.innerHTML =
                            `<img src="${e.target.result}" class="h-20 w-20 rounded-full object-cover">`;
                        document.querySelector('input[name="photo"]').after(previewDiv);
                    } else {
                        preview.innerHTML =
                            `<img src="${e.target.result}" class="h-20 w-20 rounded-full object-cover">`;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
