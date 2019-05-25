<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('employee_uuid');
//            $table->foreign('employee_uuid')->references('uuid')->on('employees');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('worked_minutes')->default(0);
            $table->string('started_by')->nullable();
            $table->string('ended_by')->nullable();
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
        Schema::dropIfExists('entries');
    }
}
