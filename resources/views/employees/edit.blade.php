<!-- resources/views/employees/edit.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Edit Karyawan')

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Edit Karyawan</h2>
        </div>

        <div class="p-4">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- Foto -->
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-300 mb-1">Foto</label>
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center overflow-hidden">
                                    @if ($employee->photo)
                                        <img id="photo-preview" src="{{ asset('storage/' . $employee->photo) }}"
                                            class="w-full h-full object-cover" alt="Foto Karyawan">
                                        <span id="photo-placeholder" class="hidden text-gray-400 text-xl">?</span>
                                    @else
                                        <img id="photo-preview" class="hidden w-full h-full object-cover" src="#"
                                            alt="Preview">
                                        <span id="photo-placeholder"
                                            class="text-gray-400 text-xl">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                                    <label for="photo"
                                        class="cursor-pointer bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-md inline-block">
                                        Ganti Foto
                                    </label>
                                    @if ($employee->photo)
                                        <button type="button" onclick="confirmDeletePhoto()"
                                            class="ml-2 text-xs text-red-400 hover:text-red-300">
                                            Hapus Foto
                                        </button>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-400">Format: JPEG, PNG (Maks. 2MB)</p>
                                </div>
                            </div>
                            <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}"
                                required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}"
                                required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Telepon <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}"
                                required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- Posisi -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-300 mb-1">Posisi/Jabatan <span
                                    class="text-red-500">*</span></label>
                            <select name="position" id="position" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Posisi</option>
                                <option value="Mekanik"
                                    {{ old('position', $employee->position) == 'Mekanik' ? 'selected' : '' }}>Mekanik
                                </option>
                                <option value="Kasir"
                                    {{ old('position', $employee->position) == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                                <option value="Admin"
                                    {{ old('position', $employee->position) == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Supervisor"
                                    {{ old('position', $employee->position) == 'Supervisor' ? 'selected' : '' }}>Supervisor
                                </option>
                                <option value="Manajer"
                                    {{ old('position', $employee->position) == 'Manajer' ? 'selected' : '' }}>Manajer
                                </option>
                            </select>
                            @error('position')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-300 mb-1">Tanggal Mulai
                                Bekerja <span class="text-red-500">*</span></label>
                            <input type="date" name="hire_date" id="hire_date"
                                value="{{ old('hire_date', $employee->hire_date) }}" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('hire_date')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji -->
                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-300 mb-1">Gaji</label>
                            <input type="number" name="salary" id="salary" step="100000"
                                value="{{ old('salary', $employee->salary) }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('salary')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Alamat <span
                                    class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $employee->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('employees.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-md">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-md">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview foto sebelum upload
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                    document.getElementById('photo-preview').classList.remove('hidden');
                    document.getElementById('photo-placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        function confirmDeletePhoto() {
            Swal.fire({
                title: 'Hapus Foto Profil?',
                text: "Foto akan dihapus permanen dari sistem",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set flag untuk hapus foto
                    document.getElementById('remove_photo').value = '1';

                    // Update tampilan
                    document.getElementById('photo-preview').classList.add('hidden');
                    document.getElementById('photo-placeholder').classList.remove('hidden');
                    document.getElementById('photo-placeholder').textContent =
                        '{{ strtoupper(substr($employee->name, 0, 1)) }}';

                    Swal.fire(
                        'Dihapus!',
                        'Foto profil akan dihapus saat Anda menyimpan perubahan.',
                        'success'
                    );
                }
            });
        }
    </script>
@endpush
