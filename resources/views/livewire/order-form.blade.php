<div>
    <div class="text-center mb-5">
        <h2 class="text-2xl font-bold">Order Permintaan Instrumen CSSD</h2>
        <p class="text-gray-600 text-sm">Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen</p>
    </div>

    {{-- FORM --}}
    <div class="grid grid-cols-3 gap-6 border-t border-gray-300 pt-4">
        {{-- KOLOM KIRI --}}
        <div class="col-span-2 space-y-3">
            <div class="flex justify-between">
                <label class="font-semibold">Dipinjam oleh:</label>
                <input type="text" wire:model="borrowedBy" class="border rounded px-2 py-1 w-64">
            </div>

            <div class="flex justify-between">
                <label class="font-semibold">Ruangan:</label>
                <select wire:model="selectedUnit" class="border rounded px-2 py-1 w-64">
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between">
                <label class="font-semibold">Tanggal Pinjam:</label>
                <input type="date" wire:model="borrowDate" class="border rounded px-2 py-1 w-64">
            </div>

            <div class="flex justify-between">
                <label class="font-semibold">Rencana Kembali:</label>
                <input type="date" wire:model="plannedReturnDate" class="border rounded px-2 py-1 w-64">
            </div>

            <div class="flex justify-between">
                <label class="font-semibold">Nomor Order:</label>
                <input type="text" wire:model="orderNumber" readonly class="border rounded px-2 py-1 w-64 bg-gray-100">
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="text-center">
            <label class="font-semibold block mb-1">QR Code</label>
            @if($qrCode)
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="mx-auto border shadow p-2">
            @endif
        </div>
    </div>

    {{-- TABEL INSTRUMEN --}}
    @if($selectedUnit)
        <div class="mt-6">
            <h3 class="font-semibold mb-2 text-lg">Daftar Instrumen Unit {{ $selectedUnit }}</h3>
            <table class="w-full border text-sm">
                <thead class="bg-green-100">
                    <tr>
                        <th class="border px-2 py-1">No</th>
                        <th class="border px-2 py-1">Nama Instrumen</th>
                        <th class="border px-2 py-1">Pilih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instruments as $index => $item)
                        <tr>
                            <td class="border text-center">{{ $index+1 }}</td>
                            <td class="border px-2">{{ $item->name }}</td>
                            <td class="border text-center">
                                <input type="checkbox" wire:click="toggleInstrument({{ $item->id }})"
                                       @checked(in_array($item->id, $selectedInstruments))>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-3 text-gray-500 text-center">Tidak ada instrumen tersedia</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- BUTTONS --}}
    <div class="mt-4 text-right space-x-2">
        <button wire:click="saveOrder" class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
            Simpan
        </button>
        <button wire:click="mount" class="bg-yellow-400 px-4 py-1 rounded hover:bg-yellow-500">
            Reset
        </button>
        <button onclick="history.back()" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
            Kembali
        </button>
    </div>

    {{-- Pesan Sukses --}}
    @if (session()->has('message'))
        <div class="mt-3 text-green-700 font-semibold text-center">
            ✅ {{ session('message') }}
        </div>
    @endif
</div>
