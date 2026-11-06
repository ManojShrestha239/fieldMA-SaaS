<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaMobileuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_mobileuser', function (Blueprint $table) {
            $table->id();
            $table->string('area_code');
            $table->foreign('area_code')->references('areacode')->on('areas');
            $table->bigInteger('mobileuser_id')->unsigned();
            $table->foreign('mobileuser_id')->references('id')->on('mobileusers');
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
        Schema::dropIfExists('area_mobileuser');
    }
}
