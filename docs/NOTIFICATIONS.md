# Dokumentasi Fitur Notifikasi (Database)

Tanggal: 2026-03-29

Dokumen ini merangkum seluruh perubahan yang dibuat untuk menambahkan **notifikasi berbasis database** pada aplikasi Laravel `katana_e_rekap`.

> Catatan: notifikasi dibuat hanya untuk event workflow penting (status proyek, ACC/approval, pengajuan/revisi cost, pembayaran, pengiriman), **bukan** untuk pembuatan dokumen administratif.

---

## 1) Penyimpanan Notifikasi di Database

### Migration
File:
- `database/migrations/2026_03_29_000000_create_notifications_table.php`

Struktur tabel `notifications` mengikuti konsep Laravel Notifications.

**Penting (fix error Data truncated):**
- Primary key kolom `id` menggunakan **UUID**:
  - `uuid('id')->primary()`

Jika sebelumnya memakai `bigIncrements('id')`, insert notifikasi akan gagal karena Laravel menyimpan UUID (contoh: `9b1157b5-...`).

---

## 2) Komponen Backend

### A. Notification Classes
Folder:
- `app/Notifications/`

Daftar notification class yang dibuat:

1. `PenawaranAccNotification.php`
   - Event: `penawaran_acc`
   - Trigger: penawaran berubah status menjadi `ACC`
   - Penerima: semua user (role: `superadmin`, `admin_marketing`, `admin_purchasing`, `admin_keuangan`)

2. `ProyekStatusChangedNotification.php`
   - Event: `proyek_status_changed`
   - Trigger: status proyek berubah
   - Penerima: PIC proyek (marketing & purchasing)

3. `PengajuanKostSubmittedNotification.php`
   - Event: `pengajuan_kost_submitted`
   - Trigger: pengajuan cost baru dibuat
   - Penerima: role keuangan (`admin_keuangan`)
   - Message sudah memuat PIC marketing (nama) sebagai informasi “Oleh: ...”.

4. `PengajuanKostRevisionRequestedNotification.php`
   - Event: `pengajuan_kost_revisi`
   - Trigger: keuangan meminta revisi pengajuan cost
   - Penerima: user pembuat pengajuan (`created_by`)

5. `PembayaranSubmittedNotification.php`
   - Event: `pembayaran_submitted`
   - Trigger: purchasing membuat pembayaran baru (status `Pending`)
   - Penerima: role keuangan (`admin_keuangan`)
   - Message: menyebut vendor, kode proyek, dan PIC purchasing.

6. `PembayaranApprovedNotification.php`
   - Event: `pembayaran_approved`
   - Trigger: keuangan approve pembayaran
   - Penerima: PIC purchasing proyek
   - Message: “Pembayaran untuk proyek {kode_proyek} telah disetujui oleh {nama verifikator}”.

7. `PengirimanCreatedNotification.php`
   - Event: `pengiriman_created`
   - Trigger: purchasing membuat pengiriman
   - Penerima: PIC marketing proyek

Semua notification memakai channel:
- `via() => ['database']`

---

### B. Service Pengirim Notifikasi
File:
- `app/Services/NotificationService.php`

Tujuan:
- Menyatukan logic pemilihan penerima (role/PIC) dan pemanggilan `$user->notify(...)`.

Method yang tersedia:
- `penawaranAcc(Penawaran $penawaran)`
- `proyekStatusChanged(Proyek $proyek, string $oldStatus, string $newStatus)`
- `pengajuanKostSubmitted(PengajuanKost $pengajuan)`
- `pengajuanKostRevisionRequested(PengajuanKost $pengajuan)`
- `pembayaranSubmitted(Pembayaran $pembayaran)`
- `pembayaranApproved(Pembayaran $pembayaran)`
- `pengirimanCreated(Pengiriman $pengiriman)`

---

## 3) Integrasi ke Workflow (Controller yang Disisipkan)

### A. Penawaran ACC
File:
- `app/Http/Controllers/marketing/PenawaranController.php`

Perubahan:
- Setelah upload surat penawaran berhasil dan status di-set `ACC`, sistem memanggil:
  - `NotificationService::penawaranAcc(...)`

**Safety fix:**
- Pemanggilan notifikasi dibungkus `try/catch` agar jika notifikasi gagal, proses upload/ACC tidak ikut gagal (menghindari HTTP 500).

**Fix tambahan untuk error 500 surat penawaran:**
- Bagian yang sebelumnya memanggil `->format('Y-m-d')` pada field tanggal yang bisa berupa string diperbaiki menjadi parsing aman menggunakan `Carbon::parse(...)`.

### B. Status Proyek
File:
- `app/Http/Controllers/marketing/ProyekController.php`

Perubahan:
- Pada endpoint update status (`updateStatus()`), setelah update status berhasil, sistem mengirim notifikasi ke PIC (marketing + purchasing) jika status berubah:
  - `NotificationService::proyekStatusChanged($proyek, $oldStatus, $newStatus)`

### C. Pengajuan Cost
File:
- `app/Http/Controllers/marketing/PengajuanKostController.php`

Perubahan:
- Setelah pengajuan cost dibuat (`store()`), sistem mengirim notifikasi ke keuangan:
  - `NotificationService::pengajuanKostSubmitted(...)`

### D. Revisi Cost oleh Keuangan
File:
- `app/Http/Controllers/keuangan/VerifikasiKostController.php`

Perubahan:
- Saat keuangan meminta revisi (`revision()`), sistem mengirim notifikasi ke pembuat pengajuan:
  - `NotificationService::pengajuanKostRevisionRequested(...)`

### E. Pembayaran
File:
- `app/Http/Controllers/purchasing/PembayaranController.php`

Perubahan:
- Setelah purchasing membuat pembayaran baru (Pending), sistem mengirim notifikasi ke keuangan:
  - `NotificationService::pembayaranSubmitted(...)`

File:
- `app/Http/Controllers/keuangan/ApprovalController.php`

Perubahan:
- Setelah pembayaran di-approve, sistem mengirim notifikasi ke PIC purchasing proyek:
  - `NotificationService::pembayaranApproved(...)`

### F. Pengiriman
File:
- `app/Http/Controllers/purchasing/PengirimanController.php`

Perubahan:
- Setelah pengiriman dibuat, sistem mengirim notifikasi ke PIC marketing proyek:
  - `NotificationService::pengirimanCreated(...)`

---

## 4) UI / Frontend

### A. Icon Notifikasi di Navbar
File:
- `resources/views/components/header.blade.php`

Fitur:
- Ikon lonceng
- Badge jumlah unread (`unreadNotifications()->count()`)
- Dropdown 5 notifikasi terakhir
- Tombol "Tandai semua dibaca"
- Link ke halaman notifikasi

### B. Halaman daftar notifikasi
File:
- `resources/views/pages/notifications/index.blade.php`

Fitur:
- List notifikasi user
- Filter unread (`?unread=1`)
- Tombol tandai dibaca per item
- Tombol tandai semua dibaca

---

## 5) Routes
File:
- `routes/web.php`

Ditambahkan (middleware `auth`):
- `GET /notifications` -> `notifications.index`
- `POST /notifications/read-all` -> `notifications.readAll`
- `POST /notifications/{id}/read` -> `notifications.read`

---

## 6) Controller Notifikasi
File:
- `app/Http/Controllers/NotificationController.php`

Endpoint:
- `index()` : daftar notifikasi, optionally unread
- `markAsRead($id)` : tandai satu notifikasi dibaca
- `markAllAsRead()` : tandai semua unread menjadi read

---

## 7) Perubahan Message Notifikasi (Bahasa Indonesia)

Perubahan konten message yang terakhir disesuaikan:

- `PembayaranApprovedNotification`
  - Message menyebut `kode_proyek` dan `nama verifikator` (dari relasi `verifikator`).

- `PembayaranSubmittedNotification`
  - Message menyebut vendor + `kode_proyek` + `nama purchasing` (PIC proyek).

- `PengajuanKostSubmittedNotification`
  - Message menyebut kode pengajuan + "Oleh: {nama PIC Marketing}".

---

## 8) Catatan Testing

- Saat menjalankan `php artisan test`, test suite default Laravel bisa gagal jika environment test menggunakan SQLite in-memory tetapi migration project memakai SQL spesifik MySQL (contoh `ALTER TABLE ... MODIFY COLUMN ... ENUM`).
- Disarankan:
  1) Jalankan test dengan database MySQL khusus testing, atau
  2) Skip/conditional migration untuk SQLite, atau
  3) Buat test notifikasi dengan koneksi MySQL.

---

## 9) Checklist Verifikasi Manual (setelah migrate/fresh)

1) Upload surat penawaran -> status ACC -> notifikasi `penawaran_acc` muncul untuk semua role.
2) Ubah status proyek -> notifikasi `proyek_status_changed` muncul di PIC marketing & purchasing.
3) Buat pengajuan cost -> notifikasi `pengajuan_kost_submitted` muncul di role keuangan.
4) Keuangan minta revisi -> notifikasi `pengajuan_kost_revisi` muncul di user pembuat pengajuan.
5) Purchasing submit pembayaran -> notifikasi `pembayaran_submitted` ke keuangan.
6) Keuangan approve -> notifikasi `pembayaran_approved` ke PIC purchasing.
7) Purchasing buat pengiriman -> notifikasi `pengiriman_created` ke PIC marketing.

---

## 10) Troubleshooting Cepat

### Notifikasi tidak masuk
- Cek `storage/logs/laravel.log`.
- Pastikan tabel `notifications` memakai `uuid` untuk `id`.

### Error "Data truncated for column 'id'"
- Penyebab: kolom `notifications.id` bukan UUID.
- Solusi: ubah migration menjadi `uuid('id')->primary()`, lalu `migrate:fresh`.
