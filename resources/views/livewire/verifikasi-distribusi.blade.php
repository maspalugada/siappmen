<div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Verifikasi Distribusi Steril</h2>
    <p class="text-gray-600 text-center mb-6">Scan QR atau pilih alat yang akan diverifikasi.</p>

    {{-- Input QR Scanner --}}
    <div class="text-center mb-4">
        <input type="text" wire:model.debounce.500ms="qrCode"
               placeholder="Tempel hasil scan QR di sini..."
               class="w-2/3 p-2 border rounded text-center">
    </div>

    <div id="reader" class="mx-auto mb-6 w-72 h-72 border border-gray-300 rounded"></div>

    {{-- Pesan hasil scan --}}
    @if($message)
        <div class="bg-green-100 border border-green-400 p-3 text-green-700 text-center rounded mb-4">
            {{ $message }}
        </div>
    @endif

    {{-- Daftar distribusi aktif --}}
    <table class="w-full border border-gray-300 text-sm">
        <thead class="bg-green-100">
            <tr>
                <th class="border px-3 py-2">Kode</th>
                <th class="border px-3 py-2">Nama Alat</th>
                <th class="border px-3 py-2">Unit Tujuan</th>
                <th class="border px-3 py-2">Status</th>
                <th class="border px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
                <tr>
                    <td class="border px-3 py-2">{{ $trx->instrument->code }}</td>
                    <td class="border px-3 py-2">{{ $trx->instrument->name }}</td>
                    <td class="border px-3 py-2">{{ $trx->toUnit->name ?? '-' }}</td>
                    <td class="border px-3 py-2 capitalize">{{ $trx->status }}</td>
                    <td class="border px-3 py-2 text-center">
                        <button wire:click="updatedQrCode('{{ $trx->instrument->qr_code }}')"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                            Verifikasi Diterima
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-3 text-gray-500">Tidak ada distribusi steril yang menunggu verifikasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('livewire:load', () => {
    // Jalankan scanner setelah Livewire siap & elemen "reader" sudah ada
    setTimeout(() => {
        const readerElement = document.getElementById("reader");
        if (!readerElement) {
            console.error("Element #reader tidak ditemukan");
            return;
        }

        const html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
            { facingMode: "environment" }, // Kamera belakang
            { fps: 10, qrbox: 250 },
            (decodedText, decodedResult) => {
                console.log("QR Ditemukan:", decodedText);
                const livewireComponent = document.querySelector('[wire\\:id]');
                if (livewireComponent) {
                    Livewire.find(livewireComponent.getAttribute('wire:id'))
                        .set('qrCode', decodedText.trim());
                }
            },
            (errorMessage) => {
                // Abaikan error pembacaan minor
            }
        ).catch(err => console.error("Gagal mengaktifkan kamera:", err));
    }, 500); // beri jeda setengah detik agar DOM siap
});
</script>
@endpush

