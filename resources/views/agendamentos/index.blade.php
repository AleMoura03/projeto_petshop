<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">


        <h2 class="text-xl mb-4">Meus Agendamentos</h2>

        <table border="1" cellpadding="10">
            <tr>
                <th>Pet</th>
                <th>Serviço</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Status</th>
                <th>Preco</th>
            </tr>

            @foreach($agendamentos as $agendamento)
                <tr>
                    <td>{{ $agendamento->pet->name ?? 'Pet removido' }}</td>
                    <td>{{ $agendamento->servico->nome ?? 'Serviço removido' }}</td>
                    <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                    <td>{{ $agendamento->hora }}</td>
                    <td>{{ $agendamento->status ?? 'Pendente' }}</td>
                    <td>R$ {{ number_format($agendamento->preco ?? 0, 2, ',', '.') }}</td>
                </tr>

                <td>
                    <a href="{{ route('agendamentos.edit', $agendamento->id) }}">
                        Editar
                    </a>
                    <form action="{{ route('agendamentos.destroy', $agendamento->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Cancelar</button>
                    </form>
                </td>
            @endforeach

        </table>
    </div>
</x-app-layout>