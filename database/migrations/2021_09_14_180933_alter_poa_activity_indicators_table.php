<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPoaActivityIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poa_activity_indicators', function (Blueprint $table) {
            $table->float('goal')->default(null)->nullable()->change();
            $table->float('progress')->default(null)->nullable()->change();
            $table->float('men_progress')->default(null)->nullable()->change();
            $table->float('women_progress')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poa_activity_indicators', function (Blueprint $table) {
            //
        });
    }
}
