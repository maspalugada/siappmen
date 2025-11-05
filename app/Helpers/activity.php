<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;

if (!function_exists('log_activity')) {
    function log_activity(string $action, ?string $description = null, array $meta = [], ?string $targetRole = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'meta' => $meta,
            'target_role' => $targetRole
        ]);
    }
}

if (!function_exists('get_last_backup_date')) {
    function get_last_backup_date()
    {
        $backupDestination = BackupDestination::create('local', config('backup.backup.name'));
        $lastBackup = $backupDestination->backups()->first();

        return $lastBackup ? $lastBackup->date()->format('d/m/Y H:i') : 'No backups found';
    }
}
