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
        // Check if the table exists
        if (!Schema::hasTable('policy_guidelines')) {
            // Create the table if it doesn't exist
            Schema::create('policy_guidelines', function (Blueprint $table) {
                $table->id(); // Auto-increment primary key
                $table->unsignedBigInteger('policy_setting_id'); // Foreign key to policy_settings table
                $table->string('file_name')->nullable(); // Name of the file, nullable
                $table->text('policy_description')->nullable(); // Description of the policy
                $table->string('uploaded_file')->nullable(); // Path or identifier for the uploaded file
                $table->timestamps(); // created_at and updated_at fields

                // Foreign key constraint with cascading delete
                $table->foreign('policy_setting_id')->references('id')->on('policy_settings')->onDelete('cascade');
            });
        } else {
            // Update the table if it exists
            Schema::table('policy_guidelines', function (Blueprint $table) {
                if (!Schema::hasColumn('policy_guidelines', 'policy_setting_id')) {
                    $table->unsignedBigInteger('policy_setting_id'); // Add the policy_setting_id column if it doesn't exist
                    $table->foreign('policy_setting_id')->references('id')->on('policy_settings')->onDelete('cascade'); // Foreign key constraint with cascading delete
                }
                if (!Schema::hasColumn('policy_guidelines', 'file_name')) {
                    $table->string('file_name')->nullable(); // Add the file_name column if it doesn't exist
                }
                if (!Schema::hasColumn('policy_guidelines', 'policy_description')) {
                    $table->text('policy_description')->nullable(); // Add policy_description column if it doesn't exist
                }
                if (!Schema::hasColumn('policy_guidelines', 'uploaded_file')) {
                    $table->string('uploaded_file')->nullable(); // Add uploaded_file column if it doesn't exist
                }
                if (!Schema::hasColumns('policy_guidelines', ['created_at', 'updated_at'])) {
                    $table->timestamps(); // Add timestamps if they don't exist
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_guidelines');
    }
};
