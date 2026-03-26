<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">

        <h2 class="text-xl mb-4">Meus Pets</h2>

        <ul>
            @foreach($pets as $pet)
                <li>
                    {{ $pet->name }} - {{ $pet->species }} - {{ $pet->breed }}


                    <a href="{{ route('pets.edit', $pet->id) }}">
                        Editar
                    </a>

                    <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Excluir</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>