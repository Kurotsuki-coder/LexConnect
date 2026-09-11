<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avocats', function (Blueprint $table) {
            $table->id('id_avocat');
            $table->unsignedBigInteger('id_utilisateur');
            $table->decimal('score_bayesien', 4, 2)->default(0);
            $table->unsignedInteger('nb_avis')->default(0);
            $table->decimal('note_moyenne_ponderee', 4, 2)->default(0);
            $table->timestamp('score_calcule_at')->nullable();

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')->on('utilisateurs')
                ->cascadeOnDelete();

            $table->index('score_bayesien');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avocats');
    }
};
