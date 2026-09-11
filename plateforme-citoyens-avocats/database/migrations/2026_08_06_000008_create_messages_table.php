<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('id_message');
            $table->unsignedBigInteger('id_dossier')->nullable();
            $table->unsignedBigInteger('id_expediteur');
            $table->unsignedBigInteger('id_receveur');
            $table->text('contenu')->nullable();
            $table->string('statut_message')->default('non_lu');
            $table->timestamp('heure')->nullable();
            $table->string('chemin_fichier')->nullable();
            $table->string('nom_fichier')->nullable();
            $table->string('type_fichier')->nullable();
            $table->unsignedBigInteger('taille_fichier')->nullable();

            $table->foreign('id_dossier')
                ->references('id_dossier')->on('dossiers')
                ->nullOnDelete();

            $table->foreign('id_expediteur')
                ->references('id_utilisateur')->on('utilisateurs')
                ->cascadeOnDelete();

            $table->foreign('id_receveur')
                ->references('id_utilisateur')->on('utilisateurs')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
