<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Post;
use App\Models\Politician;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_flags', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Post::class)->constant('posts')->onDelete('SET NULL');
            $table->foreignIdFor(Politician::class)->constant('oliticians')->onDelete('SET NULL');
            $table->foreignIdFor(User::class)->constant('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('post_flags');
    }
};
