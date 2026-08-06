<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dossier')->nullable()->after('id_avocat');
            $table->unique('id_dossier', 'avis_dossier_unique');
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->dropForeign(['id_dossier']);
            $table->dropUnique('avis_dossier_unique');
            $table->dropColumn('id_dossier');
        });
    }
};