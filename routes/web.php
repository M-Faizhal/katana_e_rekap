<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\superadmin\PengelolaanAkun;
use App\Http\Controllers\superadmin\VerifikasiProyekController;
use App\Http\Controllers\purchasing\VendorController;
use App\Http\Controllers\purchasing\ProdukController;
use App\Http\Controllers\purchasing\KalkulasiController;
use App\Http\Controllers\purchasing\PembayaranController;
use App\Http\Controllers\purchasing\PengirimanController;
use App\Http\Controllers\keuangan\ApprovalController;
use App\Http\Controllers\keuangan\PenagihanDinasController;
use App\Http\Controllers\marketing\ProyekController;
use App\Http\Controllers\marketing\WilayahController;
use App\Http\Controllers\marketing\PotensiController;
use App\Http\Controllers\marketing\PenawaranController;
use App\Http\Controllers\marketing\Export\PotensiExportController;
use App\Http\Controllers\marketing\Export\OmsetExportController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;

// Health check endpoint for Docker
Route::get('/health', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now(),
            'app' => config('app.name'),
            'database' => 'connected'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'timestamp' => now(),
            'app' => config('app.name'),
            'database' => 'disconnected',
            'error' => $e->getMessage()
        ], 503);
    }
});

// Auth routes
Route::middleware('guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLogin'])->name('login');
        Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes with authentication
Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/realtime', [DashboardController::class, 'getRealtimeData'])->name('dashboard.realtime');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');

    // Laporan Routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/proyek', [LaporanController::class, 'index'])->name('laporan.proyek');
    Route::get('/laporan/omset', [LaporanController::class, 'omset'])->name('laporan.omset');
    Route::get('/laporan/hutang-vendor', [LaporanController::class, 'hutangVendor'])->name('laporan.hutang-vendor');
    Route::get('/laporan/piutang-dinas', [LaporanController::class, 'piutangDinas'])->name('laporan.piutang-dinas');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::get('/laporan/export-omset', [OmsetExportController::class, 'exportExcel'])->name('laporan.export-omset');
    Route::get('/laporan/project/{id}', [LaporanController::class, 'getProjectDetail'])->name('laporan.project.detail');
    Route::get('/laporan/data', [LaporanController::class, 'getFilteredData'])->name('laporan.data');

    // Marketing Routes
    Route::prefix('marketing')->group(function () {
        // Proyek Routes
        Route::get('/proyek', [ProyekController::class, 'index'])->name('marketing.proyek');
        Route::post('/proyek', [ProyekController::class, 'store'])->name('marketing.proyek.store');
        Route::put('/proyek/{id}', [ProyekController::class, 'update'])->name('marketing.proyek.update');
        Route::delete('/proyek/{id}', [ProyekController::class, 'destroy'])->name('marketing.proyek.destroy');
        Route::put('/proyek/{id}/status', [ProyekController::class, 'updateStatus'])->name('marketing.proyek.status');
        Route::put('/proyek/{id}/status-gagal', [ProyekController::class, 'updateStatusGagal'])->name('marketing.proyek.status.gagal');
        Route::get('/proyek/users', [ProyekController::class, 'getUsersForSelect'])->name('marketing.proyek.users');
        Route::get('/proyek/wilayah', [ProyekController::class, 'getWilayahForSelect'])->name('marketing.proyek.wilayah');
        Route::get('/proyek/next-kode', [ProyekController::class, 'getNextKodeProyek'])->name('marketing.proyek.next-kode');
        Route::get('/proyek/current-user', [ProyekController::class, 'getCurrentUser'])->name('marketing.proyek.current-user');

        // File spesifikasi routes
        Route::get('/proyek/file/{filename}', [ProyekController::class, 'downloadFile'])->name('marketing.proyek.file.download');
        Route::get('/proyek/file/{filename}/preview', [ProyekController::class, 'previewFile'])->name('marketing.proyek.file.preview');

        // Wilayah Routes
        Route::get('/wilayah', [WilayahController::class, 'index'])->name('marketing.wilayah');
        Route::post('/wilayah', [WilayahController::class, 'store'])->name('marketing.wilayah.store');
        Route::put('/wilayah/{id}', [WilayahController::class, 'update'])->name('marketing.wilayah.update');
        Route::delete('/wilayah/{id}', [WilayahController::class, 'destroy'])->name('marketing.wilayah.destroy');
        Route::get('/wilayah/users', [WilayahController::class, 'getUsersForSelect'])->name('marketing.wilayah.users');

        // Potensi Routes (CRUD) - Same as Proyek but filtered by potensi='ya'
        Route::get('/potensi', [PotensiController::class, 'index'])->name('marketing.potensi');
        Route::post('/potensi', [PotensiController::class, 'store'])->name('marketing.potensi.store');
        Route::get('/potensi/{id}', [PotensiController::class, 'show'])->name('marketing.potensi.show');
        Route::put('/potensi/{id}', [PotensiController::class, 'update'])->name('marketing.potensi.update');
        Route::delete('/potensi/{id}', [PotensiController::class, 'destroy'])->name('marketing.potensi.destroy');
        Route::get('/potensi/{id}/detail', [PotensiController::class, 'detail'])->name('marketing.potensi.detail');
        Route::put('/potensi/{id}/status', [PotensiController::class, 'updateStatus'])->name('marketing.potensi.updateStatus');
        Route::put('/potensi/{id}/status-gagal', [PotensiController::class, 'updateStatusGagal'])->name('marketing.potensi.updateStatusGagal');
        Route::get('/potensi/users/select', [PotensiController::class, 'getUsersForSelect'])->name('marketing.potensi.users');
        Route::get('/potensi/wilayah/select', [PotensiController::class, 'getWilayahForSelect'])->name('marketing.potensi.wilayah');
        Route::get('/potensi/kode/next', [PotensiController::class, 'getNextKodeProyek'])->name('marketing.potensi.nextKode');
        Route::get('/potensi/user/current', [PotensiController::class, 'getCurrentUser'])->name('marketing.potensi.currentUser');
        
        // Export Potensi
        Route::get('/potensi/export/excel', [PotensiExportController::class, 'exportExcel'])->name('marketing.potensi.export.excel');

        // Penawaran Routes
        Route::get('/penawaran', [PenawaranController::class, 'index'])->name('marketing.penawaran');
        Route::get('/penawaran/{proyekId}', [PenawaranController::class, 'show'])->name('marketing.penawaran.detail');
        Route::post('/penawaran', [PenawaranController::class, 'store'])->name('marketing.penawaran.store');
        Route::put('/penawaran/{id}', [PenawaranController::class, 'update'])->name('marketing.penawaran.update');
        Route::get('/penawaran/project/{proyekId}/data', [PenawaranController::class, 'getPenawaranByProject'])->name('marketing.penawaran.project.data');
        Route::get('/penawaran/download/{type}/{filename}', [PenawaranController::class, 'downloadFile'])->name('penawaran.download');
    });

    // Purchasing Routes
    Route::prefix('purchasing')->group(function () {
        // Produk Routes
        Route::get('/produk', [ProdukController::class, 'index_produk_purchasing'])->name('purchasing.produk');
        Route::get('/produk/export', [ProdukController::class, 'export'])->name('purchasing.produk.export');
        Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

        // Vendor Routes
        Route::get('/vendor', [VendorController::class, 'index'])->name('purchasing.vendor');
        Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('/vendor/{id}', [VendorController::class, 'show'])->name('vendor.show');
        Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
        Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

        // Kalkulasi Routes
        Route::get('/kalkulasi', [KalkulasiController::class, 'index'])->name('purchasing.kalkulasi');
        Route::get('/kalkulasi/{id}/hps', [KalkulasiController::class, 'hps'])->name('purchasing.kalkulasi.hps');
        Route::get('/kalkulasi/proyek/{id}', [KalkulasiController::class, 'getProyekData'])->name('kalkulasi.proyek');
        Route::get('/kalkulasi/proyek/{id}/items', [KalkulasiController::class, 'getProyekItems'])->name('kalkulasi.proyek.items');
        Route::get('/kalkulasi/barang', [KalkulasiController::class, 'getBarangList'])->name('kalkulasi.barang');
        Route::get('/kalkulasi/barang/{id}', [KalkulasiController::class, 'getBarangDetails'])->name('kalkulasi.barang.details');
        Route::get('/kalkulasi/vendor', [KalkulasiController::class, 'getVendorList'])->name('kalkulasi.vendor');
        Route::post('/kalkulasi/save', [KalkulasiController::class, 'saveKalkulasi'])->name('kalkulasi.save');
        Route::post('/kalkulasi/save-with-history', [KalkulasiController::class, 'saveKalkulasiWithHistory'])->name('kalkulasi.save.history');
        Route::post('/kalkulasi/ajukan-pembayaran', [KalkulasiController::class, 'ajukanPembayaran'])->name('kalkulasi.ajukan.pembayaran');
        Route::get('/kalkulasi/{id}/riwayat', [KalkulasiController::class, 'getRiwayatHps'])->name('kalkulasi.riwayat');
        Route::get('/kalkulasi/{id}/riwayat-detail', [KalkulasiController::class, 'showRiwayatDetail'])->name('kalkulasi.riwayat.detail');
        Route::get('/kalkulasi/{id}/hps-ajukan', [KalkulasiController::class, 'hpsAjukan'])->name('purchasing.kalkulasi.hps.ajukan');
        Route::post('/kalkulasi/penawaran', [KalkulasiController::class, 'createPenawaran'])->name('kalkulasi.penawaran');
        Route::post('/kalkulasi/penawaran/preview', [KalkulasiController::class, 'previewPenawaran'])->name('kalkulasi.penawaran.preview');
        Route::get('/kalkulasi/penawaran/{proyekId}/detail', [KalkulasiController::class, 'detailPenawaran'])->name('kalkulasi.penawaran.detail');
        Route::put('/kalkulasi/penawaran/{penawaranId}/status', [KalkulasiController::class, 'updatePenawaranStatus'])->name('kalkulasi.penawaran.status');

        // Approval File Management (Combined Route)
        Route::post('/kalkulasi/manage-approval', [KalkulasiController::class, 'manageApprovalFile'])->name('kalkulasi.manage.approval');

        // New: HPS Items Summary Page
        Route::get('/kalkulasi/{id}/hps/summary', [KalkulasiController::class, 'hpsSummary'])->name('purchasing.kalkulasi.hps.summary');
        
  
        // Pembayaran Routes
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('purchasing.pembayaran');
        Route::get('/pembayaran/create/{id_proyek}/{id_vendor?}', [PembayaranController::class, 'create'])->name('purchasing.pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('purchasing.pembayaran.store');
        Route::get('/pembayaran/{id_pembayaran}', [PembayaranController::class, 'show'])->name('purchasing.pembayaran.show');
        Route::get('/pembayaran/{id_pembayaran}/edit', [PembayaranController::class, 'edit'])->name('purchasing.pembayaran.edit');
        Route::put('/pembayaran/{id_pembayaran}', [PembayaranController::class, 'update'])->name('purchasing.pembayaran.update');
        Route::delete('/pembayaran/{id_pembayaran}', [PembayaranController::class, 'destroy'])->name('purchasing.pembayaran.destroy');
        Route::get('/pembayaran/history/{id_proyek}', [PembayaranController::class, 'history'])->name('purchasing.pembayaran.history');
        Route::get('/pembayaran/suggestion/{id_proyek}', [PembayaranController::class, 'calculateSuggestion'])->name('purchasing.pembayaran.suggestion');
        Route::post('/pembayaran/cleanup-files', [PembayaranController::class, 'cleanupOrphanedFiles'])->name('purchasing.pembayaran.cleanup');

        // Pengiriman Routes
        Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('purchasing.pengiriman');
        Route::get('/pengiriman/{id}/detail', [PengirimanController::class, 'getDetailWithFiles'])->name('purchasing.pengiriman.detail');
        Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('purchasing.pengiriman.store');
        Route::get('/pengiriman/{id}/edit', [PengirimanController::class, 'edit'])->name('purchasing.pengiriman.edit');
        Route::put('/pengiriman/{id}', [PengirimanController::class, 'update'])->name('purchasing.pengiriman.update');
        Route::put('/pengiriman/{id}/update-dokumentasi', [PengirimanController::class, 'updateDokumentasi'])->name('purchasing.pengiriman.update-dokumentasi');
        Route::put('/pengiriman/{id}/verify', [PengirimanController::class, 'verify'])->name('purchasing.pengiriman.verify');
        Route::delete('/pengiriman/{id}', [PengirimanController::class, 'destroy'])->name('purchasing.pengiriman.destroy');
        Route::post('/pengiriman/cleanup-files', [PengirimanController::class, 'cleanupOrphanedFiles'])->name('purchasing.pengiriman.cleanup');
    });

    // Keuangan Routes
    Route::prefix('keuangan')->group(function () {
        // Approval routes
        Route::get('/approval', [ApprovalController::class, 'index'])->name('keuangan.approval');
        Route::get('/approval/{id_pembayaran}', [ApprovalController::class, 'detail'])->name('keuangan.approval.detail');
        Route::post('/approval/{id_pembayaran}/approve', [ApprovalController::class, 'approve'])->name('keuangan.approval.approve');
        Route::post('/approval/{id_pembayaran}/reject', [ApprovalController::class, 'reject'])->name('keuangan.approval.reject');
        Route::get('/approval-approved', [ApprovalController::class, 'approved'])->name('keuangan.approval.approved');
        Route::get('/approval-rejected', [ApprovalController::class, 'rejected'])->name('keuangan.approval.rejected');

        // API endpoint untuk status pembayaran proyek
        Route::get('/project-payment-status/{proyekId}', [ApprovalController::class, 'getProjectPaymentStatus'])->name('keuangan.project.payment.status');

        // Penagihan Dinas routes
        Route::get('/penagihan', [PenagihanDinasController::class, 'index'])->name('keuangan.penagihan');
        Route::get('/penagihan-dinas', [PenagihanDinasController::class, 'index'])->name('penagihan-dinas.index');
        Route::get('/penagihan-dinas/create/{proyekId}', [PenagihanDinasController::class, 'create'])->name('penagihan-dinas.create');
        Route::post('/penagihan-dinas', [PenagihanDinasController::class, 'store'])->name('penagihan-dinas.store');
        Route::get('/penagihan-dinas/{id}', [PenagihanDinasController::class, 'show'])->name('penagihan-dinas.show');
        Route::get('/penagihan-dinas/{id}/edit', [PenagihanDinasController::class, 'edit'])->name('penagihan-dinas.edit');
        Route::put('/penagihan-dinas/{id}', [PenagihanDinasController::class, 'update'])->name('penagihan-dinas.update');
        Route::get('/penagihan-dinas/{id}/pelunasan', [PenagihanDinasController::class, 'showPelunasan'])->name('penagihan-dinas.show-pelunasan');
        Route::post('/penagihan-dinas/{id}/pelunasan', [PenagihanDinasController::class, 'addPelunasan'])->name('penagihan-dinas.pelunasan');
        Route::get('/penagihan-dinas/{id}/history', [PenagihanDinasController::class, 'history'])->name('penagihan-dinas.history');
        Route::get('/penagihan-dinas/{id}/download/{jenis}', [PenagihanDinasController::class, 'downloadDokumen'])->name('penagihan-dinas.download-dokumen');
        Route::get('/penagihan-dinas/{id}/preview/{jenis}', [PenagihanDinasController::class, 'previewDokumen'])->name('penagihan-dinas.preview-dokumen');
        Route::get('/penagihan-dinas/bukti/{id}/download', [PenagihanDinasController::class, 'downloadBuktiPembayaran'])->name('penagihan-dinas.download-bukti');
        Route::get('/penagihan-dinas/bukti/{id}/preview', [PenagihanDinasController::class, 'previewBuktiPembayaran'])->name('penagihan-dinas.preview-bukti');
        Route::get('/penagihan-dinas/bukti-pembayaran/{id}/download', [PenagihanDinasController::class, 'downloadBuktiPembayaran'])->name('penagihan-dinas.download-bukti-pembayaran');
        Route::get('/penagihan-dinas/bukti-pembayaran/{id}/preview', [PenagihanDinasController::class, 'previewBuktiPembayaran'])->name('penagihan-dinas.preview-bukti-pembayaran');
        Route::delete('/penagihan-dinas/{id}', [PenagihanDinasController::class, 'destroy'])->name('penagihan-dinas.destroy');
        Route::delete('/penagihan-dinas/{id}/dokumen/{jenis}', [PenagihanDinasController::class, 'deleteDokumen'])->name('penagihan-dinas.delete-dokumen');
        Route::delete('/penagihan-dinas/bukti-pembayaran/{buktiId}', [PenagihanDinasController::class, 'deleteBuktiPembayaran'])->name('penagihan-dinas.delete-bukti-pembayaran');

    });

    Route::get('/produk', [ProdukController::class, 'index_produk'])->name('produk');
    Route::get('/produk/export', [ProdukController::class, 'export'])->name('produk.export');


    Route::get('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan');
    Route::put('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.update');

    // Admin only routes
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/pengelolaan-akun', [PengelolaanAkun::class, 'index'])->name('pengelolaan.akun');
        Route::post('/pengelolaan-akun', [PengelolaanAkun::class, 'store'])->name('pengelolaan.akun.store');
        Route::get('/pengelolaan-akun/{user}', [PengelolaanAkun::class, 'show'])->name('pengelolaan.akun.show');
        Route::put('/pengelolaan-akun/{user}', [PengelolaanAkun::class, 'update'])->name('pengelolaan.akun.update');
        Route::delete('/pengelolaan-akun/{user}', [PengelolaanAkun::class, 'destroy'])->name('pengelolaan.akun.destroy');
    });

    // Verifikasi Proyek Routes - Accessible by Superadmin and Manager Marketing
    Route::middleware('auth')->group(function () {
        Route::get('/verifikasi-proyek', [VerifikasiProyekController::class, 'index'])->name('superadmin.verifikasi-proyek');
        Route::get('/verifikasi-proyek/{id}', [VerifikasiProyekController::class, 'show'])->name('superadmin.verifikasi-proyek.detail');
        Route::put('/verifikasi-proyek/{id}/verify', [VerifikasiProyekController::class, 'verify'])->name('superadmin.verifikasi-proyek.verify');
        Route::get('/verifikasi-proyek-history', [VerifikasiProyekController::class, 'history'])->name('superadmin.verifikasi-proyek.history');
    });
});
