<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliticanMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politican_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('politician_id')->unsigned();
            $table->string('meta_key')->nullable();
            $table->longText('meta_value')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => InActive, 1 => Active')->nullable();
            $table->timestamps();
            $table->softDeletes();        
           
            $table->foreign('politician_id')->references('id')->on('politicians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('politican_metas');
    }
}
