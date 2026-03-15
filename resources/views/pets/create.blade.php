<h1>Cadastrar Pet</h1>

<form method="POST" action="{{ route('pets.store') }}">
    @csrf

    <label>Nome do Pet</label>
    <input type="text" name="name" required>

    <br><br>

    <label>Espécie</label>
    <input type="text" name="species">

    <br><br>

    <label>Raça</label>
    <input type="text" name="breed">

    <br><br>

    <button type="submit">Salvar Pet</button>

</form>