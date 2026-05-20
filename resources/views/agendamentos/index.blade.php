<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Banner Agendamentos Gatinho -->
        <div class="relative w-full h-[200px] sm:h-[250px] rounded-[2rem] overflow-hidden shadow-xl mb-8 border border-sky-100">
            <img src="/images/cat_bath_1775265539458.png" alt="Gato no banho" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-sky-900/80 to-transparent flex items-end p-8 sm:p-10">
                <div class="w-full flex justify-between items-end">
                    <h2 class="text-3xl sm:text-4xl font-poppins font-bold text-white drop-shadow-md">Meus Agendamentos 🗓️</h2>
                    <a href="{{ route('agendar') }}" class="inline-flex items-center px-6 py-3 bg-white text-sky-600 hover:bg-sky-50 hover:scale-105 rounded-xl font-bold border border-sky-200 shadow-md transition-all">
                        + Novo Agendamento
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros Rápidos (Pet e Serviço) -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-sky-100 dark:border-gray-700 rounded-2xl p-4 mb-6">
            <form method="GET" action="{{ route('agendamentos.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/3">
                    <x-input-label for="pet_id" :value="__('Filtrar por Pet')" />
                    <select id="pet_id" name="pet_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        <option value="">Todos os Pets</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" {{ request('pet_id') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-1/3">
                    <x-input-label for="servico_id" :value="__('Filtrar por Serviço')" />
                    <select id="servico_id" name="servico_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        <option value="">Todos os Serviços</option>
                        @foreach($servicos as $servico)
                            <option value="{{ $servico->id }}" {{ request('servico_id') == $servico->id ? 'selected' : '' }}>
                                {{ $servico->nome_limpo }} ({{ ucfirst($servico->especie) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-1/3 flex gap-2 justify-end">
                    <a href="{{ route('agendamentos.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-gray-700 hover:bg-slate-300 dark:hover:bg-gray-600 text-slate-800 dark:text-slate-200 rounded-lg transition-colors font-bold text-sm w-full text-center">
                        Limpar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors font-bold text-sm w-full">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        @if($agendamentos->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                @if(request()->filled('pet_id') || request()->filled('servico_id'))
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Nenhum agendamento encontrado para o filtro selecionado.</p>
                    <a href="{{ route('agendamentos.index') }}" class="text-sky-600 hover:underline font-semibold">Limpar filtros</a>
                @else
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Você ainda não possui nenhum agendamento.</p>
                    <a href="{{ route('agendar') }}" class="text-sky-600 hover:underline font-semibold">Fazer um agendamento agora</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($agendamentos as $agendamento)
                    @php
                        $statusLabel = $agendamento->status;
                        if ($statusLabel === 'cancelado_cliente') {
                            $statusLabel = 'cancelado';
                        }
                        
                        $statusColor = match($statusLabel) {
                            'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                            'efetuado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                            'pendente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                            'recusado', 'cancelado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                        };
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between hover:shadow-lg transition-shadow relative">
                        <div>
                            <div class="flex items-center justify-between mb-4 w-[100%]">
                                <h3 class="text-xl font-poppins font-semibold text-slate-800 dark:text-slate-100">{{ $agendamento->servico->nome ?? 'Serviço removido' }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            
                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span class="font-medium mr-1">Pet:</span> {{ $agendamento->pet->name ?? 'Pet removido' }}
                            </div>
                            
                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-medium mr-1">Data:</span> {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} às {{ $agendamento->hora }}
                            </div>

                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium mr-1">Preço:</span> R$ {{ number_format($agendamento->preco ?? 0, 2, ',', '.') }}
                            </div>

                            @if($agendamento->status === 'efetuado' && ($agendamento->foto_antes || $agendamento->foto_depois))
                                <div class="mt-4 border-t border-slate-100 dark:border-gray-700 pt-4">
                                    <h4 class="text-xs font-bold font-poppins text-slate-500 dark:text-gray-400 mb-2 uppercase tracking-wide">📸 Resultado do Banho e Tosa:</h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        @if($agendamento->foto_antes)
                                            <div class="relative rounded-xl overflow-hidden border border-slate-100 shadow-sm">
                                                <img src="{{ $agendamento->foto_antes }}" alt="Antes" class="w-full h-28 object-cover">
                                                <span class="absolute bottom-2 left-2 bg-slate-900/70 text-white text-[10px] font-bold px-2 py-0.5 rounded-lg backdrop-blur-sm">Antes</span>
                                            </div>
                                        @endif
                                        @if($agendamento->foto_depois)
                                            <div class="relative rounded-xl overflow-hidden border border-slate-100 shadow-sm">
                                                <img src="{{ $agendamento->foto_depois }}" alt="Depois" class="w-full h-28 object-cover">
                                                <span class="absolute bottom-2 left-2 bg-green-950/70 text-white text-[10px] font-bold px-2 py-0.5 rounded-lg backdrop-blur-sm">Depois ✨</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end items-center border-t border-gray-100 dark:border-gray-700 pt-4 mt-2 gap-3">
                            @if($agendamento->status === 'pendente')
                                <a href="{{ route('agendamentos.edit', $agendamento->id) }}" class="text-sky-600 hover:text-sky-800 font-medium text-sm transition-colors">
                                    Editar
                                </a>
                            @endif

                            @if($agendamento->status === 'pendente' || $agendamento->status === 'aprovado')
                                <form action="{{ route('agendamentos.cancelar', $agendamento->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm transition-colors" onclick="return confirm('Deseja realmente cancelar este serviço?')">Cancelar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
                </div>
        @endif
    </div>
</x-app-layout>