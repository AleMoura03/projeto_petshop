<x-app-layout>
    <div class="max-w-4xl mx-auto p-6" x-data="{
        species: '',
        dogBreeds: ['SRD - Sem Raça Definida', 'Shih Tzu', 'Labrador', 'Golden Retriever', 'Bulldog', 'Poodle', 'Pastor Alemão', 'Rottweiler', 'Pinscher', 'Yorkshire', 'PitBull'],
        catBreeds: ['SRD - Sem Raça Definida', 'Siamês', 'Persa', 'Maine Coon', 'Angorá', 'Sphynx', 'Ragdoll', 'Ashera'],
        dogSizes: [
            {value: 'mini', label: 'Mini (até 4kg)'},
            {value: 'pequeno', label: 'Pequeno (5-10kg)'},
            {value: 'medio', label: 'Médio (11-25kg)'},
            {value: 'grande', label: 'Grande (26-44kg)'},
            {value: 'gigante', label: 'Gigante (45kg+)'}
        ],
        catSizes: [
            {value: 'pequeno', label: 'Pequeno (até 4kg)'},
            {value: 'medio', label: 'Médio (5-8kg)'},
            {value: 'grande', label: 'Grande (9kg+)'}
        ],
        get currentBreeds() {
            return this.species === 'gato' ? this.catBreeds : (this.species === 'cachorro' ? this.dogBreeds : []);
        },
        get currentSizes() {
            return this.species === 'gato' ? this.catSizes : (this.species === 'cachorro' ? this.dogSizes : []);
        }
    }">
        <div class="relative w-full h-[150px] sm:h-[200px] rounded-[2rem] overflow-hidden shadow-xl mb-8 border border-sky-100">
            <img src="/images/my_pets_1775264248061.png" alt="Cachorros no parque" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-sky-900/80 to-transparent flex items-end p-8 sm:p-10">
                <h2 class="text-3xl sm:text-4xl font-poppins font-bold text-white drop-shadow-md">Cadastrar Pet 🐾</h2>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl p-8 border border-gray-100 dark:border-gray-700">
            <form method="POST" action="{{ route('pets.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Nome do Pet</label>
                    <input type="text" name="name" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Espécie</label>
                    <select name="species" x-model="species" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                        <option value="">Selecione</option>
                        <option value="cachorro">Cachorro</option>
                        <option value="gato">Gato</option>
                    </select>
                </div>

                <div x-show="species !== ''">
                <div x-show="species !== ''" x-transition>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Raça</label>
                    <select name="breed" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                        <option value="">Selecione</option>
                        <template x-for="breed in currentBreeds" :key="breed">
                            <option :value="breed" x-text="breed"></option>
                        </template>
                    </select>
                </div>

                <div x-show="species !== ''">
                <div x-show="species !== ''" x-transition>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Porte</label>
                    <select name="porte" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                        <option value="">Selecione</option>
                        <template x-for="size in currentSizes" :key="size.value">
                            <option :value="size.value" x-text="size.label"></option>
                        </template>
                    </select>
                </div>

                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-4 text-base">
                        🐾 Cadastrar Pet
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>