<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use App\Events\NewActivityEvent;

if (!function_exists('log_activity')) {
    function log_activity(string $action, ?string $description = null, array $meta = [], ?string $targetRole = null): void
    {
        try {
            $user = Auth::user();

            $activity = ActivityLog::create([
                'user_id'     => $user->id ?? null,
                'target_role' => $targetRole ?? ($user->role ?? 'system'),
                'action'      => strtoupper($action),
                'description' => $description,
                'metadata'    => [
                    'ip'         => Request::ip(),
                    'url'        => Request::fullUrl(),
                    'user_agent' => Request::header('User-Agent'),
                    'source'     => app()->runningInConsole() ? 'CLI/Tinker' : 'web',
                    ...$meta,
                ],
            ]);

            event(new NewActivityEvent($activity));

        } catch (\Throwable $e) {
            Log::error('Gagal mencatat log aktivitas: ' . $e->getMessage());
        }
    }
}
