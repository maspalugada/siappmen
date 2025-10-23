<div>
    {{-- BAGIAN STATISTIK --}}
    <div class="grid grid-cols-3 gap-4 mb-6 text-center">
        <div class="bg-green-100 rounded p-3 shadow">
            <h3 class="font-semibold text-green-700">Bersih</h3>
            <p class="text-3xl font-bold">{{ $pouchStats['clean'] ?? 0 }}</p>
        </div>
        <div class="bg-yellow-100 rounded p-3 shadow">
            <h3 class="font-semibold text-yellow-700">Kotor</h3>
            <p class="text-3xl font-bold">{{ $pouchStats['dirty'] ?? 0 }}</p>
        </div>
        <div class="bg-blue-100 rounded p-3 shadow">
            <h3 class="font-semibold text-blue-700">Digunakan</h3>
            <p class="text-3xl font-bold">{{ $pouchStats['in_use'] ?? 0 }}</p>
        </div>
    </div>

    {{-- BAGIAN ORDER --}}
    <div class="grid grid-cols-3 gap-4 mb-6 text-center">
        <div class="bg-gray-100 rounded p-3 shadow">
            <h3 class="font-semibold text-gray-600">Pending</h3>
            <p class="text-3xl font-bold">{{ $orderStats['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-teal-100 rounded p-3 shadow">
            <h3 class="font-semibold text-teal-700">Selesai</h3>
            <p class="text-3xl font-bold">{{ $orderStats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-purple-100 rounded p-3 shadow">
            <h3 class="font-semibold text-purple-700">Total Order</h3>
            <p class="text-3xl font-bold">{{ $orderStats['total'] ?? 0 }}</p>
        </div>
    </div>

    {{-- BAGIAN GRAFIK --}}
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white border rounded shadow p-4">
            <h3 class="font-semibold mb-2">Transaksi per Bulan ({{ date('Y') }})</h3>
            <canvas id="monthlyTransactionsChart"></canvas>
        </div>

        <div class="bg-white border rounded shadow p-4">
            <h3 class="font-semibold mb-2">Rekap per Unit Pengguna</h3>
            <div class="grid grid-cols-4 gap-2 text-center">
                @foreach($unitStats as $u)
                    <div class="border rounded p-2 text-xs bg-gray-50">
                        <p class="font-semibold">{{ $u['unit_name'] }}</p>
                        <canvas id="unitChart_{{ Str::slug($u['unit_name'],'_') }}" width="60" height="60"></canvas>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const monthlyData = @json($monthlyTransactions);
        const totals = Array(12).fill(0);
        monthlyData.forEach(m => { totals[m.month - 1] = m.total; });

        new Chart(document.getElementById('monthlyTransactionsChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: totals,
                    backgroundColor: '#4e944f'
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        const units = @json($unitStats);
        units.forEach(u => {
            const ctx = document.getElementById('unitChart_' + u.unit_name.toLowerCase().replace(/ /g,'_'));
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending','Selesai','Total'],
                    datasets: [{
                        data: [u.pending, u.completed, u.total],
                        backgroundColor: ['#FF6B6B','#8FD19E','#C7CEEA']
                    }]
                },
                options: { plugins: { legend: { display: false } } }
            });
        });

        setInterval(() => Livewire.emit('refreshDashboard'), 10000);
    </script>
</div>
