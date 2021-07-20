<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoyagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->enum('type',['video','image','audio','location','document','mix']);
            $table->string('image_ids')->nullable();
            $table->string('video_ids')->nullable();
            $table->string('contact_ids')->nullable();
            $table->string('audio_ids')->nullable();
            $table->string('document_id')->nullable();
            $table->string('location_id')->nullable();
            $table->boolean('is_read')->default(0)->nullable();
            $table->boolean('is_delete')->default(0)->nullable();
            $table->boolean('auto_delete')->default(0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voyages');
    }
}
