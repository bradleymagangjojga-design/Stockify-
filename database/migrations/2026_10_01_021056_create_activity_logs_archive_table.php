<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 100);
            $table->string('model', 200)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            // Same indexes as main table
            $table->index('user_id', 'idx_archive_user_id');
            $table->index('action', 'idx_archive_action');
            $table->index('created_at', 'idx_archive_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs_archive');
    }
};
