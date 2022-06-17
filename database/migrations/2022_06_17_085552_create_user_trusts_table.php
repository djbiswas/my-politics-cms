<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTrustsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trusts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('responded_id')->unsigned();
            $table->enum('trust',['Up', 'Down'])->nullable();
            $table->dateTime('responded_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => InActive, 1 => Active')->nullable();
            $table->timestamps();
            $table->softDeletes();        
           
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_trusts');
    }
}
