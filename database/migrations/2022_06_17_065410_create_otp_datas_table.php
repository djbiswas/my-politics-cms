<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('otp')->nullable();
            $table->dateTime('expiry_date')->nullable();
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
        Schema::dropIfExists('otp_datas');
    }
}
