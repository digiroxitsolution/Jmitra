<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_expense_other_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('hod_id')->nullable()->constrained('hods')->onDelete('set null');
            $table->timestamp('expense_date')->nullable();
            $table->string('expense_id')->nullable(false);
            $table->text('advance_taken')->nullable();
            $table->text('remark_of_advance_taken')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_time')->nullable();
            $table->tinyInteger('is_verified')->default(0)->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approval_deadline')->nullable();
            $table->timestamp('approved_time')->nullable(); // Corrected
            $table->tinyInteger('is_approved')->default(0)->nullable();
            $table->tinyInteger('is_submitted')->default(0);
            $table->timestamp('date_of_submission')->nullable(); // Date of Submission
            $table->tinyInteger('accept_policy')->default(0);            
            $table->integer('days_elapsed')->default(0)->nullable();
            $table->integer('verification_days_elapsed')->default(0)->nullable();
            $table->integer('approval_days_elapsed')->default(0)->nullable();

            $table->text('justification')->nullable(); // Justification (text)
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sla_status')->default(0)->nullable();
            $table->tinyInteger('sla_status_of_submission')->default(0);
            $table->tinyInteger('sla_status_of_approval')->default(0);
            
            $table->foreignId('rejection_master_id')->nullable()->constrained('rejection_master');
            $table->foreignId('re_open_master_id')->nullable()->constrained('re_open_master');

            $table->text('remarks')->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_expense_other_records');
    }
};
