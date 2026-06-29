<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_incrementar_puntaje_exploracion');

        DB::unprepared('
            CREATE TRIGGER trg_incrementar_puntaje_exploracion
            AFTER INSERT ON lugares_visitados
            FOR EACH ROW
            BEGIN
                UPDATE user_profiles
                SET puntaje_exploracion = COALESCE(puntaje_exploracion, 0) + 10,
                    updated_at = NOW()
                WHERE user_id = NEW.user_id;
            END
        ');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_incrementar_puntaje_exploracion');
    }
};
