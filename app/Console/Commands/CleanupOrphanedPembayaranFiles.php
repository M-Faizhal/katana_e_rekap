<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;

class CleanupOrphanedPembayaranFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pembayaran:cleanup-files {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned pembayaran files that are not referenced in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('🧹 Starting cleanup of orphaned pembayaran files...');
        
        if ($isDryRun) {
            $this->warn('⚠️  DRY RUN MODE - No files will actually be deleted');
        }

        // Ambil semua file di folder pembayaran
        $allFiles = Storage::disk('public')->files('pembayaran');
        $this->info("📁 Found " . count($allFiles) . " files in pembayaran folder");
        
        // Ambil semua path file yang masih digunakan di database
        $usedFiles = Pembayaran::whereNotNull('bukti_bayar')
            ->pluck('bukti_bayar')
            ->toArray();
        
        $this->info("📊 Found " . count($usedFiles) . " files referenced in database");

        // Cari file yang tidak terpakai
        $orphanedFiles = array_diff($allFiles, $usedFiles);
        
        if (empty($orphanedFiles)) {
            $this->info("✅ No orphaned files found. All files are properly referenced.");
            return 0;
        }

        $this->warn("🗑️  Found " . count($orphanedFiles) . " orphaned files:");
        
        foreach ($orphanedFiles as $file) {
            $this->line("   - {$file}");
        }

        if ($isDryRun) {
            $this->info("⚠️  DRY RUN: Would delete " . count($orphanedFiles) . " files");
            return 0;
        }

        // Konfirmasi sebelum menghapus
        if (!$this->confirm('Are you sure you want to delete these orphaned files?')) {
            $this->info('Operation cancelled');
            return 0;
        }

        $deletedCount = 0;
        $failedCount = 0;

        foreach ($orphanedFiles as $file) {
            try {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                $this->info("✅ Deleted: {$file}");
                Log::info("Orphaned file deleted via command: {$file}");
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("❌ Failed to delete: {$file} - " . $e->getMessage());
                Log::error("Failed to delete orphaned file: {$file}. Error: " . $e->getMessage());
            }
        }

        $this->info("🎉 Cleanup completed!");
        $this->info("✅ Successfully deleted: {$deletedCount} files");
        
        if ($failedCount > 0) {
            $this->warn("⚠️  Failed to delete: {$failedCount} files");
        }

        return 0;
    }
}
