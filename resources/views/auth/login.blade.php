<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ roleTab: 'cliente', showPassword: false }">
        @csrf
        
        <input type="hidden" name="role_tab" x-bind:value="roleTab">

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

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                x-bind:type="showPassword ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-cloak style="display: none;" x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>

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
