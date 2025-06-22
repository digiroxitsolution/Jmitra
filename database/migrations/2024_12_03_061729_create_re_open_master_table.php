<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReOpenMasterTable extends Migration
{
    public function up()
    {
        Schema::create('re_open_master', function (Blueprint $table) {
            $table->id();
            $table->string('reason_of_re_open');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('re_open_master');
    }
}
