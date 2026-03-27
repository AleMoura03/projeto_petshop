<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->decimal('preco_mini', 8, 2)->nullable();
            $table->decimal('preco_pequeno', 8, 2)->nullable();
            $table->decimal('preco_medio', 8, 2)->nullable();
            $table->decimal('preco_grande', 8, 2)->nullable();
            $table->decimal('preco_gigante', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
