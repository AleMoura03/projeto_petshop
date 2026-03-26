<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">

        <h2>Editar Pet</h2>

        <form method="POST" action="{{ route('pets.update', $pet->id) }}">
            @csrf
            @method('PUT')

            <input type="text" name="name" value="{{ $pet->name }}" required>

            <input type="text" name="species" value="{{ $pet->species }}" required>

            <input type="text" name="breed" value="{{ $pet->breed }}" required>

            <input type="text" name="age" value="{{ $pet->age }}" required>

            <button type="submit">Atualizar</button>

        </form>

    </div>
</x-app-layout>