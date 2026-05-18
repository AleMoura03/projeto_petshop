

<x-app-layout>
<div class="max-w-4xl mx-auto p-6">

    <h2>Editar Agendamento</h2>

    <form method="POST" action="{{ route('agendamentos.update', $agendamento->id) }}">
        @csrf
        @method('PUT')

        <label>Pet:</label>
        <select name="pet_id" id="pet">
            @foreach($pets as $pet)
                <option value="{{ $pet->id }}" 
                    {{ $pet->id == $agendamento->pet_id ? 'selected' : '' }}>
                    {{ $pet->name }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Serviço:</label>
        <select name="servico_id" id="servico">
            
            @foreach($servicos as $servico)
                <option value="{{ $servico->id }}"
                    {{ $servico->id == $agendamento->servico_id ? 'selected' : '' }}>
                    {{ $servico->nome }}
                </option>
            @endforeach
        </select>

        <p id="precoServico" style="font-weigth: bold;"></p>

        <div>
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Data</label>
            <input type="date" name="data" value="{{ $agendamento->data }}" min="{{ date('Y-m-d') }}" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">
        </div><br>

        <label>Hora:</label>
        <input type="time" name="hora" value="{{ $agendamento->hora }}" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-sky-500 focus:ring-sky-300 rounded-xl shadow-sm block w-full px-4 py-3">

        <div class="mt-4">
            <p class="text-xs text-sky-600 dark:text-sky-400 font-semibold bg-sky-50 dark:bg-sky-950/30 p-2.5 rounded-lg border border-sky-100 dark:border-sky-900/50">
                🕒 <strong>Horário de Funcionamento Comercial:</strong><br>
                • Segunda a Sexta: 08:00 às 18:00<br>
                • Sábados: 08:00 às 12:00<br>
                • Domingos: Fechados
            </p>
        </div>

        <br>

        <button type="submit">Salvar</button>

    </form>

</div>
</x-app-layout>

<script>
    const servicos = @json($servicos);
    const pets = @json($pets);

    const selectPet = document.getElementById('pet');
    const selectServico = document.getElementById('servico');
    const precoServico = document.getElementById('precoServico');

    function atualizarPreco() {
        if (!selectPet || !selectServico) return;

        const petId = selectPet.value;
        const servicoId = selectServico.value;

        const pet = pets.find(p => String(p.id) === String(petId));
        const servico = servicos.find(s => String(s.id) === String(servicoId));

        if (!pet || !servico) {
            precoServico.innerHTML = "Preço indisponível";
            return;
        }

        let preco = 0;

        switch (pet.porte) {
            case 'mini': preco = servico.preco_mini; break;
            case 'pequeno': preco = servico.preco_pequeno; break;
            case 'medio': preco = servico.preco_medio; break;
            case 'grande': preco = servico.preco_grande; break;
            case 'gigante': preco = servico.preco_gigante; break;
            default: preco = 0;
        }

        precoServico.innerHTML = "Preço: R$ " + Number(preco).toFixed(2);
    }

    selectPet.addEventListener('change', atualizarPreco);
    selectServico.addEventListener('change', atualizarPreco);

    // Validação front-end de Horário Comercial
    const form = document.querySelector('form');
    const dataInput = document.querySelector('input[name="data"]');
    const horaInput = document.querySelector('input[name="hora"]');

    function validarHorario() {
        const dataVal = dataInput.value;
        const horaVal = horaInput.value;

        if (!dataVal || !horaVal) return true;

        // Criar data local correta
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
        }
    });

    atualizarPreco();
</script>