<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'nome',
        'preco',
        'duracao'
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
