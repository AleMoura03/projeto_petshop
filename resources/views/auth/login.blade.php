<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ roleTab: 'cliente' }">
        @csrf

        <!-- Tabs Role Selector -->
        <div class="flex bg-slate-100 p-1 rounded-xl mb-6">
            <button type="button" @click="roleTab = 'cliente'" :class="{'bg-white shadow-sm text-orange-500 font-bold': roleTab === 'cliente', 'text-slate-500 hover:text-slate-700': roleTab !== 'cliente'}" class="flex-1 py-3 rounded-lg transition-all font-poppins text-sm">🏡 Sou Cliente</button>
            <button type="button" @click="roleTab = 'admin'" :class="{'bg-white shadow-sm text-sky-500 font-bold': roleTab === 'admin', 'text-slate-500 hover:text-slate-700': roleTab !== 'admin'}" class="flex-1 py-3 rounded-lg transition-all font-poppins text-sm">💼 AUdministrador</button>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-slate-600 hover:text-orange-500 transition-colors" href="{{ route('password.request') }}">
                    Esqueceu sua senha?
                </a>
            @endif

            <x-primary-button class="w-full sm:w-auto px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-md transition-all text-base font-poppins font-bold">
                Entrar 🐾
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
