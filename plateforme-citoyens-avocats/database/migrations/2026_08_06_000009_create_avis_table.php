<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id('id_avis');
            $table->unsignedBigInteger('id_citoyen');
            $table->unsignedBigInteger('id_avocat');
            $table->unsignedBigInteger('id_dossier')->nullable();
            $table->unsignedTinyInteger('note');
            $table->text('commentaire')->nullable();
            $table->timestamp('date')->nullable();

            $table->foreign('id_citoyen')
                ->references('id_citoyen')->on('citoyens')
                ->cascadeOnDelete();

            $table->foreign('id_avocat')
                ->references('id_avocat')->on('avocats')
                ->cascadeOnDelete();

            $table->foreign('id_dossier')
                ->references('id_dossier')->on('dossiers')
                ->nullOnDelete();

            $table->unique('id_dossier', 'avis_dossier_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
