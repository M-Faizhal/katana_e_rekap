<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PengelolaanAkun extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $stats = [
            'total' => User::count(),
            'superadmin' => User::where('role', 'superadmin')->count(),
            'admin_marketing' => User::where('role', 'admin_marketing')->count(),
            'admin_purchasing' => User::where('role', 'admin_purchasing')->count(),
            'admin_keuangan' => User::where('role', 'admin_keuangan')->count(),
        ];

        return view('pages.pengelolaan-akun', compact('users', 'stats'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,admin_marketing,admin_purchasing,admin_keuangan',
            'jabatan' => 'nullable|in:direktur,manager_marketing,staf_marketing,admin_marketing,staf_purchasing,admin_keuangan_hr,staf_keuangan',
            'label' => 'nullable|in:internal,eksternal',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'jabatan' => $request->jabatan,
            'label' => $request->label,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan!'
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:superadmin,admin_marketing,admin_purchasing,admin_keuangan',
            'jabatan' => 'nullable|in:direktur,manager_marketing,staf_marketing,admin_marketing,staf_purchasing,admin_keuangan_hr,staf_keuangan',
            'label' => 'nullable|in:internal,eksternal',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'jabatan' => $request->jabatan,
            'label' => $request->label,
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui!'
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current logged-in user
        if ($user->id_user === Auth::user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun yang sedang digunakan!'
            ], 400);
        }

        // Prevent deleting the last superadmin
        if ($user->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus superadmin terakhir!'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus!'
        ]);
    }

    /**
     * Get user data for editing
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
