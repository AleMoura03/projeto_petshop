<x-app-layout>
    <div class="max-w-4xl mc-auto p-6">
        <h2 class="text-xl mb-4">Novo Agendamento</h2>

        @if(session('sucess'))
            <div class="bg-green-200 p-2 mb-3">
                {{ session('sucess') }}
            </div>
        @endif

        <form method="POST" action="{{ 'agendamentos.store' }}">
            @csrf

            <div class="mb-3">
                <label>Pet:</label>
                <select name="pet_id" required>
                    @foreach($pets as $pet)
                        <option value="{{ $pet->id }}">{{ $pet->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Seviço:</label>
                <select name="servico_id" required>
                    @foreach($servicos as $servico)
                        <option value="{{ $servico->id }}">
                            {{ $servico->nome }} - R$ {{ $servico->preco }}
                        </option>
                    @endforeach
                </select>
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