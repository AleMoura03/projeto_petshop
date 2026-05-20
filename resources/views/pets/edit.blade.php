<x-app-layout>
    <div class="max-w-4xl mx-auto p-6" x-data="{
        species: '{{ $pet->species }}',
        selectedBreed: '{{ $pet->breed }}',
        selectedSize: '{{ $pet->porte }}',
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

        <h2 class="text-2xl mb-6 font-poppins font-bold text-gray-800 dark:text-gray-200">Editar Pet</h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl p-8 border border-gray-100 dark:border-gray-700">
            <form method="POST" action="{{ route('pets.update', $pet->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Nome do Pet</label>
                    <input type="text" name="name" value="{{ $pet->name }}" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Espécie</label>
                    <select name="species" x-model="species" x-on:change="selectedBreed = ''; selectedSize = '';" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                        <option value="cachorro">Cachorro</option>
                        <option value="gato">Gato</option>
                    </select>
                </div>

                <div x-show="species !== ''" x-transition>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Raça</label>
                    <select name="breed" x-model="selectedBreed" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                        <option value="">Selecione</option>
                        <template x-for="breed in currentBreeds" :key="breed">
                            <option :value="breed" x-text="breed" :selected="breed === selectedBreed"></option>
                        </template>
                    </select>
                </div>

                <div x-show="species !== ''" x-transition>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Porte</label>
                    <select name="porte" x-model="selectedSize" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                        <option value="">Selecione</option>
                        <template x-for="size in currentSizes" :key="size.value">
                            <option :value="size.value" x-text="size.label" :selected="size.value === selectedSize"></option>
                        </template>
                    </select>
                </div>

                <div class="flex items-center gap-4">
                    @if($pet->foto)
                        <img src="{{ $pet->foto }}" alt="{{ $pet->name }}" class="w-16 h-16 object-cover rounded-xl border border-gray-200 shadow-sm">
                    @endif
                    <div class="flex-1">
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Foto do Pet</label>
                        <input type="file" name="foto" accept="image/*" class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                    </div>
                </div>

                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-4 text-base">
                        🐾 Atualizar Pet
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>