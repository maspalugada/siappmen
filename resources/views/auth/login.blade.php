<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SiAPPMEN</title>

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

        .animate-float {
            animation: float 6s ease-in-out infinite;
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

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }

        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            bottom: 10%;
            right: 20%;
            animation-delay: 6s;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .login-form {
                padding: 1.5rem;
                margin: 1rem;
            }

            .login-title {
                font-size: 1.875rem;
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                font-size: 1rem;
            }

            .nav-brand {
                font-size: 1.125rem;
            }

            .demo-accounts {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .demo-account-item {
                padding: 0.5rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 1rem;
                margin: 0.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .login-subtitle {
                font-size: 0.875rem;
            }

            .nav-brand {
                font-size: 1rem;
            }

            .floating-shapes {
                display: none;
            }
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen gradient-bg relative overflow-hidden">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape w-32 h-32 bg-white rounded-full"></div>
        <div class="shape w-24 h-24 bg-indigo-200 rounded-lg rotate-45"></div>
        <div class="shape w-20 h-20 bg-purple-200 rounded-full"></div>
        <div class="shape w-16 h-16 bg-blue-200 rounded-lg"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-10 p-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2 text-white hover:text-indigo-100 transition duration-200 nav-brand">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xl font-bold">SiAPPMEN</span>
            </a>

            <div class="flex items-center space-x-4">
                <a href="/" class="text-white hover:text-indigo-100 px-3 py-2 rounded-lg transition duration-200">
                    Beranda
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-white hover:text-indigo-100 px-3 py-2 rounded-lg transition duration-200">
                        Daftar
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="relative z-10 flex items-center justify-center min-h-[calc(100vh-80px)] px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Title -->
            <div class="text-center animate-slide-up">
                <div class="mx-auto w-20 h-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6 animate-float">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2 login-title">
                    Selamat Datang Kembali
                </h2>
                <p class="text-indigo-100 login-subtitle">
                    Masuk ke akun SiAPPMEN Anda
                </p>
            </div>

            <!-- Login Form -->
            <div class="glass-effect rounded-2xl p-8 shadow-2xl animate-fade-in login-form" style="animation-delay: 0.2s">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                   class="input-focus block w-full pl-10 pr-3 py-3 border border-white border-opacity-30 rounded-lg bg-white bg-opacity-10 text-white placeholder-indigo-200 backdrop-blur-sm focus:outline-none focus:ring-0 transition duration-200"
                                   placeholder="Masukkan email Anda">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                   class="input-focus block w-full pl-10 pr-10 py-3 border border-white border-opacity-30 rounded-lg bg-white bg-opacity-10 text-white placeholder-indigo-200 backdrop-blur-sm focus:outline-none focus:ring-0 transition duration-200"
                                   placeholder="Masukkan password Anda">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword()" class="text-indigo-300 hover:text-white transition duration-200">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-sm" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="rounded border-white border-opacity-30 bg-white bg-opacity-10 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0">
                            <span class="ml-2 text-sm text-indigo-100">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-200 hover:text-white transition duration-200">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-hover w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Masuk ke Sistem
                        </span>
                    </button>
                </form>

                <!-- Demo Accounts -->
                <div class="mt-6 pt-6 border-t border-white border-opacity-20">
                    <p class="text-xs text-indigo-200 text-center mb-3">Akun Demo untuk Testing:</p>
                    <div class="grid grid-cols-1 gap-2 text-xs demo-accounts">
                        <div class="bg-white bg-opacity-5 rounded p-2 demo-account-item">
                            <strong class="text-white">Admin:</strong> <code class="text-indigo-200">admin@siappmen.test</code>
                        </div>
                        <div class="bg-white bg-opacity-5 rounded p-2 demo-account-item">
                            <strong class="text-white">CSSD:</strong> <code class="text-indigo-200">cssd@siappmen.test</code>
                        </div>
                        <div class="bg-white bg-opacity-5 rounded p-2 demo-account-item">
                            <strong class="text-white">Unit:</strong> <code class="text-indigo-200">unit@siappmen.test</code>
                        </div>
                        <div class="bg-white bg-opacity-5 rounded p-2 demo-account-item">
                            <strong class="text-white">Password:</strong> <code class="text-indigo-200">password</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center animate-fade-in" style="animation-delay: 0.4s">
                <p class="text-indigo-200 text-sm">
                    Belum punya akun?
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-medium text-white hover:text-indigo-100 transition duration-200">
                            Daftar sekarang
                        </a>
                    @endif
                </p>
                <p class="text-indigo-300 text-xs mt-2">
                    &copy; 2024 SiAPPMEN - Sistem Modern untuk Pengelolaan Instrumen Medis
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.focus();
            }
        });
    </script>
</body>
</html>
