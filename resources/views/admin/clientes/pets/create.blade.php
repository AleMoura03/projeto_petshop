<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-2xl text-sky-600 leading-tight">
            {{ __('Adicionar Pet para: ') }} {{ $cliente->name }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="{
        species: '{{ old('species', '') }}',
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100 p-8">
                
                <form action="{{ route('admin.clientes.pets.store', $cliente->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nome do Pet')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="species" :value="__('Espécie')" />
                            <select id="species" name="species" x-model="species" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                                <option value="">Selecione...</option>
                                <option value="cachorro">Cachorro</option>
                                <option value="gato">Gato</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('species')" />
                        </div>

                        <div x-show="species !== ''" x-transition>
                            <x-input-label for="breed" :value="__('Raça')" />
                            <select id="breed" name="breed" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                                <option value="">Selecione a Raça</option>
                                <template x-for="breed in currentBreeds" :key="breed">
                                    <option :value="breed" x-text="breed" :selected="breed === '{{ old('breed') }}'"></option>
                                </template>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('breed')" />
                        </div>

                        <div x-show="species !== ''" x-transition>
                            <x-input-label for="porte" :value="__('Porte (Tamanho)')" />
                            <select id="porte" name="porte" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                                <option value="">Selecione o Porte</option>
                                <template x-for="size in currentSizes" :key="size.value">
                                    <option :value="size.value" x-text="size.label" :selected="size.value === '{{ old('porte') }}'"></option>
                                </template>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('porte')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 gap-3">
                        <a href="{{ route('admin.clientes.index') }}" class="px-6 py-2 bg-slate-200 text-slate-700 hover:bg-slate-300 rounded-xl font-bold transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold shadow transition-colors">
                            Salvar Pet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
