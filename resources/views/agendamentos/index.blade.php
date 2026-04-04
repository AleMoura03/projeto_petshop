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

        @if($agendamentos->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 mb-4">Você ainda não possui nenhum agendamento.</p>
                <a href="{{ route('agendar') }}" class="text-sky-600 hover:underline font-semibold">Fazer um agendamento agora</a>
            </div>
        @else
            <form action="{{ route('agendamentos.bulk_destroy') }}" method="POST" id="bulk-delete-form">
                @csrf
                @method('DELETE')
                
                <div class="flex justify-between items-center mb-4 px-2">
                    <p class="text-slate-500 font-medium">Selecione os itens que deseja excluir do seu painel:</p>
                    <button type="submit" class="text-red-500 font-bold hover:text-red-700 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-all text-sm" onclick="return confirm('Excluir todos itens selecionados da tela?')">
                        🗑️ Excluir Selecionados
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($agendamentos as $agendamento)
                    @php
                        $statusLabel = $agendamento->status;
                        if ($statusLabel === 'cancelado_cliente') {
                            $statusLabel = 'cancelado';
                        }
                        
                        $statusColor = match($statusLabel) {
                            'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                            'pendente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                            'recusado', 'cancelado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                        };
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between hover:shadow-lg transition-shadow relative">
                        <!-- Checkbox Bulk Delete -->
                        <div class="absolute top-4 right-4 z-10">
                            <input type="checkbox" name="agendamento_ids[]" value="{{ $agendamento->id }}" class="w-5 h-5 text-red-500 bg-slate-100 border-slate-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer shadow-sm">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-4 w-[90%]">
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

                            <form action="{{ route('agendamentos.destroy', $agendamento->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm transition-colors" onclick="return confirm('Isto vai apenas remover do seu painel e não cancela o agendamento! Tem certeza?')" title="Ocultar da Tela">Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
                </div>
            </form>
        @endif
    </div>
</x-app-layout>