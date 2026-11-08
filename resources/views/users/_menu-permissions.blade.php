<div class="mb-6 rounded-lg border border-gray-700 bg-gray-800 p-4">
    <h3 class="text-lg font-semibold text-white">Akses Menu Staff</h3>
    <p class="mt-1 mb-4 text-sm text-gray-400">
        Pilih menu yang dapat diakses user ini. Pilihan ini membatasi permission dari role untuk akun non-admin.
    </p>

    @php
        $selectedPermissions = old('menu_permissions', $selectedMenuPermissions ?? []);
    @endphp

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($menuPermissions as $group => $permissions)
            <fieldset class="rounded-md border border-gray-700 p-3">
                <legend class="px-1 text-sm font-medium capitalize text-gray-200">
                    {{ str_replace(['-', '_'], ' ', $group ?: 'Lainnya') }}
                </legend>
                <div class="space-y-2">
                    @foreach ($permissions as $permission)
                        <label class="flex items-start gap-2 text-sm text-gray-300">
                            <input type="checkbox" name="menu_permissions[]" value="{{ $permission->name }}"
                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500">
                            <span>{{ $permission->description ?: $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>

    @error('menu_permissions')
        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
    @enderror
    @error('menu_permissions.*')
        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>
