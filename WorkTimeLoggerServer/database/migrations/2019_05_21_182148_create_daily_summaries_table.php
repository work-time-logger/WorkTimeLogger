<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('employee_uuid');
//            $table->foreign('employee_uuid')->references('uuid')->on('employees');
            $table->date('day');
            $table->integer('worked_minutes');
            $table->timestamps();
            
            $table->unique(['employee_uuid', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_summaries');
    }
}
