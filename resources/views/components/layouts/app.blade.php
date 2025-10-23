<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SiAPPMEN</title>
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans">
    <main class="p-6">
        {{ $slot }}
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
