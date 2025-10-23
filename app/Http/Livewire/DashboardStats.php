<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pouch;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class DashboardStats extends Component
{
    protected $listeners = ['refresh' => 'loadData'];

    public $pouchStats;
    public $orderStats;
    public $monthlyTransactions;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Statistik pouch
        $this->pouchStats = [
            'clean'  => Pouch::where('status', 'clean')->count(),
            'dirty'  => Pouch::where('status', 'dirty')->count(),
            'in_use' => Pouch::where('status', 'in_use')->count(),
        ];

        // Statistik order
        $this->orderStats = [
            'pending'   => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'total'     => Order::count(),
        ];

        // Transaksi per bulan (12 bulan terakhir)
        $this->monthlyTransactions = Transaction::select(
            DB::raw('MONTH(occurred_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('occurred_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(fn($item) => ['month' => $item->month, 'total' => $item->total])
        ->toArray();
        
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
    
}
