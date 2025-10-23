<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Instrument;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Unit;

class TransaksiCssd extends Component
{
    public $orderNo;
    public $borrower;
    public $selectedUnit = '';
    public $dateRequest;
    public $dateReturn;
    public $instruments = [];
    public $units = [];
    public $selectedItems = [];
    public $showTable = false;

    public function mount()
    {
        $this->units = Unit::select('id', 'name')->get();
        $this->orderNo = 'ORD-' . now()->format('YmdHis');
        $this->dateRequest = now()->format('Y-m-d');
    }

    public function updatedSelectedUnit()
    {
        if ($this->selectedUnit) {
            $this->instruments = Instrument::where('status', 'available')->get();
            $this->showTable = true;
        } else {
            $this->showTable = false;
        }
    }

    public function addInstrument($id)
    {
        $instrument = Instrument::find($id);
        if (!$instrument) {
            session()->flash('error', 'Instrumen tidak ditemukan.');
            return;
        }

        $exists = collect($this->selectedItems)->firstWhere('id', $id);
        if ($exists) {
            session()->flash('error', 'Instrumen sudah ditambahkan.');
            return;
        }

        $this->selectedItems[] = [
            'id' => $instrument->id,
            'code' => $instrument->code,
            'name' => $instrument->name,
            'qty' => 1,
            'condition' => 'Baik',
            'notes' => ''
        ];
    }

    public function removeInstrument($index)
    {
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);
    }

    public function saveOrder()
    {
        if (!$this->selectedUnit || !$this->borrower || empty($this->selectedItems)) {
            session()->flash('error', 'Lengkapi semua data dan tambahkan instrumen.');
            return;
        }

        $unit = Unit::find($this->selectedUnit);
        $order = Order::create([
            'order_no' => $this->orderNo,
            'unit_id' => $unit->id,
            'requested_by' => auth()->id(),
            'date_request' => $this->dateRequest,
            'date_return' => $this->dateReturn,
            'status' => 'pending'
        ]);

        foreach ($this->selectedItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'instrument_id' => $item['id'],
                'qty' => $item['qty'],
                'notes' => $item['notes']
            ]);
        }

        session()->flash('message', 'Transaksi berhasil disimpan.');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->borrower = '';
        $this->selectedUnit = '';
        $this->dateReturn = '';
        $this->selectedItems = [];
        $this->orderNo = 'ORD-' . now()->format('YmdHis');
        $this->showTable = false;
    }

    public function render()
    {
        $orders = Order::with('unit')->latest()->take(5)->get();

        return view('livewire.transaksi-cssd', [
            'orders' => $orders
        ])->layout('layouts.siappmen');
    }
}
