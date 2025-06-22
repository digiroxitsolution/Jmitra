<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('designations')) {
            // Create the table if it doesn't exist
            Schema::create('designations', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                // $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            // Update the table if it exists
            Schema::table('designations', function (Blueprint $table) {
                if (!Schema::hasColumn('designations', 'name')) {
                    $table->string('name')->unique(); // Add 'name' column
                }
                // if (!Schema::hasColumn('designations', 'description')) {
                //     $table->text('description')->nullable(); // Add 'description' column
                // }
                if (!Schema::hasColumns('designations', ['created_at', 'updated_at'])) {
                    $table->timestamps(); // Add timestamps if missing
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designations');
    }
};
