<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePoliticianMetasColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('politican_metas', function(Blueprint $table) {
            $table->string('type')->nullable()->after('meta_value');
            $table->renameColumn('meta_key', 'key');
            $table->renameColumn('meta_value', 'value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('politican_metas', function(Blueprint $table) {
            $table->renameColumn('key', 'meta_key');
            $table->renameColumn('value', 'meta_value');
        });
    }
}
