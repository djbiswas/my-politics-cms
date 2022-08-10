<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('politician_id')->unsigned();
            $table->longText('content')->nullable();
            $table->longText('images')->nullable();
            $table->tinyInteger('status')->default('InActive')->comment('0 => InActive, 1 => Active')->nullable();
            $table->integer('updated_by')->unsigned();
            $table->softDeletes();
            $table->foreign('politician_id')->references('id')->on('politicians');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('issues');
    }
};
