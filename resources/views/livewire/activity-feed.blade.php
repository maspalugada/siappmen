<div class="bg-white rounded border shadow p-4">
    <h3 class="text-lg font-semibold mb-2">🕒 Aktivitas Terbaru</h3>

    <ul class="space-y-2 max-h-80 overflow-y-auto">
        @foreach($logs as $log)
            <li class="border-b pb-2">
                <p class="text-sm text-gray-700">{{ $log->description }}</p>
                <span class="text-xs text-gray-400">
                    oleh {{ $log->user->name ?? 'System' }} • {{ $log->created_at->diffForHumans() }}
                </span>
            </li>
        @endforeach
    </ul>
</div>

<script>
setInterval(() => Livewire.emit('refreshFeed'), 8000);
</script>
