<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
    // Hanya superadmin & admin (sudah dibatasi di route)

    public function index(Request $request)
    {
        $users = User::with('roles')
                     ->when($request->search, function ($q) use ($request) {
                         $q->where('name', 'like', "%{$request->search}%")
                           ->orWhere('email', 'like', "%{$request->search}%");
                     })
                     ->when($request->role, function ($q) use ($request) {
                         $q->role($request->role);
                     })
                     ->latest()
                     ->paginate(15);

        $roles = Role::all();

        return view('user.index', compact('users', 'roles'));
    }

    public function create()
    {
        // Admin hanya bisa buat user dengan role di bawahnya
        $roles = $this->getRolesForCurrentUser();

        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            'role'     => 'required|exists:roles,name',
        ]);

        // Pastikan admin tidak bisa assign role superadmin
        $this->authorizeRoleAssignment($validated['role']);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('user.index')
                         ->with('success', "User {$user->name} berhasil dibuat.");
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');

        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Tidak bisa edit akun superadmin kecuali superadmin sendiri
        if ($user->hasRole('superadmin') && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Tidak bisa mengedit akun superadmin.');
        }

        $roles = $this->getRolesForCurrentUser();

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Tidak bisa edit akun superadmin kecuali superadmin sendiri
        if ($user->hasRole('superadmin') && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Tidak bisa mengedit akun superadmin.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->numbers()],
            'role'     => 'required|exists:roles,name',
        ]);

        $this->authorizeRoleAssignment($validated['role']);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            // Hanya update password jika diisi
            ...($validated['password']
                ? ['password' => Hash::make($validated['password'])]
                : []),
        ]);

        $user->syncRoles($validated['role']);

        return redirect()->route('user.index')
                         ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')
                             ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Tidak bisa hapus superadmin
        if ($user->hasRole('superadmin')) {
            return redirect()->route('user.index')
                             ->with('error', 'Akun superadmin tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('user.index')
                         ->with('success', "User {$user->name} berhasil dihapus.");
    }

    // -------------------------------------------------------
    // Helper: role yang boleh di-assign sesuai level user
    // -------------------------------------------------------
    private function getRolesForCurrentUser()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Role::all(); // Superadmin bisa assign semua role
        }

        // Admin tidak bisa assign role superadmin
        return Role::where('name', '!=', 'superadmin')->get();
    }

    private function authorizeRoleAssignment(string $role): void
    {
        if ($role === 'superadmin' && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Tidak bisa assign role superadmin.');
        }
    }
}