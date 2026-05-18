<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-3xl">⭐</span>
            <h2 class="font-poppins font-bold text-2xl text-sky-600 leading-tight">
                {{ __('Configurações: Novo Administrador') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100">
                <div class="bg-sky-600 text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-poppins font-bold text-lg flex items-center gap-2">Adicionar Novo AUdministrador</h3>
                    <span class="bg-white text-sky-600 px-3 py-1 rounded-full text-xs font-bold">Aprovação Automática</span>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nome')" />
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
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2 flex justify-end mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-xl transition-colors font-bold shadow mr-4 text-center">
                                Voltar
                            </a>
                            <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl transition-colors font-bold shadow">
                                Criar Conta de Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
