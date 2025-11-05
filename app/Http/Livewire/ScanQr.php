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

    public function updatedQrCode($value)
    {
        $this->error = '';
        $this->result = null;
        // Bersihkan input QR dari spasi dan karakter tidak penting
        $instrument = Instrument::where('qr_code', $clean)
            ->orWhere('code', $clean)
            ->with('unit')
            ->first();
        if ($instrument) {
            $this->result = [
                'name' => $instrument->name,
                'unit' => $instrument->unit->name ?? '-',
                'status' => $instrument->status,
            ];
            // Catat ke log aktivitas
            log_activity('SCAN_SUCCESS', "Alat {$instrument->name} berhasil dipindai", [
                'instrument_code' => $instrument->code,
                'qr_code' => $instrument->qr_code,
                'unit' => $instrument->unit->name ?? '-',
            ]);
        } else {
            $this->error = 'QR tidak dikenali atau alat belum terdaftar.';
            log_activity('SCAN_FAILED', "QR tidak valid: {$clean}");
        }

        $this->processQr(['code' => $value]);
    }

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
