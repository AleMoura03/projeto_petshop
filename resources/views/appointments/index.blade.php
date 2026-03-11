<h1>Meus Agendamentos</h1>

<table border="1">

<tr>
<th>Pet</th>
<th>Serviço</th>
<th>Data</th>
<th>Hora</th>
<th>Status</th>
</tr>

@foreach($appointments as $appointment)

    <tr>
    <td>{{ $appointment->pet->name }}</td>
    <td>{{ $appointment->service }}</td>
    <td>{{ $appointment->date }}</td>
    <td>{{ $appointment->time }}</td>
    <td>{{ $appointment->status }}</td>
    </tr>

@endforeach

</table>