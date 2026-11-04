<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('live_edit_style_snapshots')) {
            return;
        }
        Schema::create('live_edit_style_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('template', 191)->index();
            $table->string('type', 8); // 'css' | 'js'
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->nullable();
            // Fast "newest per template+type" lookups + pruning.
            $table->index(['template', 'type', 'id'], 'les_tmpl_type_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_edit_style_snapshots');
    }
};
