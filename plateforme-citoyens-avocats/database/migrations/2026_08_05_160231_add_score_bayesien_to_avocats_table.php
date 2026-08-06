<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avocats', function (Blueprint $table) {
            $table->decimal('score_bayesien', 4, 2)->default(0)->after('id_utilisateur');
            $table->unsignedInteger('nb_avis')->default(0)->after('score_bayesien');
            $table->decimal('note_moyenne_ponderee', 4, 2)->default(0)->after('nb_avis');
            $table->timestamp('score_calcule_at')->nullable()->after('note_moyenne_ponderee');

            $table->index('score_bayesien');
        });
    }

    public function down(): void
    {
        Schema::table('avocats', function (Blueprint $table) {
            $table->dropIndex(['score_bayesien']);
            $table->dropColumn(['score_bayesien', 'nb_avis', 'note_moyenne_ponderee', 'score_calcule_at']);
        });
    }
};