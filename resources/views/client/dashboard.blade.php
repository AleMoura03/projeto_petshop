<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('pets.create') }}" class="text-blue-500 hover:underline">Cadastrar Pet</a>
                        </li>
                        <li>
                            <a href="{{ route('agendar') }}" class="text-blue-500 hover:underline">Agendar Serviço</a>
                        </li>
                        <li>
                            <a href="{{ route('pets.index') }}" class="text-blue-500 hover:underline">Meus Pets</a>
                        </li>
                        <li>
                            <a href="{{ route('agendamentos.index') }}" class="text-blue-500 hover:underline">Meus Agendamentos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>