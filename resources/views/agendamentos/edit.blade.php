<x-app-layout>
<div class="max-w-4xl mx-auto p-6">

    <h2>Editar Agendamento</h2>

    <form method="POST" action="{{ route('agendamentos.update', $agendamento->id) }}">
        @csrf
        @method('PUT')

        <label>Pet:</label>
        <select name="pet_id">
            @foreach($pets as $pet)
                <option value="{{ $pet->id }}" 
                    {{ $pet->id == $agendamento->pet_id ? 'selected' : '' }}>
                    {{ $pet->name }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Serviço:</label>
        <select name="servico_id">
            @foreach($servicos as $servico)
                <option value="{{ $servico->id }}"
                    {{ $servico->id == $agendamento->servico_id ? 'selected' : '' }}>
                    {{ $servico->nome }}
                </option>
            @endforeach
        </select>

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