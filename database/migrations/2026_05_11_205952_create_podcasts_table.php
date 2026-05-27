<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('podcasts')) {
            Schema::create('podcasts', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->date('fecha_estreno');
                $table->string('genero');
                $table->string('duracion');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
