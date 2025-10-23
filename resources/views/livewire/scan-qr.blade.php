<div class="max-w-3xl mx-auto text-center">
    <h2 class="text-2xl font-bold mb-4">Scan QR Instrumen</h2>
    <p class="text-gray-600 mb-4">Arahkan kamera ke QR Code alat untuk validasi.</p>

    <input type="text" wire:model.debounce.500ms="qrCode"
           placeholder="Atau tempelkan hasil scan QR di sini..."
           class="w-2/3 p-2 border rounded mb-4 text-center">

    <div id="reader" class="mx-auto mb-4 w-72 h-72 border border-gray-300"></div>

    @if($result)
        <div class="bg-green-100 border border-green-400 p-4 rounded shadow text-left">
            <h3 class="font-bold text-green-700 mb-2">Alat Ditemukan ✅</h3>
            <p><strong>Nama:</strong> {{ $result['name'] }}</p>
            <p><strong>Unit:</strong> {{ $result['unit'] }}</p>
            <p><strong>Status:</strong> {{ ucfirst($result['status']) }}</p>
        </div>
    @endif

    @if($error)
        <div class="bg-red-100 border border-red-400 p-3 rounded text-red-600 mt-3">
             {{ $error }}
        </div>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('livewire:load', () => {
    const reader = new Html5Qrcode("reader");

    reader.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        qrCodeMessage => {
            console.log("QR Detected:", qrCodeMessage);
            const component = Livewire.find(
                document.querySelector('[wire\\:id]').getAttribute('wire:id')
            );
            component.set('qrCode', qrCodeMessage.trim());
        },
        errorMessage => {}
    ).catch(err => console.error("Camera Error:", err));
});
</script>
@endpush
