<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Events\NewActivityEvent;


if (!function_exists('log_activity')) {
    function log_activity($action, $description = null, $metadata = [], $targetRole = null)
    {
        $log = ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'target_role' => $targetRole,
        ]);

        broadcast(new NewActivityEvent($log))->toOthers();
    }
}