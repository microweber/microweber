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
        if (!Schema::hasTable('newsletter_subscribers')) { return; }

        Schema::table('newsletter_subscribers', function (Blueprint $table) {

            if(!Schema::hasColumn('newsletter_subscribers', 'status')) {
                $table->string('status')->default('active');
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'subscribed_at')) {
                $table->timestamp('subscribed_at')->nullable();
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'unsubscribed_at')) {
                $table->timestamp('unsubscribed_at')->nullable();
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'list_id')) {
                $table->foreignId('list_id')->nullable();
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'confirmation_code')) {
                $table->string('confirmation_code')->nullable();
            }



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('newsletter_subscribers')) {
            return;
        }

        // Reverse exactly what up() added (guarded, since up() adds conditionally).
        // up() creates a plain `list_id` column (no foreign-key constraint) and does
        // NOT create the subscriber_email_unique index, so the old dropForeign/
        // dropUnique calls here referenced things that never existed and broke
        // rollback (QueryException). confirmation_code was also missed.
        $columns = array_values(array_filter(
            ['status', 'subscribed_at', 'unsubscribed_at', 'list_id', 'confirmation_code'],
            static fn (string $column): bool => Schema::hasColumn('newsletter_subscribers', $column)
        ));

        if ($columns !== []) {
            Schema::table('newsletter_subscribers', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
