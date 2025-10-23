<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ActivityLog;

class ActivityFeed extends Component
{
    public $logs = [];

    protected $listeners = ['refreshFeed' => 'loadFeed'];

    public function mount()
    {
        $this->loadFeed();
    }

    public function loadFeed()
    {
        $this->logs = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.activity-feed');
    }
}
