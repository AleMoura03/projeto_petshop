<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetSpa e PetShop</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-roboto text-slate-800 antialiased overflow-x-hidden selection:bg-orange-500 selection:text-white">
    <div class="min-h-screen flex flex-col justify-between">
        
        <!-- Navbar -->
        <header class="w-full bg-white shadow-sm py-4 z-50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-3xl">🐾</span>
                    <h1 class="text-2xl font-poppins font-bold text-sky-500">Pet<span class="text-orange-500">Shop</span></h1>
                </div>

                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-poppins font-semibold text-slate-600 hover:text-orange-500 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-poppins font-semibold text-slate-600 hover:text-orange-500 transition-colors">Entrar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="font-poppins font-semibold px-5 py-2.5 rounded-full bg-orange-500 hover:bg-orange-600 text-white shadow-md transition-all">Cadastre-se</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex flex-col lg:flex-row items-center justify-between max-w-7xl mx-auto px-6 sm:px-8 py-12 lg:py-20 gap-12">
            <!-- Text Part -->
            <div class="lg:w-1/2 flex flex-col items-center text-center lg:items-start lg:text-left">
                <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold text-sm mb-6 inline-flex items-center gap-2">
                    ✨ O maior spa para o seu melhor amigo
                </span>
                
                <h2 class="font-poppins font-bold text-4xl lg:text-6xl text-slate-800 leading-tight mb-6">
                    Mimo, diversão e banhos incríveis para o seu <span class="text-sky-500 underline decoration-orange-400">Pet</span>.
                </h2>
                
                <p class="text-lg text-slate-600 mb-10 max-w-xl">
                    Agende os serviços de banho e tosa pela nossa plataforma de maneira simples e rápida. 
                    Seu pet volta cheiroso e cheio de mimos, enquanto você ganha mais praticidade!
                </p>

                <div class="flex gap-4 flex-col sm:flex-row w-full sm:w-auto">
                    @auth
                        <a href="{{ url('/agendar') }}" class="inline-flex justify-center items-center px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-poppins font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all text-lg">
                            Agendar Agora 🧼
                        </a>
                        <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-8 py-4 bg-white border-2 border-sky-200 hover:border-sky-500 hover:bg-sky-50 text-sky-600 font-poppins font-bold rounded-2xl transition-all text-lg">
                            Meu Painel
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-poppins font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all text-lg">
                            Criar minha conta 🐶
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-4 bg-white border-2 border-sky-200 hover:border-sky-500 hover:bg-sky-50 text-sky-600 font-poppins font-bold rounded-2xl transition-all text-lg">
                            Já sou cliente
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Image Part -->
            <div class="lg:w-1/2 w-full mt-10 lg:mt-0 relative">
                <div class="absolute inset-0 bg-sky-200 rounded-3xl transform rotate-3 scale-105 z-0 opacity-50"></div>
                <div class="absolute inset-0 bg-orange-200 rounded-3xl transform -rotate-3 scale-105 z-0 opacity-50"></div>
                <img src="/images/hero_pets_1775261891057.png" alt="Cachorros e gatos felizes" class="relative z-10 w-full h-auto rounded-3xl shadow-2xl object-cover min-h-[400px]">
            </div>
        </main>

        <!-- Fun Extra Feature Section -->
        <section class="bg-sky-500 w-full py-16 mt-8 rounded-t-[3rem]">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
                <h3 class="font-poppins font-bold text-3xl text-white mb-8">Por que escolher o nosso PetShop?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-3xl">
                        <div class="text-4xl mb-4">🛁</div>
                        <h4 class="font-poppins font-bold text-white text-xl mb-2">Banhos Premium</h4>
                        <p class="text-sky-100">Água quentinha e shampoo que não arde o olhinho.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-3xl">
                        <div class="text-4xl mb-4">✂️</div>
                        <h4 class="font-poppins font-bold text-white text-xl mb-2">Tosa Profissional</h4>
                        <p class="text-sky-100">Tosas higiênicas e cortes na tesoura por especialistas.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-3xl">
                        <div class="text-4xl mb-4">📱</div>
                        <h4 class="font-poppins font-bold text-white text-xl mb-2">Plataforma Online</h4>
                        <p class="text-sky-100">Acompanhe pelo sistema e decida em poucos cliques.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-slate-800 text-slate-400 py-6 text-center text-sm font-poppins">
            <p>&copy; {{ date('Y') }} PetShop System. Todos os direitos reservados. 🐾</p>
        </footer>
    </div>
</body>
</html>
