<div class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-center"> Master Data Ruangan</h2>

    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form wire:submit.prevent="save" class="space-y-3">
            <div>
                <label class="block font-semibold">Nama Ruangan</label>
                <input type="text" wire:model="name" class="w-full border rounded p-2">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold">Lokasi</label>
                <input type="text" wire:model="location" class="w-full border rounded p-2">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                @if($isEdit)
                    <button type="button" wire:click="resetForm" class="bg-gray-400 px-4 py-2 rounded">Batal</button>
                @endif
            </div>
        </form>
    </div>

    @if (session()->has('message'))
        <div class="text-green-600 font-semibold text-center mb-4">{{ session('message') }}</div>
    @endif

    <table class="w-full border text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="border px-3 py-2 w-10">#</th>
                <th class="border px-3 py-2">Nama</th>
                <th class="border px-3 py-2">Lokasi</th>
                <th class="border px-3 py-2 w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $i => $unit)
                <tr>
                    <td class="border px-3 py-1 text-center">{{ $i+1 }}</td>
                    <td class="border px-3 py-1">{{ $unit->name }}</td>
                    <td class="border px-3 py-1">{{ $unit->location }}</td>
                    <td class="border px-3 py-1 text-center">
                        <button wire:click="edit({{ $unit->id }})" class="bg-yellow-400 px-2 py-1 rounded">Edit</button>
                        <button wire:click="delete({{ $unit->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-gray-500 p-2">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
