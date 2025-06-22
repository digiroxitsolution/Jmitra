<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWayOfLocationMasterTable extends Migration
{
    public function up()
    {
        Schema::create('way_of_location_master', function (Blueprint $table) {
            $table->id();
            $table->string('way_of_location');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('way_of_location_master');
    }
}
