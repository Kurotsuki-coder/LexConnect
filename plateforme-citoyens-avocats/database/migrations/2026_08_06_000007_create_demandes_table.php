<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id('id_demande');
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('id_avocat');
            $table->text('message')->nullable();
            $table->string('statut_demande')->default('envoyee');
            $table->timestamp('heure')->nullable();
            $table->string('origine')->default('citoyen');

            $table->foreign('id_dossier')
                ->references('id_dossier')->on('dossiers')
                ->cascadeOnDelete();

            $table->foreign('id_avocat')
                ->references('id_avocat')->on('avocats')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
