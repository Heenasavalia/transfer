<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_info', function (Blueprint $table) {
            $table->id();
            $table->integer('voyage_id')->nullable();
            $table->foreign('voyage_id')->references('id')->on('voyages');
            $table->string('type');
            $table->string('file_name');
            $table->boolean('is_delete')->default(0)->nullable();
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
        Schema::dropIfExists('transfer_info');
    }
}
