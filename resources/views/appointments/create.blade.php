@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

<h1>Agendar Serviço</h1>

<form method="POST" action="{{ route('appointments.store') }}">
@csrf

<label>Pet</label>
<select name="pet_id">

@foreach($pets as $pet)
    <option value="{{ $pet->id }}">
    {{ $pet->name }}
    </option>
@endforeach

</select>

<br><br>

<label>Serviço</label>

<select name="service">
<option value="banho">Banho</option>
<option value="tosa">Tosa</option>
<option value="banho_tosa">Banho + Tosa</option>
</select>

<br><br>

<label>Data</label>
<input type="date" name="date">

<br><br>

<label>Horário</label>
<input type="time" name="time">

<br><br>

<button type="submit">Agendar</button>

</form>