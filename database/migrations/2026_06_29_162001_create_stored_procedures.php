<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_reporte_actividad_usuario');

        DB::unprepared('
            CREATE PROCEDURE sp_reporte_actividad_usuario(
                IN p_user_id INT,
                IN p_fecha_inicio DATE,
                IN p_fecha_fin DATE
            )
            BEGIN
                SELECT
                    u.id AS user_id,
                    u.name,
                    COUNT(DISTINCT r.id) AS total_recorridos,
                    COUNT(DISTINCT lv.id) AS total_lugares_visitados,
                    COUNT(DISTINCT lv.destino_id) AS destinos_distintos,
                    COALESCE(up.puntaje_exploracion, 0) AS puntaje_exploracion
                FROM users u
                LEFT JOIN recorridos r
                    ON r.user_id = u.id
                    AND r.created_at BETWEEN p_fecha_inicio AND p_fecha_fin
                LEFT JOIN lugares_visitados lv
                    ON lv.user_id = u.id
                    AND lv.created_at BETWEEN p_fecha_inicio AND p_fecha_fin
                LEFT JOIN user_profiles up ON up.user_id = u.id
                WHERE u.id = p_user_id
                GROUP BY u.id, u.name, up.puntaje_exploracion;
            END
        ');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_reporte_actividad_usuario');
    }
};
