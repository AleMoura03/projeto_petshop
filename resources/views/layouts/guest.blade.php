<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Autenticação | FaPet - Banho e Tosa</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-roboto text-slate-800 antialiased bg-slate-50 selection:bg-orange-500 selection:text-white">
        <div class="min-h-screen flex">
            <!-- Left Side: Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 py-12 lg:px-24">
                <div class="max-w-md w-full mx-auto">
                    <div class="mb-10 text-center lg:text-left">
                        <a href="/" class="inline-flex items-center gap-2 mb-6">
                            <span class="text-4xl hover:-rotate-12 transition-transform drop-shadow-md">🐾</span>
                            <span class="text-3xl font-poppins font-bold text-sky-500 tracking-tight">Fa<span class="text-orange-500">Pet</span></span>
                        </a>
                        <h2 class="text-2xl font-poppins font-bold text-slate-800">Acesse sua conta ou cadastre-se!</h2>
                        <p class="text-slate-500 mt-2">Os doguinhos e gatinhos estão te esperando.</p>
                    </div>

                    <div class="bg-white px-8 py-10 shadow-2xl rounded-3xl border border-sky-100/50">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Side: Image Cover -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-sky-100 items-center justify-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-orange-500/20 to-transparent z-10 mix-blend-overlay"></div>
                <img src="/images/login_pets_1775261905595.png" alt="Puppy sorrindo" class="absolute inset-0 w-full h-full object-cover">
                
                <!-- Floating Element -->
                <div class="absolute bottom-12 left-12 z-20 bg-white/20 backdrop-blur-md p-6 rounded-3xl border border-white/30 shadow-2xl max-w-sm">
                    <p class="text-white font-poppins font-semibold text-lg drop-shadow-md">
                        "O melhor lugar para seu companheiro de 4 patas." 🦮
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
