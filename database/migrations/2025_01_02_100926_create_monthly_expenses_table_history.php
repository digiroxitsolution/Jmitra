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
        Schema::create('monthly_expenses_history', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->foreignId('monthly_expense_id')->nullable()->constrained('monthly_expenses');
                $table->unique('monthly_expense_id');
                $table->string('expense_id')->nullable(false); // Unique expense ID
                
                $table->foreignId('expense_type_master_id')->nullable()->constrained('expense_type_master')->onDelete('cascade'); // Expense Type
                $table->foreignId('mode_of_expense_master_id')->nullable()->constrained('mode_of_expense_master')->onDelete('cascade'); // Expense Mode
                $table->timestamp('expense_date')->nullable(); // Expense Date
                
                $table->enum('one_way_two_way_multi_location', ['One Way', 'Two Way', 'Multi Location'])->nullable(); // One Way/Two Way/Multi Location
                $table->string('from')->nullable(); // From
                $table->string('to')->nullable(); // To
                $table->timestamp('departure_time')->nullable(); // Dep. Time
                $table->timestamp('arrival_time')->nullable(); // Arr. Time
                $table->float('km_as_per_user')->nullable(); // KM as per User
                $table->float('km_as_per_google_map')->nullable(); // KM as per Google Map
                $table->decimal('fare_amount', 10, 2)->nullable(); // Fare Amount
                $table->string('da_location')->nullable(); // DA (Location)
                $table->string('da_ex_location')->nullable(); // DA (Ex-Location)
                $table->decimal('da_outstation', 10, 2)->nullable(); // DA (Outstation)
                $table->decimal('da_total', 10, 2)->nullable(); // DA (Total)
                $table->decimal('postage', 10, 2)->nullable(); // Postage
                $table->decimal('mobile_internet', 10, 2)->nullable(); // Mobile/Internet
                $table->decimal('print_stationery', 10, 2)->nullable(); // Print Stationery

                $table->foreignId('other_expense_master_id')->nullable()->constrained('other_expense_master')->onDelete('cascade'); // Expense Type
                
                $table->decimal('other_expenses_amount', 10, 2)->nullable(); // Other Expenses Amount
                $table->enum('pre_approved', ['Yes', 'No', 'N.A.'])->nullable(); // Pre-Approved
                $table->date('approved_date')->nullable(); // Approved Date
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Approved By
                $table->foreignId('hod_id')->nullable()->constrained('hods')->onDelete('set null');
                $table->string('upload_of_approvals_documents')->nullable(); // Upload of Approvals Documents
                $table->tinyInteger('status')->default(0); // Status (1 = Pending, 2 = Rejected, 3 = Approved)
                $table->tinyInteger('is_submitted')->default(0);
                
                $table->foreignId('user_expense_other_records_id')->nullable()->constrained('user_expense_other_records')->onDelete('set null');
                 // New fields for Advance Taken and Remarks
                $table->tinyInteger('accept_policy')->default(0);
                $table->text('remarks')->nullable();               
                $table->timestamps(); // Created at and updated at
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_expenses_history');
    }
};
