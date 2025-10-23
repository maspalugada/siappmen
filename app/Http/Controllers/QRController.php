<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pouch;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    // tampilkan daftar pouch dengan QR
    public function index()
    {
        $pouches = Pouch::all();
        return view('qr.index', compact('pouches'));
    }

    // generate PDF berisi QR tiap pouch
    public function exportPdf()
    {
        $pouches = Pouch::all();
        $pdf = PDF::loadView('qr.pdf', compact('pouches'));
        return $pdf->download('pouch_qr_codes.pdf');
    }
}
