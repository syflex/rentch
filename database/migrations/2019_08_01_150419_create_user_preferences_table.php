<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('local_govt_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();

            $table->enum('accomation_preference', ['Entire House', 'Shared Apartment', 'Both'])->default('Both');
            $table->enum('gender', ['Male', 'Female', 'others'])->default('others');
            $table->enum('smoking', ['Yes', 'No', 'others'])->default('others');
            $table->enum('pets', ['Yes', 'No', 'others'])->default('others');
            $table->enum('guest', ['Yes', 'No', 'others'])->default('others');
            $table->enum('late_nights', [1, 2, 3, 4, 5])->default(1);
            $table->enum('social_life', [1, 2, 3, 4, 5])->default(1);
            $table->enum('neatness', [1, 2, 3, 4, 5])->default(1);
            $table->enum('cooking_habits', [1, 2, 3, 4, 5])->default(1);
            $table->enum('sharing_habits', [1, 2, 3, 4, 5])->default(1);
            $table->enum('party', [1, 2, 3, 4, 5])->default(1);
            $table->enum('sports', [1, 2, 3, 4, 5])->default(1);
            $table->enum('sickness_level', [1, 2, 3, 4, 5])->default(1);
            $table->text('hobbies')->nullable();
            $table->text('strugles')->nullable();
            $table->text('more_details')->nullable();
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('local_govt_id')->references('id')->on('local_govts');
            $table->foreign('city_id')->references('id')->on('cities');
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
        Schema::dropIfExists('user_preferences');
    }
}
