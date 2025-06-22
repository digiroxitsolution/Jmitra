<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicySettingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('policy_settings')) {
            // Create the table if it doesn't exist
            Schema::create('policy_settings', function (Blueprint $table) {
                $table->id();
                $table->string('policy_id')->unique(); // Unique Policy ID
                $table->string('policy_name');
                $table->unsignedBigInteger('designation_id'); // Foreign Key to designations table
                $table->string('location_da')->nullable();
                $table->string('ex_location_da')->nullable();
                $table->string('outstation_da')->nullable();
                $table->string('intercity_travel_ex_location')->nullable();
                $table->string('intercity_travel_outstation')->nullable();
                $table->decimal('charges', 10, 2)->default(0); // Decimal for financial data
                $table->date('expense_submision_date')->nullable(); // Mark as nullable if optional
                $table->date('approved_submission_date')->nullable();
                $table->date('effective_date')->nullable();
                $table->text('other')->nullable(); // Use text for larger content
                $table->timestamps();

                // Add foreign key constraint
                $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            });
        } else {
            // Update the table if it exists
            Schema::table('policy_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('policy_settings', 'policy_id')) {
                    $table->string('policy_id')->unique(); // Add unique Policy ID if it doesn't exist
                }
                if (!Schema::hasColumn('policy_settings', 'policy_name')) {
                    $table->string('policy_name'); // Add Policy Name column
                }
                if (!Schema::hasColumn('policy_settings', 'designation_id')) {
                    $table->unsignedBigInteger('designation_id'); // Add foreign key column
                }
                if (!Schema::hasColumn('policy_settings', 'location_da')) {
                    $table->string('location_da')->nullable(); // Add Location DA column
                }
                if (!Schema::hasColumn('policy_settings', 'ex_location_da')) {
                    $table->string('ex_location_da')->nullable(); // Add Ex Location DA column
                }
                if (!Schema::hasColumn('policy_settings', 'outstation_da')) {
                    $table->string('outstation_da')->nullable(); // Add Outstation DA column
                }
                if (!Schema::hasColumn('policy_settings', 'intercity_travel_ex_location')) {
                    $table->string('intercity_travel_ex_location')->nullable(); // Add Intercity Travel Ex Location column
                }
                if (!Schema::hasColumn('policy_settings', 'intercity_travel_outstation')) {
                    $table->string('intercity_travel_outstation')->nullable(); // Add Intercity Travel Outstation column
                }
                if (!Schema::hasColumn('policy_settings', 'charges')) {
                    $table->decimal('charges', 10, 2)->default(0); // Add Charges column
                }
                if (!Schema::hasColumn('policy_settings', 'expense_submision_date')) {
                    $table->date('expense_submision_date')->nullable(); // Add Expense Submission Date column
                }
                if (!Schema::hasColumn('policy_settings', 'approved_submission_date')) {
                    $table->date('approved_submission_date')->nullable(); // Add Approved Submission Date column
                }
                if (!Schema::hasColumn('policy_settings', 'other')) {
                    $table->text('other')->nullable(); // Add Other column
                }
                if (!Schema::hasColumns('policy_settings', ['created_at', 'updated_at'])) {
                    $table->timestamps(); // Add timestamps if they don't exist
                }
            });

            // Ensure foreign key constraint exists
            Schema::table('policy_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('policy_settings', 'designation_id')) {
                    $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('policy_settings');
    }
}
