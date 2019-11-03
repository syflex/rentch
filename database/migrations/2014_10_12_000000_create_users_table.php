<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('local_govt_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->enum('role', ['superadmin', 'admin', 'agent', 'tenant'])->default('tenant');
            $table->string('name');            
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('avatar')->nullable();
            $table->string('monthly_budget')->default('0');
            $table->text('current_address')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'others'])->default('others');
            $table->enum('gender', ['Male', 'Female', 'others'])->default('others');
            $table->enum('age_range', ['16-25', '26-35','36-45', '50+', 'others'])->default('others');
            $table->enum('title', ['Mr', 'Mrs','Miss'])->default('Mr')->nullable();
            $table->enum('occupation', ['Civil servant', 'Student', 'Professional', 'Corper', 'Self Employed', 'Entrepreneur', 'others'])->default('others');
            $table->text('description')->nullable();            
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('profile_open_status')->default(0);
            $table->boolean('email_verified')->default(0);
            $table->integer('is_profile_complete')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
