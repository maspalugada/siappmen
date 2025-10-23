<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:full {--cleanup : Hapus backup lama} {--no-confirm : Jalankan tanpa konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup lengkap SiAPPMEN (database + files) dengan cleanup otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai backup lengkap SiAPPMEN...');
        $this->info('Ini akan membuat backup database dan file storage');

        // Konfirmasi jika tidak ada flag --no-confirm
        if (!$this->option('no-confirm')) {
            if (!$this->confirm('Apakah Anda yakin ingin melanjutkan backup lengkap?')) {
                $this->info('❌ Backup dibatalkan oleh user');
                return Command::SUCCESS;
            }
        }

        $startTime = microtime(true);
        $cleanup = $this->option('cleanup');

        try {
            // Backup Database
            $this->info('📊 Memulai backup database...');
            $dbExitCode = Artisan::call('backup:database', [
                '--cleanup' => $cleanup,
            ], $this->getOutput());

            if ($dbExitCode !== 0) {
                throw new \Exception('Backup database gagal');
            }

            // Backup Files
            $this->info('📁 Memulai backup file storage...');
            $filesExitCode = Artisan::call('backup:files', [
                '--cleanup' => $cleanup,
            ], $this->getOutput());

            if ($filesExitCode !== 0) {
                throw new \Exception('Backup file storage gagal');
            }

            // Hitung waktu eksekusi
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            // Log aktivitas backup lengkap
            Log::info('Full backup completed', [
                'execution_time' => $executionTime,
                'cleanup_performed' => $cleanup,
                'timestamp' => Carbon::now()->toISOString()
            ]);

            $this->info("✅ Backup lengkap selesai dalam {$executionTime} detik!");
            $this->info('🎉 Semua data SiAPPMEN telah berhasil di-backup');

            // Tampilkan ringkasan
            $this->displayBackupSummary();

        } catch (\Exception $e) {
            $this->error('❌ Backup lengkap gagal: ' . $e->getMessage());
            Log::error('Full backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time' => round(microtime(true) - $startTime, 2)
            ]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Tampilkan ringkasan backup yang telah dibuat
     */
    private function displayBackupSummary(): void
    {
        $backupPath = storage_path('backups');

        if (!file_exists($backupPath)) {
            return;
        }

        $this->info('📋 Ringkasan Backup:');

        // Cari file backup terbaru
        $dbFiles = glob($backupPath . DIRECTORY_SEPARATOR . 'siappmen_backup_*.sql');
        $fileBackups = glob($backupPath . DIRECTORY_SEPARATOR . 'siappmen_files_backup_*.zip');

        // Database backup terbaru
        if (!empty($dbFiles)) {
            rsort($dbFiles);
            $latestDb = $dbFiles[0];
            $dbSize = $this->formatBytes(filesize($latestDb));
            $dbTime = date('Y-m-d H:i:s', filemtime($latestDb));
            $this->line("  🗄️  Database: " . basename($latestDb) . " ({$dbSize}) - {$dbTime}");
        }

        // File backup terbaru
        if (!empty($fileBackups)) {
            rsort($fileBackups);
            $latestFile = $fileBackups[0];
            $fileSize = $this->formatBytes(filesize($latestFile));
            $fileTime = date('Y-m-d H:i:s', filemtime($latestFile));
            $this->line("  📦 Files: " . basename($latestFile) . " ({$fileSize}) - {$fileTime}");
        }

        // Total backup files
        $allBackups = array_merge($dbFiles, $fileBackups);
        $totalSize = 0;
        foreach ($allBackups as $backup) {
            $totalSize += filesize($backup);
        }

        $this->line("  📊 Total: " . count($allBackups) . " file backup, {$this->formatBytes($totalSize)}");
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
