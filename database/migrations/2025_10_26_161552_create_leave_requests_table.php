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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['sick', 'annual', 'unpaid', 'other'])->default('annual');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_count');
            $table->text('reason');
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'approved_manager', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by_manager')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('manager_approved_at')->nullable();
            $table->text('manager_notes')->nullable();
            $table->foreignId('approved_by_admin')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('admin_approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
