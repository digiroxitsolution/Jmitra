<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectionMasterTable extends Migration
{
    public function up()
    {
        Schema::create('rejection_master', function (Blueprint $table) {
            $table->id();
            $table->string('reason_of_rejection');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rejection_master');
    }
}
