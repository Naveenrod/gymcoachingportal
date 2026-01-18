<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->enum('membership_type', ['Basic', 'Premium', 'VIP'])->default('Basic');
            $table->date('membership_start_date')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'On Hold'])->default('Active');
            // Check-in fields
            $table->string('loom_link', 500)->nullable();
            $table->string('package', 100)->nullable();
            $table->string('check_in_frequency', 50)->nullable();
            $table->string('check_in_day', 50)->nullable();
            $table->enum('submitted', ['Submitted', ''])->default('');
            $table->string('rank', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
