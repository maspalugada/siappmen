<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Instrument;
use App\Models\Transaction;

class VerifikasiDistribusi extends Component
{
    public $qrCode = '';
    public $alat = null;
    public $message = '';

    public function updatedQrCode($value)
    {
        $this->message = '';
        $this->alat = null;

        $clean = trim($value);

        $instrument = Instrument::where('qr_code', $clean)
            ->orWhere('code', $clean)
            ->first();

        if (!$instrument) {
            $this->message = 'QR tidak dikenali.';
            return;
        }

        $this->alat = $instrument;

        // Cari transaksi aktif (belum diverifikasi)
        $trx = Transaction::whereHas('items', function ($query) use ($instrument) {
            $query->where('instrument_id', $instrument->id);
        })
            ->where('status', 'delivered')
            ->latest()
            ->first();

        if (!$trx) {
            $this->message = 'Tidak ada distribusi aktif untuk alat ini.';
            return;
        }

        // Update status menjadi received
        $trx->update(['status' => 'received']);
        $instrument->update(['status' => 'in_use']);

        log_activity('DISTRIBUTION_VERIFIED', "Alat {$instrument->name} diverifikasi diterima.", [
            'instrument_id' => $instrument->id,
            'transaction_id' => $trx->id,
            'unit' => $instrument->unit->name ?? '-'
        ]);

        $this->message = "✅ Verifikasi berhasil! Alat {$instrument->name} telah diterima.";
    }

    public function render()
    {
        $transactions = Transaction::with('instrument.unit')
            ->where('status', 'delivered')
            ->latest()
            ->get();

        return view('livewire.verifikasi-distribusi', [
            'transactions' => $transactions
        ])->layout('layouts.siappmen');
    }
}
