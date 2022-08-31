<?php

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
        Schema::create('politician_voting_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Politician::class)->constant('politicians')->onDelete('SET NULL');
            $table->foreignIdFor(User::class)->nullable()->constant('users')->onDelete('CASCADE');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('politician_voting_alerts');
    }
};
