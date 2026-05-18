<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-3xl">👥</span>
            <h2 class="font-poppins font-bold text-2xl text-sky-600 leading-tight">
                {{ __('Gerenciamento de Clientes') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Formulário de Novo Cliente -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                <div class="bg-sky-600 text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-poppins font-bold text-lg flex items-center gap-2">➕ Cadastrar Novo Cliente</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.clientes.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nome do Cliente')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('E-mail')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="password" :value="__('Senha')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        </div>
                        <div class="md:col-span-4 flex justify-end mt-2">
                            <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl transition-colors font-bold shadow">
                                Criar Conta de Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Clientes -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                <div class="p-6 bg-slate-50 border-b border-sky-100">
                    <h3 class="font-poppins font-bold text-xl text-slate-800">Clientes Cadastrados ({{ $clientes->count() }})</h3>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-sm font-semibold uppercase tracking-wider">
                                <th class="px-6 py-4 border-b border-gray-200 pl-8">Nome</th>
                                <th class="px-6 py-4 border-b border-gray-200">E-mail</th>
                                <th class="px-6 py-4 border-b border-gray-200 text-center">Pets Cadastrados</th>
                                <th class="px-6 py-4 border-b border-gray-200 text-center">Data de Cadastro</th>
                                <th class="px-6 py-4 border-b border-gray-200 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($clientes as $cliente)
                                <tr class="hover:bg-sky-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap pl-8 font-medium text-slate-800">
                                        {{ $cliente->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $cliente->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600">
                                        <span class="bg-slate-200 px-3 py-1 rounded-full text-sm font-bold">{{ $cliente->pets_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600">
                                        {{ $cliente->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.clientes.pets.create', $cliente->id) }}" class="px-3 py-1 bg-green-100 text-green-700 hover:bg-green-500 hover:text-white rounded-lg transition-colors font-bold text-sm" title="Adicionar Pet">
                                                + Pet
                                            </a>
                                            <a href="{{ route('admin.clientes.agendamentos.create', $cliente->id) }}" class="px-3 py-1 bg-sky-100 text-sky-700 hover:bg-sky-500 hover:text-white rounded-lg transition-colors font-bold text-sm" title="Adicionar Agendamento">
                                                + Serviço
                                            </a>
                                            <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors font-bold text-sm" onclick="return confirm('ATENÇÃO: A exclusão de um cliente irá apagar também todos os pets e agendamentos relacionados a ele permanentemente. Deseja realmente excluir este cliente?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum cliente cadastrado no sistema.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
