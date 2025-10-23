@extends('layouts.siappmen')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold">SiAPPMEN</h2>
    <p class="text-gray-600 text-sm">Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen</p>
</div>

{{-- Contoh Diagram --}}
<div class="grid grid-cols-2 gap-6">
    <div class="border p-4 rounded shadow bg-white">
        <h3 class="font-semibold mb-2">Data Seluruh Unit Pengguna</h3>
        <canvas id="pieChart"></canvas>
        <p class="text-xs text-gray-500 mt-3">
            Diagram simulasi pendistribusian & peminjaman instrumen.
        </p>
    </div>

    <div class="border p-4 rounded shadow bg-white">
        <h3 class="font-semibold mb-2">Rekap Unit Pengguna</h3>
        <div class="grid grid-cols-4 gap-2 text-center">
            @foreach(['EBONI','SILVER','RAWAT ANAK','IWB','ICUD','UGD','POLI UMUM','POLI ANAK'] as $unit)
                <div class="p-2 border rounded bg-gray-50">
                    <p class="text-xs font-semibold">{{ $unit }}</p>
                    <canvas id="chart_{{ strtolower(str_replace(' ','_',$unit)) }}" width="50" height="50"></canvas>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Pie utama
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Belum Balik', 'Distribusi', 'Dipinjam', 'Tidak Komplit'],
            datasets: [{
                data: [4, 6, 2, 1],
                backgroundColor: ['#FF6B6B', '#4ECDC4', '#FFD93D', '#C7CEEA']
            }]
        }
    });

    // Mini chart tiap unit
    document.querySelectorAll('[id^="chart_"]').forEach(canvas => {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['OK', 'Kotor', 'Pinjam'],
                datasets: [{
                    data: [Math.random()*5, Math.random()*5, Math.random()*5],
                    backgroundColor: ['#8FD19E','#FF6B6B','#FFD93D']
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    });
});
</script>
@endsection
