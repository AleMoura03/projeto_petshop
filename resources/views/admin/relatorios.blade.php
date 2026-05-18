<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-3xl">📊</span>
            <h2 class="font-poppins font-bold text-2xl text-sky-600 leading-tight">
                {{ __('Relatório de Faturamento') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100 p-6">
                <form method="GET" action="{{ route('admin.relatorios') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    
                    <div>
                        <x-input-label for="cliente_id" :value="__('Cliente')" />
                        <select id="cliente_id" name="cliente_id" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
                            <option value="">Todos os clientes</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="servico_id" :value="__('Serviço')" />
                        <select id="servico_id" name="servico_id" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
                            <option value="">Todos os serviços</option>
                            @foreach($servicos as $servico)
                                <option value="{{ $servico->id }}" {{ request('servico_id') == $servico->id ? 'selected' : '' }}>
                                    {{ preg_replace('/ \(.*\)/', '', $servico->nome) }} ({{ ucfirst($servico->especie) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="data_inicio" :value="__('Data Inicial')" />
                        <input type="date" id="data_inicio" name="data_inicio" required value="{{ request('data_inicio') }}" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
                    </div>

                    <div>
                        <x-input-label for="data_fim" :value="__('Data Final')" />
                        <input type="date" id="data_fim" name="data_fim" required value="{{ request('data_fim') }}" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" required class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
                            <option value="aprovado" {{ request('status', 'aprovado') == 'aprovado' ? 'selected' : '' }}>Aprovados</option>
                            <option value="efetuado" {{ request('status') == 'efetuado' ? 'selected' : '' }}>Efetuados</option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                            <option value="todos" {{ request('status') == 'todos' ? 'selected' : '' }}>Todos</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="especie" :value="__('Tipo de Animal')" />
                        <select id="especie" name="especie" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
                            <option value="">Todas as Espécies</option>
                            <option value="cachorro" {{ request('especie') == 'cachorro' ? 'selected' : '' }}>Cachorro</option>
                            <option value="gato" {{ request('especie') == 'gato' ? 'selected' : '' }}>Gato</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="w-full px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors font-bold shadow text-sm">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.relatorios') }}" class="w-full px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-lg transition-colors font-bold shadow text-sm text-center">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Faturamento Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-xl rounded-2xl border border-green-200 p-8 text-white">
                <h3 class="font-poppins font-medium text-green-100 text-lg mb-1">Faturamento Total (Aprovados e Efetuados)</h3>
                <p class="text-4xl font-bold">R$ {{ number_format($totalFaturamento, 2, ',', '.') }}</p>
                @if(request()->anyFilled(['cliente_id', 'servico_id', 'mes', 'especie']))
                    <p class="text-green-100 text-sm mt-2">Valores referentes aos filtros selecionados acima.</p>
                @endif
            </div>

            <!-- Tabela de Resultados -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                <div class="p-6 bg-slate-50 border-b border-sky-100">
                    <h3 class="font-poppins font-bold text-xl text-slate-800">Agendamentos Encontrados ({{ $agendamentos->count() }})</h3>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-sm font-semibold uppercase tracking-wider">
                                <th class="px-6 py-4 border-b border-gray-200 pl-8">Cliente</th>
                                <th class="px-6 py-4 border-b border-gray-200">Pet</th>
                                <th class="px-6 py-4 border-b border-gray-200">Serviço</th>
                                <th class="px-6 py-4 border-b border-gray-200">Data</th>
                                <th class="px-6 py-4 border-b border-gray-200">Preço</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($agendamentos as $agendamento)
                                <tr class="hover:bg-sky-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap pl-8">
                                        <div class="font-medium text-slate-800">{{ $agendamento->user->name ?? 'Usuário Removido' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $agendamento->pet->name ?? 'Pet Removido' }} <span class="text-xs bg-slate-200 px-2 py-0.5 rounded-full">{{ ucfirst($agendamento->pet->especie ?? '') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ preg_replace('/ \(.*\)/', '', $agendamento->servico->nome ?? 'Sem serviço') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} às {{ $agendamento->hora }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-green-600">
                                        R$ {{ number_format($agendamento->preco, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum registro de faturamento encontrado com estes filtros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
