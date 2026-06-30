<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Índices para consultas frecuentes en rutas
        Schema::table('rutas', function (Blueprint $table) {
            $table->index('origen', 'idx_rutas_origen');
            $table->index('destino', 'idx_rutas_destino');
        });

        // Índices compuestos para reportes de recorridos por usuario y fecha
        Schema::table('recorridos', function (Blueprint $table) {
            $table->index('created_at', 'idx_recorridos_created_at');
            $table->index(['user_id', 'created_at'], 'idx_recorridos_user_created');
        });

        // Índice compuesto para cálculo de destinos distintos visitados por usuario
        Schema::table('lugares_visitados', function (Blueprint $table) {
            $table->index(['user_id', 'destino_id'], 'idx_visitados_user_destino');
        });

        // Índice en rutas.activa para filtrar rápidamente rutas activas
        Schema::table('rutas', function (Blueprint $table) {
            $table->index('activa', 'idx_rutas_activa');
        });

        // Índice en destinos.activo para filtrar destinos activos
        Schema::table('destinos', function (Blueprint $table) {
            $table->index('activo', 'idx_destinos_activo');
        });
    }

    public function down(): void
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->dropIndex('idx_rutas_origen');
            $table->dropIndex('idx_rutas_destino');
            $table->dropIndex('idx_rutas_activa');
        });

        Schema::table('recorridos', function (Blueprint $table) {
            $table->dropIndex('idx_recorridos_created_at');
            $table->dropIndex('idx_recorridos_user_created');
        });

        Schema::table('lugares_visitados', function (Blueprint $table) {
            $table->dropIndex('idx_visitados_user_destino');
        });

        Schema::table('destinos', function (Blueprint $table) {
            $table->dropIndex('idx_destinos_activo');
        });
    }
};
