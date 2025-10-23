<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Models\Unit;
use Illuminate\Support\Facades\Response;

class QRValidationController extends Controller
{
    public function validateQR(Request $request)
    {
        $code = $request->input('code');

        $instrument = Instrument::where('qr_code', $code)->first();

        if (!$instrument) {
            log_activity('QR_SCAN_FAILED', 'Kode QR tidak ditemukan', ['qr_code' => $code]);
            return Response::json(['status' => 'error', 'message' => 'QR tidak terdaftar'], 404);
        }

        // Cek status alat
        if ($instrument->status !== 'steril') {
            log_activity('QR_SCAN_INVALID_STATUS', 'Status alat tidak steril', [
                'qr_code' => $code,
                'status' => $instrument->status
            ]);
            return Response::json(['status' => 'error', 'message' => 'Alat belum steril / sedang dipakai'], 400);
        }

        // Tambahan validasi QR code
        $instrument = Instrument::where('qr_code', $this->scannedQr)->first();

        if (!$instrument) {
            $this->addError('qr', 'QR Code tidak dikenal.');
            return;
        }

        if ($instrument->status !== 'steril') {
            $this->addError('qr', 'Alat belum steril atau masih digunakan.');
            return;
        }

        // Cek keterkaitan unit
        $unit = $instrument->unit ? $instrument->unit->name : 'Belum diassign';
        log_activity('QR_VALIDATED', 'Validasi QR berhasil', [
            'qr_code' => $code,
            'instrument' => $instrument->name,
            'unit' => $unit,
        ]);

        // Setelah validasi, buat entri transaksi
        $targetUnit = $instrument->unit; // Asumsi unit tujuan adalah unit yang terkait dengan instrument
        if ($targetUnit) {
            \App\Models\Transaction::create([
                'instrument_id' => $instrument->id,
                'from_unit_id' => null, // CSSD
                'to_unit_id' => $targetUnit->id,
                'status' => 'delivered',
                'occurred_at' => now(),
            ]);

            // Lalu ubah status alat
            $instrument->update(['status' => 'in_use']);

            // Dan catat log aktivitas
            log_activity(
                'DISTRIBUTION_CONFIRMED',
                "Alat {$instrument->name} telah dikirim ke {$targetUnit->name}",
                ['instrument_id' => $instrument->id, 'unit' => $targetUnit->name]
            );
        }

        return Response::json([
            'status' => 'success',
            'instrument' => [
                'name' => $instrument->name,
                'unit' => $unit,
                'status' => $instrument->status,
            ]
        ]);
    }
}

