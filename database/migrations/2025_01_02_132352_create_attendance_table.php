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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->string('customer_name')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('zone')->nullable();
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('check_in_address')->nullable();
            $table->string('check_in_remarks')->nullable();
            $table->string('check_out_remarks')->nullable();            
            $table->string('purpose')->nullable();
            $table->string('description')->nullable();
            $table->string('joint_purpose_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
