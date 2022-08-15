<?php

use App\Models\IssueCategory;
use App\Models\Politician;
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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('notify_with_replies')->default('0');
            $table->json('tags')->nullable();
            $table->foreignIdFor(User::class)->constant('users')->onDelete('SET NULL');
            $table->foreignIdFor(Politician::class)->nullable()->constant('politicians')->onDelete('SET NULL');
            $table->foreignIdFor(IssueCategory::class)->constant('issue_categories')->onDelete('SET NULL');
            $table->unsignedTinyInteger('status')->default('0')->comment('0 => InActive, 1 => Active');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
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
