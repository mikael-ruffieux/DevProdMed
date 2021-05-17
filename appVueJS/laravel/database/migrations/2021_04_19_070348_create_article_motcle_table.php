<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleMotcleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('article_motcle', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('article_id')->unsigned();
            $table->integer('motcle_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
            $table->foreign('motcle_id')->references('id')->on('motcles')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('article_motcle', function (Blueprint $table) {
                $table->dropForeign(['article_id']);
                $table->dropForeign(['motcle_id']);
            });
        }
        Schema::dropIfExists('article_motcle');
    }
}
