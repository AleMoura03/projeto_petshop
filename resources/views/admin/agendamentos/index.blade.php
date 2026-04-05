<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <form action="{{ route('admin.agendamentos.bulk_destroy') }}" method="POST" id="bulk-delete-form">
            @csrf
            @method('DELETE')

            <div class="flex justify-between items-center mb-6">
                <h2 class="font-poppins font-bold text-2xl text-slate-800 dark:text-gray-200 leading-tight">
                    Gestão de Agendamentos 🗂️
                </h2>
                
                @if(!$agendamentos->isEmpty())
                <button type="submit" class="text-red-500 font-bold hover:text-red-700 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-all text-sm" onclick="return confirm('Ocultar definitivamente todos os agendamentos selecionados?')">
                    🗑️ Ocultar Selecionados
                </button>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 text-sm font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 rounded-tl-xl w-10"></th>
                            <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 pl-4">Cliente</th>
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
                                <td class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                                    <input type="checkbox" name="agendamento_ids[]" value="{{ $agendamento->id }}" class="w-5 h-5 text-red-500 bg-slate-100 border-slate-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer shadow-sm">
                                </td>
                                <td class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap pl-4">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">{{ $agendamento->user->name ?? 'Usuário' }}</div>
                                </td>
                                <td class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap text-gray-600 dark:text-gray-300">
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
                                        @endif

                                        <form action="{{ route('admin.agendamentos.destroy', $agendamento->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-200" title="Ocultar da Tela" onclick="return confirm('Deseja realmente ocultar este agendamento do painel administrativo?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
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
        </form>

    </div>
</x-app-layout>