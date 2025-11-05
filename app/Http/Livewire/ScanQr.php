<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Instrument;

class ScanQr extends Component
{
    public $qrCode;
    public $result = null;
    public $error = null;

    protected $listeners = ['qrScanned' => 'processQr'];


    public function processQr($data)
    {
        $this->reset(['result', 'error']);
        $code = trim($data['code'] ?? '');

        if (!$code) {
            $this->error = 'QR Code kosong atau tidak valid.';
            return;
        }

        $instrument = Instrument::with('unit')->where('qr_code', $code)->first();

        if ($instrument) {
            $this->result = [
                'code' => $instrument->code,
                'name' => $instrument->name,
                'status' => $instrument->status,
                'unit' => $instrument->unit->name ?? '-',
            ];

            // Simpan ke log aktivitas
            log_activity('QR_SCANNED', 'Validasi alat melalui QR', [
                'instrument_id' => $instrument->id,
                'qr_code' => $instrument->qr_code,
            ]);
        } else {
            $this->error = 'QR Code tidak ditemukan dalam database.';
        }
    }

    public function render()
    {
        return view('livewire.scan-qr');
    }
}
