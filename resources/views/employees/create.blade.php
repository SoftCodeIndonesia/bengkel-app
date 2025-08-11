<!-- resources/views/employees/create.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Tambah Karyawan')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Tambah Karyawan</h2>
        </div>

        <div class="p-4">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- Foto -->
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-300 mb-1">Foto</label>
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center overflow-hidden">
                                    <img id="photo-preview" class="w-full h-full object-cover hidden" src="#"
                                        alt="Preview">
                                    <span id="photo-placeholder" class="text-gray-400 text-xl">?</span>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                                    <label for="photo"
                                        class="cursor-pointer bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-md inline-block">
                                        Pilih Foto
                                    </label>
                                    <p class="mt-1 text-xs text-gray-400">Format: JPEG, PNG (Maks. 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap <span
                                    class="text-red-500">*</span></label></label>
                            <input type="text" name="name" id="name" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email <span
                                    class="text-red-500">*</span></label></label>
                            <input type="email" name="email" id="email" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Telepon <span
                                    class="text-red-500">*</span></label></label>
                            <input type="text" name="phone" id="phone" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- Posisi -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-300 mb-1">Posisi/Jabatan <span
                                    class="text-red-500">*</span></label></label>
                            <select name="position" id="position" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Posisi</option>
                                <option value="Mekanik">Mekanik</option>
                                <option value="Kasir">Kasir</option>
                                <option value="Admin">Admin</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Manajer">Manajer</option>
                            </select>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-300 mb-1">Tanggal Mulai
                                Bekerja <span class="text-red-500">*</span></label></label>
                            <input type="date" name="hire_date" id="hire_date" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Gaji -->
                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-300 mb-1">Gaji</label>
                            <input type="number" name="salary" id="salary" step="100000"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Alamat <span
                                    class="text-red-500">*</span></label></label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('employees.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-md">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-md">Simpan</button>
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
    </script>
@endpush
