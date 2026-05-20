<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'age',
        'porte',
        'foto',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function agendamentos(){
        return $this->hasMany(Agendamento::class);
    }
}
