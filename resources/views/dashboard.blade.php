<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">

        <!-- Dashboard Banner -->
        <div class="relative w-full h-[250px] sm:h-[350px] rounded-[2rem] overflow-hidden shadow-2xl mb-12 border border-sky-100">
            <img src="/images/dashboard_pets_1775264221040.png" alt="Cães felizes" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-sky-900/80 via-sky-800/40 to-transparent flex items-center p-8 sm:p-12">
                <div class="text-white max-w-lg">
                    <h2 class="text-4xl sm:text-5xl font-poppins font-bold mb-2 drop-shadow-md">Olá, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                    <p class="text-sky-100 text-lg sm:text-xl font-medium drop-shadow-sm">O que podemos fazer pelo seu bichinho hoje?</p>
                </div>
            </div>
        </div>

        <!-- Cartão Fidelidade -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-sky-100 mb-12 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-orange-500/5 rounded-full -mr-8 -mt-8"></div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="font-poppins font-bold text-2xl text-slate-800 flex items-center gap-2">
                        ⭐ Cartão Fidelidade FaPet
                    </h3>
                    <p class="text-slate-500 text-sm">A cada 10 banhos concluídos, seu pet ganha um banho totalmente grátis! 🐕🧼</p>
                </div>
                <div class="bg-orange-500 text-white font-bold px-4 py-2 rounded-xl text-xs font-poppins shadow-sm">
                    {{ $completedCount }} {{ $completedCount === 1 ? 'Banho Concluído' : 'Banhos Concluídos' }}
                </div>
            </div>

            @php
                $stamps = $completedCount % 10;
                $hasFree = ($completedCount > 0 && $stamps === 0);
                if ($hasFree) {
                    $stamps = 10;
                }
            @endphp

            @if($stamps === 10)
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center gap-4 animate-pulse">
                    <span class="text-3xl">🎉</span>
                    <div>
                        <h4 class="font-poppins font-bold text-green-800 text-lg">Parabéns! Seu cartão está completo!</h4>
                        <p class="text-green-600 text-sm">O próximo banho do seu pet é gratuito. Entre em contato pelo WhatsApp para resgatar seu prêmio!</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-5 sm:grid-cols-10 gap-3 max-w-2xl">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="aspect-square flex flex-col items-center justify-center rounded-2xl border-2 transition-all duration-300 {{ $i <= $stamps ? 'bg-orange-500 text-white border-orange-500 scale-105 shadow-md shadow-orange-500/20' : 'bg-slate-50 border-dashed border-slate-300 text-slate-400' }}">
                        @if($i <= $stamps)
                            @if($i === 10)
                                <span class="text-2xl animate-bounce">🎁</span>
                            @else
                                <span class="text-xl">🐾</span>
                            @endif
                        @else
                            @if($i === 10)
                                <span class="text-lg opacity-40">🎁</span>
                            @else
                                <span class="text-xs font-bold font-poppins">{{ $i }}</span>
                            @endif
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="/agendar" class="block group lg:col-span-2">
                <div class="bg-orange-500 p-8 rounded-3xl shadow flex flex-col h-full justify-between hover:bg-orange-600 hover:-translate-y-1 hover:shadow-xl transition-all border border-orange-400 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-9xl opacity-20 group-hover:scale-110 transition-transform">🛁</div>
                    <div>
                        <h3 class="font-poppins font-bold text-2xl text-white mb-2">Agendar Serviço</h3>
                        <p class="text-orange-100 text-base">Marque um banho e tosa para o seu pet rapidinho com os melhores profissionais.</p>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <span class="bg-white text-orange-500 font-bold px-4 py-2 rounded-xl shadow-sm text-sm">Reservar Agora →</span>
                    </div>
                </div>
            </a>

            <a href="/pets/create" class="block group">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-sky-100 hover:border-sky-300 hover:shadow-lg transition-all text-center h-full flex flex-col justify-center">
                    <div class="w-16 h-16 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl group-hover:scale-110 transition-transform">
                        🐾
                    </div>
                    <h3 class="font-poppins font-bold text-lg text-slate-800 mb-2">Cadastrar Pet</h3>
                    <p class="text-slate-500 text-sm">Adicione um novo peludo à família.</p>
                </div>
            </a>

            <a href="/pets" class="block group">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-green-100 hover:border-green-300 hover:shadow-lg transition-all text-center h-full flex flex-col justify-center">
                    <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl group-hover:scale-110 transition-transform">
                        🐕
                    </div>
                    <h3 class="font-poppins font-bold text-lg text-slate-800 mb-2">Meus Pets</h3>
                    <p class="text-slate-500 text-sm">Veja todos os seus cadastros.</p>
                </div>
            </a>

            <a href="/agendamentos" class="block group lg:col-span-4">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 hover:border-sky-300 hover:shadow-lg transition-all flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center text-2xl group-hover:bg-sky-100 group-hover:text-sky-500 transition-colors">
                            📅
                        </div>
                        <div>
                            <h3 class="font-poppins font-bold text-lg text-slate-800">Acompanhar Agendamentos</h3>
                            <p class="text-slate-500 text-sm">Gerencie todos os horários e serviços marcados para seus pets.</p>
                        </div>
                    </div>
                    <span class="text-slate-400 group-hover:text-sky-500 transition-colors">→</span>
                </div>
            </a>

        </div>

    </div>
</x-app-layout>