<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vendor_id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('listing_category_id');
            $table->enum('listing_type', ['Shared', 'Entire Space'])->default('Entire Space');
            // $table->unsignedBigInteger('listing_type_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('local_govt_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('title');
            $table->string('address');
            $table->text('description')->nullable();
            $table->text('room_policy')->nullable();
            $table->enum('service_option', ['yes', 'no'])->default('no');
            $table->string('service_description')->nullable();
            $table->integer('baths')->nullable();
            $table->integer('rooms')->nullable();
            $table->enum('pricing_type', ['monthly', '3 months', '6 months', '12 months'])->default('monthly');
            $table->integer('amount')->default(0);
            $table->integer('featured')->default(0);
            $table->integer('step')->default(0);
            $table->integer('status')->default(0);
            // $table->foreign('user_id')->references('id')->on('users'); 
            $table->foreign('listing_category_id')->references('id')->on('listing_categories');
            // $table->foreign('listing_type_id')->references('id')->on('listing_types');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('local_govt_id')->references('id')->on('local_govts');
            $table->softDeletes();
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
        Schema::dropIfExists('listings');
    }
}
