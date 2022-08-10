<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliticiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politicians', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('name_alias')->nullable();
            $table->string('slug')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('affiliation_icon')->nullable();
            $table->string('position')->nullable();
            $table->longText('politician_description')->nullable();
            $table->string('image')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => InActive, 1 => Active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('politicians');
    }
}
