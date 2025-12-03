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
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module', 100);
            $table->string('action', 50)->comment('view, create, edit, delete, approve');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['module', 'action']);
        });

        // Role-Permission pivot table
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        // Audit logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('module', 100);
            $table->string('action', 50);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
            $table->index(['user_id', 'module']);
        });

        // Update users table with additional fields
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->after('email');
            $table->foreignId('role_id')->after('employee_id');
            $table->boolean('is_active')->default(true)->after('role_id');
            $table->timestamp('last_login')->nullable()->after('is_active');
            $table->boolean('two_fa_enabled')->default(false)->after('last_login');
            $table->string('two_fa_secret')->nullable()->after('two_fa_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'role_id', 'is_active', 'last_login', 'two_fa_enabled', 'two_fa_secret']);
        });
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
