<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tagline', 120)->nullable();
            $table->text('descripcion');
            $table->enum('categoria', ['turistico', 'cultural', 'gastronomico', 'recreativo', 'historico']);
            $table->string('distrito');
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->string('imagen_url')->nullable();
            $table->decimal('calificacion', 3, 2)->default(0.00);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinos');
    }
};
