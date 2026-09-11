<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id('id_dossier');
            $table->unsignedBigInteger('id_citoyen');
            $table->string('motif');
            $table->text('description')->nullable();
            $table->float('budget')->nullable();
            $table->string('statut_dossier')->default('ouvert');
            $table->unsignedTinyInteger('niveau_urgence')->default(2);

            $table->foreign('id_citoyen')
                ->references('id_citoyen')->on('citoyens')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
