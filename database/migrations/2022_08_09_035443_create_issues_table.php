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
            $table->integer('politician_id')->unsigned()->nullable();
            $table->unsignedBigInteger('issue_category_id');
            $table->string('name')->nullable();
            $table->longText('content')->nullable();
            $table->longText('images')->nullable();
            $table->tinyInteger('status')->default('0')->comment('0 => InActive, 1 => Active')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('notify_with_replies')->default('0');
            $table->integer('updated_by')->unsigned();
            $table->json('tags')->nullable();
            $table->softDeletes();

            $table->foreign('politician_id')->references('id')->on('politicians');
            $table->foreign('issue_category_id')->references('id')->on('issue_categories')->onUpdate('cascade')->onDelete('cascade');
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
