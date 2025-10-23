<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Distribution;
use App\Models\ActivityLog;
use App\Helpers\activity;

class DistribusiSteril extends Component
{
    public $ordersReady = [];
    public $distributions = [];
    public $selectedOrder = null;
    public $notes = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Orders yang sudah steril dan siap distribusi
        $this->ordersReady = Order::where('status', 'sterilized')
            ->with('unit')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Riwayat distribusi terakhir
        $this->distributions = Distribution::with(['order.unit'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with('unit')->find($orderId);
    }

    public function sendDistribution()
    {
        if (!$this->selectedOrder) {
            session()->flash('error', 'Order tidak dipilih.');
            return;
        }

        if ($this->selectedOrder->status !== 'sterilized') {
            session()->flash('error', 'Order belum siap untuk distribusi.');
            return;
        }

        // Buat record distribusi
        Distribution::create([
            'order_id' => $this->selectedOrder->id,
            'unit_id' => $this->selectedOrder->unit_id,
            'delivered_by' => auth()->id(),
            'date_delivered' => now(),
            'status' => 'delivered',
            'notes' => $this->notes
        ]);

        // Update status order
        $this->selectedOrder->update(['status' => 'distributed']);

        // Log aktivitas
        activity('distribution', "Order {$this->selectedOrder->order_no} dikirim ke unit {$this->selectedOrder->unit->name}", $this->selectedOrder->unit_id, 'unit');

        session()->flash('message', 'Order berhasil dikirim ke unit.');

        // Reset form
        $this->selectedOrder = null;
        $this->notes = '';

        // Reload data
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.distribusi-steril')
            ->layout('layouts.siappmen');
    }
}
