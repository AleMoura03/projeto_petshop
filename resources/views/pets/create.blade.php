<h1>Cadastrar Pet</h1>

<form method="POST" action="{{ route('pets.store') }}">
    @csrf

    <label>Nome do Pet</label>
    <input type="text" name="name" required>

    <label>Espécie</label>
    <input type="text" name="species">

    <label>Raça</label>
    <input type="text" name="breed">

    <label>Idade</label>
    <input type="number" name="age">

    <button type="submit">Salvar</button>
</form>