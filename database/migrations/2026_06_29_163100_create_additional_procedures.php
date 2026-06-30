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

        // ---- SP 1: sp_ruta_mas_visitada ----
        // Retorna el top N de rutas más usadas en recorridos registrados.
        // Nota técnica: MySQL/MariaDB no soporta valores por defecto en
        // parámetros de entrada de stored procedures, por lo que si no
        // se pasa el parámetro p_limite se usa 5 como fallback interno.
        // Uso: CALL sp_ruta_mas_visitada(10);
        DB::unprepared('
            CREATE PROCEDURE sp_ruta_mas_visitada(IN p_limite INT)
            BEGIN
                DECLARE v_limite INT;
                SET v_limite = IFNULL(p_limite, 5);

                SELECT
                    r.id,
                    r.nombre,
                    r.origen,
                    r.destino,
                    COUNT(re.id) AS total_recorridos
                FROM rutas r
                LEFT JOIN recorridos re ON re.ruta_id = r.id
                GROUP BY r.id, r.nombre, r.origen, r.destino
                ORDER BY total_recorridos DESC
                LIMIT v_limite;
            END
        ');

        // ---- SP 2: sp_paradero_con_mayor_transito ----
        // Recibe un p_ruta_id y retorna los paraderos de esa ruta
        // ordenados por frecuencia de coincidencia textual en recorridos.
        // Limitación documentada: la tabla recorridos almacena origen y
        // destino como cadenas de texto libres (no FK a paraderos), por lo
        // que la aproximación se hace por coincidencia de nombre. La
        // precisión depende de que el usuario escriba el nombre exacto del
        // paradero al crear el recorrido. Una mejora futura sería
        // normalizar esta relación con una FK real.
        // Uso: CALL sp_paradero_con_mayor_transito(1);
        DB::unprepared('
            CREATE PROCEDURE sp_paradero_con_mayor_transito(IN p_ruta_id INT)
            BEGIN
                SELECT
                    p.id,
                    p.nombre,
                    p.orden,
                    COUNT(re.id) AS frecuencia_en_recorridos
                FROM paraderos p
                LEFT JOIN recorridos re
                    ON (re.origen LIKE CONCAT("%", p.nombre, "%")
                    OR re.destino LIKE CONCAT("%", p.nombre, "%"))
                    AND (p.ruta_id = re.ruta_id OR re.ruta_id IS NULL)
                WHERE p.ruta_id = p_ruta_id
                GROUP BY p.id, p.nombre, p.orden
                ORDER BY frecuencia_en_recorridos DESC, p.orden ASC;
            END
        ');

        // ---- SP 3: sp_resumen_general_sistema ----
        // Retorna en un solo result set las métricas clave del sistema.
        // Ideal para mostrar como demo en vivo durante la exposición,
        // pues resume todo el proyecto en una sola llamada.
        // Uso: CALL sp_resumen_general_sistema();
        DB::unprepared('
            CREATE PROCEDURE sp_resumen_general_sistema()
            BEGIN
                SELECT
                    (SELECT COUNT(*) FROM users) AS total_usuarios,
                    (SELECT COUNT(*) FROM rutas WHERE activa = TRUE) AS total_rutas_activas,
                    (SELECT COUNT(*) FROM rutas) AS total_rutas_todas,
                    (SELECT COUNT(*) FROM paraderos) AS total_paraderos,
                    (SELECT COUNT(*) FROM destinos) AS total_destinos,
                    (SELECT COUNT(*) FROM destinos WHERE activo = TRUE) AS total_destinos_activos,
                    (SELECT COUNT(*) FROM recorridos) AS total_recorridos,
                    (SELECT COUNT(*) FROM lugares_visitados) AS total_lugares_visitados,
                    (SELECT COALESCE(ROUND(AVG(puntaje_exploracion), 1), 0)
                     FROM user_profiles) AS promedio_puntaje_exploracion,
                    (SELECT COUNT(*) FROM auditoria_rutas) AS total_eventos_auditoria;
            END
        ');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_ruta_mas_visitada');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_paradero_con_mayor_transito');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_resumen_general_sistema');
    }
};
