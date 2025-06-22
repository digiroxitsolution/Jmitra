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
        if (!Schema::hasTable('sales')) {
            // Create the table if it doesn't exist
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->integer('sales_master_id'); // You can adjust the type if needed
                $table->integer('state_id');
                $table->decimal('sales_amount', 10, 2);
                $table->date('date_of_sales');
                $table->timestamps();
            });
        } else {
            // Update the table if it exists
            Schema::table('sales', function (Blueprint $table) {
                if (!Schema::hasColumn('sales', 'sales_master_id')) {
                    $table->integer('sales_master_id'); // Add sales_master_id column
                }
                if (!Schema::hasColumn('sales', 'state_id')) {
                    $table->integer('state_id'); // Add state_id column
                }
                if (!Schema::hasColumn('sales', 'sales_amount')) {
                    $table->decimal('sales_amount', 10, 2); // Add sales_amount column
                }
                if (!Schema::hasColumn('sales', 'date_of_sales')) {
                    $table->date('date_of_sales'); // Add date_of_sales column
                }
                if (!Schema::hasColumns('sales', ['created_at', 'updated_at'])) {
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
        Schema::dropIfExists('sales');
    }
};
