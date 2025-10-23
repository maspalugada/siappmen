<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Instrument;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Unit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderForm extends Component
{
    public $borrowedBy = '';
    public $selectedUnit = '';
    public $borrowDate = '';
    public $plannedReturnDate = '';
    public $orderNumber = '';
    public $selectedInstruments = [];
    public $qrCode = '';

    public $units = [];
    public $instruments = [];

    public function mount()
    {
        $this->units = Unit::select('name')->distinct()->pluck('name');
        $this->generateOrderNumber();
    }

    public function updatedSelectedUnit()
    {
        $this->instruments = Instrument::where('status', 'available')->get();
        $this->selectedInstruments = [];
    }

    public function toggleInstrument($instrumentId)
    {
        if (in_array($instrumentId, $this->selectedInstruments)) {
            $this->selectedInstruments = array_diff($this->selectedInstruments, [$instrumentId]);
        } else {
            $this->selectedInstruments[] = $instrumentId;
        }
    }

    public function generateOrderNumber()
    {
        $this->orderNumber = 'ORD-' . now()->format('YmdHis');
    }

    public function saveOrder()
    {
        $this->validate([
            'borrowedBy' => 'required|string',
            'selectedUnit' => 'required|string',
            'borrowDate' => 'required|date',
            'plannedReturnDate' => 'required|date|after:borrowDate',
            'selectedInstruments' => 'required|array|min:1',
        ]);

        $unit = Unit::where('name', $this->selectedUnit)->first();
        if (!$unit) {
            session()->flash('error', 'Unit tidak ditemukan!');
            return;
        }

        // Buat order
        $order = Order::create([
            'order_no' => $this->orderNumber,
            'unit_id' => $unit->id,
            'requested_by' => auth()->id(),
            'date_request' => $this->borrowDate,
            'date_return_planned' => $this->plannedReturnDate,
            'status' => 'pending'
        ]);

        // Buat order items
        foreach ($this->selectedInstruments as $instrumentId) {
            OrderItem::create([
                'order_id' => $order->id,
                'instrument_id' => $instrumentId,
                'qty' => 1,
                'notes' => 'Order dari CSSD untuk unit ' . $this->selectedUnit . ' oleh ' . $this->borrowedBy
            ]);
        }

        // Generate QR Code
        $this->qrCode = base64_encode(QrCode::format('png')->size(150)->generate($this->orderNumber));

        // Log activity
        log_activity(
            'ORDER_CREATED',
            'Order dibuat untuk unit ' . $this->selectedUnit . ' dengan ' . count($this->selectedInstruments) . ' instrumen',
            ['order_id' => $order->id, 'instruments' => $this->selectedInstruments]
        );

        session()->flash('message', 'Order berhasil dibuat dengan nomor ' . $this->orderNumber . '!');

        // Reset form
        $this->reset(['borrowedBy', 'selectedUnit', 'borrowDate', 'plannedReturnDate', 'selectedInstruments']);
        $this->generateOrderNumber();
    }

    public function render()
    {
        return view('livewire.order-form')
            ->layout('layouts.siappmen');
    }
}
