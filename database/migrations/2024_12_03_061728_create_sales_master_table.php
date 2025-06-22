<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesMasterTable extends Migration
{
    public function up()
    {
        Schema::create('sales_master', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('state_id')->constrained('states');
            $table->string('file_name');
            $table->date('date_of_upload');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_master');
    }
}
