<div class="space-y-6">
    {{-- ======== Statistik Pouch ======== --}}
    <div class="grid grid-cols-3 gap-4 text-center">
        <div class="p-4 bg-green-100 rounded shadow">
            <h3 class="text-lg font-semibold text-green-700">Bersih</h3>
            <p class="text-3xl font-bold text-green-900">{{ $pouchStats['clean'] ?? 0 }}</p>
        </div>

        <div class="p-4 bg-red-100 rounded shadow">
            <h3 class="text-lg font-semibold text-red-700">Kotor</h3>
            <p class="text-3xl font-bold text-red-900">{{ $pouchStats['dirty'] ?? 0 }}</p>
        </div>

        <div class="p-4 bg-yellow-100 rounded shadow">
            <h3 class="text-lg font-semibold text-yellow-700">Digunakan</h3>
            <p class="text-3xl font-bold text-yellow-900">{{ $pouchStats['in_use'] ?? 0 }}</p>
        </div>
    </div>

    {{-- ======== Statistik Order ======== --}}
    <div class="grid grid-cols-3 gap-4 text-center">
        <div class="p-4 bg-gray-100 rounded shadow">
            <h3 class="text-lg font-semibold text-gray-600">Pending</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $orderStats['pending'] ?? 0 }}</p>
        </div>

        <div class="p-4 bg-blue-100 rounded shadow">
            <h3 class="text-lg font-semibold text-blue-700">Selesai</h3>
            <p class="text-3xl font-bold text-blue-900">{{ $orderStats['completed'] ?? 0 }}</p>
        </div>

        <div class="p-4 bg-purple-100 rounded shadow">
            <h3 class="text-lg font-semibold text-purple-700">Total Order</h3>
            <p class="text-3xl font-bold text-purple-900">{{ $orderStats['total'] ?? 0 }}</p>
        </div>
    </div>

    {{-- ======== Grafik Transaksi Bulanan ======== --}}
    <div class="bg-white border rounded shadow p-4">
        <h3 class="text-lg font-semibold mb-2">📈 Transaksi 12 Bulan Terakhir</h3>
        <canvas id="monthlyTransactionsChart" height="100"></canvas>
    </div>

    {{-- ======== Tooltip Interaktif Berdasarkan Role ======== --}}
    @if(auth()->user()->role === 'cssd')
        <p class="text-sm text-green-600 mt-2">Mode CSSD aktif — update data setiap 10 detik</p>
    @endif

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', () => {
            const ctx = document.getElementById('monthlyTransactionsChart').getContext('2d');

            const months = [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ];

            const data = @json($monthlyTransactions);

            const totals = Array(12).fill(0);
            data.forEach(item => {
                totals[item.month - 1] = item.total;
            });

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: totals,
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79,70,229,0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
    @endpush
</div>
