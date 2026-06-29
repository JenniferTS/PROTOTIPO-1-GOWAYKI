<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paraderos', function (Blueprint $table) {
            $table->string('imagen_url')->nullable()->after('orden');
        });
    }

    public function down(): void
    {
        Schema::table('paraderos', function (Blueprint $table) {
            $table->dropColumn('imagen_url');
        });
    }
};
