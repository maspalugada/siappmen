<div class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-center"> Master Data Instrumen</h2>

    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form wire:submit.prevent="save" class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold">Kode</label>
                <input type="text" wire:model="code" class="w-full border rounded p-2">
                @error('code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold">Nama Instrumen</label>
                <input type="text" wire:model="name" class="w-full border rounded p-2">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="col-span-2">
                <label class="block font-semibold">Deskripsi</label>
                <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
            </div>

            <div>
                <label class="block font-semibold">Unit</label>
                <select wire:model="unit_id" class="w-full border rounded p-2">
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold">Status</label>
                <select wire:model="status" class="w-full border rounded p-2">
                    <option value="available">Available</option>
                    <option value="in_use">In Use</option>
                    <option value="dirty">Dirty</option>
                    <option value="steril">Steril</option>
                </select>
            </div>

            <div class="col-span-2 flex justify-end space-x-2">
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
        <thead class="bg-green-100">
            <tr>
                <th class="border px-3 py-2 w-10">#</th>
                <th class="border px-3 py-2">Kode</th>
                <th class="border px-3 py-2">Nama</th>
                <th class="border px-3 py-2">Unit</th>
                <th class="border px-3 py-2">Status</th>
                <th class="border px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($instruments as $i => $item)
                <tr>
                    <td class="border px-3 py-1 text-center">{{ $i+1 }}</td>
                    <td class="border px-3 py-1">{{ $item->code }}</td>
                    <td class="border px-3 py-1">{{ $item->name }}</td>
                    <td class="border px-3 py-1">{{ $item->unit->name ?? '-' }}</td>
                    <td class="border px-3 py-1 text-center">
                        <span class="px-2 py-1 rounded text-white {{ $item->status == 'available' ? 'bg-green-500' : ($item->status == 'in_use' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="border px-3 py-1 text-center">
                        <button wire:click="edit({{ $item->id }})" class="bg-yellow-400 px-2 py-1 rounded">Edit</button>
                        <button wire:click="delete({{ $item->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-gray-500 p-2">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
