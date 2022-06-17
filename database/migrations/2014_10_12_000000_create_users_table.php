<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('rank_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('login')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('registered_date')->nullable();
            $table->tinyInteger('lock_rank')->default(0)->nullable();
            $table->tinyInteger('display_status')->default(0)->nullable();
            $table->string('reg_status')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();        
           
            $table->foreign('rank_id')->references('id')->on('ranks');
            $table->foreign('role_id')->references('id')->on('roles');
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
