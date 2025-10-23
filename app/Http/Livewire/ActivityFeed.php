<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ActivityLog;
use Livewire\WithPagination;

class ActivityFeed extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $actionFilter = '';
    public $dateStart;
    public $dateEnd;

    protected $queryString = ['search', 'roleFilter', 'actionFilter', 'dateStart', 'dateEnd'];

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, fn($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->when($this->roleFilter, fn($q) => $q->where('target_role', $this->roleFilter))
            ->when($this->actionFilter, fn($q) => $q->where('action', $this->actionFilter))
            ->when($this->dateStart && $this->dateEnd, function ($q) {
                $q->whereBetween('created_at', [$this->dateStart, $this->dateEnd]);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.activity-feed', ['logs' => $logs]);
    }
}
