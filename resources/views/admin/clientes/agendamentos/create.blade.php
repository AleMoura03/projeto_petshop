<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-2xl text-sky-600 leading-tight">
            {{ __('Agendar Serviço para: ') }} {{ $cliente->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-sky-100 p-8">
                
                <form action="{{ route('admin.clientes.agendamentos.store', $cliente->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="pet_id" :value="__('Selecione o Pet')" />
                            <select id="pet" name="pet_id" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                                <option value="" selected disabled>Selecione...</option>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}" data-porte="{{ strtolower(trim($pet->porte)) }}" data-species="{{ strtolower(trim($pet->species)) }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                        {{ $pet->name }} ({{ ucfirst($pet->species) }} - {{ ucfirst($pet->porte) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('pet_id')" />
                        </div>

                        <div>
                            <x-input-label for="servico_id" :value="__('Serviço')" />
                            <select id="servico" name="servico_id" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                                <option value="" selected disabled>Selecione o Serviço...</option>
                                @foreach($servicos as $servico)
                                    <option value="{{ $servico->id }}" 
                                        data-especie="{{ $servico->especie ?? 'ambos' }}"
                                        data-mini="{{ $servico->preco_mini }}"
                                        data-pequeno="{{ $servico->preco_pequeno }}"
                                        data-medio="{{ $servico->preco_medio }}"
                                        data-grande="{{ $servico->preco_grande }}"
                                        data-gigante="{{ $servico->preco_gigante }}"
                                        {{ old('servico_id') == $servico->id ? 'selected' : '' }}>
                                        {{ preg_replace('/ \(.*\)/', '', $servico->nome) }} ({{ ucfirst($servico->especie) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('servico_id')" />
                            
                            <div class="mt-4 p-5 bg-slate-50 dark:bg-gray-700 rounded-xl border border-slate-200 dark:border-gray-600 flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-300 font-medium text-sm">Preço Estimado</span>
                                <span id="preco" class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold text-lg dark:bg-green-900 dark:text-green-300 shadow-sm transition-all duration-200">
                                    R$ 0,00
                                </span>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="data" :value="__('Data')" />
                            <input type="date" id="data" name="data" min="{{ date('Y-m-d') }}" value="{{ old('data') }}" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                            <x-input-error class="mt-2" :messages="$errors->get('data')" />
                        </div>

                        <div>
                            <x-input-label for="hora" :value="__('Horário')" />
                            <select id="hora" name="hora" class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                                <option value="">Selecione o Horário...</option>
                                @foreach(['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $hora)
                                    <option value="{{ $hora }}" {{ old('hora') == $hora ? 'selected' : '' }}>{{ $hora }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('hora')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 gap-3">
                        <a href="{{ route('admin.clientes.index') }}" class="px-6 py-2 bg-slate-200 text-slate-700 hover:bg-slate-300 rounded-xl font-bold transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold shadow transition-colors">
                            Agendar e Aprovar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const petSelect = document.getElementById('pet');
        const servicoSelect = document.getElementById('servico');
        const precoDisplay = document.getElementById('preco');
        
        // Armazenamos as opções originais para poder recriar as opções compatíveis
        const originalOptions = Array.from(servicoSelect.options).map(opt => ({
            value: opt.value,
            text: opt.text,
            especie: opt.getAttribute('data-especie') || '',
            mini: opt.getAttribute('data-mini'),
            pequeno: opt.getAttribute('data-pequeno'),
            medio: opt.getAttribute('data-medio'),
            grande: opt.getAttribute('data-grande'),
            gigante: opt.getAttribute('data-gigante')
        }));

        function updateServices() {
            if (petSelect.selectedIndex <= 0) {
                // Se nenhum pet foi selecionado ainda
                servicoSelect.innerHTML = '<option value="" selected disabled>Selecione um pet primeiro</option>';
                precoDisplay.innerText = 'R$ 0,00';
                return;
            }

            const selectedPet = petSelect.options[petSelect.selectedIndex];
            const petSpecies = selectedPet.getAttribute('data-species').toLowerCase();
            const petSize = selectedPet.getAttribute('data-porte').toLowerCase();

            // Limpa as opções atuais
            servicoSelect.innerHTML = '<option value="" selected disabled>Selecione um serviço</option>';

            const addedNames = new Set();

            originalOptions.forEach(opt => {
                if (!opt.value) return; // ignora o placeholder original

                const servicoEsp = opt.especie.toLowerCase();
                
                // Só adiciona se for de todos ('ambos') ou for da mesma espécie do pet
                if (servicoEsp === 'ambos' || servicoEsp === petSpecies) {
                    if (!addedNames.has(opt.text)) {
                        addedNames.add(opt.text);
                        const newOpt = document.createElement('option');
                        newOpt.value = opt.value;
                        newOpt.text = opt.text;
                        newOpt.setAttribute('data-' + petSize, opt[petSize]); // Anexa apenas o preço deste porte
                        servicoSelect.appendChild(newOpt);
                    }
                }
            });

            calcularPreco();
        }

        function calcularPreco() {
            if (servicoSelect.selectedIndex <= 0) {
                precoDisplay.innerText = 'R$ 0,00';
                return;
            }
            
            const selectedPet = petSelect.options[petSelect.selectedIndex];
            const petSize = selectedPet.getAttribute('data-porte').toLowerCase();
            const selectedOpt = servicoSelect.options[servicoSelect.selectedIndex];
            
            const preco = selectedOpt.getAttribute('data-' + petSize);

            if (preco && !isNaN(preco) && preco !== "") {
                precoDisplay.innerText = 'R$ ' + parseFloat(preco).toFixed(2).replace('.', ',');
            } else {
                precoDisplay.innerText = 'A calcular (Indisponível)';
            }
        }

        petSelect.addEventListener('change', updateServices);
        servicoSelect.addEventListener('change', calcularPreco);

        // Roda a verificação de cara caso tenha old() values (como ao voltar do form)
        updateServices();
    });
</script>
