<div class="relative">
    <button class="relative focus:outline-none" id="notifBtn">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3c0 .386-.146.735-.395 1.005L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5">{{ $unreadCount }}</span>
        @endif
    </button>

    <div id="notifMenu" class="hidden absolute right-0 mt-2 w-72 bg-white border rounded-lg shadow-lg">
        <div class="p-2 text-sm font-semibold border-b bg-gray-50">Notifikasi Terbaru</div>
        <ul class="max-h-64 overflow-y-auto divide-y">
            @forelse($notifications as $notif)
                <li class="p-2 text-sm">
                    <p class="text-gray-700">{{ $notif->description }}</p>
                    <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="p-2 text-gray-400 text-sm text-center">Tidak ada notifikasi</li>
            @endforelse
        </ul>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const btn = document.getElementById('notifBtn');
    const menu = document.getElementById('notifMenu');
    if (btn.contains(e.target)) menu.classList.toggle('hidden');
    else menu.classList.add('hidden');
});
</script>
