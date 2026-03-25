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
            </tr>

            @foreach($agendamentos as $agendamento)
                <tr>
                    <td>{{ $agendamento->pet->name }}</td>
                    <td>{{ $agendamento->servico->nome }}</td>
                    <td>{{ $agendamento->data }}</td>
                    <td>{{ $agendamento->hora }}</td>
                    <td>{{ $agendamento->status }}</td>
                </tr>
            @endforeach

        </table>
    </div>
</x-app-layout>