Storage Structure Test
===================

Direktori Storage untuk Foto Profil:
1. storage/app/public/profile-photos/ - Untuk foto profil pengguna
2. storage/app/public/photos/ - Untuk foto umum lainnya

Symbolic Link:
- public/storage -> storage/app/public

Konfigurasi:
- Disk: public
- URL: /storage
- Max file size: 2MB
- Allowed formats: jpeg, png, jpg, gif

Test Upload:
1. Akses /pengaturan
2. Upload foto profil
3. Cek file tersimpan di storage/app/public/profile-photos/
4. Akses foto via URL: /storage/profile-photos/filename.ext
