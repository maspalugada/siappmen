@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Form Order Baru</h2>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <label class="block mb-2">Tanggal Rencana Kembali</label>
        <input type="date" name="date_return_planned" class="border rounded p-2 mb-4">

        <h3 class="text-lg font-semibold mt-4 mb-2">Pilih Instrumen</h3>
        @foreach($instruments as $instrument)
            <div class="flex items-center mb-2">
                <input type="checkbox" name="items[{{ $loop->index }}][instrument_id]" value="{{ $instrument->id }}" class="mr-2">
                {{ $instrument->name }}
                <input type="number" name="items[{{ $loop->index }}][qty]" placeholder="Qty" class="border rounded p-1 ml-2 w-16">
            </div>
        @endforeach

        <button class="bg-green-600 text-white px-4 py-2 rounded mt-4">Kirim Order</button>
    </form>
</div>
@endsection
