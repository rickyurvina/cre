<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnYearToPoaActivityIndicators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poa_activity_indicators', function (Blueprint $table) {
            //
            $table->integer('year')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

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
