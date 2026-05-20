<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Roboto', sans-serif; }
            h1, h2, h3, h4, h5, h6, .font-poppins { font-family: 'Poppins', sans-serif; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-roboto antialiased text-gray-700">
        <div class="min-h-screen bg-slate-50 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @if(session('whatsapp_url'))
            <script>
                window.open("{{ session('whatsapp_url') }}", '_blank');
            </script>
        @endif

        @if(auth()->check() && !str_contains(request()->getPathInfo(), '/admin'))
             <!-- Botão Flutuante do WhatsApp -->
             <a href="https://api.whatsapp.com/send?phone=5538992379455&text=Olá!%20Gostaria%20de%20tirar%20uma%20dúvida%20sobre%20o%20petshop." 
                target="_blank" 
                class="fixed bottom-6 right-6 z-[9999] flex items-center justify-center w-16 h-16 text-white rounded-full shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300"
                style="background-color: #25D366; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3); width: 64px; height: 64px;"
                title="Fale Conosco no WhatsApp">
                 <!-- Modern SVG Chat Bubble representing WhatsApp -->
                 <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; fill: white;">
                     <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                 </svg>
             </a>
        @endif
    </body>
</html>
