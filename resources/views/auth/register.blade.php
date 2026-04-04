<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ roleTab: 'cliente' }">
        @csrf

        <!-- Tabs Role Selector -->
        <div class="flex bg-slate-100 p-1 rounded-xl mb-6">
            <button type="button" @click="roleTab = 'cliente'" :class="{'bg-white shadow-sm text-orange-500 font-bold': roleTab === 'cliente', 'text-slate-500 hover:text-slate-700': roleTab !== 'cliente'}" class="flex-1 py-3 rounded-lg transition-all font-poppins text-sm">🏡 Sou Cliente</button>
            <button type="button" @click="roleTab = 'admin'" :class="{'bg-white shadow-sm text-sky-500 font-bold': roleTab === 'admin', 'text-slate-500 hover:text-slate-700': roleTab !== 'admin'}" class="flex-1 py-3 rounded-lg transition-all font-poppins text-sm">💼 AUdministrador</button>
        </div>
        <input type="hidden" name="role" :value="roleTab">
        
        <div x-show="roleTab === 'admin'" x-transition class="mb-6 p-4 bg-sky-50 border border-sky-100 rounded-xl text-sky-800 text-sm">
            <span class="font-bold flex items-center gap-2">⚠️ Atenção:</span>
            O perfil de AUdministrador passará por aprovação de outro administrador antes de ser liberado para login.
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-4">
            <a class="underline text-sm text-slate-600 hover:text-orange-500 transition-colors" href="{{ route('login') }}">
                Já tem uma conta? Entrar
            </a>

            <x-primary-button class="w-full sm:w-auto px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-md transition-all text-base font-poppins font-bold">
                Criar Conta 🚀
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
