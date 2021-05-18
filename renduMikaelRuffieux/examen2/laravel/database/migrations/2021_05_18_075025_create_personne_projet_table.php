<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePersonneProjetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personne_projet', function (Blueprint $table) {
            $table->id();
            $table->integer('personne_id')->unsigned();
            $table->integer('projet_id')->unsigned();
            $table->foreign('personne_id')->references('id')->on('personnes')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
            $table->foreign('projet_id')->references('id')->on('projets')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('personne_projet', function (Blueprint $table) {
                $table->dropForeign(['personne_id']);
                $table->dropForeign(['projet_id']);
            });
        }
        Schema::dropIfExists('personne_projet');
    }
}
