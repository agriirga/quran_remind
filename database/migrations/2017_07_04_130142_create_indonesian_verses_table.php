<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndonesianVersesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indonesian_verses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('surah_id')->unsigned();
            $table->integer('ayah_no')->unsigned();
            $table->longtext('verse');
            $table->timestamps();

            $table->foreign('surah_id')->references('id')->on('quran_surats')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indonesian_verses');
    }
}
