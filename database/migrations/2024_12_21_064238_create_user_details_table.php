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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('company_master_id');
            $table->unsignedBigInteger('location_master_id');
            $table->unsignedBigInteger('hod_id');
            $table->unsignedBigInteger('policy_setting_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('divison_master_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->foreign('company_master_id')->references('id')->on('company_master')->onDelete('cascade');
            $table->foreign('location_master_id')->references('id')->on('location_master')->onDelete('cascade');
            $table->foreign('hod_id')->references('id')->on('hods')->onDelete('cascade');
            $table->foreign('policy_setting_id')->references('id')->on('policy_settings')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');

            $table->foreign('divison_master_id')->references('id')->on('divison_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
