<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pouch;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    // Form scan (menampilkan halaman kamera)
    public function showReturnForm()
    {
        return view('scans.return');
    }

    // Simpan data hasil scan QR (pengembalian instrumen kotor)
    public function returnDirty(Request $request)
    {
        $request->validate([
            'pouch_code'   => 'required|string',
            'patient_name' => 'required|string',
            'qty'          => 'required|integer|min:1',
        ]);

        $pouch = Pouch::where('pouch_code', $request->pouch_code)->first();

        if (!$pouch) {
            return response()->json(['error' => 'Pouch tidak ditemukan'], 404);
        }

        // Buat transaksi pengembalian kotor
        $tx = Transaction::create([
            'type'         => 'return_dirty',
            'reference_id' => null,
            'user_id'      => Auth::id(),
            'unit_id'      => $request->unit_id ?? null,
            'occurred_at'  => Carbon::now(),
            'notes'        => "Pengembalian alat untuk pasien: {$request->patient_name}",
        ]);

        // Tambahkan item transaksi
        TransactionItem::create([
            'transaction_id' => $tx->id,
            'instrument_id'  => $pouch->instrument_id,
            'qty'            => $request->qty,
            'is_complete'    => true,
            'damaged_qty'    => 0,
        ]);

        // Update status pouch
        $pouch->update(['status' => 'dirty']);

        log_activity(
            'POUCH_RETURNED',
            'Pouch dikembalikan dari Unit oleh ' . Auth::user()->name
        );

        return response()->json([
            'message' => 'Pengembalian instrumen berhasil dicatat.',
            'transaction' => $tx
        ]);
    }
}
