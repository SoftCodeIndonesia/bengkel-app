<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view users');
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create users');
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|max:2048',
            'roles' => 'required|array',
        ]);

        // dd($validated);

        DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            if ($request->hasFile('photo')) {
                $user->update([
                    'photo' => $request->file('photo')->store('user-photos', 'public')
                ]);
            }



            $user->syncRoles($validated['roles']);
        });

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('edit users');
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('edit users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('user-photos', 'public');
        }

        $user->update($updateData);

        // Sync roles berdasarkan nama role
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete users');

        // Cek jika user yang dihapus adalah super admin
        if ($user->hasRole('admin')) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus Super Admin!');
        }

        // Cek jika user sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        DB::transaction(function () use ($user) {
            // Hapus relasi roles terlebih dahulu
            $user->roles()->detach();

            // Hapus foto profil jika ada
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            // Hapus user
            $user->delete();
        });

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
