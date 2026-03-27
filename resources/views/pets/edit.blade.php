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

<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">

        <h2>Editar Pet</h2>

        <form method="POST" action="{{ route('pets.update', $pet->id) }}">
            @csrf
            @method('PUT')

            <input type="text" name="name" value="{{ $pet->name }}" required>
        <label>Espécie</label>
        <select name="species" required>
            <option value="cachorro" {{ $pet->species == 'cachorro' ? 'selected' : '' }}>
                Cachorro
            </option>
            <option value="gato" {{ $pet->species == 'gato' ? 'selected' : '' }}>
                Gato
            </option>
        </select>
        
        <br><br>

            <label>Raça:</label>
            <select name="breed">
                <option {{ $pet->breed == 'SRD' ? 'selected' : '' }}>SRD - Sem Raça Definida</option>
                <option {{ $pet->breed == 'Shih Tzu' ? 'selected' : '' }}>Shih Tzu</option>
                <option {{ $pet->breed == 'Labrador' ? 'selected' : '' }}>Labrador</option>
                <option {{ $pet->breed == 'Golden Retriever' ? 'selected' : '' }}>Golden Retriever</option>
                <option {{ $pet->breed == 'Bulldog' ? 'selected' : '' }}>Bulldog</option>
            </select>

            <br><br>

            <label>Porte:</label>
            <select name="porte">
                <option value="mini" {{ $pet->porte == 'mini' ? 'selected' : '' }}>Mini (até 4kg)</option>
                <option value="pequeno" {{ $pet->porte == 'pequeno' ? 'selected' : '' }}>Pequeno (5-10kg)</option>
                <option value="medio" {{ $pet->porte == 'medio' ? 'selected' : '' }}>Médio (11-25kg)</option>
                <option value="grande" {{ $pet->porte == 'grande' ? 'selected' : '' }}>Grande (26-44kg)</option>
                <option value="gigante" {{ $pet->porte == 'gigante' ? 'selected' : '' }}>Gigante (45kg+)</option>
            </select>

            <button type="submit">Atualizar</button>

        </form>

    </div>
</x-app-layout>