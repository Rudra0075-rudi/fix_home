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
        // Drop existing complex tables first (in reverse dependency order)
        if (Schema::hasTable('worker_service')) {
            Schema::dropIfExists('worker_service');
        }
        if (Schema::hasTable('badge_worker')) {
            Schema::dropIfExists('badge_worker');
        }
        if (Schema::hasTable('combo_service')) {
            Schema::dropIfExists('combo_service');
        }
        if (Schema::hasTable('service_combos')) {
            Schema::dropIfExists('service_combos');
        }
        if (Schema::hasTable('worker_portfolios')) {
            Schema::dropIfExists('worker_portfolios');
        }
        if (Schema::hasTable('payments')) {
            Schema::dropIfExists('payments');
        }
        if (Schema::hasTable('job_requests')) {
            Schema::dropIfExists('job_requests');
        }
        if (Schema::hasTable('badges')) {
            Schema::dropIfExists('badges');
        }
        if (Schema::hasTable('services')) {
            Schema::dropIfExists('services');
        }
        if (Schema::hasTable('workers')) {
            Schema::dropIfExists('workers');
        }

        // Simplify users table - keep only auth fields (id, email, password, type)
        // Remove name, phone, address, role if they exist
        if (Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
        if (Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
        
        // Add type column to users if it doesn't exist
        if (!Schema::hasColumn('users', 'type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('type', ['user', 'worker', 'admin'])->default('user')->after('id');
            });
        }

        // Create user_profiles table (id PK, user_id FK to users, name, email, phone)
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamps();
        });

        // Create worker_profiles table (id PK, user_id FK to users, name, email, phone)
        Schema::create('worker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamps();
        });

        // Simplify services table (id PK, worker_id FK to worker_profiles, name)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('worker_profiles')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('worker_profiles');
        Schema::dropIfExists('user_profiles');

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('email')->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->enum('role', ['user', 'worker', 'admin'])->default('user')->after('email');
            $table->dropColumn('type');
        });
    }
};
