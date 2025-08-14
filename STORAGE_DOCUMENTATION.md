# Storage System Documentation

## Struktur Direktori
```
storage/
├── app/
│   └── public/
│       ├── profile-photos/     # Foto profil pengguna
│       │   └── .gitignore      
│       └── photos/             # Foto umum lainnya
│           └── .gitignore      
└── STORAGE_README.txt

public/
└── storage -> ../storage/app/public (symbolic link)
```

## Konfigurasi

### Filesystems (config/filesystems.php)
- **Default disk**: public
- **Public disk**: storage/app/public
- **URL**: /storage
- **Visibility**: public

### Upload Validation
- **Max file size**: 2MB
- **Allowed formats**: jpeg, png, jpg, gif
- **Validation rules**: image|mimes:jpeg,png,jpg,gif|max:2048

## Model User Methods

### Accessors
- `$user->profile_photo_url` - URL lengkap foto profil atau placeholder
- `$user->profile_photo_path` - Path file di storage

### Helper Methods
- `$user->hasProfilePhoto()` - Cek apakah user punya foto profil

## Controller Methods

### PengaturanController
- `handlePhotoUpload($file, $oldPhoto)` - Handle upload foto dengan hapus foto lama
- Validasi upload foto dalam method `update()`

## Commands

### Cleanup Unused Photos
```bash
# Dry run - lihat file yang akan dihapus tanpa menghapus
php artisan storage:cleanup-photos --dry-run

# Hapus file yang tidak terpakai
php artisan storage:cleanup-photos
```

## Setup Instructions

### 1. Setup Symbolic Link
```bash
php artisan storage:link
```

### 2. Buat Direktori Storage
```bash
mkdir storage/app/public/profile-photos
mkdir storage/app/public/photos
```

### 3. Set Permissions (Linux/Mac)
```bash
chmod -R 755 storage/
chmod -R 755 public/storage
```

## Usage Examples

### Upload Foto di Form
```html
<form enctype="multipart/form-data">
    <input type="file" name="foto" accept="image/*">
</form>
```

### Display Foto di View
```blade
<img src="{{ $user->profile_photo_url }}" alt="Profile Photo">
```

### Manual File Upload
```php
$path = $request->file('foto')->store('profile-photos', 'public');
$user->foto = $path;
$user->save();
```

## Security Considerations

1. **File Validation**: Selalu validasi tipe dan ukuran file
2. **Filename**: Generate unique filename untuk menghindari collision
3. **Directory Traversal**: Gunakan Laravel's storage methods
4. **Public Access**: Hanya file di public disk yang bisa diakses via web

## Maintenance

### Cleanup Schedule
Jalankan cleanup command secara berkala untuk menghapus file yang tidak terpakai:

```bash
# Tambahkan ke crontab atau task scheduler
php artisan storage:cleanup-photos
```

### Backup
Backup direktori storage/app/public secara regular sesuai dengan policy backup aplikasi.

## Troubleshooting

### Symbolic Link Tidak Berfungsi
```bash
# Hapus link lama
rm public/storage

# Buat ulang
php artisan storage:link
```

### File Tidak Bisa Diakses
1. Cek permissions direktori storage
2. Cek apakah symbolic link ada
3. Cek konfigurasi web server

### Upload Gagal
1. Cek max upload size di php.ini
2. Cek permissions direktori storage
3. Cek disk space
