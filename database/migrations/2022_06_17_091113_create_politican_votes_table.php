<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliticanVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politican_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('politician_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->enum('vote',['Up', 'Down'])->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => InActive, 1 => Active')->nullable();
            $table->timestamps();
            $table->softDeletes();        
           
            $table->foreign('politician_id')->references('id')->on('politicians');
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
        Schema::dropIfExists('politican_votes');
    }
}
