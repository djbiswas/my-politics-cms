<?php

use App\Models\Issue;
use App\Models\User;
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
        Schema::create('issue_media', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Issue::class)->nullable()->constant('issues')->onDelete('CASCADE');
            $table->foreignIdFor(User::class)->nullable()->constant('users')->onDelete('CASCADE');
            $table->text('media_path')->nullable();
            $table->unsignedTinyInteger('type')->nullable()->comment('0 => Image, 1 => Video, 2 => Gif');
            $table->unsignedTinyInteger('status')->default(0)->comment('0 => Inactive, 1 => Active');
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
        Schema::dropIfExists('issue_media');
    }
};
