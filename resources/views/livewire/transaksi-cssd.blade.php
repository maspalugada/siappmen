<div class="p-6">
    <h2 class="text-2xl font-bold text-center mb-1">SiAPPMEN</h2>
    <p class="text-sm text-gray-600 text-center mb-6">
        Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen <br>
        <span class="font-semibold text-green-700">Order Permintaan Instrumen CSSD</span>
    </p>

    {{-- FORM UTAMA --}}
    <div class="border border-green-300 rounded-lg p-5 mb-6 shadow-sm bg-white">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="font-semibold">Dipinjam oleh:</label>
                <input wire:model="borrower" type="text" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="font-semibold">No Order:</label>
                <input type="text" readonly value="{{ $orderNo }}" class="w-full border rounded p-2 bg-gray-100">
            </div>
            <div>
                <label class="font-semibold">Ruangan:</label>
                <select wire:model="selectedUnit" class="w-full border rounded p-2">
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col items-center">
                <label class="font-semibold">QR Code:</label>
                <div class="p-2 bg-gray-50 border rounded">
                    {!! QrCode::size(100)->generate($orderNo) !!}
                </div>
                <small class="text-gray-500 text-xs">Tempel QR pada pouch atau dokumen</small>
            </div>
            <div>
                <label class="font-semibold">Tanggal:</label>
                <input wire:model="dateRequest" type="date" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="font-semibold">Rencana Dikembalikan:</label>
                <input wire:model="dateReturn" type="date" class="w-full border rounded p-2">
            </div>
        </div>
    </div>

    {{-- TABEL INPUT INSTRUMEN --}}
    @if($showTable)
        <div class="border border-gray-300 rounded-lg p-4 bg-white mb-6">
            <h3 class="font-semibold mb-2 text-green-700">Pilih Instrumen:</h3>
            <table class="w-full border text-sm">
                <thead class="bg-green-100">
                    <tr>
                        <th class="border px-2 py-1">Nama Instrumen</th>
                        <th class="border px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instruments as $item)
                        <tr>
                            <td class="border px-2 py-1">{{ $item->name }}</td>
                            <td class="border px-2 py-1 text-center">
                                <button wire:click="addInstrument({{ $item->id }})" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                    + Tambah
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- INSTRUMEN TERPILIH --}}
        <div class="border border-gray-300 rounded-lg p-4 bg-white">
            <h3 class="font-semibold mb-2 text-green-700">Daftar Instrumen Dipinjam:</h3>
            <table class="w-full border text-sm">
                <thead class="bg-green-100">
                    <tr>
                        <th class="border px-2 py-1">Kode</th>
                        <th class="border px-2 py-1">Nama Instrumen</th>
                        <th class="border px-2 py-1">QTY</th>
                        <th class="border px-2 py-1">Kondisi</th>
                        <th class="border px-2 py-1">Keterangan</th>
                        <th class="border px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($selectedItems as $index => $item)
                        <tr>
                            <td class="border px-2 py-1">{{ $item['code'] }}</td>
                            <td class="border px-2 py-1">{{ $item['name'] }}</td>
                            <td class="border px-2 py-1 text-center">{{ $item['qty'] }}</td>
                            <td class="border px-2 py-1 text-center">{{ $item['condition'] }}</td>
                            <td class="border px-2 py-1">{{ $item['notes'] }}</td>
                            <td class="border px-2 py-1 text-center">
                                <button wire:click="removeInstrument({{ $index }})" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-2 text-gray-500">Belum ada instrumen ditambahkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- TOMBOL AKSI --}}
    <div class="mt-6 text-center">
        <button wire:click="resetForm" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Kembali</button>
        <button wire:click="resetForm" class="bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-500">Reset</button>
        <button wire:click="saveOrder" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
    </div>

    {{-- PESAN --}}
    @if (session()->has('message'))
        <div class="mt-4 text-center text-green-600 font-semibold">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mt-4 text-center text-red-600 font-semibold">{{ session('error') }}</div>
    @endif

    {{-- DAFTAR ORDER TERAKHIR --}}
    <div class="mt-10 border border-gray-300 rounded-lg p-4 bg-white">
        <h3 class="font-semibold mb-2 text-green-700">Riwayat Order Terakhir</h3>
        <table class="w-full border text-sm">
            <thead class="bg-green-100">
                <tr>
                    <th class="border px-2 py-1">No Order</th>
                    <th class="border px-2 py-1">Tanggal</th>
                    <th class="border px-2 py-1">Ruangan</th>
                    <th class="border px-2 py-1">Status</th>
                    <th class="border px-2 py-1">QR</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="border px-2 py-1">{{ $order->order_no }}</td>
                        <td class="border px-2 py-1">{{ $order->date_request }}</td>
                        <td class="border px-2 py-1">{{ $order->unit->name ?? '-' }}</td>
                        <td class="border px-2 py-1 text-center">{{ ucfirst($order->status) }}</td>
                        <td class="border px-2 py-1 text-center">{!! QrCode::size(60)->generate($order->order_no) !!}
                            <div class="mt-1">
                                <a href="{{ route('transaksi.cssd.qr', $order->order_no) }}" target="_blank"
                                   class="text-blue-600 underline text-xs hover:text-blue-800">Cetak</a> |
                                <a href="{{ route('transaksi.cssd.qr.labels', $order->order_no) }}" target="_blank"
                                   class="text-green-600 underline text-xs hover:text-green-800">Cetak Label</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
