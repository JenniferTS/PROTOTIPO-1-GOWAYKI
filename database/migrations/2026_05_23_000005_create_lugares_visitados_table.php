<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lugares_visitados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('destino_id')->constrained('destinos')->onDelete('cascade');
            $table->date('fecha_visita')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'destino_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lugares_visitados');
    }
};
