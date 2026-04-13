<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('mcp_clients')) {
            Schema::create('mcp_clients', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->json('allowed_scopes')->nullable();
                $table->json('allowed_tools')->nullable();
                $table->json('allowed_modules')->nullable();
                $table->unsignedInteger('rate_limit_per_minute')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('revoked_at')->nullable();
                $table->unsignedInteger('created_by_user_id')->nullable();
                $table->unsignedInteger('updated_by_user_id')->nullable();
                $table->timestamps();

                $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('updated_by_user_id')->references('id')->on('users')->nullOnDelete();
                $table->index(['is_active', 'revoked_at']);
                $table->index('last_used_at');
            });
        }

        if (! Schema::hasTable('mcp_client_tokens')) {
            Schema::create('mcp_client_tokens', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('mcp_client_id')->constrained('mcp_clients')->cascadeOnDelete();
                $table->foreignId('rotated_from_token_id')->nullable()->constrained('mcp_client_tokens')->nullOnDelete();
                $table->string('name');
                $table->string('token_hash');
                $table->string('token_last_eight', 8);
                $table->json('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->string('last_used_ip', 45)->nullable();
                $table->text('last_used_user_agent')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('revoked_at')->nullable();
                $table->text('revocation_reason')->nullable();
                $table->unsignedInteger('created_by_user_id')->nullable();
                $table->unsignedInteger('revoked_by_user_id')->nullable();
                $table->timestamps();

                $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('revoked_by_user_id')->references('id')->on('users')->nullOnDelete();
                $table->index(['mcp_client_id', 'revoked_at']);
                $table->index('expires_at');
                $table->index('last_used_at');
            });
        }

        if (! Schema::hasTable('mcp_client_token_events')) {
            Schema::create('mcp_client_token_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mcp_client_id')->constrained('mcp_clients')->cascadeOnDelete();
                $table->foreignId('mcp_client_token_id')->nullable()->constrained('mcp_client_tokens')->cascadeOnDelete();
                $table->unsignedInteger('actor_user_id')->nullable();
                $table->string('action');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();
                $table->index(['mcp_client_id', 'action', 'created_at']);
                $table->index(['mcp_client_token_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mcp_client_token_events');
        Schema::dropIfExists('mcp_client_tokens');
        Schema::dropIfExists('mcp_clients');
    }
};
