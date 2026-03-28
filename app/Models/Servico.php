<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'nome',
        'preco_mini',
        'preco_pequeno',
        'preco_medio',
        'preco_grande',
        'preco_gigante',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
