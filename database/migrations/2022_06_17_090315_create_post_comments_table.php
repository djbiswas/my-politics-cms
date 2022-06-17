<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_comment_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->longText('comment')->nullable();
            $table->text('gif')->nullable();
            $table->text('image')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => InActive, 1 => Active')->nullable();
            $table->timestamps();
            $table->softDeletes();        
            
            $table->foreign('post_id')->references('id')->on('posts');
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
        Schema::dropIfExists('post_comments');
    }
}
