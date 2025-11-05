<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session()->has('activity'))
            <script>
                Swal.fire({
                    toast: true,
                    icon: 'info',
                    title: '{{ session('activity') }}',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
        @endif
        <script>
        window.addEventListener('activity', event => {
            Swal.fire({
                toast: true,
                icon: 'info',
                title: event.detail.message,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500
            });
        });
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Pusher JS -->
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script>
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            wsHost: '{{ env('PUSHER_HOST') }}',
            wsPort: {{ env('PUSHER_PORT') }},
            forceTLS: false,
            enabledTransports: ['ws', 'wss']
        });

        const channel = pusher.subscribe('activity');
        channel.bind('App\\Events\\NewActivityEvent', function(data) {
            const userRole = "{{ Auth::user()->role }}";

            if (data.target_role === userRole || data.target_role === null) {
                Swal.fire({
                    toast: true,
                    icon: 'info',
                    title: data.description,
                    text: 'oleh ' + data.user + ' • ' + data.time,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Livewire.emit('refreshNotifications');
                Livewire.emit('refreshFeed');
            }
        });
        </script>
    </body>
    @livewireScripts
    @stack('scripts')
</html>
