<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-xl mb-4">Novo Agendamento</h2>

        @if(session('success'))
            <div class="bg-green-200 p-2 mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('agendar.store') }}">
            @csrf

            <div class="mb-3">
                <label>Escolha o pet</label>
                <select name="pet_id" id="pet">
                    @foreach($pets as $pet)
                        <option value="{{ $pet->id }}" data-porte="{{ $pet->porte }}">
                            {{ $pet->name }} ({{ $pet->porte }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Serviço:</label>
                <select name="servico_id" id="servico">
                    @foreach($servicos as $servico)
                        <option value="{{ $servico->id }}" data-mini="{{ $servico->preco_mini }}"
                            data-pequeno="{{ $servico->preco_pequeno }}" data-medio="{{ $servico->preco_medio }}"
                            data-grande="{{ $servico->preco_grande }}" data-gigante="{{ $servico->preco_gigante }}">

                            {{ $servico->nome }}
                        </option>
                    @endforeach
                </select>
                <p><strong>Preço:</strong> <span id="preco">R$ 0,00</span></p>

            </div>

            <div class="mb-3">
                <label>Data:</label>
                <input type="date" name="data" required>
            </div>

            <div class="mb-3">
                <label>Hora:</label>
                <input type="time" name="hora" required>
            </div>

            <button type="submit">
                Agendar
            </button>

        </form>

    </div>
</x-app-layout>

<script>
    function calcularPreco() {
        let pet = document.getElementById('pet');
        let servico = document.getElementById('servico');

        let porte = pet.options[pet.selectedIndex].getAttribute('data-porte');

        let preco = servico.options[servico.selectedIndex]
            .getAttribute('data-' + porte);

        if (preco) {
            document.getElementById('preco').innerText = 'R$ ' + parseFloat(preco).toFixed(2);
        } else {
            document.getElementById('preco').innerText = 'R$ 0,00';
        }
    }

    // dispara ao mudar
    document.getElementById('pet').addEventListener('change', calcularPreco);
    document.getElementById('servico').addEventListener('change', calcularPreco);

    // executa ao carregar
    calcularPreco();
</script>