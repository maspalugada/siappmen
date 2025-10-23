<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SiAPPMEN - Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }

        .animate-bounce-in {
            animation: bounceIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hero-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .pulse-ring {
            animation: pulseRing 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }

        @keyframes pulseRing {
            0% {
                transform: scale(0.33);
            }
            40%, 50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(1.5);
            }
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .hero-title {
                font-size: 2.5rem !important;
                line-height: 1.1;
            }

            .hero-subtitle {
                font-size: 1.125rem !important;
                line-height: 1.4;
            }

            .feature-card {
                padding: 1rem;
            }

            .floating-shapes .shape {
                display: none;
            }

            .mobile-nav-links {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem !important;
            }

            .hero-subtitle {
                font-size: 1rem !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Splash Screen -->
    <div x-data="splashScreen" x-show="show" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center gradient-bg">

        <div class="text-center">
            <!-- Logo with pulse animation -->
            <div class="relative mb-8">
                <div class="absolute inset-0 rounded-full pulse-ring bg-white opacity-20"></div>
                <div class="relative bg-white rounded-full p-6 shadow-2xl">
                    <svg class="w-16 h-16 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Brand -->
            <h1 class="text-4xl font-bold text-white mb-2 animate-bounce-in">SiAPPMEN</h1>
            <p class="text-indigo-100 text-lg animate-fade-in" style="animation-delay: 0.2s">
                Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen
            </p>

            <!-- Loading bar -->
            <div class="mt-8 w-64 mx-auto">
                <div class="bg-white bg-opacity-20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full animate-pulse" style="width: 100%"></div>
                </div>
                <p class="text-white text-sm mt-2 animate-fade-in" style="animation-delay: 0.4s">Memuat aplikasi...</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div x-data="mainContent" x-show="!showSplash" x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <svg class="w-8 h-8 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">SiAPPMEN</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 mobile-nav-links">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-600 hover:text-indigo-600 px-4 py-2 rounded-lg font-medium transition duration-200">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative gradient-bg text-white overflow-hidden">
            <div class="absolute inset-0 hero-pattern"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-slide-up hero-title">
                        Sistem Modern untuk
                        <span class="block text-indigo-200">Pengelolaan Instrumen Medis</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto animate-fade-in hero-subtitle" style="animation-delay: 0.2s">
                        SiAPPMEN memudahkan pengambilan dan pendistribusian instrumen medis di rumah sakit dengan teknologi QR code dan tracking real-time.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in" style="animation-delay: 0.4s">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 shadow-lg">
                                Akses Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 shadow-lg">
                                Mulai Sekarang
                            </a>
                            <a href="#features" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition duration-200">
                                Pelajari Lebih Lanjut
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Floating elements -->
            <div class="absolute top-20 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-bounce-in" style="animation-delay: 1s"></div>
            <div class="absolute bottom-20 right-10 w-16 h-16 bg-white bg-opacity-10 rounded-full animate-bounce-in" style="animation-delay: 1.2s"></div>
            <div class="absolute top-1/2 right-20 w-12 h-12 bg-white bg-opacity-10 rounded-full animate-bounce-in" style="animation-delay: 1.4s"></div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan SiAPPMEN</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Teknologi modern untuk meningkatkan efisiensi dan keamanan pengelolaan instrumen medis
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="feature-card bg-white rounded-xl shadow-lg p-6 transition-all duration-300 border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 15h4.01M12 21h4.01M12 12v.01M12 15v.01M12 18v.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">QR Code Tracking</h3>
                        <p class="text-gray-600">Setiap pouch instrumen dilengkapi QR code unik untuk tracking yang akurat dan real-time.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card bg-white rounded-xl shadow-lg p-6 transition-all duration-300 border border-gray-100">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Role-Based Access</h3>
                        <p class="text-gray-600">Sistem keamanan dengan role Admin, CSSD, dan Unit untuk kontrol akses yang ketat.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card bg-white rounded-xl shadow-lg p-6 transition-all duration-300 border border-gray-100">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Real-time Dashboard</h3>
                        <p class="text-gray-600">Dashboard interaktif dengan chart dan statistik real-time untuk monitoring operasional.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card bg-white rounded-xl shadow-lg p-6 transition-all duration-300 border border-gray-100">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Order Management</h3>
                        <p class="text-gray-600">Sistem peminjaman instrumen yang terstruktur dengan approval workflow.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="feature-card bg-white rounded-xl shadow-lg p-6 transition-all duration-300 border border-gray-100">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Activity Logging</h3>
                        <p class="text-gray-600">Pencatatan aktivitas lengkap untuk audit trail dan compliance.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="feature-card bg-white rounded-xl shadow-lg p-6 transition-all duration-300 border border-gray-100">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Automated Backup</h3>
                        <p class="text-gray-600">Sistem backup otomatis database dan file untuk keamanan data maksimal.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 gradient-bg text-white">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Meningkatkan Efisiensi Rumah Sakit Anda?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Bergabunglah dengan rumah sakit modern yang telah menggunakan SiAPPMEN untuk pengelolaan instrumen medis yang lebih baik.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 shadow-lg">
                            Akses Sistem
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 shadow-lg">
                            Mulai Sekarang - Gratis
                        </a>
                        <a href="mailto:contact@siappmen.com" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition duration-200">
                            Hubungi Kami
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 text-indigo-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xl font-bold">SiAPPMEN</span>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen untuk meningkatkan efisiensi dan keamanan pengelolaan instrumen medis di rumah sakit.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Produk</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition duration-200">Dashboard</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">QR Code</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Reports</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">API</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Dukungan</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="mailto:support@siappmen.com" class="hover:text-white transition duration-200">Email Support</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Dokumentasi</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Status</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; 2024 SiAPPMEN. All rights reserved. | Powered by Laravel & Livewire</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('splashScreen', () => ({
                show: true,

                init() {
                    setTimeout(() => {
                        this.show = false;
                    }, 2500); // Show splash for 2.5 seconds
                }
            }));

            Alpine.data('mainContent', () => ({
                showSplash: true,

                init() {
                    setTimeout(() => {
                        this.showSplash = false;
                    }, 2500);
                }
            }));
        });
    </script>
</body>
</html>
