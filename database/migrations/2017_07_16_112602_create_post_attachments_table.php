<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->string('attachment_title')->nullable();
            $table->string('path_location');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_attachments');
    }
}
