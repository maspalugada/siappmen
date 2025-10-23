<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class NavbarNotification extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $role = Auth::user()->role;

        $this->notifications = ActivityLog::where('target_role', $role)
            ->latest()
            ->take(5)
            ->get();

        $this->unreadCount = $this->notifications->count();
    }

    public function render()
    {
        return view('livewire.navbar-notification');
    }
}
