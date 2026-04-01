<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = [
        'user_id',
        'pet_id',
        'servico_id',
        'data',
        'hora'
    ];
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class)->withDefault([
            'nome' => 'Serviço removido'
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
