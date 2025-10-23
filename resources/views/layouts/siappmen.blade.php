<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiAPPMEN - CSSD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    

</head>

<body class="bg-gray-100 font-sans">
    
    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside class="w-56 bg-green-300 border-r border-green-500">
            <div class="p-3 text-center bg-green-500 font-bold text-white text-lg">DASBOARD</div>

            <ul class="text-sm mt-2">
                <li class="px-3 py-2 hover:bg-green-400 cursor-pointer">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                {{-- Master Data --}}
                <li class="px-3 py-2 hover:bg-green-400 cursor-pointer font-semibold">
                    <span> Master Data</span>
                    <ul class="ml-4 mt-1 space-y-1">
                        <li>
                            <a href="{{ route('master.units') }}"
                               class="block px-2 py-1 rounded hover:bg-green-200 {{ request()->routeIs('master.units') ? 'bg-green-200 font-bold' : '' }}">
                               Master Ruangan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('master.instruments') }}"
                               class="block px-2 py-1 rounded hover:bg-green-200 {{ request()->routeIs('master.instruments') ? 'bg-green-200 font-bold' : '' }}">
                               Master Instrumen
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('scan.qr') }}"
                       class="block hover:bg-green-200 px-2 py-1 rounded">
                       Scan QR Instrumen
                    </a>
                </li>
                <li>
                     <a href="{{ route('verifikasi.distribusi') }}" 
                        class="block hover:bg-green-200 px-2 py-1 rounded">
                        Verifikasi Distribusi Steril
                     </a>
                </li>





                <li class="px-3 py-2 bg-green-200 font-semibold">
                    <a href="{{ route('transaksi.cssd') }}">Transaksi Peminjaman / Bon Instrumen</a>
                </li>
                <li>
                    <a href="{{ route('distribusi.steril') }}" class="block hover:bg-green-200 px-2 py-1 rounded">Distribusi Steril</a>
                </li>
            </ul>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 bg-white">
            {{-- HEADER --}}
            <header class="flex justify-between items-center bg-green-400 py-2 px-4 border-b border-green-600">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
                    <h1 class="font-bold text-xl text-black">SiAPPMEN</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="font-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                    <button onclick="document.getElementById('logoutForm').submit()" class="bg-red-600 text-white rounded-full px-3 py-1 font-semibold">⏻</button>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </header>

            {{-- CONTENT --}}
            <section class="p-6">
                @yield('content')
            </section>
        </main>
    </div>
    @livewireScripts
    @stack('scripts')
    
</body>
</html>
