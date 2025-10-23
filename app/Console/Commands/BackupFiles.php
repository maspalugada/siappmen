<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class BackupFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:files {--cleanup : Hapus backup lama}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup file storage SiAPPMEN dengan cleanup otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai backup file storage SiAPPMEN...');

        try {
            // Buat direktori backup jika belum ada
            $backupPath = storage_path('backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            // Generate nama file backup
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "siappmen_files_backup_{$timestamp}.zip";
            $filepath = $backupPath . DIRECTORY_SEPARATOR . $filename;

            $this->info("Membuat backup file ke: {$filepath}");

            // Direktori yang akan di-backup
            $directoriesToBackup = [
                'storage/app/public',     // File uploads public
                'storage/app/private',    // File private (jika ada)
                'storage/logs',           // Log files
                'storage/backups',        // Backup files sebelumnya
            ];

            // Buat ZIP archive
            $zip = new ZipArchive();
            if ($zip->open($filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception('Tidak dapat membuat file ZIP');
            }

            $totalFiles = 0;
            $totalSize = 0;

            foreach ($directoriesToBackup as $dir) {
                $fullPath = base_path($dir);
                if (file_exists($fullPath)) {
                    $this->info("📁 Memproses direktori: {$dir}");
                    $files = $this->getFilesFromDirectory($fullPath);

                    foreach ($files as $file) {
                        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                        $zip->addFile($file, $relativePath);
                        $totalFiles++;
                        $totalSize += filesize($file);
                    }
                } else {
                    $this->warn("⚠️  Direktori tidak ditemukan: {$dir}");
                }
            }

            $zip->close();

            // Verifikasi file backup
            if (!file_exists($filepath) || filesize($filepath) === 0) {
                throw new \Exception('File backup ZIP tidak dibuat atau kosong');
            }

            $fileSize = $this->formatBytes(filesize($filepath));
            $this->info("✅ Backup file berhasil dibuat: {$filename}");
            $this->info("📊 Statistik: {$totalFiles} file, {$this->formatBytes($totalSize)} data asli, {$fileSize} terkompresi");

            // Log aktivitas backup
            Log::info('File backup created', [
                'filename' => $filename,
                'total_files' => $totalFiles,
                'original_size' => $totalSize,
                'compressed_size' => filesize($filepath),
                'path' => $filepath
            ]);

            // Cleanup backup lama jika diminta
            if ($this->option('cleanup')) {
                $this->cleanupOldBackups($backupPath);
            }

            $this->info('🎉 Backup file storage selesai!');

        } catch (\Exception $e) {
            $this->error('❌ Backup file gagal: ' . $e->getMessage());
            Log::error('File backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Mendapatkan semua file dari direktori secara rekursif
     */
    private function getFilesFromDirectory(string $directory): array
    {
        $files = [];

        if (!file_exists($directory)) {
            return $files;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Cleanup backup files yang lebih lama dari 30 hari
     */
    private function cleanupOldBackups(string $backupPath): void
    {
        $this->info('🧹 Membersihkan backup file lama...');

        $files = glob($backupPath . DIRECTORY_SEPARATOR . 'siappmen_files_backup_*.zip');
        $deletedCount = 0;
        $totalSize = 0;

        foreach ($files as $file) {
            $fileTime = filemtime($file);
            $thirtyDaysAgo = Carbon::now()->subDays(30)->timestamp;

            if ($fileTime < $thirtyDaysAgo) {
                $fileSize = filesize($file);
                if (unlink($file)) {
                    $deletedCount++;
                    $totalSize += $fileSize;
                    $this->line("🗑️  Menghapus: " . basename($file));
                }
            }
        }

        if ($deletedCount > 0) {
            $this->info("✅ Dihapus {$deletedCount} file backup lama, menghemat {$this->formatBytes($totalSize)}");
        } else {
            $this->info('ℹ️  Tidak ada backup file lama yang perlu dihapus');
        }
    }

    /**
     * Format bytes ke format yang mudah dibaca
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
