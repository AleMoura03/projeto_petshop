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

        <!-- Banner Relatório de Gastos -->
        <div class="relative w-full h-[150px] sm:h-[200px] rounded-[2rem] overflow-hidden shadow-xl mb-8 border border-sky-100">
            <img src="/images/my_pets_1775264248061.png" alt="Pets no parque" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-sky-900/80 to-transparent flex items-end p-8 sm:p-10">
                <h2 class="text-3xl sm:text-4xl font-poppins font-bold text-white drop-shadow-md">Relatório de Gastos 📊</h2>
            </div>
        </div>

        <!-- Filtros e Card de Faturamento -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100 p-6 mb-8">
            <h3 class="font-poppins font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                🔍 Filtrar Minha Pesquisa
                <span class="text-xs font-normal text-slate-500">(Todos os campos são obrigatórios para a busca)</span>
            </h3>

            <div class="flex flex-col lg:flex-row gap-6 items-stretch">
                <!-- Form de Filtros -->
                <form method="GET" action="{{ route('cliente.relatorios') }}" class="w-full lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="data_inicio" :value="__('Data Inicial')" />
                        <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <x-input-label for="data_fim" :value="__('Data Final')" />
                        <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <x-input-label for="especie" :value="__('Espécie do Animal')" />
                        <select id="especie" name="especie" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                            <option value="" disabled {{ !request('especie') ? 'selected' : '' }}>Selecione a Espécie</option>
                            <option value="todos" {{ request('especie') == 'todos' ? 'selected' : '' }}>Todos os Animais</option>
                            <option value="cachorro" {{ request('especie') == 'cachorro' ? 'selected' : '' }}>Cachorro</option>
                            <option value="gato" {{ request('especie') == 'gato' ? 'selected' : '' }}>Gato</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="servico_nome" :value="__('Tipo de Serviço')" />
                        <select id="servico_nome" name="servico_nome" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                            <option value="" disabled {{ !request('servico_nome') ? 'selected' : '' }}>Selecione o Serviço</option>
                            <option value="todos" {{ request('servico_nome') == 'todos' ? 'selected' : '' }}>Todos os Serviços</option>
                            @foreach($servicos as $servico)
                                <option value="{{ $servico->nome_limpo }}" 
                                        data-especie="{{ $servico->especie }}"
                                        {{ request('servico_nome') == $servico->nome_limpo ? 'selected' : '' }}>
                                    {{ $servico->nome_limpo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex gap-2 justify-end mt-2">
                        <a href="{{ route('cliente.relatorios') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-lg transition-colors font-bold shadow text-sm text-center">
                            Limpar Filtros
                        </a>
                        <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors font-bold shadow text-sm">
                            Pesquisar Gastos
                        </button>
                    </div>
                </form>

                <!-- Card Total Gasto -->
                <div class="w-full lg:w-1/3 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow flex flex-col justify-center items-center text-center">
                    <h3 class="font-poppins font-semibold text-green-100 text-sm uppercase tracking-wider mb-2">Total Gasto</h3>
                    <p class="text-4xl font-extrabold">R$ {{ number_format($totalGasto, 2, ',', '.') }}</p>
                    <span class="text-xs bg-white/20 text-white px-2.5 py-0.5 rounded-full mt-3 font-medium">Aprovados & Efetuados</span>
                    @if(request()->anyFilled(['data_inicio', 'data_fim', 'especie', 'servico_nome']))
                        <p class="text-green-100 text-xs mt-2 italic">Valor filtrado do período</p>
                    @else
                        <p class="text-green-100 text-xs mt-2 italic">Valor acumulado total</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabela de Resultados -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
            <div class="p-6 bg-slate-50 border-b border-sky-100">
                <h3 class="font-poppins font-bold text-xl text-slate-800">
                    Histórico de Agendamentos Encontrados ({{ $agendamentos->count() }})
                </h3>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 text-sm font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4 border-b border-gray-200 pl-8">Pet</th>
                            <th class="px-6 py-4 border-b border-gray-200">Serviço</th>
                            <th class="px-6 py-4 border-b border-gray-200">Data e Hora</th>
                            <th class="px-6 py-4 border-b border-gray-200">Status</th>
                            <th class="px-6 py-4 border-b border-gray-200 text-right pr-8">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($agendamentos as $agendamento)
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap pl-8 font-medium text-slate-800">
                                    {{ $agendamento->pet->name ?? 'Pet Removido' }} 
                                    <span class="text-xs bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full ml-1">
                                        {{ ucfirst($agendamento->pet->especie ?? '') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ preg_replace('/ \(.*\)/', '', $agendamento->servico->nome ?? 'Sem serviço') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} às {{ $agendamento->hora }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusLabel = $agendamento->status;
                                        if ($statusLabel === 'cancelado_cliente') {
                                            $statusLabel = 'cancelado';
                                        }
                                        
                                        $statusColor = match($agendamento->status) {
                                            'aprovado' => 'bg-green-100 text-green-800',
                                            'efetuado' => 'bg-blue-100 text-blue-800',
                                            'pendente' => 'bg-yellow-100 text-yellow-800',
                                            'recusado', 'cancelado', 'cancelado_cliente' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600 text-right pr-8">
                                    R$ {{ number_format($agendamento->preco, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum registro encontrado. Tente filtrar para ver seus gastos!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const especieSelect = document.getElementById('especie');
        const servicoSelect = document.getElementById('servico_nome');

        // Guarda as opções originais do select de serviços para filtrar posteriormente
        const originalOptions = Array.from(servicoSelect.options).map(opt => ({
            value: opt.value,
            text: opt.text,
            especie: opt.getAttribute('data-especie') || '',
            disabled: opt.disabled,
            selected: opt.selected
        }));

        function updateServices() {
            const selectedEspecie = especieSelect.value;
            
            if (!selectedEspecie) {
                servicoSelect.disabled = true;
                // Deixa apenas a opção inicial desabilitada selecionada
                servicoSelect.innerHTML = '<option value="" disabled selected>Selecione a Espécie Primeiro</option>';
                return;
            }

            servicoSelect.disabled = false;
            
            // Limpa o select de serviços
            servicoSelect.innerHTML = '';
            
            // Adiciona a opção padrão
            const defaultOpt = document.createElement('option');
            defaultOpt.value = "";
            defaultOpt.text = "Selecione o Serviço";
            defaultOpt.disabled = true;
            
            let selectedVal = "{{ request('servico_nome') }}";
            defaultOpt.selected = !selectedVal;
            servicoSelect.appendChild(defaultOpt);

            // Adiciona a opção "Todos os Serviços"
            const todosOpt = document.createElement('option');
            todosOpt.value = "todos";
            todosOpt.text = "Todos os Serviços";
            if (selectedVal === 'todos') {
                todosOpt.selected = true;
            }
            servicoSelect.appendChild(todosOpt);

            originalOptions.forEach(opt => {
                if (!opt.value) return; // ignora placeholders

                const servicoEsp = opt.especie.toLowerCase();

                // Mostra se a espécie selecionada for 'todos', ou o serviço for de 'ambos', ou coincidir com a espécie selecionada
                if (selectedEspecie === 'todos' || servicoEsp === 'ambos' || servicoEsp === selectedEspecie) {
                    const newOpt = document.createElement('option');
                    newOpt.value = opt.value;
                    newOpt.text = opt.text + (selectedEspecie === 'todos' && servicoEsp !== 'ambos' ? ' (' + opt.especie + ')' : '');
                    newOpt.setAttribute('data-especie', opt.especie);
                    
                    if (opt.value === selectedVal) {
                        newOpt.selected = true;
                    }
                    servicoSelect.appendChild(newOpt);
                }
            });
        }

        especieSelect.addEventListener('change', updateServices);

        // Dispara de imediato para caso de reload ou valores antigos de busca preenchidos
        updateServices();
    });
</script>
