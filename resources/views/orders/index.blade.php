@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Daftar Order Saya</h2>
    <a href="{{ route('orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Buat Order</a>

    <table class="w-full mt-4 border">
        <thead>
            <tr class="bg-gray-100">
                <th>No</th><th>Kode Order</th><th>Tanggal</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr class="border-b">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $o->order_no }}</td>
                <td>{{ $o->date_request }}</td>
                <td>{{ strtoupper($o->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
