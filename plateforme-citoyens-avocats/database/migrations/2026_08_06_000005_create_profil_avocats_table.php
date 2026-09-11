<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_avocats', function (Blueprint $table) {
            $table->id('id_profil');
            $table->unsignedBigInteger('id_avocat');
            $table->string('specialites')->nullable();
            $table->text('bio')->nullable();
            $table->string('disponibilite')->default('disponible');
            $table->string('horaire')->nullable();
            $table->string('numero_barre')->nullable();
            $table->string('region')->nullable();
            $table->timestamps();

            $table->foreign('id_avocat')
                ->references('id_avocat')->on('avocats')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_avocats');
    }
};
