<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('id_contact');
            $table->string('nom');
            $table->string('email');
            $table->string('sujet');
            $table->text('message');
            $table->string('statut')->default('nouveau');
            $table->unsignedBigInteger('id_utilisateur')->nullable();
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};