

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

        <br><br>

        <label>Data:</label>
        <input type="date" name="data" value="{{ $agendamento->data }}">

        <br><br>

        <label>Hora:</label>
        <input type="time" name="hora" value="{{ $agendamento->hora }}">

        <br><br>

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

    atualizarPreco();
</script>