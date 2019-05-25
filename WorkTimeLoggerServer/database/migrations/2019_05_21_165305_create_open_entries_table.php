<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_entries', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('employee_uuid');
//            $table->foreign('employee_uuid')->references('uuid')->on('employees');
            $table->timestamp('start')->nullable();
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
        Schema::dropIfExists('open_entries');
    }
}
