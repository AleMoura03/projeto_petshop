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
                                                <form action="{{ route('agendamentos.efetuar', $agendamento->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white rounded-lg transition-colors border border-blue-200" title="Marcar como Efetuado/Concluído">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                                    </button>
                                                </form>
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
</x-app-layout>