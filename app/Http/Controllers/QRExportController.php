<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRExportController extends Controller
{
    /**
     * Cetak single QR (A6) untuk satu order.
     */
    public function export($orderNo)
    {
        return $this->generatePdf($orderNo, 'pdf.qr-order', 'A6', 'portrait', 150, 'QR-');
    }

    /**
     * Cetak labels (4 per A4) untuk satu order.
     */
    public function exportLabels($orderNo)
    {
        return $this->generatePdf($orderNo, 'pdf.qr-order-labels', 'A4', 'portrait', 120, 'LABELS-');
    }

    private function generatePdf($orderNo, $view, $paper, $orientation, $qrSize, $prefix)
    {
        $order = Order::where('order_no', $orderNo)->with('unit')->firstOrFail();

        // Buat konten QR yang aman dengan hash
        $type = 'order';
        $hash = hash('sha256', $order->order_no . env('APP_KEY'));
        $qrContent = base64_encode("{$type}|{$order->order_no}|{$hash}");

        // generate SVG lalu encode base64 supaya bisa dimasukkan ke <img>
        $qrSvg = base64_encode(QrCode::format('svg')->size($qrSize)->generate($qrContent));

        $pdf = Pdf::loadView($view, [
            'order' => $order,
            'qrSvg' => $qrSvg
        ])->setPaper($paper, $orientation);

        return $pdf->stream($prefix . $order->order_no . '.pdf');
    }
}
