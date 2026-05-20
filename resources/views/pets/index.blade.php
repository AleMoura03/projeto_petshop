<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">

        <!-- Banner Meus Pets -->
        <div class="relative w-full h-[200px] sm:h-[250px] rounded-[2rem] overflow-hidden shadow-xl mb-8 border border-green-100">
            <img src="/images/my_pets_1775264248061.png" alt="Cachorros no parque" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-green-900/80 to-transparent flex items-end p-8 sm:p-10">
                <div class="w-full flex justify-between items-end">
                    <h2 class="text-3xl sm:text-4xl font-poppins font-bold text-white drop-shadow-md">Meus Pets 🐾</h2>
                    <a href="{{ route('pets.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-green-600 hover:bg-green-50 hover:scale-105 rounded-xl font-bold border border-green-200 shadow-md transition-all">
                        + Cadastrar Novo
                    </a>
                </div>
            </div>
        </div>

        @if($pets->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 mb-4">Você ainda não tem nenhum pet cadastrado.</p>
            </div>
        @else
            <form action="{{ route('pets.bulk_destroy') }}" method="POST" id="bulk-delete-pets-form">
                @csrf
                @method('DELETE')
                
                <div class="flex justify-between items-center mb-4 px-2">
                    <p class="text-slate-500 font-medium">Selecione os pets que deseja excluir:</p>
                    <button type="submit" class="text-red-500 font-bold hover:text-red-700 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-all text-sm" onclick="return confirm('Excluir definitivamente todos os pets selecionados e seus agendamentos?')">
                        🗑️ Excluir Selecionados
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pets as $pet)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between hover:shadow-lg transition-shadow relative">
                        <!-- Checkbox Bulk Delete -->
                        <div class="absolute top-4 right-4 z-10">
                            <input type="checkbox" name="pet_ids[]" value="{{ $pet->id }}" class="w-5 h-5 text-red-500 bg-slate-100 border-slate-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer shadow-sm">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-4 w-[90%]">
                                <div class="flex items-center gap-3">
                                    @if($pet->foto)
                                        <img src="{{ $pet->foto }}" alt="{{ $pet->name }}" class="w-12 h-12 object-cover rounded-xl shadow-sm border border-slate-100">
                                    @else
                                        <div class="w-12 h-12 bg-sky-50 dark:bg-sky-950/30 flex items-center justify-center rounded-xl text-2xl">
                                            {{ $pet->species === 'gato' ? '🐱' : '🐶' }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-xl font-poppins font-semibold text-slate-800 dark:text-slate-100 leading-tight">{{ $pet->name }}</h3>
                                    </div>
                                </div>
                                <span class="bg-sky-100 text-sky-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-sky-900 dark:text-sky-300">
                                    {{ ucfirst($pet->species) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                <span class="font-medium">Raça:</span> {{ preg_replace('/SRD - /', '', $pet->breed) }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                <span class="font-medium">Porte:</span> {{ ucfirst($pet->porte) }}
                            </p>
                        </div>

                        <div class="flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-4 mt-2">
                            <a href="{{ route('pets.edit', $pet->id) }}" class="text-sky-600 hover:text-sky-800 font-medium text-sm transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </a>

                            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm transition-colors flex items-center" onclick="return confirm('Deseja realmente excluir este pet?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
                </div>
            </form>
        @endif
    </div>
</x-app-layout>