<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Instrument;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Unit melihat daftar ordernya
    public function index()
    {
        $orders = Order::with('items.instrument')
            ->where('requested_by', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // Form order baru
    public function create()
    {
        $instruments = Instrument::all();
        return view('orders.create', compact('instruments'));
    }

    // Simpan order baru
    public function store(Request $request)
    {
        $request->validate([
            'date_return_planned' => 'required|date',
            'items' => 'required|array',
            'items.*.instrument_id' => 'required|exists:instruments,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'order_no' => 'ORD' . strtoupper(Str::random(5)),
            'unit_id' => Auth::user()->unit_id ?? 1,
            'requested_by' => Auth::id(),
            'date_request' => now(),
            'date_return_planned' => $request->date_return_planned,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'instrument_id' => $item['instrument_id'],
                'qty' => $item['qty'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        log_activity(
            'ORDER_CREATED',
            'Order baru dibuat oleh ' . Auth::user()->name,
            ['order_no' => $order->order_no]
        );

        $this->dispatchBrowserEvent('activity', ['message' => 'Order baru berhasil dibuat!']);

        return redirect()->route('orders.index')->with('success', 'Order berhasil dikirim ke CSSD');
    }

    // CSSD melihat semua order
    public function manage()
    {
        $orders = Order::with('unit', 'items.instrument', 'requester')->latest()->get();
        return view('orders.manage', compact('orders'));
    }

    // Ubah status
    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);

        log_activity(
            'ORDER_STATUS_UPDATED',
            "Order {$order->order_no} diubah menjadi {$request->status} oleh " . Auth::user()->name,
            ['order_id' => $order->id, 'new_status' => $request->status]
        );

        return back()->with('success', 'Status order diperbarui!');
    }
}
