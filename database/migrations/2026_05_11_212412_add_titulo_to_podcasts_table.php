<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('podcasts') && !Schema::hasColumn('podcasts', 'titulo')) {
            Schema::table('podcasts', function (Blueprint $table) {
                $table->string('titulo');
                $table->date('fecha_estreno');
                $table->string('genero');
                $table->string('duracion');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('podcasts') && Schema::hasColumn('podcasts', 'titulo')) {
            Schema::table('podcasts', function (Blueprint $table) {
                $table->dropColumn(['titulo', 'fecha_estreno', 'genero', 'duracion']);
            });
        }
    }
};
