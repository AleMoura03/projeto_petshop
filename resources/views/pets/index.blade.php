<h1>Meus Pets</h1>

<a href="/pets/create">Cadastrar novo pet</a>

<table border="1">

    <tr>
        <th>Nome</th>
        <th>Espécie</th>
        <th>Raça</th>
        <th>Idade</th>
    </tr>

    @foreach($pets as $pet)

        <tr>
            <td>{{ $pet->name }}</td>
            <td>{{ $pet->species }}</td>
            <td>{{ $pet->breed }}</td>
            <td>{{ $pet->age }}</td>
        </tr>

    @endforeach

</table>