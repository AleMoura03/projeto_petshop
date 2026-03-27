@php
$racas = [
    'SRD - Sem Raça Definida',
    'Shih Tzu',
    'Labrador',
    'Golden Retriever',
    'Bulldog',
    'Poodle',
    'Pastor Alemão',
    'Rottweiler',
    'Pinscher',
    'Yorkshire'
];
@endphp

<h1>Cadastrar Pet</h1>

<form method="POST" action="{{ route('pets.store') }}">
    @csrf

    <label>Nome do Pet</label>
    <input type="text" name="name" required>

    <br><br>

    <label>Espécie</label>
    <select name="species" required>
        <option value="">Selecione</option>
        <option value="cachorro">Cachorro</option>
        <option value="gato">Gato</option>
    </select>
    
    <br><br>

    <label>Raça</label>
    <select name="breed" required>
        @foreach($racas as $raca)
            <option value="{{ $raca }}">
                {{ $raca }}
            </option>
        @endforeach
    </select>

    <label>Porte:</label>
    <select name="porte" required>
        <option value="">Selecione</option>
        <option value="mini">Mini (até 4kg)</option>
        <option value="pequeno">Pequeno (5-10kg)</option>
        <option value="medio">Médio (11-25kg)</option>
        <option value="grande">Grande (26-44kg)</option>
        <option value="gigante">Gigante (45kg+)</option>
    </select>

    <br><br>

    <button type="submit">Salvar Pet</button>

</form>