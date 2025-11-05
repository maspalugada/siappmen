<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SiAPPMEN') }} - CSSD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div x-data="{ sidebarOpen: true }" class="flex min-h-screen">
        <!-- SIDEBAR -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white z-30 md:relative md:translate-x-0">
            <div class="flex items-center justify-between p-4 bg-gray-900">
                <span class="font-bold text-xl">SiAPPMEN</span>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                    Dashboard
                </a>

                <div x-data="{ open: true }" class="px-4 mt-4">
                    <button @click="open = !open" class="w-full flex justify-between items-center text-left text-gray-400">
                        <span class="font-semibold">Master Data</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <ul x-show="open" x-transition class="ml-4 mt-2 space-y-2 text-sm">
                        <li><a href="{{ route('master.units') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('master.units') ? 'bg-gray-700 font-bold' : '' }}">Master Ruangan</a></li>
                        <li><a href="{{ route('master.instruments') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('master.instruments') ? 'bg-gray-700 font-bold' : '' }}">Master Instrumen</a></li>
                    </ul>
                </div>
                 <a href="{{ route('transaksi.cssd') }}" class="flex items-center mt-4 px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('transaksi.cssd') ? 'bg-gray-700' : '' }}">
                    Transaksi Bon
                </a>

            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1">
            <!-- HEADER -->
            <header class="flex justify-between items-center bg-white py-3 px-6 border-b">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="flex-1"></div>
                <div class="flex items-center space-x-4">
                    <span class="font-semibold text-gray-600">{{ Auth::user()->name ?? 'User' }}</span>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    <button onclick="document.getElementById('logoutForm').submit()" class="text-gray-500 hover:text-red-600" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </div>
            </header>

            <!-- CONTENT -->
            <section class="p-6">
                {{ $slot ?? '' }}
            </section>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
