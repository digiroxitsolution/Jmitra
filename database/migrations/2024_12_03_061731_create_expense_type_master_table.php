<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseTypeMasterTable extends Migration
{
    public function up()
    {
        Schema::create('expense_type_master', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expense_type_master');
    }
}
