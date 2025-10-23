<div class="p-6">
    <h2 class="text-2xl font-bold text-center mb-2 text-green-700">Distribusi Steril CSSD</h2>
    <p class="text-sm text-gray-600 text-center mb-6">Pendistribusian alat steril kembali ke unit peminjam</p>

    {{-- Daftar order yang siap didistribusikan --}}
    <div class="border border-gray-300 rounded-lg p-4 bg-white mb-6">
        <h3 class="font-semibold mb-2">Order Siap Distribusi</h3>
        <table class="w-full border text-sm">
            <thead class="bg-green-100">
                <tr>
                    <th class="border px-2 py-1">No Order</th>
                    <th class="border px-2 py-1">Unit</th>
                    <th class="border px-2 py-1">Tanggal</th>
                    <th class="border px-2 py-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordersReady as $order)
                    <tr>
                        <td class="border px-2 py-1">{{ $order->order_no }}</td>
                        <td class="border px-2 py-1">{{ $order->unit->name ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $order->date_request }}</td>
                        <td class="border px-2 py-1 text-center">
                            <button wire:click="selectOrder({{ $order->id }})"
                                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Pilih</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-gray-500 p-2">Belum ada order siap distribusi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form distribusi --}}
    @if($selectedOrder)
        <div class="border border-green-400 rounded-lg p-4 bg-green-50 mb-6">
            <h3 class="font-semibold text-green-700 mb-2">Distribusikan Order {{ $selectedOrder->order_no }}</h3>
            <p><strong>Ruangan:</strong> {{ $selectedOrder->unit->name ?? '-' }}</p>
            <textarea wire:model="notes" rows="3" placeholder="Catatan pengiriman..." class="w-full border rounded p-2 mt-2"></textarea>
            <div class="text-center mt-3">
                <button wire:click="sendDistribution" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim Distribusi</button>
                <button wire:click="$set('selectedOrder', null)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Batal</button>
            </div>
        </div>
    @endif

    {{-- Riwayat distribusi --}}
    <div class="border border-gray-300 rounded-lg p-4 bg-white">
        <h3 class="font-semibold mb-2">Riwayat Distribusi Terakhir</h3>
        <table class="w-full border text-sm">
            <thead class="bg-green-100">
                <tr>
                    <th class="border px-2 py-1">No Order</th>
                    <th class="border px-2 py-1">Unit</th>
                    <th class="border px-2 py-1">Tanggal Kirim</th>
                    <th class="border px-2 py-1">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distributions as $dist)
                    <tr>
                        <td class="border px-2 py-1">{{ $dist->order->order_no }}</td>
                        <td class="border px-2 py-1">{{ $dist->order->unit->name ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $dist->date_delivered }}</td>
                        <td class="border px-2 py-1 text-center">{{ ucfirst($dist->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-gray-500 p-2">Belum ada distribusi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 text-center text-green-600 font-semibold">{{ session('message') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 text-center text-red-600 font-semibold">{{ session('error') }}</div>
    @endif
</div>
