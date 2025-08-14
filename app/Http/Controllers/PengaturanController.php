<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class PengaturanController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('pages.pengaturan', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add password validation if password is being changed
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'];
        }

        $request->validate($rules, [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
            'current_password.required' => 'Password saat ini wajib diisi untuk mengubah password.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Check current password if trying to change password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini tidak benar.'
                ])->withInput();
            }
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            $fotoPath = $this->handlePhotoUpload($request->file('foto'), $user->foto);
            $user->foto = $fotoPath;
        }

        // Update user data
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->no_telepon = $request->no_telepon;
        $user->alamat = $request->alamat;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Handle photo upload for user profile
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $oldPhoto
     * @return string
     */
    private function handlePhotoUpload($file, $oldPhoto = null)
    {
        // Delete old photo if exists
        if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store new photo in profile-photos directory
        $path = $file->storeAs('profile-photos', $filename, 'public');

        return $path;
    }
}
