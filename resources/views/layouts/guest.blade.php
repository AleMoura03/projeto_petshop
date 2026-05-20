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

        <!-- Botão Flutuante do WhatsApp -->
        <a href="https://api.whatsapp.com/send?phone=5538992379455&text=Olá!%20Gostaria%20de%20tirar%20uma%20dúvida%20sobre%20o%20petshop." 
           target="_blank" 
           class="fixed bottom-6 right-6 z-[9999] flex items-center justify-center w-16 h-16 text-white rounded-full shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300"
           style="background-color: #25D366; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3); width: 64px; height: 64px;"
           title="Fale Conosco no WhatsApp">
            <!-- Official WhatsApp SVG Icon -->
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" style="color: white; fill: white;">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.455 5.703 1.458h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
    </body>
</html>
