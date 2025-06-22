<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherExpenseMasterTable extends Migration
{
    public function up()
    {
        Schema::create('other_expense_master', function (Blueprint $table) {
            $table->id();
            $table->string('other_expense');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('other_expense_master');
    }
}
