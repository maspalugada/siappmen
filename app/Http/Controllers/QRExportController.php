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
        $order = Order::where('order_no', $orderNo)->with('unit')->firstOrFail();

        // generate SVG lalu encode base64 supaya bisa dimasukkan ke <img>
        $qrSvg = base64_encode(QrCode::format('svg')->size(150)->generate($order->order_no));

        $pdf = Pdf::loadView('pdf.qr-order', [
            'order' => $order,
            'qrSvg' => $qrSvg
        ])->setPaper('A6', 'portrait');

        return $pdf->stream('QR-'.$order->order_no.'.pdf');
    }

    /**
     * Cetak labels (4 per A4) untuk satu order.
     */
    public function exportLabels($orderNo)
    {
        $order = Order::where('order_no', $orderNo)->with('unit')->firstOrFail();

        // generate SVG QR sedikit lebih besar untuk label
        $qrSvg = base64_encode(QrCode::format('svg')->size(120)->generate($order->order_no));

        $pdf = Pdf::loadView('pdf.qr-order-labels', [
            'order' => $order,
            'qrSvg' => $qrSvg
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('LABELS-'.$order->order_no.'.pdf');
    }
}
