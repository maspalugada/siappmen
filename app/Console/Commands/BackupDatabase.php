<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--cleanup : Hapus backup lama}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database SiAPPMEN dengan cleanup otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai backup database SiAPPMEN...');

        try {
            // Buat direktori backup jika belum ada
            $backupPath = storage_path('backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            // Generate nama file backup
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "siappmen_backup_{$timestamp}.sql";
            $filepath = $backupPath . DIRECTORY_SEPARATOR . $filename;

            $this->info("Membuat backup ke: {$filepath}");

            // Ambil konfigurasi database
            $dbConfig = config('database.connections.mysql');
            $host = $dbConfig['host'];
            $database = $dbConfig['database'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];

            // Command mysqldump
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Eksekusi command
            $output = null;
            $returnCode = null;
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception('Gagal menjalankan mysqldump: ' . implode("\n", $output));
            }

            // Verifikasi file backup
            if (!file_exists($filepath) || filesize($filepath) === 0) {
                throw new \Exception('File backup tidak dibuat atau kosong');
            }

            $fileSize = $this->formatBytes(filesize($filepath));
            $this->info("✅ Backup berhasil dibuat: {$filename} ({$fileSize})");

            // Log aktivitas backup
            Log::info('Database backup created', [
                'filename' => $filename,
                'size' => $fileSize,
                'path' => $filepath
            ]);

            // Cleanup backup lama jika diminta
            if ($this->option('cleanup')) {
                $this->cleanupOldBackups($backupPath);
            }

            $this->info('🎉 Backup database selesai!');

        } catch (\Exception $e) {
            $this->error('❌ Backup gagal: ' . $e->getMessage());
            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Cleanup backup files yang lebih lama dari 30 hari
     */
    private function cleanupOldBackups(string $backupPath): void
    {
        $this->info('🧹 Membersihkan backup lama...');

        $files = glob($backupPath . DIRECTORY_SEPARATOR . 'siappmen_backup_*.sql');
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
            $this->info('ℹ️  Tidak ada backup lama yang perlu dihapus');
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
