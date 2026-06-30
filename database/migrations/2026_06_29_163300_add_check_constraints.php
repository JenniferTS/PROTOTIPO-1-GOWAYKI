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

        // Verificación de versión: MariaDB 10.2.1+ soporta CHECK constraints.
        // Este proyecto corre sobre MariaDB 10.4.32, completamente compatible.

        // chk_costo_positivo: previene costos negativos en rutas
        DB::unprepared('
            ALTER TABLE rutas
            ADD CONSTRAINT chk_costo_positivo
            CHECK (costo_aproximado_soles >= 0)
        ');

        // chk_tiempo_positivo: previene tiempos de viaje no positivos
        // Un tiempo de 0 o negativo no tiene sentido en una ruta real
        DB::unprepared('
            ALTER TABLE rutas
            ADD CONSTRAINT chk_tiempo_positivo
            CHECK (tiempo_estimado_minutos > 0)
        ');

        // chk_calificacion_rango: la calificación de destinos debe estar entre 0.0 y 5.0
        DB::unprepared('
            ALTER TABLE destinos
            ADD CONSTRAINT chk_calificacion_rango
            CHECK (calificacion >= 0 AND calificacion <= 5)
        ');

        // chk_orden_positivo: el orden de los paraderos debe ser 0 o positivo
        DB::unprepared('
            ALTER TABLE paraderos
            ADD CONSTRAINT chk_orden_positivo
            CHECK (orden >= 0)
        ');

        // chk_email_formato_basico: validación simple de formato email en users
        DB::unprepared('
            ALTER TABLE users
            ADD CONSTRAINT chk_email_formato
            CHECK (email LIKE "%@%.%")
        ');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('ALTER TABLE rutas DROP CHECK chk_costo_positivo');
        DB::unprepared('ALTER TABLE rutas DROP CHECK chk_tiempo_positivo');
        DB::unprepared('ALTER TABLE destinos DROP CHECK chk_calificacion_rango');
        DB::unprepared('ALTER TABLE paraderos DROP CHECK chk_orden_positivo');
        DB::unprepared('ALTER TABLE users DROP CHECK chk_email_formato');
    }
};
