<?php

namespace App\Events;

use App\Models\ActivityLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewActivityEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log;

    public function __construct(ActivityLog $log)
    {
        $this->log = $log;
    }

    public function broadcastOn()
    {
        return new Channel('activity');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->log->id,
            'user' => $this->log->user->name ?? 'System',
            'description' => $this->log->description,
            'time' => $this->log->created_at->diffForHumans(),
            'target_role' => $this->log->target_role,
        ];
    }
}
