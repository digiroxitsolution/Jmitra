<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('hods', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('name');
    //         $table->string('email')->nullable(); // Email can be null
    //         $table->string('department')->nullable();      // Department can be null
    //         $table->string('remarks')->nullable();         // Remarks can be null
    //         $table->timestamps();
    //     });
    // }
    public function up()
    {
        if (!Schema::hasTable('hods')) {
            // Create the table if it doesn't exist
            Schema::create('hods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                // $table->string('email')->nullable(); // Email can be null
                // $table->string('department')->nullable();      // Department can be null
                // $table->string('remarks')->nullable();
                $table->timestamps();
            });
        } else {
            // Update the table if it exists
            Schema::table('hods', function (Blueprint $table) {
                if (!Schema::hasColumn('hods', 'name')) {
                    $table->string('name')->unique(); // Add 'name' column
                }
                // if (!Schema::hasColumn('hods', 'email')) {
                //     $table->text('email')->nullable(); // Add 'description' column
                // }
                // if (!Schema::hasColumn('hods', 'department')) {
                //     $table->text('department')->nullable(); // Add 'description' column
                // }
                // if (!Schema::hasColumn('hods', 'remarks')) {
                //     $table->text('remarks')->nullable(); // Add 'description' column
                // }
                if (!Schema::hasColumns('hods', ['created_at', 'updated_at'])) {
                    $table->timestamps(); // Add timestamps if missing
                }

            });
        }
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hods');
    }
};
