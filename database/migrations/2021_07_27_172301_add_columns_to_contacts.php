<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->decimal('employer_cost', 10, 2)->nullable();
            $table->json('competencies')->nullable();
            $table->json('working_skills')->nullable();
            $table->text('work_experience')->nullable();
            $table->string('contract_type', 255)->nullable();
            $table->date('contract_start')->nullable();
            $table->date('contract_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
}
