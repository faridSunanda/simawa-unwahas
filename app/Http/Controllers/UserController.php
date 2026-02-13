<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'mahasiswa', 'dosen', 'staff'])->orderBy('created_at', 'desc');

        // Filter by role if provided
        if ($request->has('role') && $request->role !== 'semua') {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->get();
        $roles = Role::orderBy('display_name')->get();

        return view('superadmin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::orderBy('display_name')->get();

        // Get unique fakultas and prodi from existing data
        $fakultasList = Mahasiswa::distinct()->pluck('fakultas')->filter()->unique()->sort()->values();
        $prodiList = Mahasiswa::distinct()->pluck('prodi')->filter()->unique()->sort()->values();

        return view('superadmin.users.create', compact('roles', 'fakultasList', 'prodiList'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $role = Role::find($request->role_id);
        $roleName = $role?->name;

        // Base validation
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ];

        // Add role-specific validation
        if ($roleName === 'mahasiswa') {
            $rules['nim'] = 'required|string|unique:mahasiswas,nim';
            $rules['fakultas'] = 'required|string';
            $rules['prodi'] = 'required|string';
            $rules['angkatan'] = 'nullable|string';
        } elseif (in_array($roleName, ['kaprodi', 'dekan', 'pimpinan'])) {
            $rules['nip'] = 'nullable|string';
            $rules['fakultas'] = 'required|string';
            $rules['prodi'] = $roleName === 'kaprodi' ? 'required|string' : 'nullable|string';
        } elseif ($roleName === 'kemahasiswaan') {
            $rules['nip'] = 'nullable|string';
            $rules['bagian'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'is_active' => $request->has('is_active'),
            ]);

            // Create role-specific profile
            if ($roleName === 'mahasiswa') {
                Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $validated['nim'],
                    'fakultas' => $validated['fakultas'],
                    'prodi' => $validated['prodi'],
                    'angkatan' => $validated['angkatan'] ?? null,
                ]);
            } elseif (in_array($roleName, ['kaprodi', 'dekan', 'pimpinan'])) {
                Dosen::create([
                    'user_id' => $user->id,
                    'nip' => $validated['nip'] ?? null,
                    'jabatan' => ucfirst($roleName),
                    'fakultas' => $validated['fakultas'],
                    'prodi' => $validated['prodi'] ?? null,
                ]);
            } elseif ($roleName === 'kemahasiswaan') {
                Staff::create([
                    'user_id' => $user->id,
                    'nip' => $validated['nip'] ?? null,
                    'bagian' => $validated['bagian'] ?? 'Kemahasiswaan',
                ]);
            }

            DB::commit();
            return redirect()->route('superadmin.users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $user->load(['mahasiswa', 'dosen', 'staff']);
        $roles = Role::orderBy('display_name')->get();

        // Get unique fakultas and prodi from existing data
        $fakultasList = Mahasiswa::distinct()->pluck('fakultas')->filter()->unique()->sort()->values();
        $prodiList = Mahasiswa::distinct()->pluck('prodi')->filter()->unique()->sort()->values();

        return view('superadmin.users.edit', compact('user', 'roles', 'fakultasList', 'prodiList'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $role = Role::find($request->role_id);
        $roleName = $role?->name;

        // Base validation
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ];

        // Add role-specific validation
        if ($roleName === 'mahasiswa') {
            $nimRule = $user->mahasiswa
                ? Rule::unique('mahasiswas', 'nim')->ignore($user->mahasiswa->id)
                : 'unique:mahasiswas,nim';
            $rules['nim'] = ['required', 'string', $nimRule];
            $rules['fakultas'] = 'required|string';
            $rules['prodi'] = 'required|string';
            $rules['angkatan'] = 'nullable|string';
        } elseif (in_array($roleName, ['kaprodi', 'dekan', 'pimpinan'])) {
            $rules['nip'] = 'nullable|string';
            $rules['fakultas'] = 'required|string';
            $rules['prodi'] = $roleName === 'kaprodi' ? 'required|string' : 'nullable|string';
        } elseif ($roleName === 'kemahasiswaan') {
            $rules['nip'] = 'nullable|string';
            $rules['bagian'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'is_active' => $request->has('is_active'),
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // Update/Create role-specific profile
            if ($roleName === 'mahasiswa') {
                $user->mahasiswa()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nim' => $validated['nim'],
                        'fakultas' => $validated['fakultas'],
                        'prodi' => $validated['prodi'],
                        'angkatan' => $validated['angkatan'] ?? null,
                    ]
                );
            } elseif (in_array($roleName, ['kaprodi', 'dekan', 'pimpinan'])) {
                $user->dosen()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $validated['nip'] ?? null,
                        'jabatan' => ucfirst($roleName),
                        'fakultas' => $validated['fakultas'],
                        'prodi' => $validated['prodi'] ?? null,
                    ]
                );
            } elseif ($roleName === 'kemahasiswaan') {
                $user->staff()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $validated['nip'] ?? null,
                        'bagian' => $validated['bagian'] ?? 'Kemahasiswaan',
                    ]
                );
            }

            DB::commit();
            return redirect()->route('superadmin.users.index')
                ->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating own account
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Tidak dapat menonaktifkan akun sendiri');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('superadmin.users.index')
            ->with('success', "User berhasil {$status}");
    }
}
