<?php


use App\Http\Livewire\MasterUnit;
use App\Http\Livewire\MasterInstrument;
use App\Http\Livewire\DistribusiSteril;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\OrderController;
use App\Http\Livewire\TransaksiCssd;
use App\Exports\ActivityLogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route aplikasi web SiAPPMEN
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// === Dashboard ===
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/scan/qr', \App\Http\Livewire\ScanQr::class)->name('scan.qr');

    // Dashboard utama
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Semua role bisa akses dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- Admin ---
    Route::middleware('auth','role:admin')->group(function () {
        Route::get('/activity/logs', \App\Http\Livewire\ActivityFeed::class)->name('activity.logs');
        Route::get('/qr', [QRController::class, 'index'])->name('qr.index');
        Route::get('/qr/pdf', [QRController::class, 'exportPdf'])->name('qr.pdf');
    });

    // --- CSSD ---
    Route::middleware('role:cssd,admin')->group(function () {
        Route::get('/master/units', MasterUnit::class)->name('master.units');
        Route::get('/master/instruments', MasterInstrument::class)->name('master.instruments');
        Route::get('/scan/return', [ScanController::class, 'showReturnForm'])->name('scan.return');
        Route::post('/scan/return', [ScanController::class, 'returnDirty'])->name('scan.return.post');
        Route::get('/orders/manage', [OrderController::class, 'manage'])->name('orders.manage');
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        
        Route::get('/transaksi/cssd', \App\Http\Livewire\TransaksiCssd::class)
            ->middleware(['auth', 'role:cssd,admin'])
            ->name('transaksi.cssd');
            
        Route::get('/distribusi/steril', DistribusiSteril::class)
             ->middleware(['auth', 'verified'])
             ->name('distribusi.steril');

        Route::get('/verifikasi/distribusi', \App\Http\Livewire\VerifikasiDistribusi::class)
            ->name('verifikasi.distribusi');
    });

    // --- Unit ---
    Route::middleware('role:unit,admin')->group(function () {
        Route::get('/order', function () {
            return "Halaman order unit";
        });
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        
    });



    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Scan & QR Code routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/scan/qr', \App\Http\Livewire\ScanQr::class)->name('scan.qr');
    });

    Route::get('/scan/return', [ScanController::class, 'showReturnForm'])->name('scan.return');
    Route::post('/scan/return', [ScanController::class, 'returnDirty'])->name('scan.return.post');
    Route::get('/qr', [QRController::class, 'index'])->name('qr.index');
    Route::get('/qr/pdf', [QRController::class, 'exportPdf'])->name('qr.pdf');

    // Route untuk cetak QR Code PDF
    Route::get('/transaksi/cssd/qr/{orderNo}', [\App\Http\Controllers\QRExportController::class, 'export'])
        ->name('transaksi.cssd.qr');
    Route::get('/transaksi/cssd/qr-labels/{orderNo}', [\App\Http\Controllers\QRExportController::class, 'exportLabels'])
    ->name('transaksi.cssd.qr.labels');

    Route::get('/activity/export', function () {
    return Excel::download(new ActivityLogsExport, 'activity_logs.xlsx');
    })->name('activity.export');

});

require __DIR__ . '/auth.php';
