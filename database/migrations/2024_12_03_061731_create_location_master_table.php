<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationMasterTable extends Migration
{
    public function up()
    {
        Schema::create('location_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('state_id');
            $table->string('working_location');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_master');
    }
}
