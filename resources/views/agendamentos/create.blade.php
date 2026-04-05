<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl mb-6 font-poppins font-bold text-gray-800 dark:text-gray-200">Novo Agendamento 🛁</h2>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg flex items-center shadow-sm">
                <svg class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row">
            
            <!-- Side Image -->
            <div class="lg:w-2/5 relative min-h-[250px] lg:min-h-auto flex items-center justify-center p-8 bg-sky-50">
                <img src="/images/bath_pets_1775261919954.png" alt="Cachorro tomando banho" class="w-full h-[250px] lg:h-full object-cover rounded-2xl shadow-md z-10 relative">
                <div class="absolute inset-0 bg-sky-100 opacity-50 z-0"></div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('agendar.store') }}" class="space-y-4 p-8 lg:w-3/5">
                @csrf

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Qual pet será atendido?</label>
                    <select name="pet_id" id="pet" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3" required>
                        <option value="" selected disabled>Selecione seu pet</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" data-porte="{{ strtolower(trim($pet->porte)) }}" data-species="{{ strtolower(trim($pet->species)) }}">
                                {{ $pet->name }} ({{ ucfirst($pet->species) }} - {{ ucfirst($pet->porte) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Escolha o Serviço</label>
                    <select name="servico_id" id="servico" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3" required>
                        <option value="" selected disabled>Selecione um serviço</option>
                        @foreach($servicos as $servico)
                            <option value="{{ $servico->id }}" 
                                data-especie="{{ $servico->especie ?? 'ambos' }}"
                                data-mini="{{ $servico->preco_mini }}"
                                data-pequeno="{{ $servico->preco_pequeno }}"
                                data-medio="{{ $servico->preco_medio }}"
                                data-grande="{{ $servico->preco_grande }}"
                                data-gigante="{{ $servico->preco_gigante }}">
                                {{ preg_replace('/ \(.*\)/', '', $servico->nome) }}
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="mt-4 p-5 bg-slate-50 dark:bg-gray-700 rounded-xl border border-slate-200 dark:border-gray-600 flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Preço Estimado</span>
                        <span id="preco" class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold text-lg dark:bg-green-900 dark:text-green-300 shadow-sm transition-all duration-200">
                            R$ 0,00
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Data</label>
                        <input type="date" name="data" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Hora</label>
                        <input type="time" name="hora" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                    </div>
                </div>

                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-4 text-base">
                        🗓️ Confirmar Agendamento
                    </x-primary-button>
                </div>

            </form>
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