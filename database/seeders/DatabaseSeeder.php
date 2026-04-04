<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Limpar serviços existentes para evitar duplicidade
        \App\Models\Servico::query()->delete();

        \App\Models\Servico::create([
            'nome' => 'Banho (Cachorro)',
            'especie' => 'cachorro',
            'preco_mini' => 40.00,
            'preco_pequeno' => 50.00,
            'preco_medio' => 65.00,
            'preco_grande' => 85.00,
            'preco_gigante' => 110.00,
            'duracao' => 60
        ]);

        \App\Models\Servico::create([
            'nome' => 'Banho e Tosa (Cachorro)',
            'especie' => 'cachorro',
            'preco_mini' => 65.00,
            'preco_pequeno' => 80.00,
            'preco_medio' => 100.00,
            'preco_grande' => 130.00,
            'preco_gigante' => 160.00,
            'duracao' => 120
        ]);

        \App\Models\Servico::create([
            'nome' => 'Banho (Gato)',
            'especie' => 'gato',
            'preco_pequeno' => 60.00, // Preço balanceado: menor que o gigante canino
            'preco_medio' => 75.00,
            'preco_grande' => 90.00,
            'duracao' => 60
        ]);

        \App\Models\Servico::create([
            'nome' => 'Banho e Tosa (Gato)',
            'especie' => 'gato',
            'preco_pequeno' => 90.00,
            'preco_medio' => 110.00,
            'preco_grande' => 130.00,
            'duracao' => 120
        ]);
    }
}
