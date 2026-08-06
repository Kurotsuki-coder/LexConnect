<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('contenu')->nullable()->change();
            $table->string('chemin_fichier')->nullable();
            $table->string('nom_fichier')->nullable();
            $table->string('type_fichier')->nullable();
            $table->unsignedBigInteger('taille_fichier')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['chemin_fichier', 'nom_fichier', 'type_fichier', 'taille_fichier']);
        });
    }
};