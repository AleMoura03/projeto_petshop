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
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Escolha os Serviços (você pode selecionar mais de um)</label>
                    <div id="servicos-container" class="grid grid-cols-1 gap-2 max-h-[220px] overflow-y-auto p-1 border border-gray-200 dark:border-gray-700 rounded-xl">
                        <p class="text-gray-500 text-sm p-4 text-center">Selecione um pet primeiro</p>
                    </div>
                    
                    <div class="mt-4 p-5 bg-slate-50 dark:bg-gray-700 rounded-xl border border-slate-200 dark:border-gray-600 flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Preço Total Estimado</span>
                        <span id="preco" class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold text-lg dark:bg-green-900 dark:text-green-300 shadow-sm transition-all duration-200">
                            R$ 0,00
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Data</label>
                        <input type="date" name="data" min="{{ date('Y-m-d') }}" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Hora</label>
                        <input type="time" name="hora" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-xs text-sky-600 dark:text-sky-400 font-semibold bg-sky-50 dark:bg-sky-950/30 p-2.5 rounded-lg border border-sky-100 dark:border-sky-900/50">
                            🕒 <strong>Horário de Funcionamento Comercial:</strong><br>
                            • Segunda a Sexta: 08:00 às 18:00<br>
                            • Sábados: 08:00 às 12:00<br>
                            • Domingos: Fechados
                        </p>
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
        const container = document.getElementById('servicos-container');
        const precoDisplay = document.getElementById('preco');
        
        // Dados estáticos dos serviços
        const servicosList = [
            @foreach($servicos as $servico)
            {
                id: {{ $servico->id }},
                nome: "{{ $servico->nome }}",
                especie: "{{ strtolower(trim($servico->especie ?? 'ambos')) }}",
                mini: {{ $servico->preco_mini ?? 0 }},
                pequeno: {{ $servico->preco_pequeno ?? 0 }},
                medio: {{ $servico->preco_medio ?? 0 }},
                grande: {{ $servico->preco_grande ?? 0 }},
                gigante: {{ $servico->preco_gigante ?? 0 }}
            },
            @endforeach
        ];

        function updateServices() {
            if (petSelect.selectedIndex <= 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm p-4 text-center">Selecione um pet primeiro</p>';
                precoDisplay.innerText = 'R$ 0,00';
                return;
            }

            const selectedPet = petSelect.options[petSelect.selectedIndex];
            const petSpecies = selectedPet.getAttribute('data-species').toLowerCase();
            const petSize = selectedPet.getAttribute('data-porte').toLowerCase();

            container.innerHTML = '';
            let count = 0;

            servicosList.forEach(servico => {
                if (servico.especie === 'ambos' || servico.especie === petSpecies) {
                    const preco = servico[petSize];
                    const precoFormatted = parseFloat(preco).toFixed(2).replace('.', ',');

                    const label = document.createElement('label');
                    label.className = 'flex items-center gap-3 p-3 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600 hover:bg-sky-50 dark:hover:bg-slate-700 cursor-pointer transition-all';
                    
                    label.innerHTML = `
                        <input type="checkbox" name="servico_ids[]" value="${servico.id}" data-preco="${preco}" class="w-5 h-5 text-sky-600 bg-white border-gray-300 rounded focus:ring-sky-500 focus:ring-2 cursor-pointer shadow-sm">
                        <div class="flex-1">
                            <span class="font-medium text-slate-800 dark:text-slate-200">${servico.nome}</span>
                        </div>
                        <span class="text-sm font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-950/30 px-2.5 py-1 rounded-lg">R$ ${precoFormatted}</span>
                    `;

                    // Add change listener to calculate price
                    label.querySelector('input').addEventListener('change', calcularPreco);
                    
                    container.appendChild(label);
                    count++;
                }
            });

            if (count === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm p-4 text-center">Nenhum serviço disponível para este pet.</p>';
            }

            calcularPreco();
        }

        function calcularPreco() {
            let total = 0;
            const checkboxes = container.querySelectorAll('input[type="checkbox"]:checked');
            
            checkboxes.forEach(cb => {
                const precoVal = parseFloat(cb.getAttribute('data-preco'));
                if (!isNaN(precoVal)) {
                    total += precoVal;
                }
            });

            precoDisplay.innerText = 'R$ ' + total.toFixed(2).replace('.', ',');
        }

        petSelect.addEventListener('change', updateServices);
 
        // Validação front-end de Horário Comercial
        const form = document.querySelector('form');
        const dataInput = document.querySelector('input[name="data"]');
        const horaInput = document.querySelector('input[name="hora"]');

        function validarHorario() {
            const dataVal = dataInput.value;
            const horaVal = horaInput.value;

            if (!dataVal || !horaVal) return true;

            const date = new Date(dataVal + 'T00:00:00');
            const day = date.getDay(); // 0 (Domingo) - 6 (Sábado)
            
            const [hours, minutes] = horaVal.split(':').map(Number);
            const timeNum = hours * 60 + minutes;

            if (day === 0) {
                alert('O petshop está fechado aos domingos! Por favor, selecione outro dia.');
                dataInput.value = '';
                return false;
            }

            if (day === 6) { // Sábado
                if (timeNum < 8 * 60 || timeNum > 12 * 60) {
                    alert('Horário comercial de Sábado é das 08:00 às 12:00. Por favor, escolha outro horário.');
                    horaInput.value = '';
                    return false;
                }
            } else { // Segunda a Sexta
                if (timeNum < 8 * 60 || timeNum > 18 * 60) {
                    alert('Horário comercial de Segunda a Sexta é das 08:00 às 18:00. Por favor, escolha outro horário.');
                    horaInput.value = '';
                    return false;
                }
            }
            return true;
        }

        dataInput.addEventListener('change', validarHorario);
        horaInput.addEventListener('change', validarHorario);

        form.addEventListener('submit', function(e) {
            if (!validarHorario()) {
                e.preventDefault();
                return;
            }
            
            const checkboxes = container.querySelectorAll('input[type="checkbox"]:checked');
            if (checkboxes.length === 0 && petSelect.selectedIndex > 0) {
                alert('Por favor, selecione pelo menos um serviço!');
                e.preventDefault();
            }
        });

        // Inicializa
        updateServices();
    });
</script>