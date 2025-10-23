<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pouch;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class DashboardChart extends Component
{
    protected $listeners = ['refreshDashboard' => 'loadData'];

    public $pouchStats;
    public $orderStats;
    public $monthlyTransactions;
    public $unitStats;

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

        // Transaksi bulanan
        $this->monthlyTransactions = Transaction::select(
            DB::raw('MONTH(occurred_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('occurred_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(fn($t) => ['month' => $t->month, 'total' => $t->total])
        ->toArray();

        // Statistik per unit (berdasarkan orders)
        $this->unitStats = Order::join('units', 'orders.unit_id', '=', 'units.id')
            ->select('units.name as unit_name',
                DB::raw('SUM(orders.status = "pending") as pending'),
                DB::raw('SUM(orders.status = "completed") as completed'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('units.id', 'units.name')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard-chart');
    }
}
