<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_amenities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('amenity_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('amenity_id')->references('id')->on('amenities');
            // $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_amenities');
    }
}
