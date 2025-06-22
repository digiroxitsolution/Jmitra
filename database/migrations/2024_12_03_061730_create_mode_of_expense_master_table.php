<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeOfExpenseMasterTable extends Migration
{
    public function up()
    {
        Schema::create('mode_of_expense_master', function (Blueprint $table) {
            $table->id();
            $table->string('mode_expense');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mode_of_expense_master');
    }
}
