<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateModelHasRolesTable extends Migration
{
    public function up()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Set the default value as a string with backslashes
            $table->string('model_type')->default('App\Models\User')->change();
        });
    }

    public function down()
    {
        // Revert the change in case of rollback (optional)
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_type')->default('')->change();
        });
    }
}
