<h1>Cadastrar Pet</h1>

<form method="POST" action="{{ route('pets.store') }}">
    @csrf

    <label>Nome do Pet</label>
    <input type="text" name="name" required>

    <br><br>

    <label>Espécie</label>
    <select name="species">
        <option value="cachorro">Cachorro</option>
        <option value="gato">Gato</option>
    </select>

    <br><br>

    <label>Raça</label>
    <select name="breed">
        <option>SRD - Sem Raça Definida</option>
        <option>Shih Tzu</option>
        <option>Yorkshire</option>
        <option>Poodle</option>
        <option>Lhasa Apso</option>
        <option>Golden Retriever</option>
        <option>Labrador</option>
        <option>Bulldog Francês</option>
        <option>Pinscher</option>
        <option>Pastor Alemão</option>
        <option>Outros</option>
    </select>

    <label>Idade</label>
    <select name="age_range">
        <option>3-6 meses</option>
        <option>6-9 meses</option>
        <option>9-12 meses</option>
        <option>1-3 anos</option>
        <option>3-6 anos</option>
        <option>6+ anos</option>
    </select>

    <br><br>

    <button type="submit">Salvar Pet</button>

</form>