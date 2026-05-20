<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-3xl">🐾</span>
            <h2 class="font-poppins font-bold text-2xl text-sky-600 leading-tight">
                {{ __('Painel do AUdministrador') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Admin Banner Image -->
            <div class="w-full relative h-[250px] rounded-3xl overflow-hidden shadow-lg border border-sky-100">
                <img src="/images/admin_pets_1775264236536.png" alt="Cãozinho formal" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-sky-900/60 to-transparent flex items-center p-10">
                    <div class="text-white">
                        <h3 class="font-poppins font-bold text-3xl mb-2">Bem-vindo, Chefe!</h3>
                        @if(auth()->user()->is_super_admin)
                            <p class="text-sky-100 text-lg max-w-lg">Como líder desta matilha, você gerencia todos os agendamentos e aprova novos AUdministradores da plataforma.</p>
                        @else
                            <p class="text-sky-100 text-lg max-w-lg">Aqui você gerencia todos os agendamentos e serviços da plataforma.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Adicionar Novo Admin (Apenas Super Admin) -->
            @if(auth()->user()->is_super_admin)
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                <div class="bg-sky-600 text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-poppins font-bold text-lg flex items-center gap-2">⭐ Adicionar Novo AUdministrador</h3>
                    <span class="bg-white text-sky-600 px-3 py-1 rounded-full text-xs font-bold">Aprovação Automática</span>
                </div>
                <div class="p-6 flex justify-between items-center bg-slate-50">
                    <p class="text-slate-600">Como líder desta matilha, você pode criar novas contas de audministradores.</p>
                    <a href="{{ route('admin.users.create') }}" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl transition-colors font-bold shadow text-sm">
                        + Configurar Nova Conta
                    </a>
                </div>
            </div>
            @endif

            <!-- Aprovações de AUdministradores -->
            @if(isset($pendingAdmins) && $pendingAdmins->count() > 0)
            <div class="bg-orange-50 border border-orange-200 shadow-xl rounded-2xl overflow-hidden">
                <div class="bg-orange-500 text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-poppins font-bold text-lg flex items-center gap-2">⚠️ Solicitações de AUdministradores Pendentes</h3>
                    <span class="bg-white text-orange-600 px-3 py-1 rounded-full text-xs font-bold">{{ $pendingAdmins->count() }} aguardando</span>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-orange-800 text-sm font-semibold uppercase tracking-wider border-b border-orange-200">
                                <th class="px-6 py-3">Nome</th>
                                <th class="px-6 py-3">E-mail</th>
                                <th class="px-6 py-3">Pendente desde</th>
                                <th class="px-6 py-3 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-200">
                            @foreach($pendingAdmins as $admin)
                                <tr class="hover:bg-orange-100/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800">{{ $admin->name }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $admin->email }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('admin.users.approve', $admin->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors font-bold shadow text-sm">
                                                    Aprovar
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.reject', $admin->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors font-bold shadow text-sm" onclick="return confirm('Tem certeza que deseja recusar este usuário?')">
                                                    Recusar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Gerenciamento de AUdministradores Cadastrados (Apenas Super Admin) -->
            @if(auth()->user()->is_super_admin && isset($allAdmins) && $allAdmins->count() > 0)
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                <div class="bg-slate-800 text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-poppins font-bold text-lg flex items-center gap-2">👥 Membros da Matilha (AUdministradores)</h3>
                    <span class="bg-sky-600 text-white px-3 py-1 rounded-full text-xs font-bold">{{ $allAdmins->count() }} cadastrado(s)</span>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-sm font-semibold uppercase tracking-wider">
                                <th class="px-6 py-4 pl-8">Nome</th>
                                <th class="px-6 py-4">E-mail</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center pr-8">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($allAdmins as $admin)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 pl-8 font-medium text-slate-800">
                                        {{ $admin->name }}
                                        @if($admin->is_super_admin)
                                            <span class="ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Líder</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $admin->email }}</td>
                                    <td class="px-6 py-4">
                                        @if($admin->is_approved)
                                            <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Aprovado</span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center pr-8">
                                        @if(!$admin->is_super_admin)
                                            <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3.5 py-1.5 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-200 rounded-lg transition-colors font-semibold text-xs shadow-sm" onclick="return confirm('Tem certeza que deseja excluir esta conta de AUdministrador?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">Ação indisponível</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Seção de Agenda e Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Calendário Visual (2/3 da largura) -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xl border border-sky-100">
                    <h3 class="font-poppins font-bold text-xl text-slate-800 mb-4 flex items-center gap-2">
                        📅 Agenda de Atendimentos
                    </h3>
                    <div id="calendar" class="min-h-[450px]"></div>
                </div>

                <!-- Painel de Métricas e Gráficos (1/3 da largura) -->
                <div class="space-y-6">
                    <!-- Gráfico de Rosca: Distribuição de Pets -->
                    <div class="bg-white p-6 rounded-2xl shadow-xl border border-sky-100 flex flex-col justify-between h-[250px]">
                        <h3 class="font-poppins font-bold text-base text-slate-800 mb-2">🐕 Espécies Atendidas</h3>
                        <div class="relative w-full h-[150px] flex items-center justify-center">
                            <canvas id="speciesChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Barra: Faturamento dos Últimos Dias -->
                    <div class="bg-white p-6 rounded-2xl shadow-xl border border-sky-100 flex flex-col justify-between h-[250px]">
                        <h3 class="font-poppins font-bold text-base text-slate-800 mb-2">💰 Faturamento Recente (R$)</h3>
                        <div class="relative w-full h-[150px] flex items-center justify-center">
                            <canvas id="billingChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tabela de Agendamentos -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                    <div class="p-6 bg-slate-50 border-b border-sky-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <h3 class="font-poppins font-bold text-xl text-slate-800">Agendamentos de Serviços</h3>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 text-sm font-semibold uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 rounded-tl-xl pl-8">Cliente</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">Pet</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">Serviço</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">Data e Hora</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">Preço</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">Status</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 text-center rounded-tr-xl">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($agendamentos as $agendamento)
                                <tr class="hover:bg-sky-50 dark:hover:bg-slate-700 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap pl-8 border-b border-gray-100">
                                        <div class="font-medium text-slate-800 dark:text-slate-200">{{ $agendamento->user->name ?? 'Usuário removido' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 border-b border-gray-100">
                                        {{ $agendamento->pet->name ?? 'Sem pet' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                        {{ preg_replace('/ \(.*\)/', '', $agendamento->servico->nome ?? 'Sem serviço') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} <span class="text-gray-400">às</span> {{ $agendamento->hora }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-700 dark:text-slate-300">
                                        R$ {{ number_format($agendamento->preco, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusLabel = $agendamento->status;
                                            if ($statusLabel === 'cancelado_cliente') {
                                                $statusLabel = 'cancelado (cliente)';
                                            }
                                            
                                            $statusColor = match($agendamento->status) {
                                                'aprovado' => 'bg-green-100 text-green-800',
                                                'efetuado' => 'bg-blue-100 text-blue-800',
                                                'pendente' => 'bg-yellow-100 text-yellow-800',
                                                'recusado', 'cancelado_cliente', 'cancelado' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                            @if($agendamento->status == 'pendente')
                                                <form action="{{ route('agendamentos.aprovar', $agendamento->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-200" title="Aprovar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('agendamentos.recusar', $agendamento->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-orange-50 text-orange-600 hover:bg-orange-500 hover:text-white rounded-lg transition-colors border border-orange-200" title="Recusar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            @elseif($agendamento->status == 'aprovado')
                                                <button type="button" onclick="document.getElementById('efetuar-modal-{{ $agendamento->id }}').showModal()" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white rounded-lg transition-colors border border-blue-200" title="Marcar como Concluído">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438z"></path></svg>
                                                </button>

                                                <!-- Modal para Finalizar Agendamento -->
                                                <dialog id="efetuar-modal-{{ $agendamento->id }}" class="rounded-2xl p-6 shadow-2xl border border-slate-100 max-w-md w-full backdrop:bg-slate-900/50 text-left">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h4 class="font-poppins font-bold text-lg text-slate-800">Concluir Agendamento</h4>
                                                        <button type="button" onclick="document.getElementById('efetuar-modal-{{ $agendamento->id }}').close()" class="text-slate-400 hover:text-slate-600">✕</button>
                                                    </div>
                                                    <form action="{{ route('agendamentos.efetuar', $agendamento->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                        @csrf
                                                        <p class="text-slate-600 text-sm">Você está marcando o serviço para <strong>{{ $agendamento->pet->name }}</strong> como concluído. Opcionalmente, adicione fotos de antes e depois:</p>
                                                        
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Foto Antes (Opcional)</label>
                                                            <input type="file" name="foto_antes" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Foto Depois (Opcional)</label>
                                                            <input type="file" name="foto_depois" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                                        </div>

                                                        <div class="flex justify-end gap-2 pt-2">
                                                            <button type="button" onclick="document.getElementById('efetuar-modal-{{ $agendamento->id }}').close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-sm transition-colors">Cancelar</button>
                                                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow transition-colors">Concluir Serviço</button>
                                                        </div>
                                                    </form>
                                                </dialog>

                                                <form action="{{ route('agendamentos.lembrete', $agendamento->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-200" title="Enviar Lembrete por WhatsApp">
                                                        💬
                                                    </button>
                                                </form>
                                            @elseif($agendamento->status == 'efetuado')
                                                <button type="button" onclick="document.getElementById('fotos-modal-{{ $agendamento->id }}').showModal()" class="p-2 bg-slate-50 text-slate-600 hover:bg-slate-200 rounded-lg transition-colors border border-slate-200" title="Ver/Atualizar Fotos">
                                                    📸
                                                </button>

                                                <!-- Modal para Ver/Editar Fotos -->
                                                <dialog id="fotos-modal-{{ $agendamento->id }}" class="rounded-2xl p-6 shadow-2xl border border-slate-100 max-w-lg w-full backdrop:bg-slate-900/50 text-left">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h4 class="font-poppins font-bold text-lg text-slate-800">Fotos de Antes & Depois</h4>
                                                        <button type="button" onclick="document.getElementById('fotos-modal-{{ $agendamento->id }}').close()" class="text-slate-400 hover:text-slate-600">✕</button>
                                                    </div>
                                                    <form action="{{ route('agendamentos.efetuar', $agendamento->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                        @csrf
                                                        
                                                        <!-- Grid de visualização de fotos existentes -->
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Foto Antes</span>
                                                                @if($agendamento->foto_antes)
                                                                    <img src="{{ $agendamento->foto_antes }}" alt="Antes" class="w-full h-32 object-cover rounded-xl border mb-2">
                                                                @else
                                                                    <div class="w-full h-32 bg-slate-100 flex items-center justify-center text-slate-400 text-xs rounded-xl border border-dashed mb-2">Sem foto cadastrada</div>
                                                                @endif
                                                                <input type="file" name="foto_antes" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-sky-50 file:text-sky-700">
                                                            </div>

                                                            <div>
                                                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Foto Depois</span>
                                                                @if($agendamento->foto_depois)
                                                                    <img src="{{ $agendamento->foto_depois }}" alt="Depois" class="w-full h-32 object-cover rounded-xl border mb-2">
                                                                @else
                                                                    <div class="w-full h-32 bg-slate-100 flex items-center justify-center text-slate-400 text-xs rounded-xl border border-dashed mb-2">Sem foto cadastrada</div>
                                                                @endif
                                                                <input type="file" name="foto_depois" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-green-50 file:text-green-700">
                                                            </div>
                                                        </div>

                                                        <div class="flex justify-end gap-2 pt-4">
                                                            <button type="button" onclick="document.getElementById('fotos-modal-{{ $agendamento->id }}').close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-sm transition-colors">Fechar</button>
                                                            <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm shadow transition-colors">Salvar Fotos</button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                        @if($agendamentos->isEmpty())
                            <div class="p-8 text-center text-gray-500">Nenhum agendamento encontrado no painel.</div>
                        @endif
                    </div>
            </div>
        </div>
    </div>

    <!-- Scripts e estilos para Agenda e Gráficos -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inicialização do FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia'
                },
                events: @json($calendarEvents),
                eventClick: function(info) {
                    alert(
                        '📝 Agendamento:\n' + 
                        info.event.title + '\n\n' +
                        'Status: ' + info.event.extendedProps.status + '\n' +
                        'Preço: R$ ' + info.event.extendedProps.preco
                    );
                }
            });
            calendar.render();

            // 2. Gráfico de Espécies (Chart.js)
            const speciesCtx = document.getElementById('speciesChart').getContext('2d');
            new Chart(speciesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cães', 'Gatos'],
                    datasets: [{
                        data: [{{ $speciesCounts['cachorro'] }}, {{ $speciesCounts['gato'] }}],
                        backgroundColor: ['#0EA5E9', '#F43F5E'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        }
                    }
                }
            });

            // 3. Gráfico de Faturamento (Chart.js)
            const billingCtx = document.getElementById('billingChart').getContext('2d');
            new Chart(billingCtx, {
                type: 'bar',
                data: {
                    labels: @json($faturamentoDatas),
                    datasets: [{
                        label: 'Faturamento (R$)',
                        data: @json($faturamentoValores),
                        backgroundColor: '#10B981',
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F1F5F9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>