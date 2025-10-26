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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['employee', 'manager', 'admin'])->default('employee')->after('email');
            $table->foreignId('department_id')->nullable()->after('role')->constrained()->onDelete('set null');
            $table->string('phone')->nullable()->after('department_id');
            $table->string('employee_id')->unique()->nullable()->after('phone');
            $table->string('photo')->nullable()->after('employee_id');
            $table->boolean('is_active')->default(true)->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id', 'phone', 'employee_id', 'photo', 'is_active']);
        });
    }
};
