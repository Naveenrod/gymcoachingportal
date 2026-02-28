<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->integer('duration_minutes')->default(60);
            $table->enum('session_type', ['Personal Training', 'Group Class', 'Consultation', 'Assessment'])->default('Personal Training');
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled', 'No-Show'])->default('Scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('appointment_date');
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
