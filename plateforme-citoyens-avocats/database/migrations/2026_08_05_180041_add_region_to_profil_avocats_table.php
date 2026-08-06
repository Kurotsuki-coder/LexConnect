<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profil_avocats', function (Blueprint $table) {

            $table->string('region')
                  ->nullable()
                  ->after('numero_barre');

        });
    }


    public function down()
    {
        Schema::table('profil_avocats', function (Blueprint $table) {

            $table->dropColumn('region');

        });
    }
};

