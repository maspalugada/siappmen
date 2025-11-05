@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Kelola Order</h2>

    <table class="w-full mt-4 border">
        <thead>
            <tr class="bg-gray-100">
                <th>No</th><th>Kode Order</th><th>Unit</th><th>Tanggal</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr class="border-b">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $o->order_no }}</td>
                <td>{{ $o->unit->name ?? '-' }}</td>
                <td>{{ $o->date_request }}</td>
                <td>{{ strtoupper($o->status) }}</td>
                <td>
                    <form action="{{ route('orders.updateStatus', $o) }}" method="POST">
                        @csrf
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" @if($o->status == 'pending') selected @endif>Pending</option>
                            <option value="approved" @if($o->status == 'approved') selected @endif>Approved</option>
                            <option value="rejected" @if($o->status == 'rejected') selected @endif>Rejected</option>
                            <option value="completed" @if($o->status == 'completed') selected @endif>Completed</option>
                            <option value="cancelled" @if($o->status == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
