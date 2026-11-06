<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileuserTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobileuser_teams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('senior_id')->unsigned();
            $table->foreign('senior_id')->references('id')->on('mobileusers')->onDelete('cascade');
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('mobileusers')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate assignments
            $table->unique(['senior_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobileuser_teams');
    }
}
