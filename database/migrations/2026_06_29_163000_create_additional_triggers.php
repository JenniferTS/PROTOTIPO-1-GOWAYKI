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

        // 1.1 — Tabla de auditoría para cambios en rutas
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS auditoria_rutas (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                ruta_id BIGINT UNSIGNED NOT NULL,
                accion ENUM("update", "delete") NOT NULL,
                datos_anteriores JSON DEFAULT NULL,
                usuario_id BIGINT UNSIGNED DEFAULT NULL,
                fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_auditoria_ruta_id (ruta_id),
                INDEX idx_auditoria_fecha (fecha_evento)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        // 1.2 — Columna total_visitas en destinos
        DB::unprepared('
            ALTER TABLE destinos
            ADD COLUMN total_visitas INT NOT NULL DEFAULT 0
            AFTER calificacion
        ');

        // ---- Trigger 1: trg_evitar_eliminar_ruta_con_paraderos ----
        // Justificación de negocio: protege la integridad referencial
        // a nivel de base de datos. Si un usuario (o un script externo como
        // phpMyAdmin) intenta eliminar una ruta que tiene paraderos asociados,
        // este trigger lo impide con un error explícito, evitando huérfanos
        // incluso si la operación no pasa por el ORM de Laravel.
        DB::unprepared('
            CREATE TRIGGER trg_evitar_eliminar_ruta_con_paraderos
            BEFORE DELETE ON rutas
            FOR EACH ROW
            BEGIN
                IF (SELECT COUNT(*) FROM paraderos WHERE ruta_id = OLD.id) > 0 THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "No se puede eliminar la ruta porque tiene paraderos asociados. Elimine los paraderos primero.";
                END IF;
            END
        ');

        // ---- Trigger 2: trg_actualizar_destino_mas_visitado ----
        // Justificación de negocio: mantiene actualizado el contador
        // total_visitas en la tabla destinos sin necesidad de recalcular
        // agregaciones cada vez que se consulta "destino más visitado".
        // Patrón de contador desnormalizado que reduce consultas COUNT()
        // en tiempo real, ideal para dashboards y rankings.
        DB::unprepared('
            CREATE TRIGGER trg_incrementar_total_visitas_destino
            AFTER INSERT ON lugares_visitados
            FOR EACH ROW
            BEGIN
                UPDATE destinos
                SET total_visitas = COALESCE(total_visitas, 0) + 1
                WHERE id = NEW.destino_id;
            END
        ');

        // ---- Trigger 3a: trg_auditoria_rutas_update ----
        // Justificación de negocio: registra el historial de cambios
        // sobre la tabla crítica rutas. Cada modificación captura el
        // estado anterior completo de la fila (datos_anteriores en JSON)
        // y el usuario que realizó el cambio, permitiendo auditoría
        // forense y trazabilidad de modificaciones.
        DB::unprepared('
            CREATE TRIGGER trg_auditoria_rutas_update
            AFTER UPDATE ON rutas
            FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_rutas (ruta_id, accion, datos_anteriores, usuario_id)
                VALUES (
                    OLD.id,
                    "update",
                    JSON_OBJECT(
                        "id", OLD.id,
                        "nombre", OLD.nombre,
                        "descripcion", OLD.descripcion,
                        "origen", OLD.origen,
                        "destino", OLD.destino,
                        "tiempo_estimado_minutos", OLD.tiempo_estimado_minutos,
                        "costo_aproximado_soles", OLD.costo_aproximado_soles,
                        "color_linea", OLD.color_linea,
                        "activa", OLD.activa
                    ),
                    @current_user_id
                );
            END
        ');

        // ---- Trigger 3b: trg_auditoria_rutas_delete ----
        // Justificación de negocio: mismo propósito que el trigger de
        // UPDATE, pero para eliminaciones. Garantiza que ninguna ruta
        // desaparezca del sistema sin dejar registro de su estado final
        // antes del borrado, esencial para cumplimiento de políticas
        // de retención de datos.
        DB::unprepared('
            CREATE TRIGGER trg_auditoria_rutas_delete
            AFTER DELETE ON rutas
            FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_rutas (ruta_id, accion, datos_anteriores, usuario_id)
                VALUES (
                    OLD.id,
                    "delete",
                    JSON_OBJECT(
                        "id", OLD.id,
                        "nombre", OLD.nombre,
                        "descripcion", OLD.descripcion,
                        "origen", OLD.origen,
                        "destino", OLD.destino,
                        "tiempo_estimado_minutos", OLD.tiempo_estimado_minutos,
                        "costo_aproximado_soles", OLD.costo_aproximado_soles,
                        "color_linea", OLD.color_linea,
                        "activa", OLD.activa
                    ),
                    @current_user_id
                );
            END
        ');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_evitar_eliminar_ruta_con_paraderos');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_incrementar_total_visitas_destino');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_auditoria_rutas_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_auditoria_rutas_delete');

        DB::unprepared('ALTER TABLE destinos DROP COLUMN IF EXISTS total_visitas');
        DB::unprepared('DROP TABLE IF EXISTS auditoria_rutas');
    }
};
