<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>

<h1>Painel do Cliente</h1>

<ul>
    <li>
        <a href="{{ route('pets.create') }}">Cadastrar Pet</a>
    </li>

    <li>
        <a href="{{ route('agendar') }}">Agendar Serviço</a>    </li>

    <li>
        <a href="{{ route('pets.index') }}">Meus Pets</a>
    </li>

    <li>
        <a href="{{ route('agendamentos.index') }}">Meus Agendamentos</a>
    </li>
</ul>