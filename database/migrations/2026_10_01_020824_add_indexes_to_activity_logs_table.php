<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Single-column indexes
            $table->index('user_id', 'idx_activity_user_id');
            $table->index('action', 'idx_activity_action');
            $table->index('created_at', 'idx_activity_created_at');

            // Composite indexes for common queries
            $table->index(['action', 'created_at'], 'idx_activity_action_date');
            $table->index(['user_id', 'created_at'], 'idx_activity_user_date');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_activity_user_id');
            $table->dropIndex('idx_activity_action');
            $table->dropIndex('idx_activity_created_at');
            $table->dropIndex('idx_activity_action_date');
            $table->dropIndex('idx_activity_user_date');
        });
    }
};
