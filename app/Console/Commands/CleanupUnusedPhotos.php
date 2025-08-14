<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class CleanupUnusedPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanup-photos {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused profile photos from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info('Scanning for unused profile photos...');

        // Get all photos in storage
        $allPhotos = collect(Storage::disk('public')->files('profile-photos'))
            ->merge(Storage::disk('public')->files('photos'))
            ->filter(function ($file) {
                return !str_contains($file, '.gitignore');
            });

        // Get all used photos from database
        $usedPhotos = User::whereNotNull('foto')->pluck('foto')->toArray();

        // Find unused photos
        $unusedPhotos = $allPhotos->diff($usedPhotos);

        if ($unusedPhotos->isEmpty()) {
            $this->info('No unused photos found.');
            return;
        }

        $this->info("Found {$unusedPhotos->count()} unused photos:");

        foreach ($unusedPhotos as $photo) {
            $this->line("  - {$photo}");
        }

        if ($isDryRun) {
            $this->info("\nDry run mode - no files were deleted.");
            $this->info("Run without --dry-run to actually delete these files.");
            return;
        }

        if (!$this->confirm('Do you want to delete these unused photos?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $deletedCount = 0;
        foreach ($unusedPhotos as $photo) {
            if (Storage::disk('public')->delete($photo)) {
                $deletedCount++;
                $this->line("Deleted: {$photo}");
            } else {
                $this->error("Failed to delete: {$photo}");
            }
        }

        $this->info("\nCleanup completed. Deleted {$deletedCount} files.");
    }
}
